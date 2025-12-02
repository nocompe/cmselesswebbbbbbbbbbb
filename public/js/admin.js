/**
 * ELESS CMS - Admin Panel JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
        });
    }

    // Refresh button
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            location.reload();
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Confirm delete forms
    const deleteForms = document.querySelectorAll('form[data-confirm]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm(form.dataset.confirm || '¿Estás seguro?')) {
                e.preventDefault();
            }
        });
    });

    // File input preview
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.querySelector(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Global Search
    const globalSearch = document.getElementById('globalSearch');
    if (globalSearch) {
        globalSearch.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                // Implementar búsqueda global
                console.log('Search:', globalSearch.value);
            }
        });
    }

    // CSRF Token for AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        window.csrfToken = csrfToken.getAttribute('content');
    }

    // Sortable functionality
    initSortable();

    // Rich text editors
    initEditors();
});

// Initialize sortable tables/lists
function initSortable() {
    const sortables = document.querySelectorAll('[data-sortable]');
    sortables.forEach(container => {
        // Add sortable functionality if needed
        // You can integrate SortableJS here
    });
}

// Initialize rich text editors
function initEditors() {
    const editors = document.querySelectorAll('[data-editor]');
    editors.forEach(editor => {
        // Initialize editor (e.g., TinyMCE, CKEditor, etc.)
    });
}

// AJAX helper function
async function fetchAPI(url, options = {}) {
    const defaults = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json',
        },
    };

    const config = { ...defaults, ...options };
    
    try {
        const response = await fetch(url, config);
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Error en la solicitud');
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    const container = document.querySelector('.admin-content');
    if (container) {
        container.insertBefore(notification, container.firstChild);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Toggle status via AJAX
async function toggleStatus(url) {
    try {
        const response = await fetchAPI(url, { method: 'POST' });
        if (response.success) {
            location.reload();
        }
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

// Delete item via AJAX
async function deleteItem(url, message = '¿Estás seguro de eliminar este elemento?') {
    if (!confirm(message)) return;
    
    try {
        const response = await fetchAPI(url, { method: 'DELETE' });
        if (response.success) {
            location.reload();
        }
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

// Image upload handler
function handleImageUpload(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0] && preview) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Export for global use
window.fetchAPI = fetchAPI;
window.showNotification = showNotification;
window.toggleStatus = toggleStatus;
window.deleteItem = deleteItem;
window.handleImageUpload = handleImageUpload;
