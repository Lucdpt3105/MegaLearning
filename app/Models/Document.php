<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'folder',
        'approval_status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'download_count',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
}
