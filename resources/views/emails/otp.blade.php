<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 100%; max-width: 600px; border-collapse: collapse; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #0f172a; padding: 32px 40px; text-align: center;">
                            <div style="display: inline-block; background-color: #ffffff; padding: 8px; margin-bottom: 16px;">
                                <img src="https://img.icons8.com/ios-filled/24/000000/shopping-bag.png" alt="ShopEase" style="display: block;">
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">ShopEase</h1>
                        </td>
                    </tr>

                    {{-- Icon --}}
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center;">
                            <div style="display: inline-block; width: 64px; height: 64px; background-color: #dbeafe; border-radius: 50%; line-height: 64px;">
                                <img src="https://img.icons8.com/ios-filled/32/3b82f6/secured-letter.png" alt="OTP" style="vertical-align: middle;">
                            </div>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 0 40px 24px; text-align: center;">
                            <h2 style="margin: 0 0 8px; color: #0f172a; font-size: 22px; font-weight: 600;">Verification Code</h2>
                            <p style="margin: 0; color: #64748b; font-size: 14px;">Use this code to verify your identity</p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <p style="margin: 0; color: #334155; font-size: 15px; line-height: 1.6;">
                                Hi <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 16px 0 0; color: #334155; font-size: 15px; line-height: 1.6;">
                                You requested to reset your password. Please use the verification code below to proceed.
                            </p>
                        </td>
                    </tr>

                    {{-- OTP Box --}}
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <div style="background-color: #f8fafc; border: 2px dashed #e2e8f0; padding: 24px; display: inline-block;">
                                <p style="margin: 0 0 8px; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Your OTP Code</p>
                                <p style="margin: 0; color: #0f172a; font-size: 36px; font-weight: 700; letter-spacing: 8px; font-family: monospace;">{{ $otp }}</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Expiry Notice --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fef3c7; border-left: 4px solid #f59e0b;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="width: 24px; vertical-align: top;">
                                                    <img src="https://img.icons8.com/ios/20/f59e0b/clock.png" alt="Clock">
                                                </td>
                                                <td style="padding-left: 12px;">
                                                    <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                                                        This code will expire in <strong>10 minutes</strong>. Do not share this code with anyone.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Warning --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.6;">
                                If you didn't request this code, please ignore this email. Someone may have entered your email by mistake.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin: 0 0 8px; color: #64748b; font-size: 12px;">
                                            This is an automated security message from ShopEase.
                                        </p>
                                        <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                                            © {{ date('Y') }} ShopEase. All rights reserved.
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
