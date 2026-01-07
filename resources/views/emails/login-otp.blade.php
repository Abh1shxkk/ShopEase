<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login OTP</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 500px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    {{-- Header --}}
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center; border-bottom: 1px solid #e2e8f0;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: #0f172a; letter-spacing: 0.05em;">
                                Shop<span style="color: #3b82f6;">/</span>Ease
                            </h1>
                        </td>
                    </tr>
                    
                    {{-- Content --}}
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 600; color: #0f172a;">
                                Login Verification
                            </h2>
                            <p style="margin: 0 0 24px; font-size: 15px; color: #64748b; line-height: 1.6;">
                                Hi {{ $user->name }},<br><br>
                                Use the following OTP to complete your login. This code is valid for 10 minutes.
                            </p>
                            
                            {{-- OTP Box --}}
                            <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 12px; padding: 30px; text-align: center; margin-bottom: 24px;">
                                <p style="margin: 0 0 8px; font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;">
                                    Your OTP Code
                                </p>
                                <p style="margin: 0; font-size: 36px; font-weight: 700; color: #ffffff; letter-spacing: 0.3em; font-family: 'Courier New', monospace;">
                                    {{ $otp }}
                                </p>
                            </div>
                            
                            <p style="margin: 0 0 16px; font-size: 13px; color: #94a3b8; line-height: 1.6;">
                                If you didn't request this login, please ignore this email or contact support if you have concerns.
                            </p>
                            
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px;">
                                <p style="margin: 0; font-size: 13px; color: #92400e;">
                                    <strong>Security Tip:</strong> Never share your OTP with anyone. Our team will never ask for your OTP.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8; text-align: center;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
