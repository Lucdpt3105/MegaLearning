<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoCall;
use App\Models\ClassRoom;
use App\Models\User;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MeetingController extends Controller
{
    /**
     * Display a listing of all video calls (Admin)
     */
    public function index(Request $request)
    {
        $query = VideoCall::with(['classRoom.subject', 'host']);

        // Filters
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        if ($request->filled('host_id')) {
            $query->where('host_id', $request->host_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('scheduled_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('scheduled_at', '<=', $request->date_to);
        }

        $videoCalls = $query->latest('scheduled_at')->paginate(15);
        
        $classRooms = ClassRoom::with('subject')->get();
        $teachers = User::role('teacher')->get();

        // Statistics
        $stats = [
            'total_meetings' => VideoCall::count(),
            'active_meetings' => VideoCall::where('status', 'in_progress')->count(),
            'scheduled_meetings' => VideoCall::where('status', 'scheduled')->count(),
            'completed_meetings' => VideoCall::where('status', 'ended')->count(),
            'total_duration' => VideoCall::sum('duration'),
        ];

        return view('admin.meetings.index', compact('videoCalls', 'classRooms', 'teachers', 'stats'));
    }

    /**
     * Display list of meeting rooms (legacy)
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
            'active_meetings' => VideoCall::where('status', 'in_progress')->count(),
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
            'host_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'nullable|integer|min:15|max:480',
            'description' => 'nullable|string',
            'platform' => 'required|in:jitsi,zoom',
        ]);

        $platform = $validated['platform'];
        $roomCode = 'VC-' . strtoupper(Str::random(8));
        $meetingUrl = '';
        $password = null;
        $settings = [
            'allow_chat' => true,
            'allow_screen_share' => true,
            'lobby_enabled' => false,
            'platform' => $platform,
        ];

        // Create meeting based on platform
        if ($platform === 'zoom') {
            try {
                $zoomService = app(ZoomService::class);
                $zoomMeeting = $zoomService->createMeeting([
                    'topic' => $validated['title'],
                    'start_time' => $validated['scheduled_at'],
                    'duration' => $validated['duration'] ?? 60,
                    'agenda' => $validated['description'] ?? '',
                ]);

                $roomCode = (string) $zoomMeeting['meeting_id'];
                $meetingUrl = $zoomMeeting['meeting_url'];
                $password = $zoomMeeting['password'] ?? null;
                $settings['zoom_meeting_id'] = $zoomMeeting['meeting_id'];
                $settings['zoom_password'] = $password;
                $settings['zoom_start_url'] = $zoomMeeting['start_url'];
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Lỗi tạo Zoom meeting: ' . $e->getMessage());
            }
        } else {
            // Jitsi
            $meetingUrl = 'https://meet.jit.si/' . $roomCode;
        }

        $meeting = VideoCall::create([
            'title' => $validated['title'],
            'class_room_id' => $validated['class_room_id'],
            'host_id' => $validated['host_id'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration' => $validated['duration'] ?? 60,
            'description' => $validated['description'] ?? null,
            'status' => 'scheduled',
            'room_code' => $roomCode,
            'meeting_url' => $meetingUrl,
            'password' => $password,
            'settings' => $settings,
        ]);

        return back()->with('success', 'Đã tạo phòng học thành công! Mã phòng: ' . $roomCode);
    }

    /**
     * Update meeting status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,in_progress,ended,cancelled',
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
