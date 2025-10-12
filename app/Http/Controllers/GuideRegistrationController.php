<?php

namespace App\Http\Controllers;

use App\Models\GuideRegistrationRequest;
use App\Models\GuideRegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        return view('guide-registration.create');
    }

    /**
     * Store guide registration request
     */
    /**
 * Store guide registration request
 */
/**
 * Store guide registration request
 */
public function store(Request $request)
{
    // Log 1: Form submission received
    Log::info('=== GUIDE REGISTRATION START ===');
    Log::info('Form data received (excluding files)', [
        'data' => $request->except(['profile_photo', 'national_id_doc', 'driving_license', 'guide_certificate', 'language_qualification', 'vehicle_photo', 'vehicle_license'])
    ]);

    // Log 2: Check if files are present
    Log::info('Files received', [
        'profile_photo' => $request->hasFile('profile_photo'),
        'national_id_doc' => $request->hasFile('national_id_doc'),
        'driving_license' => $request->hasFile('driving_license'),
        'guide_certificate' => $request->hasFile('guide_certificate'),
        'language_qualification' => $request->hasFile('language_qualification'),
        'vehicle_photo' => $request->hasFile('vehicle_photo'),
        'vehicle_license' => $request->hasFile('vehicle_license'),
    ]);

    try {
        // Log 3: Starting validation
        Log::info('Starting validation...');

        // Validate all fields
        $validated = $request->validate([
            // Personal Information
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:guide_registration_requests'],
            'phone' => ['required', 'string', 'max:50'],
            'national_id' => ['required', 'string', 'max:50'],
            'years_experience' => ['required', 'integer', 'min:0', 'max:50'],
            
            // Multi-select fields (arrays) - Form sends these names
            'languages' => ['required', 'array', 'min:1'],
            'languages.*' => ['string'],
            'expertise_areas' => ['required', 'array', 'min:1'],
            'expertise_areas.*' => ['string'],
            'regions_can_guide' => ['required', 'array', 'min:1'],
            'regions_can_guide.*' => ['string'],
            
            // Experience description
            'experience_description' => ['nullable', 'string', 'max:1000'],
            
            // Profile photo (required)
            'profile_photo' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB
            
            // Documents (optional)
            'national_id_doc' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'], // 10MB
            'driving_license' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
            'guide_certificate' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
            'language_qualification' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
            'vehicle_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'vehicle_license' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
        ]);

        // Log 4: Validation passed
        Log::info('✓ Validation passed successfully', [
            'validated_fields' => array_keys($validated)
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Log 5: Validation failed
        Log::error('✗ VALIDATION FAILED', [
            'errors' => $e->errors(),
            'failed_fields' => array_keys($e->errors())
        ]);
        
        return back()
            ->withErrors($e->errors())
            ->withInput();
    }

    // Log 6: Starting database transaction
    Log::info('Starting database transaction...');
    DB::beginTransaction();

    try {
        // Log 7: Uploading profile photo
        Log::info('Step 1: Uploading profile photo...');
        
        if (!$request->hasFile('profile_photo')) {
            throw new \Exception('Profile photo file not found');
        }

        $profilePhoto = $request->file('profile_photo');
        Log::info('Profile photo details', [
            'original_name' => $profilePhoto->getClientOriginalName(),
            'size' => $profilePhoto->getSize(),
            'mime_type' => $profilePhoto->getMimeType()
        ]);

        $profilePhotoPath = $profilePhoto->store('guide-documents/profile-photos', 'public');
        Log::info('✓ Profile photo uploaded', [
            'path' => $profilePhotoPath
        ]);

        // Log 8: Uploading documents
        Log::info('Step 2: Processing document uploads...');
        
        // National ID Document
        $nationalIdDocPath = '';
        if ($request->hasFile('national_id_doc')) {
            $nationalIdDocPath = $request->file('national_id_doc')->store('guide-documents/national-id', 'public');
            Log::info('✓ National ID document uploaded', ['path' => $nationalIdDocPath]);
        }

        // Driving License (optional)
        $drivingLicensePath = null;
        if ($request->hasFile('driving_license')) {
            $drivingLicensePath = $request->file('driving_license')->store('guide-documents/driving-license', 'public');
            Log::info('✓ Driving license uploaded', ['path' => $drivingLicensePath]);
        }

        // Guide Certificate (optional)
        $guideCertificatePath = null;
        if ($request->hasFile('guide_certificate')) {
            $guideCertificatePath = $request->file('guide_certificate')->store('guide-documents/certificates', 'public');
            Log::info('✓ Guide certificate uploaded', ['path' => $guideCertificatePath]);
        }

        // Language Certificates (optional, JSON array)
        $languageCertificates = null;
        if ($request->hasFile('language_qualification')) {
            $langCertPath = $request->file('language_qualification')->store('guide-documents/language-certs', 'public');
            $languageCertificates = json_encode([$langCertPath]);
            Log::info('✓ Language certificate uploaded', ['path' => $langCertPath]);
        }

        // Vehicle Photos (optional, JSON array)
        $vehiclePhotos = null;
        if ($request->hasFile('vehicle_photo')) {
            $vehiclePhotoPath = $request->file('vehicle_photo')->store('guide-documents/vehicles', 'public');
            $vehiclePhotos = json_encode([$vehiclePhotoPath]);
            Log::info('✓ Vehicle photo uploaded', ['path' => $vehiclePhotoPath]);
        }

        // Vehicle License (optional)
        $vehicleLicensePath = null;
        if ($request->hasFile('vehicle_license')) {
            $vehicleLicensePath = $request->file('vehicle_license')->store('guide-documents/vehicle-licenses', 'public');
            Log::info('✓ Vehicle license uploaded', ['path' => $vehicleLicensePath]);
        }

        // Log 9: Creating registration request
        Log::info('Step 3: Creating registration request in database...');
        
        $dataToInsert = [
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'national_id' => $validated['national_id'],
            'years_experience' => $validated['years_experience'],
            'languages' => $validated['languages'], // Map: form field -> DB column
            'expertise_areas' => $validated['expertise_areas'],
            'regions_can_guide' => $validated['regions_can_guide'],
            'experience_description' => $validated['experience_description'] ?? '', // Empty string if null
            'profile_photo' => $profilePhotoPath,
            'status' => 'documents_pending', // Match database ENUM value
            
            // Document fields (matching database structure)
            'national_id_document' => $nationalIdDocPath,
            'driving_license' => $drivingLicensePath,
            'guide_certificate' => $guideCertificatePath,
            'language_certificates' => $languageCertificates,
            'vehicle_photos' => $vehiclePhotos,
            'vehicle_license' => $vehicleLicensePath,
        ];

        Log::info('Data to insert', [
            'data' => $dataToInsert
        ]);

        $registrationRequest = GuideRegistrationRequest::create($dataToInsert);

        Log::info('✓ Registration request created', [
            'id' => $registrationRequest->id,
            'email' => $registrationRequest->email
        ]);

        // Log 10: Committing transaction
        Log::info('Step 4: Committing database transaction...');
        DB::commit();
        Log::info('✓ Database transaction committed successfully');

        // Log 11: Sending emails
        Log::info('Step 5: Sending confirmation emails...');

        try {
            // Send confirmation email to guide
            Log::info('Sending confirmation email to guide...', [
                'to' => $registrationRequest->email
            ]);
            
            Mail::to($registrationRequest->email)->send(
                new GuideRegistrationConfirmation($registrationRequest)
            );
            
            Log::info('✓ Confirmation email sent to guide');

        } catch (\Exception $e) {
            Log::error('✗ Failed to send confirmation email to guide', [
                'error' => $e->getMessage()
            ]);
            // Continue anyway - don't fail registration if email fails
        }

        try {
            // Send notification email to admin
            $adminEmail = env('MAIL_ADMIN_EMAIL', 'admin@example.com');
            Log::info('Sending notification email to admin...', [
                'to' => $adminEmail
            ]);
            
            Mail::to($adminEmail)->send(
                new GuideRegistrationAdminNotification($registrationRequest)
            );
            
            Log::info('✓ Notification email sent to admin');

        } catch (\Exception $e) {
            Log::error('✗ Failed to send notification email to admin', [
                'error' => $e->getMessage()
            ]);
            // Continue anyway - don't fail registration if email fails
        }

        // Log 12: Success - redirecting
        Log::info('=== GUIDE REGISTRATION SUCCESS ===', [
            'registration_id' => $registrationRequest->id,
            'guide_email' => $registrationRequest->email
        ]);

        return redirect()->route('guide-registration.success')
            ->with('registration_id', $registrationRequest->id)
            ->with('success', 'Registration submitted successfully!');

    } catch (\Exception $e) {
        // Log 13: Error - rolling back
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
        return view('guide-registration.success');
    }
}