<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 4px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1a1a1a; padding: 30px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 300; letter-spacing: 3px;">ORDER CONFIRMED</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; color: #333; font-size: 16px;">Dear {{ $order->shipping_name }},</p>
                            <p style="margin: 0 0 25px; color: #666; font-size: 14px; line-height: 1.6;">
                                Thank you for your order! We're pleased to confirm that your order has been received and is being processed.
                            </p>
                            
                            <!-- Order Details Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f8f8; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 5px 0;"><strong style="color: #333;">Order Number:</strong></td>
                                                <td style="padding: 5px 0; text-align: right; color: #666;">{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0;"><strong style="color: #333;">Invoice Number:</strong></td>
                                                <td style="padding: 5px 0; text-align: right; color: #666;">{{ $invoiceNumber }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0;"><strong style="color: #333;">Order Date:</strong></td>
                                                <td style="padding: 5px 0; text-align: right; color: #666;">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0;"><strong style="color: #333;">Payment Method:</strong></td>
                                                <td style="padding: 5px 0; text-align: right; color: #666;">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Items -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 2px solid #1a1a1a;"><strong style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #333;">Item</strong></td>
                                    <td style="padding: 10px 0; border-bottom: 2px solid #1a1a1a; text-align: center;"><strong style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #333;">Qty</strong></td>
                                    <td style="padding: 10px 0; border-bottom: 2px solid #1a1a1a; text-align: right;"><strong style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #333;">Amount</strong></td>
                                </tr>
                                @foreach($order->items as $item)
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eee; color: #333;">{{ $item->product_name }}</td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eee; text-align: center; color: #666;">{{ $item->quantity }}</td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #eee; text-align: right; color: #333;">₹{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </table>
                            
                            <!-- Totals -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 5px 0; color: #666;">Subtotal:</td>
                                    <td style="padding: 5px 0; text-align: right; color: #333;">₹{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if(isset($order->discount) && $order->discount > 0)
                                <tr>
                                    <td style="padding: 5px 0; color: #28a745;">Discount:</td>
                                    <td style="padding: 5px 0; text-align: right; color: #28a745;">-₹{{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 5px 0; color: #666;">Tax (GST 18%):</td>
                                    <td style="padding: 5px 0; text-align: right; color: #333;">₹{{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0; color: #666;">Shipping:</td>
                                    <td style="padding: 5px 0; text-align: right; color: #333;">{{ $order->shipping > 0 ? '₹'.number_format($order->shipping, 2) : 'FREE' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px 0 5px; border-top: 2px solid #1a1a1a;"><strong style="font-size: 16px; color: #333;">Total:</strong></td>
                                    <td style="padding: 15px 0 5px; border-top: 2px solid #1a1a1a; text-align: right;"><strong style="font-size: 18px; color: #1a1a1a;">₹{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 15px; color: #666; font-size: 14px; line-height: 1.6;">
                                Your tax invoice is attached to this email as a PDF. You can also download it anytime from your order history.
                            </p>
                            
                            <p style="margin: 0; color: #666; font-size: 14px;">
                                If you have any questions, please contact us at <a href="mailto:support@shopease.com" style="color: #1a1a1a;">support@shopease.com</a>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f8f8; padding: 25px 40px; text-align: center; border-top: 1px solid #eee;">
                            <p style="margin: 0; color: #999; font-size: 12px;">Thank you for shopping with us!</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
