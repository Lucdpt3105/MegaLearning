@extends('admin.layout')

@section('title', 'Thêm môn học')
@section('page-title', 'Thêm môn học mới')
@section('page-description', 'Tạo một môn học mới trong hệ thống')

@section('content')
    <div class="max-w-3xl">
        <!-- Back Button -->
        <a href="{{ route('admin.subjects.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
            <span class="mr-2">←</span>
            Quay lại danh sách
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form id="createSubjectForm">
                @csrf
                
                <!-- Subject Name -->
                <div class="mb-6">
                    <label for="subject_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên môn học <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="subject_name" 
                           name="subject_name" 
                           required
                           placeholder="Ví dụ: Lập Trình Web"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="mt-2 text-sm text-gray-500">Nhập tên môn học rõ ràng và dễ hiểu</p>
                    <p id="subject_name_error" class="mt-2 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.subjects.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        Hủy
                    </a>
                    <button type="submit" 
                            id="submitBtn"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <span id="btnText">Tạo môn học</span>
                        <span id="btnLoading" class="hidden">Đang tạo...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('createSubjectForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        const errorEl = document.getElementById('subject_name_error');
        
        // Disable button
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        errorEl.classList.add('hidden');
        
        const formData = {
            subject_name: document.getElementById('subject_name').value
        };
        
        try {
            const response = await fetch('/api/v1/subjects', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('✅ Tạo môn học thành công!');
                window.location.href = '{{ route("admin.subjects.index") }}';
            } else {
                // Show validation errors
                if (data.errors && data.errors.subject_name) {
                    errorEl.textContent = data.errors.subject_name[0];
                    errorEl.classList.remove('hidden');
                } else {
                    alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại'));
                }
                
                // Re-enable button
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
            
            // Re-enable button
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    });
</script>
@endpush
