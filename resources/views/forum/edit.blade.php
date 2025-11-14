@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-8">
  <h1 class="mb-6 text-center text-3xl font-extrabold tracking-tight text-gray-900">EDIT POST</h1>

  @if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
      <p class="mb-2 font-semibold">Vui lòng sửa các lỗi sau:</p>
      <ul class="list-inside list-disc space-y-1">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('forum.update', $question->getKey()) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
      <label for="title" class="mb-1 inline-block text-sm font-medium text-gray-700">Title</label>
      <input
        type="text"
        id="title"
        name="title"
        value="{{ old('title', $question->title) }}"
        required
        placeholder="Type your title here"
        class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      />
      @error('title')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="content" class="mb-1 inline-block text-sm font-medium text-gray-700">Content</label>
      <textarea
        id="content"
        name="content"
        rows="10"
        required
        placeholder="Type your content here"
        class="block w-full resize-y rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >{{ old('content', $question->content) }}</textarea>
      @error('content')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

     <div class="flex items-center justify-end gap-3">
      <a href="{{ route('forum.back') }}"
        class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        Back
      </a>
      <button type="submit"
        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        Update
      </button>
    </div>
  </form>
</div>
@endsection
