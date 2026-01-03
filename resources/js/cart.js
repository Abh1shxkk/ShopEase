// Cart functionality with AJAX
window.updateCartCount = async function() {
    try {
        const response = await fetch('/cart/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        // Update all cart count badges
        document.querySelectorAll('[data-cart-count]').forEach(el => {
            el.textContent = data.count;
            el.style.display = data.count > 0 ? 'flex' : 'none';
        });
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
};

// Add to cart with AJAX
window.addToCartAjax = async function(productId, quantity = 1) {
    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update cart count
            document.querySelectorAll('[data-cart-count]').forEach(el => {
                el.textContent = data.cart_count;
                el.style.display = data.cart_count > 0 ? 'flex' : 'none';
            });
            
            // Show success message
            if (typeof showToast === 'function') {
                showToast(data.message);
            }
        } else {
            if (typeof showToast === 'function') {
                showToast(data.message, 'error');
            }
        }
        
        return data;
    } catch (error) {
        console.error('Error adding to cart:', error);
        if (typeof showToast === 'function') {
            showToast('Failed to add to cart', 'error');
        }
    }
};

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('[data-cart-count]')) {
        updateCartCount();
    }
});
