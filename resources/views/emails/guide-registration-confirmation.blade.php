<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Request Received</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $registrationRequest->full_name }},</p>
            
            <p>Thank you for your interest in becoming a tour guide with our platform!</p>
            
            <p>We have successfully received your registration request with the following details:</p>
            
            <ul>
                <li><strong>Name:</strong> {{ $registrationRequest->full_name }}</li>
                <li><strong>Email:</strong> {{ $registrationRequest->email }}</li>
                <li><strong>Phone:</strong> {{ $registrationRequest->phone }}</li>
                <li><strong>Experience:</strong> {{ $registrationRequest->years_experience }} years</li>
            </ul>
            
            <h3>What happens next?</h3>
            <ol>
                <li>Our Registration Manager will review your documents within 3-5 business days</li>
                <li>We may contact you if we need any additional information</li>
                <li>If your application is approved, we'll schedule an interview</li>
                <li>After a successful interview, your account will be activated</li>
                <li>You'll receive your login credentials via email</li>
            </ol>
            
            <p>If you have any questions, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>The Tourism Platform Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2025 Tourism Platform. All rights reserved.</p>
        </div>
    </div>
</body>
</html>