@extends('admin.layout')

@section('title', 'Cài đặt hệ thống')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Cài đặt hệ thống ⚙️</h1>
        <p class="text-gray-600 mt-1">Quản lý cấu hình và tùy chỉnh hệ thống</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('general')" id="tab-general"
                        class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                    <i data-feather="settings" class="w-4 h-4 inline mr-2"></i>
                    Cài đặt chung
                </button>
                <button onclick="showTab('appearance')" id="tab-appearance"
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i data-feather="layout" class="w-4 h-4 inline mr-2"></i>
                    Giao diện
                </button>
                <button onclick="showTab('email')" id="tab-email"
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i data-feather="mail" class="w-4 h-4 inline mr-2"></i>
                    Email
                </button>
                <button onclick="showTab('security')" id="tab-security"
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i data-feather="shield" class="w-4 h-4 inline mr-2"></i>
                    Bảo mật
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content: General Settings -->
    <div id="content-general" class="tab-content">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i data-feather="settings" class="w-5 h-5 inline text-blue-600"></i>
                Cài đặt chung
            </h2>

            <form action="{{ route('admin.settings.update.general') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tên website</label>
                        <input type="text" name="site_name"
                               value="{{ setting('site_name', 'MegaLearning') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả website</label>
                        <textarea name="site_description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ setting('site_description', 'Nền tảng học tập trực tuyến chất lượng cao') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email liên hệ</label>
                        <input type="email" name="contact_email"
                               value="{{ setting('contact_email', 'contact@megalearning.com') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                        <input type="text" name="contact_phone"
                               value="{{ setting('contact_phone', '0123456789') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                        <textarea name="contact_address" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ setting('contact_address', 'Hà Nội, Việt Nam') }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i data-feather="save" class="w-4 h-4 inline mr-2"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Content: Appearance -->
    <div id="content-appearance" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i data-feather="layout" class="w-5 h-5 inline text-blue-600"></i>
                Giao diện
            </h2>

            <form action="{{ route('admin.settings.update.general') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.svg') }}"
                                 class="h-16 w-16 rounded-lg border object-cover" />
                            <input type="file" name="site_logo"
                                   class="text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                        <input type="file" name="site_favicon"
                               class="text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">PNG hoặc ICO, 32x32px</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Màu chủ đạo</label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="primary_color"
                                   value="{{ setting('primary_color', '#3b82f6') }}"
                                   class="h-10 w-20 rounded border border-gray-300">
                            <span class="text-sm text-gray-600">{{ setting('primary_color', '#3b82f6') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i data-feather="save" class="w-4 h-4 inline mr-2"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Content: Email -->
    <div id="content-email" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i data-feather="mail" class="w-5 h-5 inline text-blue-600"></i>
                Cấu hình Email
            </h2>

            <form action="{{ route('admin.settings.update.general') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" name="mail_host"
                               value="{{ setting('mail_host', 'smtp.gmail.com') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="number" name="mail_port"
                                   value="{{ setting('mail_port', '587') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                            <select name="mail_encryption"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="tls" {{ setting('mail_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ setting('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="mail_username"
                               value="{{ setting('mail_username') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="mail_password"
                               placeholder="••••••••"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                        <input type="email" name="mail_from_address"
                               value="{{ setting('mail_from_address', 'noreply@megalearning.com') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                        <input type="text" name="mail_from_name"
                               value="{{ setting('mail_from_name', 'MegaLearning') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i data-feather="save" class="w-4 h-4 inline mr-2"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Content: Security -->
    <div id="content-security" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i data-feather="shield" class="w-5 h-5 inline text-blue-600"></i>
                Bảo mật
            </h2>

            <form action="{{ route('admin.settings.update.security') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới</label>
                        <input type="password" name="new_password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Tối thiểu 8 ký tự</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                        <i data-feather="lock" class="w-4 h-4 inline mr-2"></i>
                        Đổi mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Reset all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Highlight selected tab button
    const selectedButton = document.getElementById('tab-' + tabName);
    selectedButton.classList.remove('border-transparent', 'text-gray-500');
    selectedButton.classList.add('border-blue-500', 'text-blue-600');
}

// Initialize Feather icons
feather.replace();
</script>
@endsection
