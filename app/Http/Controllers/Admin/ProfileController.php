<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:8',
        ]);

        $user = auth()->user();

        // Check old password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng!']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function updateAvatar(Request $request)
{
    $request->validate([
        'avatar' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048', // 2MB
        ]
    ], [
        'avatar.required' => 'Vui lòng chọn ảnh đại diện.',
        'avatar.image'    => 'File phải là hình ảnh.',
        'avatar.mimes'    => 'Ảnh chỉ hỗ trợ JPG, JPEG, PNG, WEBP.',
        'avatar.max'      => 'Ảnh tối đa 2MB.',
    ]);

    $user = auth()->user();

    // Xóa avatar cũ
    if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar))) {
        unlink(public_path('uploads/avatars/' . $user->avatar));
    }

    // Tạo tên file mới an toàn
    $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $request->avatar->extension();

    // Lưu file vào thư mục public/uploads/avatars
    $request->avatar->move(public_path('uploads/avatars'), $fileName);

    // Lưu DB
    $user->update([
        'avatar' => $fileName
    ]);

    return back()->with('success', 'Cập nhật avatar thành công!');
}
}
