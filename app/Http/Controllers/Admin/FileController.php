<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of documents
     */
    public function index(Request $request)
    {
        $query = Document::with(['subject', 'uploader']);

        // Filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('uploaded_by')) {
            $query->where('uploaded_by', $request->uploaded_by);
        }

        if ($request->filled('folder')) {
            $query->where('folder', $request->folder);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('file_name', 'like', '%' . $request->search . '%');
            });
        }

        $documents = $query->latest()->paginate(20);
        
        // For filters
        $subjects = Subject::orderBy('name')->get();
        $uploaders = User::role(['teacher', 'admin'])->orderBy('name')->get();

        // Statistics
        $stats = [
            'total_files' => Document::count(),
            'total_size' => Document::sum('file_size'),
            'pending_files' => Document::where('approval_status', 'pending')->count(),
            'total_downloads' => Document::sum('download_count'),
        ];

        return view('admin.files.index', compact('documents', 'subjects', 'uploaders', 'stats'));
    }

    /**
     * Show upload form
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.files.upload', compact('subjects'));
    }

    /**
     * Store uploaded file
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'folder' => 'required|in:general,lecture,exam,homework',
            'file' => 'required|file|max:51200', // 50MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/' . $validated['folder'], $fileName, 'public');

            Document::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'subject_id' => $validated['subject_id'],
                'uploaded_by' => auth()->id(),
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'folder' => $validated['folder'],
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return redirect()->route('admin.files.index')
                ->with('success', 'Tải lên file thành công!');
        }

        return back()->with('error', 'Không tìm thấy file!');
    }

    /**
     * Download file
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);
        $document->incrementDownloadCount();

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Delete file
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Đã xóa file thành công!');
    }
}
