<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\ForumQuestion;
use App\Models\ForumAnswer;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForumController extends Controller
{
    /**
     * Display forum topics (threads)
     */
    public function topics(Request $request)
    {
        $query = ForumThread::with(['creator', 'subject', 'moderator'])
            ->withCount('posts')
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('created_at', 'DESC');

        // Filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $threads = $query->paginate(20);

        // Statistics
        $stats = [
            'total_threads' => ForumThread::count(),
            'active_threads' => ForumThread::where('status', 'active')->count(),
            'pending_threads' => ForumThread::where('status', 'pending')->count(),
            'total_posts' => ForumPost::count(),
        ];

        // For filters
        $subjects = Subject::orderBy('name')->get();

        return view('admin.forums.topics', compact('threads', 'stats', 'subjects'));
    }

    /**
     * Display forum posts
     */
    public function posts(Request $request)
    {
        $query = ForumPost::with(['thread', 'author', 'moderator'])
            ->orderBy('created_at', 'DESC');

        // Filters
        if ($request->filled('thread_id')) {
            $query->where('thread_id', $request->thread_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate(20);

        // Statistics
        $stats = [
            'total_posts' => ForumPost::count(),
            'active_posts' => ForumPost::where('status', 'active')->count(),
            'pending_posts' => ForumPost::where('status', 'pending')->count(),
            'flagged_posts' => ForumPost::where('status', 'flagged')->count(),
        ];

        // For filters
        $threads = ForumThread::orderBy('created_at', 'DESC')->limit(50)->get();

        return view('admin.forums.posts', compact('posts', 'stats', 'threads'));
    }

    /**
     * Display moderation queue
     */
    public function moderation(Request $request)
    {
        // Pending threads
        $pendingThreads = ForumThread::with(['creator', 'subject'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        // Pending posts
        $pendingPosts = ForumPost::with(['thread', 'author'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        // Flagged posts
        $flaggedPosts = ForumPost::with(['thread', 'author'])
            ->where('status', 'flagged')
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        // Recent moderation activity
        $recentModeration = ForumPost::with(['thread', 'author', 'moderator'])
            ->whereNotNull('moderated_at')
            ->orderBy('moderated_at', 'DESC')
            ->limit(10)
            ->get();

        // Statistics
        $stats = [
            'pending_threads' => ForumThread::where('status', 'pending')->count(),
            'pending_posts' => ForumPost::where('status', 'pending')->count(),
            'flagged_posts' => ForumPost::where('status', 'flagged')->count(),
            'total_moderated_today' => ForumPost::whereDate('moderated_at', today())->count(),
        ];

        return view('admin.forums.moderation', compact(
            'pendingThreads',
            'pendingPosts', 
            'flaggedPosts',
            'recentModeration',
            'stats'
        ));
    }

    /**
     * Approve thread
     */
    public function approveThread($id)
    {
        $thread = ForumThread::findOrFail($id);
        $thread->update([
            'status' => 'active',
            'moderated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Chủ đề đã được phê duyệt');
    }

    /**
     * Reject thread
     */
    public function rejectThread($id)
    {
        $thread = ForumThread::findOrFail($id);
        $thread->update([
            'status' => 'rejected',
            'moderated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Chủ đề đã bị từ chối');
    }

    /**
     * Delete thread
     */
    public function deleteThread($id)
    {
        $thread = ForumThread::findOrFail($id);
        $thread->delete();

        return back()->with('success', 'Chủ đề đã được xóa');
    }

    /**
     * Approve post
     */
    public function approvePost($id)
    {
        $post = ForumPost::findOrFail($id);
        $post->update([
            'status' => 'active',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Bài viết đã được phê duyệt');
    }

    /**
     * Reject post
     */
    public function rejectPost($id)
    {
        $post = ForumPost::findOrFail($id);
        $post->update([
            'status' => 'rejected',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Bài viết đã bị từ chối');
    }

    /**
     * Delete post
     */
    public function deletePost($id)
    {
        $post = ForumPost::findOrFail($id);
        $post->delete();

        return back()->with('success', 'Bài viết đã được xóa');
    }

    /**
     * Toggle thread pin
     */
    public function togglePin($id)
    {
        $thread = ForumThread::findOrFail($id);
        $thread->update(['is_pinned' => !$thread->is_pinned]);

        return back()->with('success', $thread->is_pinned ? 'Chủ đề đã được ghim' : 'Đã bỏ ghim chủ đề');
    }

    /**
     * Toggle thread lock
     */
    public function toggleLock($id)
    {
        $thread = ForumThread::findOrFail($id);
        $thread->update(['is_locked' => !$thread->is_locked]);

        return back()->with('success', $thread->is_locked ? 'Chủ đề đã bị khóa' : 'Đã mở khóa chủ đề');
    }
}
