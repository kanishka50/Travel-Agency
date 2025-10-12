<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { background-color: #fef2f2; padding: 30px; border: 1px solid #fecaca; }
        .info-box { background-color: white; padding: 15px; border-left: 4px solid #2563eb; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🆕 New Guide Registration Request</h1>
        </div>
        
        <div class="content">
            <p><strong>A new guide has submitted a registration request!</strong></p>
            
            <div class="info-box">
                <h3>Applicant Details:</h3>
                <ul>
                    <li><strong>Name:</strong> {{ $registrationRequest->full_name }}</li>
                    <li><strong>Email:</strong> {{ $registrationRequest->email }}</li>
                    <li><strong>Phone:</strong> {{ $registrationRequest->phone }}</li>
                    <li><strong>National ID:</strong> {{ $registrationRequest->national_id }}</li>
                    <li><strong>Experience:</strong> {{ $registrationRequest->years_experience }} years</li>
                    <li><strong>Languages:</strong> {{ implode(', ', $registrationRequest->languages) }}</li>
                    <li><strong>Regions:</strong> {{ implode(', ', $registrationRequest->regions_can_guide) }}</li>
                </ul>
            </div>
            
            <p><strong>Action Required:</strong> Please review this application in the admin panel.</p>
            
            <a href="{{ route('admin.dashboard') }}" class="button">View in Admin Panel</a>
            
            <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
                Request ID: #{{ $registrationRequest->id }}<br>
                Submitted: {{ $registrationRequest->created_at->format('M d, Y h:i A') }}
            </p>
        </div>
    </div>
</body>
</html>