<?php

namespace App\Services;

use App\Mail\GuideApproved;
use App\Mail\GuideRejected;
use App\Models\Guide;
use App\Models\GuideRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GuideApprovalService
{
    /**
     * Generate unique Guide ID in format: GD-YYYY-NNNN
     * Example: GD-2025-0001, GD-2025-0002
     */
    public function generateGuideId(): string
    {
        $year = now()->year;
        $prefix = "GD-{$year}-";

        // Get the last guide ID for this year
        $lastGuide = Guide::where('guide_id_number', 'LIKE', "{$prefix}%")
            ->orderBy('guide_id_number', 'desc')
            ->first();

        if ($lastGuide) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastGuide->guide_id_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First guide of the year
            $newNumber = 1;
        }

        // Format with leading zeros (4 digits)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Approve guide registration and create/convert User + Guide accounts
     *
     * @param GuideRegistrationRequest $request
     * @param int $reviewerId Admin ID who approved
     * @return array ['success' => bool, 'message' => string, 'guide' => Guide|null, 'password' => string|null]
     */
    public function approveGuide(GuideRegistrationRequest $request, int $reviewerId): array
    {
        try {
            return DB::transaction(function () use ($request, $reviewerId) {
                // Generate random password
                $password = Str::password(12, true, true, false); // 12 chars, letters, numbers, no symbols
                $passwordChanged = false;

                // Check if user already exists
                $existingUser = User::where('email', $request->email)->first();

                if ($existingUser) {
                    // User already exists - convert them to guide
                    $user = $existingUser;

                    // Update existing user to guide type
                    $user->update([
                        'user_type' => 'guide',
                        'status' => 'active',
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);

                    // Only generate new password if user was tourist (they applied through form)
                    // If already a guide somehow, keep their existing password
                    if ($existingUser->user_type === 'tourist') {
                        $user->update(['password' => Hash::make($password)]);
                        $passwordChanged = true;
                    } else {
                        $password = null; // Don't show password for existing guide users
                    }
                } else {
                    // Create new User account
                    $user = User::create([
                        'email' => $request->email,
                        'password' => Hash::make($password),
                        'user_type' => 'guide',
                        'status' => 'active',
                        'email_verified_at' => now(), // Auto-verify since admin approved
                    ]);
                    $passwordChanged = true;
                }

                // Generate unique Guide ID
                $guideId = $this->generateGuideId();

                // Create Guide profile
                $guide = Guide::create([
                    'user_id' => $user->id,
                    'guide_id_number' => $guideId,
                    'full_name' => $request->full_name,
                    'guide_type' => $request->guide_type ?? 'not_specified',
                    'phone' => $request->phone,
                    'national_id' => $request->national_id,
                    'bio' => $request->experience_description,
                    'profile_photo' => $request->profile_photo,
                    'languages' => $request->languages,
                    'expertise_areas' => $request->expertise_areas,
                    'regions_can_guide' => $request->regions_can_guide,
                    'years_experience' => $request->years_experience,
                    'commission_rate' => 90.00, // Default 90% commission
                ]);

                // Update registration request
                $request->update([
                    'status' => 'approved',
                    'reviewed_by' => $reviewerId,
                    'reviewed_at' => now(),
                ]);

                // Send approval email if password was set/changed
                if ($passwordChanged && $password) {
                    try {
                        Mail::to($user->email)->send(new GuideApproved($guide, $password, $guideId));
                    } catch (\Exception $e) {
                        // Log email error but don't fail the approval
                        \Log::error('Failed to send guide approval email: ' . $e->getMessage());
                    }
                }

                $message = "Guide approved successfully! Guide ID: {$guideId}";
                if ($existingUser) {
                    $message .= "\n(Existing user account converted to guide)";
                }
                if ($passwordChanged) {
                    $message .= "\nApproval email sent to guide.";
                }

                return [
                    'success' => true,
                    'message' => $message,
                    'guide' => $guide,
                    'user' => $user,
                    'password' => $passwordChanged ? $password : null,
                    'guide_id' => $guideId,
                    'password_changed' => $passwordChanged,
                    'email_sent' => $passwordChanged,
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to approve guide: ' . $e->getMessage(),
                'guide' => null,
                'user' => null,
                'password' => null,
                'guide_id' => null,
                'password_changed' => false,
            ];
        }
    }

    /**
     * Reject guide registration with reason
     *
     * @param GuideRegistrationRequest $request
     * @param int $reviewerId Admin ID who rejected
     * @param string $reason Rejection reason
     * @return array ['success' => bool, 'message' => string]
     */
    public function rejectGuide(GuideRegistrationRequest $request, int $reviewerId, string $reason): array
    {
        try {
            $request->update([
                'status' => 'rejected',
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
                'admin_notes' => $reason,
            ]);

            // Send rejection email
            try {
                Mail::to($request->email)->send(new GuideRejected($request, $reason));
            } catch (\Exception $e) {
                // Log email error but don't fail the rejection
                \Log::error('Failed to send guide rejection email: ' . $e->getMessage());
            }

            return [
                'success' => true,
                'message' => 'Guide registration rejected. Rejection email sent to applicant.',
                'email_sent' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to reject guide: ' . $e->getMessage(),
                'email_sent' => false,
            ];
        }
    }

    /**
     * Check if registration request can be approved
     *
     * @param GuideRegistrationRequest $request
     * @return array ['canApprove' => bool, 'reason' => string|null]
     */
    public function canApprove(GuideRegistrationRequest $request): array
    {
        // Check if already approved
        if ($request->status === 'approved') {
            return [
                'canApprove' => false,
                'reason' => 'This request has already been approved.',
            ];
        }

        // Check if email already exists as a guide (not just user - tourists can become guides)
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser && $existingUser->user_type === 'guide') {
            return [
                'canApprove' => false,
                'reason' => 'A guide account with this email already exists.',
            ];
        }

        // Check if national ID already exists as a guide
        if (Guide::where('national_id', $request->national_id)->exists()) {
            return [
                'canApprove' => false,
                'reason' => 'A guide with this National ID already exists.',
            ];
        }

        return [
            'canApprove' => true,
            'reason' => null,
        ];
    }

    /**
     * Check if registration request can be rejected
     *
     * @param GuideRegistrationRequest $request
     * @return array ['canReject' => bool, 'reason' => string|null]
     */
    public function canReject(GuideRegistrationRequest $request): array
    {
        // Check if already approved (shouldn't reject approved requests)
        if ($request->status === 'approved') {
            return [
                'canReject' => false,
                'reason' => 'Cannot reject an already approved request.',
            ];
        }

        // Check if already rejected
        if ($request->status === 'rejected') {
            return [
                'canReject' => false,
                'reason' => 'This request has already been rejected.',
            ];
        }

        return [
            'canReject' => true,
            'reason' => null,
        ];
    }
}
