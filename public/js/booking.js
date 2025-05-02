
/**
 * Booking JavaScript
 * 
 * Handles booking form functionality
 */

// Wait for the DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Initialize booking components
    initDatepicker();
    initGuestCounter();
    initPriceCalculator();
    initBookingForm();
    initPaymentTabs();
    
    // Check if the booking is completed
    const bookingComplete = document.querySelector('.booking-complete');
    if (bookingComplete) {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
});

/**
 * Initialize datepicker
 */
function initDatepicker() {
    const dateInput = document.querySelector('#booking_date');
    
    if (dateInput) {
        // Get disabled dates from data attribute
        let disabledDates = [];
        if (dateInput.dataset.disabledDates) {
            try {
                disabledDates = JSON.parse(dateInput.dataset.disabledDates);
            } catch (e) {
                console.error('Invalid disabled dates format', e);
            }
        }
        
        // Get min and max date
        const minDate = dateInput.dataset.minDate || 'today';
        const maxDate = dateInput.dataset.maxDate || null;
        
        // Initialize Flatpickr if available
        if (typeof flatpickr !== 'undefined') {
            flatpickr(dateInput, {
                dateFormat: 'Y-m-d',
                minDate: minDate,
                maxDate: maxDate,
                disable: disabledDates,
                inline: false,
                static: true,
                disableMobile: true,
                onChange: function(selectedDates, dateStr) {
                    // Trigger price calculation
                    const event = new Event('change');
                    document.querySelector('#booking_adults').dispatchEvent(event);
                }
            });
        }
    }
}

/**
 * Initialize guest counter
 */
function initGuestCounter() {
    const counters = document.querySelectorAll('.guest-counter');
    
    counters.forEach(function(counter) {
        const decreaseBtn = counter.querySelector('.decrease-btn');
        const increaseBtn = counter.querySelector('.increase-btn');
        const input = counter.querySelector('input');
        
        if (decreaseBtn && increaseBtn && input) {
            // Get min and max values
            const min = parseInt(input.getAttribute('min') || 0);
            const max = parseInt(input.getAttribute('max') || 999);
            
            // Decrease button event
            decreaseBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                if (value > min) {
                    input.value = value - 1;
                    
                    // Trigger change event
                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
                
                // Disable/enable decrease button based on value
                decreaseBtn.disabled = (parseInt(input.value) <= min);
                increaseBtn.disabled = (parseInt(input.value) >= max);
            });
            
            // Increase button event
            increaseBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                if (value < max) {
                    input.value = value + 1;
                    
                    // Trigger change event
                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
                
                // Disable/enable increase button based on value
                decreaseBtn.disabled = (parseInt(input.value) <= min);
                increaseBtn.disabled = (parseInt(input.value) >= max);
            });
            
            // Initial state
            decreaseBtn.disabled = (parseInt(input.value) <= min);
            increaseBtn.disabled = (parseInt(input.value) >= max);
            
            // Input change event
            input.addEventListener('change', function() {
                let value = parseInt(input.value);
                
                // Validate min and max
                if (isNaN(value) || value < min) {
                    input.value = min;
                } else if (value > max) {
                    input.value = max;
                }
                
                // Disable/enable buttons based on value
                decreaseBtn.disabled = (parseInt(input.value) <= min);
                increaseBtn.disabled = (parseInt(input.value) >= max);
            });
        }
    });
}

/**
 * Initialize price calculator
 */
function initPriceCalculator() {
    const adultsInput = document.querySelector('#booking_adults');
    const childrenInput = document.querySelector('#booking_children');
    const dateInput = document.querySelector('#booking_date');
    const priceDisplay = document.querySelector('#price_display');
    const totalPriceDisplay = document.querySelector('#total_price_display');
    const totalPriceInput = document.querySelector('#booking_total_price');
    
    if (adultsInput && totalPriceDisplay && totalPriceInput) {
        // Get base and discount price
        const basePrice = parseFloat(document.querySelector('#booking_base_price').value || 0);
        const discountPrice = parseFloat(document.querySelector('#booking_discount_price').value || 0);
        const price = (discountPrice > 0) ? discountPrice : basePrice;
        
        // Calculate total price function
        const calculateTotalPrice = function() {
            const adults = parseInt(adultsInput.value || 0);
            const children = parseInt(childrenInput ? childrenInput.value : 0);
            
            // Base calculation (adults full price, children half price)
            let total = (adults * price) + (children * price * 0.5);
            
            // Format currency
            const formattedTotal = formatCurrency(total);
            
            // Update displays
            if (totalPriceDisplay) {
                totalPriceDisplay.textContent = formattedTotal;
            }
            
            // Update hidden input
            if (totalPriceInput) {
                totalPriceInput.value = total.toFixed(2);
            }
        };
        
        // Initial calculation
        calculateTotalPrice();
        
        // Add change event listeners
        adultsInput.addEventListener('change', calculateTotalPrice);
        
        if (childrenInput) {
            childrenInput.addEventListener('change', calculateTotalPrice);
        }
        
        if (dateInput) {
            dateInput.addEventListener('change', calculateTotalPrice);
        }
    }
}

/**
 * Initialize booking form
 */
function initBookingForm() {
    const bookingForm = document.querySelector('#booking_form');
    
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            // Validate form
            const requiredFields = bookingForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Validate email
            const emailInput = bookingForm.querySelector('#booking_email');
            if (emailInput && emailInput.value.trim()) {
                if (!validateEmail(emailInput.value.trim())) {
                    isValid = false;
                    emailInput.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    let errorMessage = emailInput.parentNode.querySelector('.invalid-feedback');
                    if (!errorMessage) {
                        errorMessage = document.createElement('div');
                        errorMessage.classList.add('invalid-feedback');
                        errorMessage.textContent = 'Please enter a valid email address';
                        emailInput.parentNode.appendChild(errorMessage);
                    }
                }
            }
            
            // Validate phone
            const phoneInput = bookingForm.querySelector('#booking_phone');
            if (phoneInput && phoneInput.value.trim()) {
                if (!validatePhone(phoneInput.value.trim())) {
                    isValid = false;
                    phoneInput.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    let errorMessage = phoneInput.parentNode.querySelector('.invalid-feedback');
                    if (!errorMessage) {
                        errorMessage = document.createElement('div');
                        errorMessage.classList.add('invalid-feedback');
                        errorMessage.textContent = 'Please enter a valid phone number';
                        phoneInput.parentNode.appendChild(errorMessage);
                    }
                }
            }
            
            // Check if form is valid
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = bookingForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
        
        // Clear validation on input
        bookingForm.querySelectorAll('input, select, textarea').forEach(function(field) {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    }
}

/**
 * Initialize payment tabs
 */
function initPaymentTabs() {
    const paymentTabs = document.querySelectorAll('.payment-method');
    const paymentContents = document.querySelectorAll('.payment-content');
    
    if (paymentTabs.length > 0) {
        paymentTabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                const method = this.dataset.method;
                
                // Update hidden input
                const methodInput = document.querySelector('#payment_method');
                if (methodInput) {
                    methodInput.value = method;
                }
                
                // Remove active class from all tabs
                paymentTabs.forEach(function(t) {
                    t.classList.remove('active');
                });
                
                // Add active class to current tab
                this.classList.add('active');
                
                // Hide all content
                paymentContents.forEach(function(content) {
                    content.classList.remove('active');
                });
                
                // Show current content
                const content = document.querySelector('.payment-content[data-method="' + method + '"]');
                if (content) {
                    content.classList.add('active');
                }
            });
        });
        
        // Show first payment method by default
        if (paymentTabs[0]) {
            paymentTabs[0].click();
        }
    }
}

/**
 * Format currency
 * 
 * @param {number} amount Amount to format
 * @param {string} currency Currency code
 * @param {string} locale Locale
 * @return {string} Formatted currency
 */
function formatCurrency(amount, currency = 'EUR', locale = 'en-US') {
    // Get currency symbol from HTML
    const symbol = document.querySelector('#currency_symbol')?.value || '€';
    
    return symbol + amount.toFixed(2);
}

/**
 * Validate email
 * 
 * @param {string} email Email to validate
 * @return {boolean} Is valid
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Validate phone
 * 
 * @param {string} phone Phone to validate
 * @return {boolean} Is valid
 */
function validatePhone(phone) {
    const re = /^[+]?[(]?[0-9]{3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{4,6}$/;
    return re.test(String(phone));
}