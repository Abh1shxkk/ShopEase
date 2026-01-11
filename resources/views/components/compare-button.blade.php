@props(['productId'])

<button 
    onclick="addToCompare({{ $productId }})" 
    class="w-10 h-10 flex items-center justify-center border border-slate-200 hover:bg-slate-50 hover:border-slate-900 transition-all"
    title="Add to Compare"
>
    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
    </svg>
</button>

@once
@push('scripts')
<script>
function addToCompare(productId) {
    fetch('{{ route('compare.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            updateCompareCount(data.count);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Something went wrong', 'error'));
}

function updateCompareCount(count) {
    const badge = document.getElementById('compare-count');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}
</script>
@endpush
@endonce
