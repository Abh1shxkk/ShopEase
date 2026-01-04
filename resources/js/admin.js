// Admin Panel AJAX Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Toast notification function
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-24 right-6 z-50 px-6 py-4 shadow-lg flex items-center gap-3 transform transition-all duration-300 translate-x-full ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-red-600 text-white'}`;
        
        const icon = type === 'success' 
            ? '<svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
            : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        
        toast.innerHTML = `
            ${icon}
            <span class="text-[13px]">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 opacity-60 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => toast.classList.remove('translate-x-full'), 10);
        
        // Auto remove after 4 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    };

    // AJAX Delete handler
    window.ajaxDelete = function(url, confirmMessage = 'Are you sure you want to delete this item?') {
        if (!confirm(confirmMessage)) return;
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Deleted successfully!');
                // Reload the page or remove the element
                setTimeout(() => location.reload(), 500);
            } else {
                showToast(data.message || 'Failed to delete', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    };

    // AJAX Status toggle
    window.ajaxToggleStatus = function(url, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Status updated!');
                setTimeout(() => location.reload(), 500);
            } else {
                showToast(data.message || 'Failed to update status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    };

    // AJAX Form submission
    document.querySelectorAll('form[data-ajax]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                if (data.success) {
                    showToast(data.message || 'Saved successfully!');
                    if (data.redirect) {
                        setTimeout(() => window.location.href = data.redirect, 500);
                    }
                } else if (data.errors) {
                    // Handle validation errors
                    Object.keys(data.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('border-red-500');
                            const errorEl = document.createElement('p');
                            errorEl.className = 'text-[11px] text-red-500 mt-1 error-message';
                            errorEl.textContent = data.errors[key][0];
                            input.parentNode.appendChild(errorEl);
                        }
                    });
                    showToast('Please fix the errors', 'error');
                } else {
                    showToast(data.message || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                showToast('An error occurred', 'error');
            });
        });
        
        // Clear errors on input
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                const errorMsg = this.parentNode.querySelector('.error-message');
                if (errorMsg) errorMsg.remove();
            });
        });
    });

    // Quick inline edit
    window.inlineEdit = function(element, url, field) {
        const currentValue = element.textContent.trim();
        const input = document.createElement('input');
        input.type = 'text';
        input.value = currentValue;
        input.className = 'h-8 px-2 bg-white border border-slate-300 text-[12px] focus:outline-none focus:border-slate-900';
        
        element.innerHTML = '';
        element.appendChild(input);
        input.focus();
        input.select();
        
        const save = () => {
            const newValue = input.value.trim();
            if (newValue !== currentValue && newValue !== '') {
                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ [field]: newValue })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        element.textContent = newValue;
                        showToast('Updated!');
                    } else {
                        element.textContent = currentValue;
                        showToast('Failed to update', 'error');
                    }
                })
                .catch(() => {
                    element.textContent = currentValue;
                    showToast('Failed to update', 'error');
                });
            } else {
                element.textContent = currentValue;
            }
        };
        
        input.addEventListener('blur', save);
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') save();
            if (e.key === 'Escape') {
                element.textContent = currentValue;
            }
        });
    };
});
