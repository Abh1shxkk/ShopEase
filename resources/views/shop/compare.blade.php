@extends('layouts.shop')

@section('title', 'Compare Products')

@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-slate-900 transition-colors">Shop</a>
        <span>/</span>
        <span class="text-slate-900">Compare Products</span>
    </nav>

    <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl font-serif tracking-wide text-slate-900">Compare Products</h1>
        @if($products->count() > 0)
        <form action="{{ route('compare.clear') }}" method="POST">
            @csrf
            <button type="submit" class="text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
                Clear All
            </button>
        </form>
        @endif
    </div>

    @if($products->count() === 0)
    <div class="text-center py-20">
        <svg class="w-16 h-16 mx-auto text-slate-200 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
        </svg>
        <h2 class="text-xl font-serif text-slate-900 mb-3">No products to compare</h2>
        <p class="text-slate-500 text-sm mb-8">Add products to compare by clicking the compare icon on product cards.</p>
        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
            Browse Products
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead>
                <tr>
                    <th class="w-48 p-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-400 bg-slate-50 border-b border-slate-100"></th>
                    @foreach($products as $product)
                    <th class="p-4 text-center bg-slate-50 border-b border-slate-100 relative">
                        <button onclick="removeFromCompare({{ $product->id }})" class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <a href="{{ route('shop.show', $product) }}" class="block">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-32 h-40 object-cover mx-auto mb-3">
                            <h3 class="text-sm font-medium text-slate-900 hover:text-slate-600 transition-colors">{{ $product->name }}</h3>
                        </a>
                    </th>
                    @endforeach
                    @for($i = $products->count(); $i < 4; $i++)
                    <th class="p-4 text-center bg-slate-50 border-b border-slate-100">
                        <div class="w-32 h-40 mx-auto mb-3 border-2 border-dashed border-slate-200 flex items-center justify-center">
                            <a href="{{ route('shop.index') }}" class="text-[10px] font-bold tracking-widest uppercase text-slate-400 hover:text-slate-900 transition-colors">
                                + Add Product
                            </a>
                        </div>
                    </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-4 text-[11px] font-bold tracking-widest uppercase text-slate-500 border-b border-slate-100">Price</td>
                    @foreach($products as $product)
                    <td class="p-4 text-center border-b border-slate-100">
                        @if($product->discount_price)
                        <span class="text-lg font-semibold text-red-600">Rs. {{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-sm text-slate-400 line-through block">Rs. {{ number_format($product->price, 2) }}</span>
                        @else
                        <span class="text-lg font-semibold text-slate-900">Rs. {{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    @endforeach
                    @for($i = $products->count(); $i < 4; $i++)
                    <td class="p-4 text-center border-b border-slate-100 text-slate-300">-</td>
                    @endfor
                </tr>
                <tr>
                    <td class="p-4 text-[11px] font-bold tracking-widest uppercase text-slate-500 border-b border-slate-100">Category</td>
                    @foreach($products as $product)
                    <td class="p-4 text-center text-sm text-slate-600 border-b border-slate-100">{{ $product->category_name }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 4; $i++)
                    <td class="p-4 text-center border-b border-slate-100 text-slate-300">-</td>
                    @endfor
                </tr>
                <tr>
                    <td class="p-4 text-[11px] font-bold tracking-widest uppercase text-slate-500 border-b border-slate-100">Rating</td>
                    @foreach($products as $product)
                    <td class="p-4 text-center border-b border-slate-100">
                        <div class="flex items-center justify-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-xs text-slate-500 ml-1">({{ $product->reviews_count }})</span>
                        </div>
                    </td>
                    @endforeach
                    @for($i = $products->count(); $i < 4; $i++)
                    <td class="p-4 text-center border-b border-slate-100 text-slate-300">-</td>
                    @endfor
                </tr>
                <tr>
                    <td class="p-4 text-[11px] font-bold tracking-widest uppercase text-slate-500 border-b border-slate-100">Availability</td>
                    @foreach($products as $product)
                    <td class="p-4 text-center border-b border-slate-100">
                        @if($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 text-xs text-green-700">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            In Stock
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-xs text-red-700">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                            Out of Stock
                        </span>
                        @endif
                    </td>
                    @endforeach
                    @for($i = $products->count(); $i < 4; $i++)
                    <td class="p-4 text-center border-b border-slate-100 text-slate-300">-</td>
                    @endfor
                </tr>
                <tr>
                    <td class="p-4 text-[11px] font-bold tracking-widest uppercase text-slate-500 border-b border-slate-100">Description</td>
                    @foreach($products as $product)
                    <td class="p-4 text-center text-sm text-slate-600 border-b border-slate-100">{{ Str::limit($product->description, 150) }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 4; $i++)
                    <td class="p-4 text-center border-b border-slate-100 text-slate-300">-</td>
                    @endfor
                </tr>
                <tr>
                    <td class="p-4 text-[11px] font-bold tracking-widest uppercase text-slate-500"></td>
                    @foreach($products as $product)
                    <td class="p-4 text-center">
                        @auth
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }} class="w-full py-3 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Add to Cart
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="block w-full py-3 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors text-center">
                            Login to Buy
                        </a>
                        @endauth
                    </td>
                    @endforeach
                    @for($i = $products->count(); $i < 4; $i++)
                    <td class="p-4 text-center"></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
    @endif
</div>

@push('scripts')
<script>
function removeFromCompare(productId) {
    fetch('{{ route('compare.remove') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    }).then(() => window.location.reload());
}
</script>
@endpush
@endsection
