<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 100%; max-width: 600px; border-collapse: collapse; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #0f172a; padding: 32px 40px; text-align: center;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background-color: #ffffff; padding: 8px; margin-bottom: 16px;">
                                            <img src="https://img.icons8.com/ios-filled/24/000000/shopping-bag.png" alt="ShopEase" style="display: block;">
                                        </div>
                                        <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">ShopEase</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Key Icon --}}
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center;">
                            <div style="display: inline-block; width: 64px; height: 64px; background-color: #fef3c7; border-radius: 50%; line-height: 64px;">
                                <img src="https://img.icons8.com/ios-filled/32/f59e0b/key.png" alt="Key" style="vertical-align: middle;">
                            </div>
                        </td>
                    </tr>

                    {{-- Main Content --}}
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <h2 style="margin: 0 0 8px; color: #0f172a; font-size: 22px; font-weight: 600;">Reset Your Password</h2>
                            <p style="margin: 0; color: #64748b; font-size: 14px;">We received a request to reset your password</p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <p style="margin: 0; color: #334155; font-size: 15px; line-height: 1.6;">
                                Hi <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 16px 0 0; color: #334155; font-size: 15px; line-height: 1.6;">
                                We received a request to reset the password for your ShopEase account. Click the button below to create a new password.
                            </p>
                        </td>
                    </tr>

                    {{-- CTA Button --}}
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; text-decoration: none; padding: 16px 40px; font-size: 12px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;">
                                Reset Password
                            </a>
                        </td>
                    </tr>

                    {{-- Link Expiry Notice --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f1f5f9; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="width: 24px; vertical-align: top;">
                                                    <img src="https://img.icons8.com/ios/20/64748b/clock.png" alt="Clock">
                                                </td>
                                                <td style="padding-left: 12px;">
                                                    <p style="margin: 0; color: #475569; font-size: 13px; line-height: 1.5;">
                                                        This link will expire in <strong>60 minutes</strong>. After that, you'll need to request a new password reset.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Alternative Link --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <p style="margin: 0; color: #64748b; font-size: 12px; line-height: 1.6;">
                                If the button doesn't work, copy and paste this link into your browser:
                            </p>
                            <p style="margin: 8px 0 0; word-break: break-all;">
                                <a href="{{ $resetUrl }}" style="color: #3b82f6; font-size: 12px; text-decoration: none;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    {{-- Warning Box --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fef2f2; border-left: 4px solid #ef4444;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="width: 24px; vertical-align: top;">
                                                    <img src="https://img.icons8.com/ios-filled/24/ef4444/error.png" alt="Warning">
                                                </td>
                                                <td style="padding-left: 12px;">
                                                    <p style="margin: 0; color: #991b1b; font-size: 13px; line-height: 1.5;">
                                                        <strong>Didn't request this?</strong> If you didn't request a password reset, please ignore this email. Your password will remain unchanged.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin: 0 0 8px; color: #64748b; font-size: 12px;">
                                            This is an automated message from ShopEase.
                                        </p>
                                        <p style="margin: 0 0 16px; color: #94a3b8; font-size: 11px;">
                                            © {{ date('Y') }} ShopEase. All rights reserved.
                                        </p>
                                        <p style="margin: 0;">
                                            <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; font-size: 11px; margin: 0 8px;">Website</a>
                                            <span style="color: #cbd5e1;">|</span>
                                            <a href="mailto:support@shopease.com" style="color: #64748b; text-decoration: none; font-size: 11px; margin: 0 8px;">Support</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
