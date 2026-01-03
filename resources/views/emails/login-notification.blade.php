<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Notification</title>
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

                    {{-- Security Icon --}}
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center;">
                            <div style="display: inline-block; width: 64px; height: 64px; background-color: #ecfdf5; border-radius: 50%; line-height: 64px;">
                                <img src="https://img.icons8.com/ios-filled/32/10b981/checkmark.png" alt="Success" style="vertical-align: middle;">
                            </div>
                        </td>
                    </tr>

                    {{-- Main Content --}}
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <h2 style="margin: 0 0 8px; color: #0f172a; font-size: 22px; font-weight: 600;">New Login Detected</h2>
                            <p style="margin: 0; color: #64748b; font-size: 14px;">We noticed a new sign-in to your account</p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <p style="margin: 0; color: #334155; font-size: 15px; line-height: 1.6;">
                                Hi <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 16px 0 0; color: #334155; font-size: 15px; line-height: 1.6;">
                                Your ShopEase account was just accessed. If this was you, no action is needed. If you didn't sign in, please secure your account immediately.
                            </p>
                        </td>
                    </tr>

                    {{-- Login Details Box --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 4px; color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Login Details</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 20px;">
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 12px 0; border-top: 1px solid #e2e8f0;">
                                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                                        <tr>
                                                            <td style="width: 24px; vertical-align: top;">
                                                                <img src="https://img.icons8.com/ios/20/64748b/clock.png" alt="Time">
                                                            </td>
                                                            <td style="padding-left: 12px;">
                                                                <p style="margin: 0 0 2px; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Date & Time</p>
                                                                <p style="margin: 0; color: #0f172a; font-size: 14px; font-weight: 500;">{{ $loginTime }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 12px 0; border-top: 1px solid #e2e8f0;">
                                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                                        <tr>
                                                            <td style="width: 24px; vertical-align: top;">
                                                                <img src="https://img.icons8.com/ios/20/64748b/ip-address.png" alt="IP">
                                                            </td>
                                                            <td style="padding-left: 12px;">
                                                                <p style="margin: 0 0 2px; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">IP Address</p>
                                                                <p style="margin: 0; color: #0f172a; font-size: 14px; font-weight: 500;">{{ $ipAddress }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 12px 0; border-top: 1px solid #e2e8f0;">
                                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                                        <tr>
                                                            <td style="width: 24px; vertical-align: top;">
                                                                <img src="https://img.icons8.com/ios/20/64748b/monitor.png" alt="Device">
                                                            </td>
                                                            <td style="padding-left: 12px;">
                                                                <p style="margin: 0 0 2px; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Device</p>
                                                                <p style="margin: 0; color: #0f172a; font-size: 14px; font-weight: 500;">{{ Str::limit($userAgent, 60) }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 20px 20px;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Warning Box --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fef3c7; border-left: 4px solid #f59e0b;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="width: 24px; vertical-align: top;">
                                                    <img src="https://img.icons8.com/ios-filled/24/f59e0b/error.png" alt="Warning">
                                                </td>
                                                <td style="padding-left: 12px;">
                                                    <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                                                        <strong>Wasn't you?</strong> If you didn't sign in, change your password immediately and contact our support team.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA Button --}}
                    <tr>
                        <td style="padding: 0 40px 40px; text-align: center;">
                            <a href="{{ url('/profile') }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; text-decoration: none; padding: 14px 32px; font-size: 12px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;">
                                Review Account Activity
                            </a>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin: 0 0 8px; color: #64748b; font-size: 12px;">
                                            This is an automated security notification from ShopEase.
                                        </p>
                                        <p style="margin: 0 0 16px; color: #94a3b8; font-size: 11px;">
                                            © {{ date('Y') }} ShopEase. All rights reserved.
                                        </p>
                                        <p style="margin: 0;">
                                            <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; font-size: 11px; margin: 0 8px;">Website</a>
                                            <span style="color: #cbd5e1;">|</span>
                                            <a href="{{ url('/profile') }}" style="color: #64748b; text-decoration: none; font-size: 11px; margin: 0 8px;">Account</a>
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
