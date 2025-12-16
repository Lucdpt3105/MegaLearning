<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Subject;
use App\Services\AdminNotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    protected $adminNotificationService;

    public function __construct(AdminNotificationService $adminNotificationService)
    {
        $this->adminNotificationService = $adminNotificationService;
    }

    /**
     * Display a listing of documents
     */
    public function index(Request $request)
    {
        $query = Document::with(['subject', 'uploader'])
            ->where('uploaded_by', auth()->id());

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by folder
        if ($request->filled('folder')) {
            $query->where('folder', $request->folder);
        }

        // Filter by approval status
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $documents = $query->latest()->paginate(15);

        // Get subjects for filter dropdown
        $subjects = Subject::where('teacher_id', auth()->id())
            ->orderBy('name')
            ->get();

        // Get folders for filter dropdown
        $folders = Document::where('uploaded_by', auth()->id())
            ->whereNotNull('folder')
            ->distinct()
            ->pluck('folder');

        // Statistics
        $stats = [
            'total' => Document::where('uploaded_by', auth()->id())->count(),
            'pending' => Document::where('uploaded_by', auth()->id())->where('approval_status', 'pending')->count(),
            'approved' => Document::where('uploaded_by', auth()->id())->where('approval_status', 'approved')->count(),
            'rejected' => Document::where('uploaded_by', auth()->id())->where('approval_status', 'rejected')->count(),
        ];

        return view('teacher.documents.index', compact('documents', 'subjects', 'folders', 'stats'));
    }

    /**
     * Show the form for creating a new document
     */
    public function create()
    {
        $subjects = Subject::where('teacher_id', auth()->id())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('teacher.documents.create', compact('subjects'));
    }

    /**
     * Store a newly created document (Upload)
     * Post-condition: Send to UC-ADM-021 for approval
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'folder' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip|max:51200', // 50MB max
            'auto_approve' => 'boolean', // Teacher can self-approve
        ]);

        // Check if user owns the subject
        $subject = Subject::findOrFail($validated['subject_id']);
        $this->authorize('update', $subject);

        try {
            DB::beginTransaction();

            // Store file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            // Determine approval status
            $approvalStatus = 'pending';
            $approvedBy = null;
            $approvedAt = null;

            if ($request->boolean('auto_approve', false)) {
                // Teacher self-approve
                $approvalStatus = 'approved';
                $approvedBy = auth()->id();
                $approvedAt = now();
            }

            // Create document record
            $document = Document::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'subject_id' => $validated['subject_id'],
                'uploaded_by' => auth()->id(),
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'folder' => $validated['folder'],
                'approval_status' => $approvalStatus,
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'download_count' => 0,
            ]);

            // Gửi thông báo cho admin về tài liệu mới
            $this->adminNotificationService->notifyNewDocumentUploaded($document);

            DB::commit();

            $message = $approvalStatus === 'approved' 
                ? 'Tài liệu đã được tải lên và phê duyệt thành công.'
                : 'Tài liệu đã được tải lên và đang chờ kiểm duyệt từ Admin.';

            return redirect()
                ->route('teacher.documents.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded file if exists
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tải tài liệu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified document
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);

        $document->load(['subject', 'uploader', 'approver']);

        return view('teacher.documents.show', compact('document'));
    }

    /**
     * Show the form for editing (updating) document
     */
    public function edit(Document $document)
    {
        $this->authorize('update', $document);

        $subjects = Subject::where('teacher_id', auth()->id())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('teacher.documents.edit', compact('document', 'subjects'));
    }

    /**
     * Update document (Upload new version)
     * Post-condition: Send to UC-ADM-021 for approval
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'folder' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip|max:51200',
            'auto_approve' => 'boolean',
        ]);

        // Check if user owns the new subject
        $subject = Subject::findOrFail($validated['subject_id']);
        $this->authorize('update', $subject);

        try {
            DB::beginTransaction();

            $oldFilePath = $document->file_path;

            // Update document info
            $document->title = $validated['title'];
            $document->description = $validated['description'];
            $document->subject_id = $validated['subject_id'];
            $document->folder = $validated['folder'];

            // If new file uploaded, replace old file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');

                $document->file_path = $filePath;
                $document->file_name = $file->getClientOriginalName();
                $document->file_type = $file->getClientMimeType();
                $document->file_size = $file->getSize();
                
                // Determine approval status
                if ($request->boolean('auto_approve', false)) {
                    $document->approval_status = 'approved';
                    $document->approved_by = auth()->id();
                    $document->approved_at = now();
                    $document->rejection_reason = null;
                    $message = 'Tài liệu đã được cập nhật và phê duyệt thành công.';
                } else {
                    $document->approval_status = 'pending';
                    $document->rejection_reason = null;
                    $document->approved_by = null;
                    $document->approved_at = null;
                    $message = 'Tài liệu đã được cập nhật và đang chờ kiểm duyệt lại từ Admin.';
                }

                // Delete old file
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            } else {
                $message = 'Thông tin tài liệu đã được cập nhật.';
            }

            $document->save();

            DB::commit();

            return redirect()
                ->route('teacher.documents.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật tài liệu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified document (Delete)
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        try {
            DB::beginTransaction();

            $filePath = $document->file_path;

            // Delete database record
            $document->delete();

            // Delete physical file
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            DB::commit();

            return redirect()
                ->route('teacher.documents.index')
                ->with('success', 'Tài liệu đã được xóa thành công.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Có lỗi xảy ra khi xóa tài liệu: ' . $e->getMessage());
        }
    }

    /**
     * Organize documents (Create folder)
     */
    public function createFolder(Request $request)
    {
        $validated = $request->validate([
            'folder_name' => 'required|string|max:255',
        ]);

        // Just return success - folders are created dynamically when documents are uploaded
        return response()->json([
            'success' => true,
            'message' => 'Thư mục đã được tạo. Bạn có thể chọn thư mục này khi tải tài liệu lên.',
            'folder' => $validated['folder_name']
        ]);
    }

    /**
     * Move document to folder
     */
    public function moveToFolder(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'folder' => 'nullable|string|max:255',
        ]);

        $document->update([
            'folder' => $validated['folder']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tài liệu đã được di chuyển thành công.'
        ]);
    }

    /**
     * Download document
     */
    public function download(Document $document)
    {
        $this->authorize('view', $document);

        // Teacher can download their own documents regardless of approval status
        if ($document->uploaded_by === auth()->id()) {
            $document->incrementDownloadCount();
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        // Others can only download approved documents
        if ($document->approval_status !== 'approved') {
            abort(403, 'Tài liệu chưa được phê duyệt.');
        }

        // Increment download count
        $document->incrementDownloadCount();

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Self-approve document (Teacher can approve their own documents)
     */
    public function approve(Document $document)
    {
        $this->authorize('update', $document);

        try {
            $document->update([
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tài liệu đã được phê duyệt thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject document (Teacher can reject/unpublish their own documents)
     */
    public function reject(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $document->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $validated['reason'] ?? 'Tự từ chối bởi giáo viên',
                'approved_by' => null,
                'approved_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tài liệu đã được gỡ xuống.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
