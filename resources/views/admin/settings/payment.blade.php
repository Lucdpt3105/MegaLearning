@extends('admin.layout')

@section('title', 'Cài đặt Thanh toán')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Cài đặt Thanh toán</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.settings.payment.update') }}">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Cổng thanh toán</h3>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="payment_methods[]" value="vnpay" class="mr-2">
                        <span>VNPay</span>
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="payment_methods[]" value="momo" class="mr-2">
                        <span>Momo</span>
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="payment_methods[]" value="zalopay" class="mr-2">
                        <span>ZaloPay</span>
                    </label>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Cấu hình VNPay</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">TMN Code</label>
                    <input type="text" name="vnpay_tmn_code" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hash Secret</label>
                    <input type="password" name="vnpay_hash_secret" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu cài đặt</button>
            </div>
        </form>
    </div>
</div>
@endsection
