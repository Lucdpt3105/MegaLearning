<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Subjects Management
    Route::get('/subjects', function () {
        return view('admin.subjects.index');
    })->name('subjects.index');

    Route::get('/subjects/create', function () {
        return view('admin.subjects.create');
    })->name('subjects.create');

    Route::get('/subjects/{id}', function ($id) {
        return view('admin.subjects.show', ['id' => $id]);
    })->name('subjects.show');

    Route::get('/subjects/{id}/edit', function ($id) {
        return view('admin.subjects.edit', ['id' => $id]);
    })->name('subjects.edit');

    // Topics Management
    Route::get('/topics', function () {
        return view('admin.topics.index');
    })->name('topics.index');

    Route::get('/topics/create', function () {
        return view('admin.topics.create');
    })->name('topics.create');

    Route::get('/topics/{id}/edit', function ($id) {
        return view('admin.topics.edit', ['id' => $id]);
    })->name('topics.edit');

    // Questions Management
    Route::get('/questions', function () {
        return view('admin.questions.index');
    })->name('questions.index');

    Route::get('/questions/create', function () {
        return view('admin.questions.create');
    })->name('questions.create');

    Route::get('/questions/{id}/edit', function ($id) {
        return view('admin.questions.edit', ['id' => $id]);
    })->name('questions.edit');

    // Exams Management
    Route::get('/exams', function () {
        return view('admin.exams.index');
    })->name('exams.index');

    Route::get('/exams/create', function () {
        return view('admin.exams.create');
    })->name('exams.create');

    Route::get('/exams/{id}/edit', function ($id) {
        return view('admin.exams.edit', ['id' => $id]);
    })->name('exams.edit');
});
