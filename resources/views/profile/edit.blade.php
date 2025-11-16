@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-black-900 dark:text-blue-400">Chỉnh sửa thông tin cá nhân</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Cập nhật thông tin tài khoản và cài đặt của bạn</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <nav class="space-y-2">
                        <a href="#profile-info" class="flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Thông tin cá nhân
                        </a>
                        <a href="{{ route('profile.password') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Đổi mật khẩu
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Section -->
                        <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Ảnh đại diện</label>
                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    @if($user->avatar)
                                        <img id="avatar-preview" class="h-20 w-20 object-cover rounded-full" 
                                             src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                        <div id="avatar-preview" class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="avatar" id="avatar" accept="image/*" 
                                           class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none"
                                           onchange="previewImage(event)">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">JPG, PNG hoặc GIF (Tối đa 2MB)</p>
                                    @error('avatar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($user->avatar)
                                        <button type="button" onclick="removeAvatar()" class="mt-2 text-sm text-red-600 hover:text-red-800">Xóa ảnh</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                1. Thông tin cá nhân
                            </h3>

                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Student/Teacher ID -->
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        @if($user->hasRole('teacher'))
                                            Mã giáo viên
                                        @else
                                            Mã học sinh / ID
                                        @endif
                                    </label>
                                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $user->student_id) }}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="{{ $user->hasRole('teacher') ? 'VD: GV2025001' : 'VD: HS2025001' }}">
                                    @error('student_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Giới tính
                                    </label>
                                    <select name="gender" id="gender" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                        <option value="">Chọn giới tính</option>
                                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div class="mb-4">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Ngày sinh
                                </label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="mb-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                2. Thông tin liên hệ
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required
                                           pattern="[0-9]{10,11}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                           placeholder="VD: 0912345678">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">10-11 chữ số</p>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Địa chỉ (tuỳ chọn)
                                </label>
                                <textarea name="address" id="address" rows="2" 
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                          placeholder="Nhập địa chỉ của bạn">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Bio Section -->
                        <div class="mb-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Giới thiệu bản thân (Bio)
                            </label>
                            <textarea name="bio" id="bio" rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                      placeholder="Giới thiệu ngắn về bản thân...">{{ old('bio', $user->bio) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mô tả ngắn gọn về bản thân bạn.</p>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('dashboard') }}" class="px-6 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                Hủy
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete avatar -->
<form id="delete-avatar-form" action="{{ route('profile.avatar.delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = `<img src="${e.target.result}" class="h-20 w-20 object-cover rounded-full" alt="Preview">`;
        }
        reader.readAsDataURL(file);
    }
}

function removeAvatar() {
    if (confirm('Bạn có chắc chắn muốn xóa ảnh đại diện?')) {
        document.getElementById('delete-avatar-form').submit();
    }
}
</script>
@endsection
