# Chuẩn Giao diện Admin - MegaLearning

## 📐 Layout Structure

### 1. Blade Sections
```blade
@extends('admin.layout')

@section('title', 'Tên trang')
@section('page-title', 'Tiêu đề hiển thị')
@section('page-description', 'Mô tả ngắn gọn')

@section('content')
    <!-- Nội dung trang -->
@endsection
```

**Lưu ý:**
- ❌ KHÔNG dùng wrapper `<div class="p-6">`
- ✅ Layout tự động xử lý padding và margin

---

## 🎨 Statistics Cards

### Pattern Thống nhất
```blade
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Tiêu đề thống kê</p>
                <p class="text-4xl font-bold">{{ number_format($value) }}</p>
                @if($unit)
                    <p class="text-xs text-blue-100 mt-1">{{ $unit }}</p>
                @endif
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">🎯</span>
            </div>
        </div>
    </div>
</div>
```

**Màu sắc:**
- Blue (`from-blue-500 to-blue-600`) - Chính, tổng quan
- Green (`from-green-500 to-green-600`) - Hoạt động, thành công
- Purple (`from-purple-500 to-purple-600`) - Người dùng, nhóm
- Orange (`from-orange-500 to-orange-600`) - Thời gian, thống kê
- Indigo (`from-indigo-500 to-indigo-600`) - Dữ liệu, phân tích
- Cyan (`from-cyan-500 to-cyan-600`) - Kết nối, mạng
- Teal (`from-teal-500 to-teal-600`) - Tiến độ, hoàn thành

---

## 🎴 Content Cards

### Card Cơ bản
```blade
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i data-feather="icon-name" class="w-5 h-5 inline text-blue-600"></i>
        Tiêu đề
    </h3>
    <!-- Nội dung -->
</div>
```

### Card với Hover Effect
```blade
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
    <!-- Nội dung -->
</div>
```

### Card với Icon Badge
```blade
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-lg font-semibold text-gray-800">Tiêu đề</h4>
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
            <span class="text-2xl">📚</span>
        </div>
    </div>
    <!-- Nội dung -->
</div>
```

---

## 📝 Forms & Filters

### Filter Form
```blade
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Label</label>
            <input type="text" name="field"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
            </button>
            <a href="{{ route('...') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-feather="x" class="w-4 h-4 inline"></i>
            </a>
        </div>
    </form>
</div>
```

---

## 📊 Tables

### Table với Header Section
```blade
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">
            <i data-feather="list" class="w-5 h-5 inline text-blue-600"></i>
            Tiêu đề bảng
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cột 1
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">Dữ liệu</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
    @endif
</div>
```

### Empty State
```blade
@empty
    <tr>
        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
            <i data-feather="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
            <p class="text-lg">Không có dữ liệu</p>
        </td>
    </tr>
@endforelse
```

---

## 🎯 Progress Bars

```blade
<div class="w-full bg-gray-200 rounded-full h-2.5">
    <div class="bg-blue-600 h-2.5 rounded-full transition-all" 
         style="width: {{ $percentage }}%">
    </div>
</div>
```

**Với label:**
```blade
<div class="flex justify-between text-xs text-gray-600 mb-2">
    <span>Tiến độ</span>
    <span class="font-semibold">{{ $percentage }}%</span>
</div>
<div class="w-full bg-gray-200 rounded-full h-2.5">
    <div class="bg-blue-600 h-2.5 rounded-full transition-all" 
         style="width: {{ $percentage }}%">
    </div>
</div>
```

---

## 🏷️ Status Badges

```blade
<span class="px-2 py-1 text-xs rounded-full 
    {{ $status == 'active' ? 'bg-green-100 text-green-700' : 
       ($status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 
       'bg-gray-100 text-gray-700') }}">
    {{ $statusText }}
</span>
```

---

## 🔘 Buttons

### Primary
```blade
<button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
    <i data-feather="plus" class="w-5 h-5 inline mr-2"></i>
    Thêm mới
</button>
```

### Secondary
```blade
<button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
    Hủy
</button>
```

### Success (Small)
```blade
<button class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
    Bắt đầu
</button>
```

### Danger (Small)
```blade
<button class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
    Xóa
</button>
```

---

## 📱 Modals

```blade
<div id="modalId" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">Tiêu đề Modal</h3>
        
        <form action="{{ route('...') }}" method="POST">
            @csrf
            <!-- Form fields -->
            
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Lưu
                </button>
                <button type="button" onclick="document.getElementById('modalId').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>
```

---

## 🎨 Color Palette

### Backgrounds
- White cards: `bg-white`
- Page background: `bg-slate-50` (tự động từ layout)
- Gray hover: `hover:bg-gray-50`

### Borders
- Light: `border-gray-100`
- Medium: `border-gray-200`
- Dark: `border-gray-300`

### Text
- Primary: `text-gray-800` / `text-gray-900`
- Secondary: `text-gray-600`
- Muted: `text-gray-500`

### Shadows
- Light: `shadow-sm`
- Medium: `shadow-md`
- Heavy: `shadow-lg`

---

## ✨ Icons

Sử dụng Feather Icons:
```blade
<i data-feather="icon-name" class="w-5 h-5 inline text-blue-600"></i>
```

**Icon sizes:**
- Small: `w-4 h-4` (trong buttons)
- Medium: `w-5 h-5` (trong headings)
- Large: `w-6 h-6` (trong sections)
- Extra large: `w-12 h-12` hoặc `w-16 h-16` (empty states)

**Khởi tạo icons:**
```blade
@push('scripts')
<script>
    feather.replace();
</script>
@endpush
```

---

## 📏 Spacing

### Margins
- Between sections: `mb-6`
- Between cards: `gap-6` (trong grid)
- Inside cards: `p-6`

### Grid Gaps
- Default: `gap-6`
- Tight: `gap-4`

---

## 🎭 Typography

### Headings
- Page title: Tự động từ `@section('page-title')`
- Section: `text-lg font-semibold text-gray-800`
- Card title: `text-lg font-semibold text-gray-800`
- Stat value: `text-4xl font-bold`

### Text
- Label: `text-sm font-medium text-gray-700`
- Body: `text-sm text-gray-600`
- Small: `text-xs text-gray-600`

---

## ✅ Checklist

Khi tạo view mới, đảm bảo:
- [ ] Sử dụng đúng blade sections
- [ ] Không có wrapper `<div class="p-6">`
- [ ] Stats cards dùng gradient + icon badge tròn
- [ ] Content cards có `rounded-xl shadow-sm border-gray-100`
- [ ] Tables có header section riêng
- [ ] Forms có labels và focus styles
- [ ] Buttons có hover transitions
- [ ] Icons được khởi tạo với `feather.replace()`
- [ ] Empty states có icon và message rõ ràng
- [ ] Responsive với `md:` và `lg:` breakpoints
