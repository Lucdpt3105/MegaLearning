<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ZoomService
{
    private string $accountId;
    private string $clientId;
    private string $clientSecret;
    private ?string $sdkKey;
    private string $baseUrl = 'https://api.zoom.us/v2';

    public function __construct()
    {
        $this->accountId = config('services.zoom.account_id');
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->sdkKey = config('services.zoom.sdk_key');
    }

    /**
     * Get OAuth access token for Zoom API
     */
    private function getAccessToken(): string
    {
        // Cache token for 55 minutes (Zoom tokens expire in 1 hour)
        return Cache::remember('zoom_access_token', 3300, function () {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->failed()) {
                throw new \Exception('Failed to get Zoom access token: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    /**
     * Create a Zoom meeting
     * 
     * @param array $data Meeting configuration
     * @return array Meeting details with join_url, meeting_id, password
     */
    public function createMeeting(array $data): array
    {
        $token = $this->getAccessToken();

        // Prepare meeting settings
        $meetingData = [
            'topic' => $data['topic'] ?? 'Video Call',
            'type' => 2, // Scheduled meeting
            'start_time' => isset($data['start_time']) 
                ? Carbon::parse($data['start_time'])->format('Y-m-d\TH:i:s') 
                : now()->format('Y-m-d\TH:i:s'),
            'duration' => $data['duration'] ?? 60, // minutes
            'timezone' => 'Asia/Ho_Chi_Minh',
            'agenda' => $data['agenda'] ?? '',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
                'mute_upon_entry' => true,
                'watermark' => false,
                'auto_recording' => $data['auto_recording'] ?? 'none', // 'none', 'local', 'cloud'
                'waiting_room' => true,
                'allow_multiple_devices' => true,
            ],
        ];

        $response = Http::withToken($token)
            ->post($this->baseUrl . '/users/me/meetings', $meetingData);

        if ($response->failed()) {
            throw new \Exception('Failed to create Zoom meeting: ' . $response->body());
        }

        $meeting = $response->json();

        return [
            'meeting_id' => $meeting['id'],
            'meeting_url' => $meeting['join_url'],
            'start_url' => $meeting['start_url'], // Host's URL to start
            'password' => $meeting['password'] ?? null,
            'encrypted_password' => $meeting['encrypted_password'] ?? null,
        ];
    }

    /**
     * Get meeting details
     */
    public function getMeeting(string $meetingId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->get($this->baseUrl . '/meetings/' . $meetingId);

        if ($response->failed()) {
            throw new \Exception('Failed to get Zoom meeting: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Update meeting
     */
    public function updateMeeting(string $meetingId, array $data): bool
    {
        $token = $this->getAccessToken();

        $updateData = [];
        
        if (isset($data['topic'])) {
            $updateData['topic'] = $data['topic'];
        }
        
        if (isset($data['start_time'])) {
            $updateData['start_time'] = Carbon::parse($data['start_time'])->format('Y-m-d\TH:i:s');
        }
        
        if (isset($data['duration'])) {
            $updateData['duration'] = $data['duration'];
        }

        $response = Http::withToken($token)
            ->patch($this->baseUrl . '/meetings/' . $meetingId, $updateData);

        return $response->successful();
    }

    /**
     * Delete meeting
     */
    public function deleteMeeting(string $meetingId): bool
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->delete($this->baseUrl . '/meetings/' . $meetingId);

        return $response->successful();
    }

    /**
     * Get meeting recordings
     */
    public function getRecordings(string $meetingId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->get($this->baseUrl . '/meetings/' . $meetingId . '/recordings');

        if ($response->failed()) {
            return [];
        }

        return $response->json('recording_files', []);
    }

    /**
     * Generate SDK signature for Zoom Web SDK (client-side)
     * This should be called from controller and passed to frontend
     */
    public function generateSDKSignature(string $meetingNumber, int $role = 0): array
    {
        // Role: 0 = participant, 1 = host
        $iat = time();
        $exp = $iat + 60 * 60 * 2; // Token valid for 2 hours

        $payload = [
            'sdkKey' => $this->sdkKey,
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => $iat,
            'exp' => $exp,
            'tokenExp' => $exp,
        ];

        // Note: For production, you should use proper JWT library
        // This is a simplified version
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", $this->clientSecret, true);
        $signature = base64_encode($signature);

        return [
            'signature' => "$header.$payload.$signature",
            'sdkKey' => $this->sdkKey,
        ];
    }

    /**
     * Get SDK credentials for frontend
     */
    public function getSDKCredentials(): array
    {
        return [
            'sdkKey' => $this->sdkKey,
            'sdkSecret' => $this->clientSecret,
        ];
    }
}
