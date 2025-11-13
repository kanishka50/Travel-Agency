<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Application Update</title>
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
        .reason-box {
            background: white;
            border-left: 4px solid #dc3545;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Update on Your Guide Application</h1>
        <p style="margin: 10px 0 0 0;">Tourism Platform</p>
    </div>

    <div class="content">
        <p>Dear {{ $applicantName }},</p>

        <p>Thank you for your interest in becoming a guide on our Tourism Platform. We appreciate the time and effort you put into your application.</p>

        <p>After careful review of your application, we regret to inform you that we are unable to approve your guide registration at this time.</p>

        <div class="reason-box">
            <h3 style="margin-top: 0; color: #dc3545;">Reason for Decision:</h3>
            <p style="margin-bottom: 0;">{{ $rejectionReason }}</p>
        </div>

        <div class="info-box">
            <strong>ℹ️ What's Next?</strong><br>
            We encourage you to address the concerns mentioned above and reapply in the future. Our team is committed to maintaining high standards for all guides on our platform to ensure the best experience for our travelers.
        </div>

        <h3>If You Have Questions:</h3>
        <p>If you would like more information about this decision or guidance on how to improve your application, please don't hesitate to contact us at:</p>
        <p style="text-align: center; font-size: 18px; color: #667eea;">
            <strong>{{ $supportEmail }}</strong>
        </p>

        <p>We appreciate your understanding and wish you all the best in your professional endeavors.</p>

        <p>Best regards,<br>
        <strong>Tourism Platform Team</strong></p>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>For inquiries, contact us at {{ $supportEmail }}</p>
        <p>&copy; {{ date('Y') }} Tourism Platform. All rights reserved.</p>
    </div>
</body>
</html>
