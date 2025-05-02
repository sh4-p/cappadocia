/**
 * Admin Panel JavaScript
 * 
 * Handles all admin panel functionality
 */

// Wait for the DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Initialize admin components
    initSidebar();
    initDropdowns();
    initAlerts();
    initDataTables();
    initFormValidation();
    initImagePreview();
    initSortable();
    initEditor();
    initDatepicker();
    initTabs();
    initTooltips();
    initDeleteConfirmation();
    initToggleStatus();
    initMultiLanguageForm();
    
    // Call AOS (Animate On Scroll) if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
        });
    }
});

/**
 * Initialize sidebar
 */
function initSidebar() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebarToggleMobile = document.querySelector('.sidebar-toggle-mobile');
    const sidebar = document.querySelector('.sidebar');
    const adminWrapper = document.querySelector('.admin-wrapper');
    
    if (sidebarToggle && sidebar && adminWrapper) {
        // Toggle sidebar
        sidebarToggle.addEventListener('click', function() {
            adminWrapper.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', adminWrapper.classList.contains('sidebar-collapsed'));
        });
    }
    
    if (sidebarToggleMobile && sidebar) {
        // Toggle mobile sidebar
        sidebarToggleMobile.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-mobile-open');
        });
        
        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (sidebar.classList.contains('sidebar-mobile-open') && 
                !sidebar.contains(event.target) && 
                !sidebarToggleMobile.contains(event.target)) {
                sidebar.classList.remove('sidebar-mobile-open');
            }
        });
    }
    
    // Restore sidebar state from localStorage
    if (adminWrapper && localStorage.getItem('sidebar-collapsed') === 'true') {
        adminWrapper.classList.add('sidebar-collapsed');
    }
}

/**
 * Initialize dropdowns
 */
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(function(dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (toggle && menu) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close all other dropdowns
                dropdowns.forEach(function(otherDropdown) {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.querySelector('.dropdown-menu').classList.remove('show');
                    }
                });
                
                menu.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });
        }
    });
}

/**
 * Initialize alerts
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        const closeBtn = alert.querySelector('.alert-close');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                alert.classList.add('fade-out');
                
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
            
            // Auto close after 5 seconds
            setTimeout(function() {
                alert.classList.add('fade-out');
                
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        }
    });
}

/**
 * Initialize data tables
 */
function initDataTables() {
    const tables = document.querySelectorAll('.datatable');
    
    if (tables.length > 0) {
        tables.forEach(function(table) {
            // Check if DataTable is available
            if (typeof $.fn.DataTable !== 'undefined') {
                $(table).DataTable({
                    responsive: true,
                    language: {
                        search: '',
                        searchPlaceholder: 'Search...',
                        lengthMenu: 'Show _MENU_ entries',
                        info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                        infoEmpty: 'Showing 0 to 0 of 0 entries',
                        infoFiltered: '(filtered from _MAX_ total entries)',
                        paginate: {
                            first: '<i class="material-icons">first_page</i>',
                            previous: '<i class="material-icons">chevron_left</i>',
                            next: '<i class="material-icons">chevron_right</i>',
                            last: '<i class="material-icons">last_page</i>'
                        }
                    }
                });
            }
        });
    }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Focus first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

/**
 * Initialize image preview
 */
function initImagePreview() {
    const imageInputs = document.querySelectorAll('.image-upload-input');
    
    imageInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const previewContainer = this.closest('.image-upload').querySelector('.image-preview');
            
            if (previewContainer) {
                // Clear previous preview
                previewContainer.innerHTML = '';
                
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-image');
                        
                        previewContainer.appendChild(img);
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });
    });
}

/**
 * Initialize sortable lists
 */
function initSortable() {
    const sortableLists = document.querySelectorAll('.sortable');
    
    if (sortableLists.length > 0) {
        // Check if Sortable is available
        if (typeof Sortable !== 'undefined') {
            sortableLists.forEach(function(list) {
                Sortable.create(list, {
                    handle: '.sort-handle',
                    animation: 150,
                    onEnd: function(evt) {
                        updateOrder(evt.to);
                    }
                });
            });
        }
    }
}

/**
 * Update order of sortable items
 * 
 * @param {HTMLElement} container Sortable container
 */
function updateOrder(container) {
    const items = container.querySelectorAll('.sortable-item');
    const updateUrl = container.dataset.updateUrl;
    
    if (!updateUrl) return;
    
    const orderData = Array.from(items).map(function(item, index) {
        return {
            id: item.dataset.id,
            order: index + 1
        };
    });
    
    // Send order update with fetch API
    fetch(updateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ items: orderData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Order updated successfully', 'success');
        } else {
            showNotification('Failed to update order', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating order:', error);
        showNotification('Error updating order', 'error');
    });
}

/**
 * Initialize rich text editor
 */
function initEditor() {
    const editors = document.querySelectorAll('.rich-editor');
    
    if (editors.length > 0) {
        // Check if TinyMCE is available
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.rich-editor',
                height: 400,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | \
                          alignleft aligncenter alignright alignjustify | \
                          bullist numlist outdent indent | removeformat | help',
                valid_elements: '*[*]',
                valid_children: '+body[style]',
                extended_valid_elements: 'script[language|type|src]'
            });
        }
    }
}

/**
 * Initialize datepicker
 */
function initDatepicker() {
    const datepickers = document.querySelectorAll('.datepicker');
    
    if (datepickers.length > 0) {
        // Check if Flatpickr is available
        if (typeof flatpickr !== 'undefined') {
            datepickers.forEach(function(input) {
                flatpickr(input, {
                    dateFormat: 'Y-m-d',
                    allowInput: true,
                    disableMobile: true
                });
            });
        }
    }
}

/**
 * Initialize tabs
 */
function initTabs() {
    const tabContainers = document.querySelectorAll('.tabs');
    
    tabContainers.forEach(function(container) {
        const tabs = container.querySelectorAll('.tab-button');
        const tabContents = container.querySelectorAll('.tab-content');
        
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                const target = this.dataset.target;
                
                // Deactivate all tabs
                tabs.forEach(function(t) {
                    t.classList.remove('active');
                });
                
                // Hide all tab contents
                tabContents.forEach(function(content) {
                    content.classList.remove('active');
                });
                
                // Activate current tab and content
                this.classList.add('active');
                container.querySelector('.' + target).classList.add('active');
                
                // Store active tab in localStorage
                const tabsId = container.dataset.id;
                if (tabsId) {
                    localStorage.setItem('active-tab-' + tabsId, target);
                }
            });
        });
        
        // Restore active tab from localStorage
        const tabsId = container.dataset.id;
        if (tabsId) {
            const activeTab = localStorage.getItem('active-tab-' + tabsId);
            if (activeTab) {
                const tab = container.querySelector('.tab-button[data-target="' + activeTab + '"]');
                if (tab) {
                    tab.click();
                }
            } else {
                // Activate first tab by default
                const firstTab = container.querySelector('.tab-button');
                if (firstTab) {
                    firstTab.click();
                }
            }
        } else {
            // Activate first tab by default
            const firstTab = container.querySelector('.tab-button');
            if (firstTab) {
                firstTab.click();
            }
        }
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(function(element) {
        const tooltip = document.createElement('div');
        tooltip.classList.add('tooltip');
        tooltip.textContent = element.dataset.tooltip;
        
        element.appendChild(tooltip);
        
        element.addEventListener('mouseenter', function() {
            tooltip.classList.add('show');
        });
        
        element.addEventListener('mouseleave', function() {
            tooltip.classList.remove('show');
        });
    });
}

/**
 * Initialize delete confirmation
 */
function initDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.getAttribute('href');
            const name = this.dataset.name || 'this item';
            
            if (confirm('Are you sure you want to delete ' + name + '?')) {
                window.location.href = url;
            }
        });
    });
}

/**
 * Initialize toggle status
 */
function initToggleStatus() {
    const toggles = document.querySelectorAll('.status-toggle');
    
    toggles.forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const url = this.dataset.url;
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;
            
            // Send status update with fetch API
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: id, status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Status updated successfully', 'success');
                } else {
                    showNotification('Failed to update status', 'error');
                    // Revert toggle state
                    toggle.checked = !toggle.checked;
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                showNotification('Error updating status', 'error');
                // Revert toggle state
                toggle.checked = !toggle.checked;
            });
        });
    });
}

/**
 * Initialize multi-language form
 */
function initMultiLanguageForm() {
    const langTabs = document.querySelectorAll('.lang-tabs .lang-tab');
    const langContents = document.querySelectorAll('.lang-content');
    
    langTabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const langCode = this.dataset.lang;
            
            // Deactivate all tabs
            langTabs.forEach(function(t) {
                t.classList.remove('active');
            });
            
            // Hide all language contents
            langContents.forEach(function(content) {
                content.classList.remove('active');
            });
            
            // Activate current tab and content
            this.classList.add('active');
            document.querySelector('.lang-content[data-lang="' + langCode + '"]').classList.add('active');
            
            // Store active language in localStorage
            localStorage.setItem('active-language', langCode);
        });
    });
    
    // Restore active language from localStorage or activate first tab
    const activeLanguage = localStorage.getItem('active-language');
    if (activeLanguage) {
        const tab = document.querySelector('.lang-tab[data-lang="' + activeLanguage + '"]');
        if (tab) {
            tab.click();
        } else {
            const firstTab = document.querySelector('.lang-tab');
            if (firstTab) {
                firstTab.click();
            }
        }
    } else {
        const firstTab = document.querySelector('.lang-tab');
        if (firstTab) {
            firstTab.click();
        }
    }
}

/**
 * Show notification
 * 
 * @param {string} message Notification message
 * @param {string} type Notification type (success, error, warning, info)
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.classList.add('notification', 'notification-' + type);
    
    // Add notification content
    let icon = '';
    switch (type) {
        case 'success':
            icon = 'check_circle';
            break;
        case 'error':
            icon = 'error';
            break;
        case 'warning':
            icon = 'warning';
            break;
        default:
            icon = 'info';
    }
    
    notification.innerHTML = `
        <i class="material-icons">${icon}</i>
        <span>${message}</span>
        <button class="notification-close">
            <i class="material-icons">close</i>
        </button>
    `;
    
    // Add notification to container or create one
    let container = document.querySelector('.notification-container');
    if (!container) {
        container = document.createElement('div');
        container.classList.add('notification-container');
        document.body.appendChild(container);
    }
    
    container.appendChild(notification);
    
    // Close button event
    const closeBtn = notification.querySelector('.notification-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            notification.classList.add('fade-out');
            setTimeout(function() {
                notification.remove();
                
                // Remove container if empty
                if (container.children.length === 0) {
                    container.remove();
                }
            }, 300);
        });
    }
    
    // Auto close after 3 seconds
    setTimeout(function() {
        notification.classList.add('fade-out');
        setTimeout(function() {
            notification.remove();
            
            // Remove container if empty
            if (container.children.length === 0) {
                container.remove();
            }
        }, 300);
    }, 3000);
}