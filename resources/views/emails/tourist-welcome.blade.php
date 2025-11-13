<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Tourism Platform</title>
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
        .welcome-box {
            background: white;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .features {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .feature-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .feature-item:last-child {
            border-bottom: none;
        }
        .feature-icon {
            color: #667eea;
            font-weight: bold;
            margin-right: 10px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
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
        <h1 style="margin: 0;">Welcome to Tourism Platform!</h1>
        <p style="margin: 10px 0 0 0;">Your adventure begins here</p>
    </div>

    <div class="content">
        <div class="welcome-box">
            <h2 style="margin-top: 0; color: #667eea;">Hello {{ $touristName }}!</h2>
            <p>Thank you for joining Tourism Platform. We're excited to help you discover amazing destinations and experiences!</p>
        </div>

        <div class="info-box">
            <strong>📧 Important: Verify Your Email</strong><br>
            We've sent you a separate email verification link. Please check your inbox and verify your email address to access all features.
        </div>

        <h3>What You Can Do:</h3>
        <div class="features">
            <div class="feature-item">
                <span class="feature-icon">🗺️</span>
                <strong>Browse Tour Plans:</strong> Explore curated tour packages designed by professional guides
            </div>
            <div class="feature-item">
                <span class="feature-icon">📝</span>
                <strong>Submit Custom Requests:</strong> Tell us what you want, and guides will bid on your request
            </div>
            <div class="feature-item">
                <span class="feature-icon">👤</span>
                <strong>Connect with Guides:</strong> Find experienced local guides for personalized experiences
            </div>
            <div class="feature-item">
                <span class="feature-icon">📅</span>
                <strong>Book & Manage:</strong> Easy booking system with real-time availability
            </div>
            <div class="feature-item">
                <span class="feature-item">⭐</span>
                <strong>Review & Rate:</strong> Share your experiences and help others make informed decisions
            </div>
            <div class="feature-item">
                <span class="feature-icon">❤️</span>
                <strong>Save Favorites:</strong> Bookmark your favorite tours and guides for later
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ config('app.url') }}" class="button">Start Exploring</a>
        </div>

        <h3>Your Account Details:</h3>
        <div class="info-box">
            <strong>Email:</strong> {{ $email }}<br>
            <strong>Name:</strong> {{ $touristName }}
        </div>

        <h3>Need Help?</h3>
        <p>If you have any questions or need assistance, our support team is here to help:</p>
        <p style="text-align: center; font-size: 18px; color: #667eea;">
            <strong>{{ $supportEmail }}</strong>
        </p>

        <div class="info-box">
            <strong>💡 Tip:</strong> Complete your profile to get personalized tour recommendations!
        </div>

        <p>We're committed to making your travel experience unforgettable. Let's explore the world together!</p>

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
