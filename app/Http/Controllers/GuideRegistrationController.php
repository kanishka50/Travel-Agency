<?php

namespace App\Http\Controllers;

use App\Models\GuideRegistrationRequest;
use App\Models\Guide;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuideRegistrationConfirmation;
use App\Mail\GuideRegistrationAdminNotification;
use Illuminate\Support\Facades\Log;

class GuideRegistrationController extends Controller
{
    /**
     * Show the guide registration form
     */
    public function create()
    {
        $regions = Region::getActiveRegions();

        return view('auth.guide-register', compact('regions'));
    }

    /**
     * Store guide registration request
     */
    public function store(Request $request)
    {
        Log::info('=== GUIDE REGISTRATION START ===');
        Log::info('Form data received (excluding files)', [
            'data' => $request->except(['profile_photo', 'national_id_doc', 'driving_license', 'guide_certificate', 'language_qualification'])
        ]);

        Log::info('Files received', [
            'profile_photo' => $request->hasFile('profile_photo'),
            'national_id_doc' => $request->hasFile('national_id_doc'),
            'driving_license' => $request->hasFile('driving_license'),
            'guide_certificate' => $request->hasFile('guide_certificate'),
            'language_qualification' => $request->hasFile('language_qualification'),
        ]);

        try {
            Log::info('Starting validation...');

            $validated = $request->validate([
                // Personal Information
                'full_name' => ['required', 'string', 'max:255'],
                'guide_type' => ['required', 'string', 'in:' . implode(',', array_keys(Guide::GUIDE_TYPES))],
                'email' => ['required', 'email', 'max:255', 'unique:guide_registration_requests'],
                'phone' => ['required', 'string', 'max:50'],
                'national_id' => ['required', 'string', 'max:50'],
                'years_experience' => ['required', 'integer', 'min:0', 'max:50'],

                // Multi-select fields (arrays)
                'languages' => ['required', 'array', 'min:1'],
                'languages.*' => ['string'],
                'expertise_areas' => ['nullable', 'array'],
                'expertise_areas.*' => ['string'],
                'regions_can_guide' => ['required', 'array', 'min:1'],
                'regions_can_guide.*' => ['string'],

                // Experience description
                'experience_description' => ['required', 'string', 'min:50', 'max:1000'],

                // Profile photo (required)
                'profile_photo' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB

                // Documents (optional)
                'national_id_doc' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'], // 10MB
                'driving_license' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
                'guide_certificate' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
                'language_qualification' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
            ]);

            Log::info('Validation passed successfully', [
                'validated_fields' => array_keys($validated)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('VALIDATION FAILED', [
                'errors' => $e->errors(),
                'failed_fields' => array_keys($e->errors())
            ]);

            return back()
                ->withErrors($e->errors())
                ->withInput();
        }

        Log::info('Starting database transaction...');
        DB::beginTransaction();

        try {
            // Upload profile photo
            Log::info('Step 1: Uploading profile photo...');

            if (!$request->hasFile('profile_photo')) {
                throw new \Exception('Profile photo file not found');
            }

            $profilePhoto = $request->file('profile_photo');
            $profilePhotoPath = $profilePhoto->store('guide-documents/profile-photos', 'public');
            Log::info('Profile photo uploaded', ['path' => $profilePhotoPath]);

            // Upload documents
            Log::info('Step 2: Processing document uploads...');

            // National ID Document
            $nationalIdDocPath = '';
            if ($request->hasFile('national_id_doc')) {
                $nationalIdDocPath = $request->file('national_id_doc')->store('guide-documents/national-id', 'public');
                Log::info('National ID document uploaded', ['path' => $nationalIdDocPath]);
            }

            // Driving License (optional)
            $drivingLicensePath = null;
            if ($request->hasFile('driving_license')) {
                $drivingLicensePath = $request->file('driving_license')->store('guide-documents/driving-license', 'public');
                Log::info('Driving license uploaded', ['path' => $drivingLicensePath]);
            }

            // Guide Certificate (optional)
            $guideCertificatePath = null;
            if ($request->hasFile('guide_certificate')) {
                $guideCertificatePath = $request->file('guide_certificate')->store('guide-documents/certificates', 'public');
                Log::info('Guide certificate uploaded', ['path' => $guideCertificatePath]);
            }

            // Language Certificates (optional, JSON array)
            $languageCertificates = null;
            if ($request->hasFile('language_qualification')) {
                $langCertPath = $request->file('language_qualification')->store('guide-documents/language-certs', 'public');
                $languageCertificates = json_encode([$langCertPath]);
                Log::info('Language certificate uploaded', ['path' => $langCertPath]);
            }

            // Create registration request
            Log::info('Step 3: Creating registration request in database...');

            $dataToInsert = [
                'full_name' => $validated['full_name'],
                'guide_type' => $validated['guide_type'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'national_id' => $validated['national_id'],
                'years_experience' => $validated['years_experience'],
                'languages' => $validated['languages'],
                'expertise_areas' => $validated['expertise_areas'] ?? [],
                'regions_can_guide' => $validated['regions_can_guide'],
                'experience_description' => $validated['experience_description'],
                'profile_photo' => $profilePhotoPath,
                'status' => 'documents_pending',

                // Document fields
                'national_id_document' => $nationalIdDocPath,
                'driving_license' => $drivingLicensePath,
                'guide_certificate' => $guideCertificatePath,
                'language_certificates' => $languageCertificates,
            ];

            $registrationRequest = GuideRegistrationRequest::create($dataToInsert);

            Log::info('Registration request created', [
                'id' => $registrationRequest->id,
                'email' => $registrationRequest->email
            ]);

            // Commit transaction
            Log::info('Step 4: Committing database transaction...');
            DB::commit();
            Log::info('Database transaction committed successfully');

            // Send emails
            Log::info('Step 5: Sending confirmation emails...');

            try {
                Mail::to($registrationRequest->email)->send(
                    new GuideRegistrationConfirmation($registrationRequest)
                );
                Log::info('Confirmation email sent to guide');
            } catch (\Exception $e) {
                Log::error('Failed to send confirmation email to guide', [
                    'error' => $e->getMessage()
                ]);
            }

            try {
                $adminEmail = env('MAIL_ADMIN_EMAIL', 'admin@example.com');
                Mail::to($adminEmail)->send(
                    new GuideRegistrationAdminNotification($registrationRequest)
                );
                Log::info('Notification email sent to admin');
            } catch (\Exception $e) {
                Log::error('Failed to send notification email to admin', [
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('=== GUIDE REGISTRATION SUCCESS ===', [
                'registration_id' => $registrationRequest->id,
                'guide_email' => $registrationRequest->email
            ]);

            return redirect()->route('guide-registration.success')
                ->with('registration_id', $registrationRequest->id)
                ->with('success', 'Registration submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('=== GUIDE REGISTRATION FAILED ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('auth.guide-register-success');
    }
}
