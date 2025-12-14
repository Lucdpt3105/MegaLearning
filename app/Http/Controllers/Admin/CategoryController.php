<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('courses')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Return JSON for API requests
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        }
        
        // Return view for web requests
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create(Request $request)
    {
        // API doesn't need create form
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Use POST /api/v1/categories to create a category'
            ], 405);
        }
        
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug',
            'is_active' => 'nullable|boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $category = Category::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Danh mục đã được tạo thành công',
                'data' => $category
            ], 201);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công');
    }

    /**
     * Display the specified category
     */
    public function show(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        }

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        // API doesn't need edit form, return data directly
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        }
        
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in database
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
            'is_active' => 'nullable|boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->has('is_active')) {
            $validated['is_active'] = true;
        } else {
            $validated['is_active'] = false;
        }

        $category->update($validated);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Danh mục đã được cập nhật',
                'data' => $category
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật');
    }

    /**
     * Remove the specified category from database
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Danh mục đã được xóa'
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa');
    }
}
