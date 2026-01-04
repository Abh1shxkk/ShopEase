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
            <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 30px; text-align: center;">
                <h1 style="color: white; margin: 0; font-size: 24px; font-weight: 600;">🎉 Back in Stock!</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 14px;">Good news - your wishlist item is available</p>
            </div>

            <div style="padding: 30px;">
                {{-- Product Card --}}
                <div style="background: #f8fafc; border-radius: 8px; padding: 20px; text-align: center;">
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                    @endif
                    <h2 style="color: #0f172a; font-size: 20px; margin: 0 0 10px;">{{ $product->name }}</h2>
                    <p style="color: #64748b; font-size: 14px; margin: 0 0 15px;">{{ $product->category_name }}</p>
                    <p style="color: #0f172a; font-size: 24px; font-weight: 700; margin: 0;">
                        ₹{{ number_format($product->discount_price ?? $product->price, 0) }}
                        @if($product->discount_price)
                        <span style="color: #94a3b8; font-size: 16px; text-decoration: line-through; font-weight: 400;">₹{{ number_format($product->price, 0) }}</span>
                        @endif
                    </p>
                </div>

                <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 25px 0; text-align: center;">
                    The item you wanted is now back in stock! Don't miss out - grab it before it sells out again.
                </p>

                {{-- Action Button --}}
                <div style="text-align: center;">
                    <a href="{{ route('shop.show', $product) }}" style="display: inline-block; background: #0f172a; color: white; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                        Shop Now →
                    </a>
                </div>

                <p style="color: #94a3b8; font-size: 12px; text-align: center; margin-top: 25px;">
                    Only {{ $product->stock }} items left in stock!
                </p>
            </div>

            {{-- Footer --}}
            <div style="background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;">
                <p style="color: #64748b; font-size: 12px; margin: 0;">
                    You received this email because you requested to be notified when this item was back in stock.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
