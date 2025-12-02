<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Complaint extends Model
{
    protected $fillable = [
        'complaint_number',
        'booking_id',
        'filed_by',
        'filed_by_type',
        'complainant_type',
        'complainant_id',
        'against_user_id',
        'against_type',
        'against_id',
        'complaint_type',
        'subject',
        'description',
        'evidence_files',
        'status',
        'priority',
        'assigned_to',
        'admin_notes',
        'resolution_summary',
        'visible_to_defendant',
        'resolved_at',
    ];

    protected $casts = [
        'evidence_files' => 'array',
        'visible_to_defendant' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate complaint number
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

        static::creating(function ($complaint) {
            if (empty($complaint->complaint_number)) {
                $complaint->complaint_number = 'CMP-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the booking related to this complaint
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who filed the complaint
     */
    public function filedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    /**
     * Get the complainant (polymorphic)
     */
    public function complainant(): MorphTo
    {
        return $this->morphTo('complainant', 'complainant_type', 'complainant_id');
    }

    /**
     * Get the defendant (polymorphic - who the complaint is against)
     */
    public function against(): MorphTo
    {
        return $this->morphTo('against', 'against_type', 'against_id');
    }

    /**
     * Get the user being complained about
     */
    public function againstUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'against_user_id');
    }

    /**
     * Get the admin assigned to this complaint
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    /**
     * Get all responses for this complaint
     */
    public function responses(): HasMany
    {
        return $this->hasMany(ComplaintResponse::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get only public responses (visible to parties)
     */
    public function publicResponses(): HasMany
    {
        return $this->responses()->where('internal_only', false);
    }

    /**
     * Get only internal notes (admin only)
     */
    public function internalNotes(): HasMany
    {
        return $this->responses()->where('internal_only', true);
    }

    /**
     * Check if complaint is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if complaint is under review
     */
    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    /**
     * Check if complaint is resolved
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Check if complaint is closed
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Check if user can view this complaint
     */
    public function canBeViewedBy($user, $userType): bool
    {
        // Admins can see everything
        if ($userType === 'admin') {
            return true;
        }

        // Complainant can always see their complaint
        if ($this->filed_by == $user->user_id ||
            ($this->complainant_type === $userType && $this->complainant_id == $user->id)) {
            return true;
        }

        // Defendant can see if visible_to_defendant is true
        if ($this->visible_to_defendant) {
            if ($this->against_user_id == $user->user_id ||
                ($this->against_type === $userType && $this->against_id == $user->id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Scope to get complaints filed by a user
     */
    public function scopeFiledBy($query, $userId, $userType)
    {
        return $query->where(function($q) use ($userId, $userType) {
            $q->where('filed_by', $userId)
              ->orWhere(function($q2) use ($userId, $userType) {
                  $q2->where('complainant_type', $userType)
                     ->where('complainant_id', $userId);
              });
        });
    }

    /**
     * Scope to get complaints against a user
     */
    public function scopeAgainst($query, $userId, $userType)
    {
        return $query->where(function($q) use ($userId, $userType) {
            $q->where('against_user_id', $userId)
              ->orWhere(function($q2) use ($userId, $userType) {
                  $q2->where('against_type', $userType)
                     ->where('against_id', $userId);
              });
        });
    }

    /**
     * Scope to get open complaints
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope to get unresolved complaints
     */
    public function scopeUnresolved($query)
    {
        return $query->whereIn('status', ['open', 'under_review']);
    }

    /**
     * Get complainant name
     */
    public function getComplainantName(): string
    {
        // Try to get name from polymorphic complainant (tourist/guide/admin models)
        if ($this->complainant_id && $this->complainant_type) {
            // Load the polymorphic relationship if not already loaded
            if (!$this->relationLoaded('complainant')) {
                $this->load('complainant');
            }

            if ($this->complainant) {
                // Tourist, Guide, and Admin models all have 'full_name' attribute
                if (isset($this->complainant->full_name)) {
                    return $this->complainant->full_name;
                }
            }
        }

        // Fallback: try to get from User model (though users table doesn't have name column)
        if ($this->filed_by) {
            if (!$this->relationLoaded('filedByUser')) {
                $this->load('filedByUser');
            }

            if ($this->filedByUser && isset($this->filedByUser->email)) {
                // Return email as fallback if no name
                return $this->filedByUser->email;
            }
        }

        return 'Unknown User';
    }

    /**
     * Get defendant name
     */
    public function getDefendantName(): string
    {
        // Try to get name from polymorphic against (tourist/guide/admin models)
        if ($this->against_id && $this->against_type) {
            // Load the polymorphic relationship if not already loaded
            if (!$this->relationLoaded('against')) {
                $this->load('against');
            }

            if ($this->against) {
                // Tourist, Guide, and Admin models all have 'full_name' attribute
                if (isset($this->against->full_name)) {
                    return $this->against->full_name;
                }
            }
        }

        // Fallback: try to get from User model (though users table doesn't have name column)
        if ($this->against_user_id) {
            if (!$this->relationLoaded('againstUser')) {
                $this->load('againstUser');
            }

            if ($this->againstUser && isset($this->againstUser->email)) {
                // Return email as fallback if no name
                return $this->againstUser->email;
            }
        }

        return 'Unknown User';
    }

    /**
     * Get complaint type label
     */
    public function getComplaintTypeLabel(): string
    {
        return match($this->complaint_type) {
            'service_quality' => 'Service Quality',
            'safety_concern' => 'Safety Concern',
            'unprofessional_behavior' => 'Unprofessional Behavior',
            'payment_issue' => 'Payment Issue',
            'cancellation_dispute' => 'Cancellation Dispute',
            'other' => 'Other',
            default => $this->complaint_type,
        };
    }

    /**
     * Get priority label
     */
    public function getPriorityLabel(): string
    {
        return ucfirst($this->priority);
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'open' => 'Open',
            'under_review' => 'Under Review',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => $this->status,
        };
    }
}
