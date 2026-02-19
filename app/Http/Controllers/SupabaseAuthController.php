<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\AdminNotificationService;
use App\Notifications\WelcomeNotification;

class SupabaseAuthController extends Controller
{
    protected $adminNotificationService;

    public function __construct(AdminNotificationService $adminNotificationService)
    {
        $this->adminNotificationService = $adminNotificationService;
    }

    /**
     * Handle the Supabase OAuth callback.
     * Frontend sends us the access_token after Supabase Google OAuth completes.
     */
    public function callback(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        $accessToken = $request->input('access_token');

        // Verify the token with Supabase and get user info
        $supabaseUrl = config('supabase.url');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'apikey' => config('supabase.anon_key'),
        ])->get("{$supabaseUrl}/auth/v1/user");

        if ($response->failed()) {
            Log::error('Supabase token verification failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return redirect('/login')->withErrors([
                'email' => 'Xác thực Google thất bại. Vui lòng thử lại.',
            ]);
        }

        $supabaseUser = $response->json();
        $supabaseId = $supabaseUser['id'] ?? null;
        $email = $supabaseUser['email'] ?? null;
        $userMetadata = $supabaseUser['user_metadata'] ?? [];
        $fullName = $userMetadata['full_name'] ?? $userMetadata['name'] ?? 'User';
        $avatarUrl = $userMetadata['avatar_url'] ?? $userMetadata['picture'] ?? null;

        if (!$email || !$supabaseId) {
            return redirect('/login')->withErrors([
                'email' => 'Không thể lấy thông tin email từ Google. Vui lòng thử lại.',
            ]);
        }

        // Find existing user by supabase_id or email
        $user = User::where('supabase_id', $supabaseId)->first()
              ?? User::where('email', $email)->first();

        if ($user) {
            // Link Supabase account if not already linked
            if (!$user->supabase_id) {
                $user->update([
                    'supabase_id' => $supabaseId,
                    'avatar_url' => $avatarUrl,
                ]);
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'supabase_id' => $supabaseId,
                'avatar_url' => $avatarUrl,
                'password' => bcrypt(Str::random(32)),
                'email_verified_at' => now(), // Google already verified email
            ]);

            // Default role: student
            $user->assignRole('student');

            // Notify admin about new registration
            $this->adminNotificationService->notifyNewStudentRegistration($user);

            // Send welcome email to user
            $user->notify(new WelcomeNotification('google'));
        }

        // Check if user is locked
        if ($user->is_locked) {
            return redirect('/login')->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ]);
        }

        // Login the user
        Auth::login($user, true);
        $request->session()->regenerate();

        // Update last login time
        $user->update(['last_login_at' => now()]);

        // Determine redirect URL based on role
        if ($user->hasRole('admin')) {
            $redirectUrl = '/admin';
        } elseif ($user->hasRole('teacher')) {
            $redirectUrl = '/teacher/dashboard';
        } else {
            $redirectUrl = '/student/dashboard';
        }

        // Return JSON for AJAX requests, redirect for regular requests
        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }
}
