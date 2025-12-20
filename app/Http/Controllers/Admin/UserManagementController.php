<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Quản lý người dùng - Hiển thị danh sách người dùng
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Lọc theo vai trò
        if ($request->has('role') && $request->role != '') {
            $query->role($request->role);
        }

        // Lọc theo trạng thái (khóa/mở khóa)
        if ($request->has('status')) {
            if ($request->status === 'locked') {
                $query->where('is_locked', true);
            } elseif ($request->status === 'active') {
                $query->where('is_locked', false);
            }
        }

        // Tìm kiếm theo tên hoặc email
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Thêm tài khoản quản trị - Hiển thị form tạo
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Thêm tài khoản quản trị - Lưu tài khoản mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_locked' => false,
        ]);

        $user->assignRole($validated['role']);

        // Log hoạt động
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_user',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Created new user: {$user->name} ({$user->email})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'new_values' => $user->toArray(),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Tạo tài khoản thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles', 'teachingSubjects', 'enrolledClasses', 'grades', 'activityLogs');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Sửa thông tin người dùng - Hiển thị form sửa
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Sửa thông tin người dùng - Cập nhật
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'role' => 'required|exists:roles,name',
        ]);

        $oldValues = $user->toArray();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ];

        // Chỉ cập nhật password nếu có nhập mật khẩu mới
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Cập nhật role
        $user->syncRoles([$validated['role']]);

        // Log hoạt động
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_user',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Updated user: {$user->name}" . (!empty($validated['password']) ? ' (password changed)' : ''),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'old_values' => $oldValues,
            'new_values' => $user->fresh()->toArray(),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Cập nhật thông tin người dùng thành công!' . (!empty($validated['password']) ? ' Mật khẩu đã được thay đổi.' : ''));
    }

    /**
     * Xóa tài khoản
     */
    public function destroy(Request $request, User $user)
    {
        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản của chính bạn!');
        }

        $oldValues = $user->toArray();

        // Log hoạt động trước khi xóa
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_user',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Deleted user: {$user->name} ({$user->email})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'old_values' => $oldValues,
        ]);

        $user->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Xóa tài khoản thành công!');
    }

    /**
     * Khóa/mở khóa tài khoản
     */
    public function toggleLock(Request $request, User $user)
    {
        // Không cho phép khóa chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể khóa tài khoản của chính bạn!');
        }

        $user->is_locked = !$user->is_locked;
        $user->save();

        // Log hoạt động
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $user->is_locked ? 'lock_user' : 'unlock_user',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => ($user->is_locked ? 'Locked' : 'Unlocked') . " user: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $message = $user->is_locked ? 'Đã khóa tài khoản thành công!' : 'Đã mở khóa tài khoản thành công!';
        return back()->with('success', $message);
    }

    /**
     * Phân quyền
     */
    public function updatePermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $oldRole = $user->roles->pluck('name')->first();
        $user->syncRoles([$validated['role']]);

        // Log hoạt động
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_permissions',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Changed role from {$oldRole} to {$validated['role']} for user: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'old_values' => ['role' => $oldRole],
            'new_values' => ['role' => $validated['role']],
        ]);

        return back()->with('success', 'Cập nhật quyền thành công!');
    }

    /**
 * Danh sách HỌC SINH
 */
public function students()
{
    $users = \App\Models\User::role('student')->paginate(20);

    return view('admin.users.students', compact('users'));
}

/**
 * Danh sách GIÁO VIÊN
 */
public function teachers()
{
    $users = \App\Models\User::role('teacher')->paginate(20);

    return view('admin.users.teachers', compact('users'));
}

/**
 * Danh sách QUẢN TRỊ VIÊN
 */
public function admins()
{
    $users = \App\Models\User::role('admin')->paginate(20);

    return view('admin.users.admins', compact('users'));
}
}
