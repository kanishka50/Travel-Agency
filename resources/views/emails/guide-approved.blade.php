<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Application Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .credentials-box {
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .credential-row:last-child {
            border-bottom: none;
        }
        .credential-label {
            font-weight: bold;
            color: #667eea;
        }
        .credential-value {
            color: #333;
            font-family: monospace;
            background: #f5f5f5;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: #5568d3;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="success-icon">✓</div>
        <h1 style="margin: 0;">Congratulations!</h1>
        <p style="margin: 10px 0 0 0;">Your Guide Application Has Been Approved</p>
    </div>

    <div class="content">
        <p>Dear {{ $guideName }},</p>

        <p>We are thrilled to inform you that your application to become a guide on our Tourism Platform has been <strong>approved</strong>!</p>

        <p>Welcome to our community of professional tour guides. You can now start creating tour plans and connecting with travelers.</p>

        <div class="credentials-box">
            <h3 style="margin-top: 0; color: #667eea;">Your Login Credentials</h3>

            <div class="credential-row">
                <span class="credential-label">Guide ID:</span>
                <span class="credential-value">{{ $guideId }}</span>
            </div>

            <div class="credential-row">
                <span class="credential-label">Email:</span>
                <span class="credential-value">{{ $email }}</span>
            </div>

            <div class="credential-row">
                <span class="credential-label">Temporary Password:</span>
                <span class="credential-value">{{ $password }}</span>
            </div>
        </div>

        <div class="warning">
            <strong>⚠️ Important Security Notice:</strong><br>
            For your security, please change your password immediately after logging in for the first time.
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
        </div>

        <h3>Next Steps:</h3>
        <ol>
            <li>Click the button above to log in to your guide dashboard</li>
            <li>Complete your profile information</li>
            <li>Add your banking details for payments</li>
            <li>Create your first tour plan</li>
            <li>Start receiving booking requests!</li>
        </ol>

        <p>If you have any questions or need assistance getting started, please don't hesitate to contact our support team.</p>

        <p>We're excited to have you on board!</p>

        <p>Best regards,<br>
        <strong>Tourism Platform Team</strong></p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Tourism Platform. All rights reserved.</p>
    </div>
</body>
</html>
