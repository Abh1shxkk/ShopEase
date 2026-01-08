<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Purchase</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border: 1px solid #e2e8f0;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #0f172a; padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 300; letter-spacing: 2px;">SHOPEASE</h1>
                        </td>
                    </tr>

                    {{-- Main Content --}}
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px; color: #0f172a; font-size: 22px; font-weight: 500;">
                                @if($abandonedCart->reminder_count == 0)
                                    You left something behind! 🛒
                                @elseif($abandonedCart->reminder_count == 1)
                                    Still thinking about it?
                                @else
                                    Last chance to grab your items!
                                @endif
                            </h2>
                            
                            <p style="margin: 0 0 30px; color: #64748b; font-size: 15px; line-height: 1.6;">
                                Hi {{ $abandonedCart->user->name }},<br><br>
                                @if($abandonedCart->reminder_count == 0)
                                    We noticed you left some amazing items in your cart. Don't worry, we've saved them for you!
                                @elseif($abandonedCart->reminder_count == 1)
                                    Your cart is waiting for you. These items are selling fast, so don't miss out!
                                @else
                                    This is your last reminder! Your cart will expire soon. Complete your purchase now before it's too late.
                                @endif
                            </p>

                            {{-- Cart Items --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                @foreach($abandonedCart->cart_snapshot as $item)
                                <tr>
                                    <td style="padding: 15px; border-bottom: 1px solid #e2e8f0;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="80" style="vertical-align: top;">
                                                    <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" width="70" height="70" style="object-fit: cover; border: 1px solid #e2e8f0;">
                                                </td>
                                                <td style="vertical-align: top; padding-left: 15px;">
                                                    <p style="margin: 0 0 5px; color: #0f172a; font-size: 14px; font-weight: 500;">{{ $item['product_name'] }}</p>
                                                    <p style="margin: 0; color: #64748b; font-size: 13px;">Qty: {{ $item['quantity'] }}</p>
                                                </td>
                                                <td width="100" style="vertical-align: top; text-align: right;">
                                                    <p style="margin: 0; color: #0f172a; font-size: 15px; font-weight: 600;">₹{{ number_format($item['subtotal'], 2) }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            </table>

                            {{-- Total --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px; background-color: #f8fafc; padding: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; color: #64748b; font-size: 14px;">Cart Total</p>
                                    </td>
                                    <td style="text-align: right;">
                                        <p style="margin: 0; color: #0f172a; font-size: 20px; font-weight: 600;">₹{{ number_format($abandonedCart->cart_total, 2) }}</p>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA Button --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $abandonedCart->recovery_url }}" style="display: inline-block; padding: 16px 40px; background-color: #0f172a; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;">
                                            Complete My Purchase
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            @if($abandonedCart->reminder_count >= 2)
                            {{-- Urgency Message --}}
                            <p style="margin: 30px 0 0; padding: 15px; background-color: #fef3c7; color: #92400e; font-size: 13px; text-align: center; border-left: 4px solid #f59e0b;">
                                ⚠️ Your cart will expire in 24 hours. Don't miss out!
                            </p>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 10px; color: #64748b; font-size: 12px;">
                                Need help? <a href="{{ route('support.index') }}" style="color: #0f172a;">Contact Support</a>
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
</body>
</html>
