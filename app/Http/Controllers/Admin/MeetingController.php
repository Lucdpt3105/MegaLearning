<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoCall;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MeetingController extends Controller
{
    /**
     * Display list of meeting rooms
     */
    public function rooms(Request $request)
    {
        $query = VideoCall::with(['classRoom.subject', 'host'])
            ->orderBy('created_at', 'DESC');

        // Filters
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $meetings = $query->paginate(20);

        // Statistics
        $stats = [
            'total_meetings' => VideoCall::count(),
            'active_meetings' => VideoCall::where('status', 'active')->count(),
            'completed_meetings' => VideoCall::where('status', 'ended')->count(),
            'total_duration' => VideoCall::sum('duration'),
        ];

        // For filters
        $classRooms = ClassRoom::with('subject')->orderBy('name')->get();

        return view('admin.meetings.rooms', compact('meetings', 'stats', 'classRooms'));
    }

    /**
     * Display meeting history
     */
    public function history(Request $request)
    {
        $query = VideoCall::with(['classRoom.subject', 'host'])
            ->whereIn('status', ['ended', 'cancelled'])
            ->orderBy('created_at', 'DESC');

        // Filters
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        if ($request->filled('host_id')) {
            $query->where('host_id', $request->host_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $meetings = $query->paginate(20);

        // Current month statistics
        $monthly_stats = [
            'total_meetings' => VideoCall::whereIn('status', ['ended', 'cancelled'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'total_duration' => VideoCall::whereIn('status', ['ended', 'cancelled'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('duration') ?? 0,
            'average_duration' => round(VideoCall::whereIn('status', ['ended', 'cancelled'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->avg('duration') ?? 0),
        ];

        // Top hosts
        $top_hosts = User::whereHas('hostedVideoCalls', function($query) {
                $query->whereIn('status', ['ended', 'cancelled']);
            })
            ->withCount(['hostedVideoCalls' => function($query) {
                $query->whereIn('status', ['ended', 'cancelled']);
            }])
            ->withSum(['hostedVideoCalls' => function($query) {
                $query->whereIn('status', ['ended', 'cancelled']);
            }], 'duration')
            ->orderByDesc('hosted_video_calls_count')
            ->limit(3)
            ->get()
            ->map(function($host) {
                return (object)[
                    'name' => $host->name,
                    'meetings_count' => $host->hosted_video_calls_count ?? 0,
                    'total_duration' => $host->hosted_video_calls_sum_duration ?? 0,
                ];
            });

        // For filters
        $classRooms = ClassRoom::with('subject')->orderBy('name')->get();

        return view('admin.meetings.history', compact('meetings', 'monthly_stats', 'top_hosts', 'classRooms'));
    }

    /**
     * Create new meeting room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_room_id' => 'required|exists:class_rooms,id',
            'scheduled_at' => 'nullable|date',
            'duration' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $meeting = VideoCall::create([
            'title' => $validated['title'],
            'class_room_id' => $validated['class_room_id'],
            'host_id' => auth()->id(),
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'duration' => $validated['duration'] ?? 0,
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
            'meeting_url' => 'https://meet.megalearning.com/' . uniqid(),
        ]);

        return redirect()->route('admin.meetings.rooms')
            ->with('success', 'Phòng họp đã được tạo thành công!');
    }

    /**
     * Update meeting status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,active,ended,cancelled',
        ]);

        $meeting = VideoCall::findOrFail($id);
        $meeting->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Trạng thái phòng họp đã được cập nhật!');
    }

    /**
     * Delete meeting
     */
    public function destroy($id)
    {
        $meeting = VideoCall::findOrFail($id);
        $meeting->delete();

        return redirect()->back()
            ->with('success', 'Phòng họp đã được xóa!');
    }
}
