<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\VideoCall;
use App\Models\ClassRoom;
use App\Models\User;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VideoCallController extends Controller
{
    /**
     * Display a listing of video calls (UC-GV-001)
     */
    public function index(Request $request)
    {
        $query = VideoCall::with(['classRoom.subject', 'host'])
            ->where('host_id', Auth::id());

        // Filter by class
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('scheduled_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('scheduled_at', '<=', $request->date_to);
        }

        $videoCalls = $query->latest('scheduled_at')->paginate(15);
        
        // Get teacher's class rooms for filter
        $classRooms = ClassRoom::whereHas('subject', function($q) {
            $q->where('teacher_id', Auth::id());
        })->get();

        return view('teacher.video-calls.index', compact('videoCalls', 'classRooms'));
    }

    /**
     * Show the form for creating a new video call (UC-GV-002)
     */
    public function create()
    {
        $classRooms = ClassRoom::whereHas('subject', function($q) {
            $q->where('teacher_id', Auth::id());
        })->with('subject')->get();

        return view('teacher.video-calls.create', compact('classRooms'));
    }

    /**
     * Store a newly created resource (UC-GV-002)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'nullable|integer|min:15|max:480',
            'is_recording' => 'boolean',
            'platform' => 'required|in:jitsi,zoom', // New field
        ]);

        // Check class ownership
        $classRoom = ClassRoom::findOrFail($validated['class_room_id']);
        if ($classRoom->subject->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $platform = $validated['platform'];
        $roomCode = 'VC-' . strtoupper(Str::random(8));
        $meetingUrl = '';
        $settings = [
            'allow_chat' => true,
            'allow_screen_share' => true,
            'allow_recording' => $request->boolean('is_recording'),
            'lobby_enabled' => false, // Tắt phòng chờ - học sinh vào được luôn
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
                    'auto_recording' => $request->boolean('is_recording') ? 'cloud' : 'none',
                ]);

                $meetingUrl = $zoomMeeting['meeting_url'];
                $settings['zoom_meeting_id'] = $zoomMeeting['meeting_id'];
                $settings['zoom_password'] = $zoomMeeting['password'];
                $settings['zoom_start_url'] = $zoomMeeting['start_url'];
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Lỗi tạo Zoom meeting: ' . $e->getMessage());
            }
        } else {
            // Jitsi
            $meetingUrl = $this->generateJitsiUrl($roomCode);
        }

        $videoCall = VideoCall::create([
            'class_room_id' => $validated['class_room_id'],
            'host_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'room_code' => $roomCode,
            'meeting_url' => $meetingUrl,
            'scheduled_at' => $validated['scheduled_at'],
            'duration' => $validated['duration'] ?? 60,
            'is_recording' => $request->boolean('is_recording'),
            'status' => 'scheduled',
            'settings' => $settings,
        ]);

        // Notify students about new video call
        $notificationService = app(\App\Services\VideoCallNotificationService::class);
        $notificationService->notifyStudents($videoCall);

        return redirect()
            ->route('teacher.video-calls.show', $videoCall)
            ->with('success', 'Buổi học trực tuyến đã được tạo thành công! Mã phòng: ' . $roomCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(VideoCall $videoCall)
    {
        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $videoCall->load(['classRoom.subject', 'classRoom.students', 'attendance']);

        return view('teacher.video-calls.show', compact('videoCall'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VideoCall $videoCall)
    {
        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can't edit if already started or ended
        if (in_array($videoCall->status, ['in_progress', 'ended'])) {
            return redirect()
                ->route('teacher.video-calls.show', $videoCall)
                ->with('error', 'Không thể chỉnh sửa buổi học đã bắt đầu hoặc kết thúc.');
        }

        $classRooms = ClassRoom::whereHas('subject', function($q) {
            $q->where('teacher_id', Auth::id());
        })->get();

        return view('teacher.video-calls.edit', compact('videoCall', 'classRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VideoCall $videoCall)
    {
        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration' => 'nullable|integer|min:15|max:480',
            'is_recording' => 'boolean',
        ]);

        $videoCall->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'duration' => $validated['duration'] ?? 60,
            'is_recording' => $request->boolean('is_recording'),
        ]);

        return redirect()
            ->route('teacher.video-calls.show', $videoCall)
            ->with('success', 'Đã cập nhật thông tin buổi học!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VideoCall $videoCall)
    {
        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can't delete if in progress
        if ($videoCall->status === 'in_progress') {
            return redirect()
                ->back()
                ->with('error', 'Không thể xóa buổi học đang diễn ra!');
        }

        $videoCall->delete();

        return redirect()
            ->route('teacher.video-calls.index')
            ->with('success', 'Đã xóa buổi học trực tuyến!');
    }

    /**
     * Start the video call (UC-GV-003)
     */
    public function start(VideoCall $videoCall)
    {
        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($videoCall->status !== 'scheduled') {
            return redirect()
                ->back()
                ->with('error', 'Buổi học không ở trạng thái chờ bắt đầu!');
        }

        $videoCall->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()
            ->route('teacher.video-calls.join', $videoCall)
            ->with('success', 'Đã bắt đầu buổi học!');
    }

    /**
     * End the video call
     */
    public function end(VideoCall $videoCall)
    {
        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($videoCall->status !== 'in_progress') {
            return redirect()
                ->back()
                ->with('error', 'Buổi học chưa bắt đầu!');
        }

        $started = Carbon::parse($videoCall->started_at);
        $actualDuration = $started->diffInMinutes(now());

        $videoCall->update([
            'status' => 'ended',
            'ended_at' => now(),
            'duration' => $actualDuration,
        ]);

        return redirect()
            ->route('teacher.video-calls.show', $videoCall)
            ->with('success', 'Đã kết thúc buổi học! Thời lượng: ' . $actualDuration . ' phút');
    }

    /**
     * Join video call room (UC-GV-003)
     */
    public function join(VideoCall $videoCall)
    {
        // Check ownership or enrollment
        $isHost = $videoCall->host_id === Auth::id();
        $isStudent = $videoCall->classRoom->students->contains(Auth::id());

        if (!$isHost && !$isStudent) {
            abort(403, 'Bạn không có quyền tham gia buổi học này.');
        }

        // Auto-start if host joins and status is scheduled
        if ($isHost && $videoCall->status === 'scheduled') {
            $videoCall->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Check platform
        $platform = $videoCall->settings['platform'] ?? 'jitsi';
        
        if ($platform === 'zoom') {
            // For Zoom, redirect to Zoom URL directly
            if ($isHost && isset($videoCall->settings['zoom_start_url'])) {
                // Host uses start URL to have full control
                return redirect()->away($videoCall->settings['zoom_start_url']);
            } else {
                // Students and host (if no start_url) use regular join URL
                return redirect()->away($videoCall->meeting_url);
            }
        }

        // For Jitsi, render embedded room
        $user = Auth::user();
        $displayName = $user->name . ($isHost ? ' (Giáo viên)' : '');

        return view('teacher.video-calls.room', compact('videoCall', 'displayName', 'isHost'));
    }

    /**
     * Invite students (UC-GV-003)
     */
    public function invite(Request $request, VideoCall $videoCall)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'message' => 'nullable|string|max:500',
        ]);

        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $students = User::whereIn('id', $validated['student_ids'])->get();

        // TODO: Send notification
        
        return redirect()
            ->back()
            ->with('success', 'Đã gửi lời mời đến ' . $students->count() . ' học sinh!');
    }

    /**
     * Toggle recording
     */
    public function toggleRecording(VideoCall $videoCall)
    {
        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $videoCall->update([
            'is_recording' => !$videoCall->is_recording,
        ]);

        $status = $videoCall->is_recording ? 'bật' : 'tắt';

        return response()->json([
            'success' => true,
            'message' => "Đã {$status} ghi hình!",
            'is_recording' => $videoCall->is_recording,
        ]);
    }

    /**
     * Save recording URL (UC-GV-004)
     */
    public function saveRecording(Request $request, VideoCall $videoCall)
    {
        $validated = $request->validate([
            'recording_url' => 'required|url',
        ]);

        // Check ownership
        if ($videoCall->host_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $videoCall->update([
            'recording_url' => $validated['recording_url'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu link video ghi hình!',
        ]);
    }

    /**
     * Generate Jitsi Meet URL
     */
    protected function generateJitsiUrl($roomCode)
    {
        // Use Jitsi public instance or self-hosted
        $jitsiDomain = config('services.jitsi.domain', 'meet.jit.si');
        return "https://{$jitsiDomain}/{$roomCode}";
    }
}
