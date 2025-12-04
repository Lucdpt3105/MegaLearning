<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Service to log user activities
 * Used for UC-ADM-052, UC-ADM-053 (Activity Logging)
 */
class ActivityLogger
{
    /**
     * Log an activity
     */
    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    /**
     * Log a successful login
     */
    public static function logLogin(?int $userId = null): void
    {
        ActivityLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => 'login',
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a failed login attempt
     */
    public static function logLoginFailed(string $reason): void
    {
        ActivityLog::create([
            'user_id' => null,
            'action' => 'login_failed',
            'description' => $reason,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a logout
     */
    public static function logLogout(): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'logout',
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log create action
     */
    public static function logCreate(string $entityType, int $entityId, array $newValues = []): void
    {
        self::log('create_' . $entityType, $entityType, $entityId, null, null, $newValues);
    }

    /**
     * Log update action
     */
    public static function logUpdate(
        string $entityType, 
        int $entityId, 
        array $oldValues = [], 
        array $newValues = []
    ): void {
        self::log('update_' . $entityType, $entityType, $entityId, null, $oldValues, $newValues);
    }

    /**
     * Log delete action
     */
    public static function logDelete(string $entityType, int $entityId, array $oldValues = []): void
    {
        self::log('delete_' . $entityType, $entityType, $entityId, null, $oldValues, null);
    }

    /**
     * Log exam submission
     */
    public static function logExamSubmission(int $examId, int $submissionId): void
    {
        self::log('submit_exam', 'exam', $examId, "Submission ID: {$submissionId}");
    }

    /**
     * Log document upload
     */
    public static function logDocumentUpload(int $documentId, string $filename): void
    {
        self::log('upload_document', 'document', $documentId, "Uploaded: {$filename}");
    }

    /**
     * Log document approval/rejection
     */
    public static function logDocumentModeration(int $documentId, string $status, ?string $reason = null): void
    {
        self::log(
            "moderate_document_{$status}", 
            'document', 
            $documentId, 
            $reason ? "Reason: {$reason}" : null
        );
    }

    /**
     * Log video call
     */
    public static function logVideoCall(string $action, int $videoCallId, ?array $data = null): void
    {
        self::log("video_call_{$action}", 'video_call', $videoCallId, null, null, $data);
    }

    /**
     * Log grading activity
     */
    public static function logGrading(int $submissionId, float $score): void
    {
        self::log('grade_submission', 'submission', $submissionId, "Score: {$score}");
    }
}
