<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ComplaintResponse extends Model
{
    public $timestamps = false; // Uses created_at only

    protected $fillable = [
        'complaint_id',
        'admin_id',
        'responder_type',
        'responder_id',
        'response_text',
        'response_type',
        'visible_to_complainant',
        'visible_to_defendant',
        'internal_only',
        'attachments',
    ];

    protected $casts = [
        'visible_to_complainant' => 'boolean',
        'visible_to_defendant' => 'boolean',
        'internal_only' => 'boolean',
        'attachments' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the complaint this response belongs to
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Get the admin who wrote this response
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Define morph map for polymorphic relationships
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'admin' => \App\Models\Admin::class,
            'tourist' => \App\Models\Tourist::class,
            'guide' => \App\Models\Guide::class,
        ]);
    }

    /**
     * Get the responder (polymorphic - can be admin, tourist, or guide)
     */
    public function responder(): MorphTo
    {
        return $this->morphTo('responder', 'responder_type', 'responder_id');
    }

    /**
     * Check if this is an admin response
     */
    public function isFromAdmin(): bool
    {
        return $this->responder_type === 'admin' || $this->admin_id !== null;
    }

    /**
     * Check if this is a tourist response
     */
    public function isFromTourist(): bool
    {
        return $this->responder_type === 'tourist';
    }

    /**
     * Check if this is a guide response
     */
    public function isFromGuide(): bool
    {
        return $this->responder_type === 'guide';
    }

    /**
     * Check if this response is internal (admin only)
     */
    public function isInternal(): bool
    {
        return $this->internal_only;
    }

    /**
     * Check if this response is public (visible to parties)
     */
    public function isPublic(): bool
    {
        return !$this->internal_only;
    }

    /**
     * Check if user can view this response
     */
    public function canBeViewedBy($user, $userType): bool
    {
        // Admins can see everything
        if ($userType === 'admin') {
            return true;
        }

        // Internal notes are admin-only
        if ($this->internal_only) {
            return false;
        }

        // Check if this is the complaint's complainant
        $complaint = $this->complaint;
        $isComplainant = false;

        if ($complaint->filed_by == $user->user_id ||
            ($complaint->complainant_type === $userType && $complaint->complainant_id == $user->id)) {
            $isComplainant = true;
        }

        // Check if this is the complaint's defendant
        $isDefendant = false;

        if ($complaint->against_user_id == $user->user_id ||
            ($complaint->against_type === $userType && $complaint->against_id == $user->id)) {
            $isDefendant = true;
        }

        // Check visibility flags
        if ($isComplainant && $this->visible_to_complainant) {
            return true;
        }

        if ($isDefendant && $this->visible_to_defendant) {
            return true;
        }

        return false;
    }

    /**
     * Get response type label
     */
    public function getResponseTypeLabel(): string
    {
        return match($this->response_type) {
            'email' => 'Email Response',
            'internal_note' => 'Internal Note',
            'status_update' => 'Status Update',
            'public_note' => 'Public Note',
            'request_info' => 'Information Request',
            'evidence_submission' => 'Evidence Submitted',
            'defendant_response' => 'Defendant Response',
            'complainant_response' => 'Complainant Response',
            default => $this->response_type,
        };
    }

    /**
     * Get responder name
     */
    public function getResponderName(): string
    {
        // Check if this is an admin response
        if ($this->isFromAdmin()) {
            // Load admin relationship if not already loaded
            if (!$this->relationLoaded('admin')) {
                $this->load('admin');
            }

            if ($this->admin && isset($this->admin->full_name)) {
                return $this->admin->full_name;
            }

            return 'Admin';
        }

        // Load polymorphic responder relationship if not already loaded
        if ($this->responder_id && $this->responder_type) {
            if (!$this->relationLoaded('responder')) {
                $this->load('responder');
            }

            if ($this->responder) {
                // Tourist, Guide, and Admin models all have 'full_name' attribute
                if (isset($this->responder->full_name)) {
                    return $this->responder->full_name;
                }
            }
        }

        return 'Unknown User';
    }

    /**
     * Scope to get only public responses
     */
    public function scopePublic($query)
    {
        return $query->where('internal_only', false);
    }

    /**
     * Scope to get only internal notes
     */
    public function scopeInternal($query)
    {
        return $query->where('internal_only', true);
    }

    /**
     * Scope to get responses visible to complainant
     */
    public function scopeVisibleToComplainant($query)
    {
        return $query->where('visible_to_complainant', true)
                     ->where('internal_only', false);
    }

    /**
     * Scope to get responses visible to defendant
     */
    public function scopeVisibleToDefendant($query)
    {
        return $query->where('visible_to_defendant', true)
                     ->where('internal_only', false);
    }
}
