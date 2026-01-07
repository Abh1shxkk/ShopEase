<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; max-width: 600px;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #0f172a; padding: 30px 40px; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 400; letter-spacing: 2px; color: #ffffff;">
                                Shop<span style="color: #3b82f6; font-style: italic;">/</span>Ease
                            </h1>
                        </td>
                    </tr>
                    
                    {{-- Content --}}
                    <tr>
                        <td style="padding: 40px;">
                            <div style="color: #334155; font-size: 15px; line-height: 1.8;">
                                {!! $campaign->content !!}
                            </div>
                        </td>
                    </tr>
                    
                    {{-- CTA Button --}}
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <table cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="background-color: #0f172a; padding: 16px 40px;">
                                        <a href="{{ url('/shop') }}" style="color: #ffffff; text-decoration: none; font-size: 12px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">
                                            SHOP NOW
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8fafc; padding: 30px 40px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 15px; font-size: 12px; color: #64748b; text-align: center;">
                                You're receiving this email because you subscribed to our newsletter.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #64748b; text-align: center;">
                                <a href="{{ $unsubscribeUrl }}" style="color: #3b82f6; text-decoration: underline;">Unsubscribe</a>
                                &nbsp;|&nbsp;
                                <a href="{{ url('/') }}" style="color: #3b82f6; text-decoration: underline;">Visit Website</a>
                            </p>
                            <p style="margin: 20px 0 0; font-size: 11px; color: #94a3b8; text-align: center;">
                                © {{ date('Y') }} ShopEase. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
