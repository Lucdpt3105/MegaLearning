<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount('courses')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.categories.index', compact('categories'));
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        // Load courses that belong to this teacher only
        $category->load(['courses' => function($query) {
            $query->where('teacher_id', auth()->id())
                  ->with('teacher');
        }]);

        return view('teacher.categories.show', compact('category'));
    }
}
