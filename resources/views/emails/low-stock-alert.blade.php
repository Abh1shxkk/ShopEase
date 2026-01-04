<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc;">
    <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            {{-- Header --}}
            <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); padding: 30px; text-align: center;">
                <h1 style="color: white; margin: 0; font-size: 24px; font-weight: 600;">⚠️ Inventory Alert</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 14px;">Some products need your attention</p>
            </div>

            <div style="padding: 30px;">
                {{-- Out of Stock Products --}}
                @if($outOfStockProducts->isNotEmpty())
                <div style="margin-bottom: 30px;">
                    <h2 style="color: #dc2626; font-size: 16px; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #fecaca;">
                        🚫 Out of Stock ({{ $outOfStockProducts->count() }} products)
                    </h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        @foreach($outOfStockProducts as $product)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 0;">
                                <strong style="color: #1e293b;">{{ $product->name }}</strong>
                                <br>
                                <span style="color: #64748b; font-size: 12px;">SKU: #{{ $product->id }}</span>
                            </td>
                            <td style="padding: 12px 0; text-align: right;">
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    {{ $product->stock }} in stock
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @endif

                {{-- Low Stock Products --}}
                @if($lowStockProducts->isNotEmpty())
                <div style="margin-bottom: 30px;">
                    <h2 style="color: #d97706; font-size: 16px; margin: 0 0 15px; padding-bottom: 10px; border-bottom: 2px solid #fef3c7;">
                        ⚠️ Low Stock ({{ $lowStockProducts->count() }} products)
                    </h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        @foreach($lowStockProducts as $product)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 0;">
                                <strong style="color: #1e293b;">{{ $product->name }}</strong>
                                <br>
                                <span style="color: #64748b; font-size: 12px;">Threshold: {{ $product->low_stock_threshold }}</span>
                            </td>
                            <td style="padding: 12px 0; text-align: right;">
                                <span style="background: #fffbeb; color: #d97706; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    {{ $product->stock }} left
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @endif

                {{-- Action Button --}}
                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ url('/admin/inventory') }}" style="display: inline-block; background: #0f172a; color: white; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                        Manage Inventory →
                    </a>
                </div>
            </div>

            {{-- Footer --}}
            <div style="background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;">
                <p style="color: #64748b; font-size: 12px; margin: 0;">
                    This is an automated alert from ShopEase Admin
                </p>
            </div>
        </div>
    </div>
</body>
</html>
