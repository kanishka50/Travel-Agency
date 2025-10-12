<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideRegistrationRequest extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'national_id',
        'years_experience',
        'languages',
        'expertise_areas',
        'regions_can_guide',
        'experience_description',
        'profile_photo',
        'status',
        
        // Document fields (stored in same table)
        'national_id_document',
        'driving_license',
        'guide_certificate',
        'language_certificates',
        'vehicle_photos',
        'vehicle_license',
        
        // Admin fields
        'admin_notes',
        'interview_date',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'languages' => 'array',
        'expertise_areas' => 'array',
        'regions_can_guide' => 'array',
        'language_certificates' => 'array', // JSON column
        'vehicle_photos' => 'array', // JSON column
        'interview_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    // Relationship to admin who reviewed
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    // Helper methods for status
    public function isDocumentsPending(): bool
    {
        return $this->status === 'documents_pending';
    }

    public function isDocumentsVerified(): bool
    {
        return $this->status === 'documents_verified';
    }

    public function isInterviewScheduled(): bool
    {
        return $this->status === 'interview_scheduled';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // Helper to get profile photo URL
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo 
            ? asset('storage/' . $this->profile_photo) 
            : asset('images/default-avatar.png');
    }

    // Helper to get national ID document URL
    public function getNationalIdDocumentUrlAttribute(): ?string
    {
        return $this->national_id_document 
            ? asset('storage/' . $this->national_id_document) 
            : null;
    }

    // Helper to get driving license URL
    public function getDrivingLicenseUrlAttribute(): ?string
    {
        return $this->driving_license 
            ? asset('storage/' . $this->driving_license) 
            : null;
    }

    // Helper to get guide certificate URL
    public function getGuideCertificateUrlAttribute(): ?string
    {
        return $this->guide_certificate 
            ? asset('storage/' . $this->guide_certificate) 
            : null;
    }

    // Helper to get vehicle license URL
    public function getVehicleLicenseUrlAttribute(): ?string
    {
        return $this->vehicle_license 
            ? asset('storage/' . $this->vehicle_license) 
            : null;
    }
}