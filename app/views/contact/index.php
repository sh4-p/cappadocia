<?php
/**
 * Contact Page View
 */
?>

<!-- Page Banner -->
<div class="page-banner" style="background-image: url('<?php echo $imgUrl; ?>/contact_bg.png');">
    <div class="container">
        <h1 class="page-title"><?php _e('contact_us'); ?></h1>
        <div class="breadcrumbs">
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a>
            <span class="separator">/</span>
            <span class="current"><?php _e('contact_us'); ?></span>
        </div>
    </div>
</div>

<!-- Contact Section -->
<section class="contact-section section">
    <div class="container">
        <div class="contact-info-cards">
            <div class="contact-info-card" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-info-icon">
                    <i class="material-icons">location_on</i>
                </div>
                <h3 class="contact-info-title"><?php _e('our_location'); ?></h3>
                <p class="contact-info-text"><?php echo $settings['address']; ?></p>
            </div>
            
            <div class="contact-info-card" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-info-icon">
                    <i class="material-icons">phone</i>
                </div>
                <h3 class="contact-info-title"><?php _e('phone_number'); ?></h3>
                <p class="contact-info-text"><?php echo $settings['contact_phone']; ?></p>
            </div>
            
            <div class="contact-info-card" data-aos="fade-up" data-aos-delay="300">
                <div class="contact-info-icon">
                    <i class="material-icons">email</i>
                </div>
                <h3 class="contact-info-title"><?php _e('email_address'); ?></h3>
                <p class="contact-info-text"><?php echo $settings['contact_email']; ?></p>
            </div>
        </div>
        
        <div class="contact-form-wrapper">
            <div class="contact-form" data-aos="fade-up">
                <?php if ($session->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <i class="material-icons">check_circle</i>
                        <span><?php echo $this->session->getFlash('success'); ?></span>
                        <button type="button" class="alert-close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php if ($session->hasFlash('error')): ?>
                    <div class="alert alert-error">
                        <i class="material-icons">error</i>
                        <span><?php echo $this->session->getFlash('error'); ?></span>
                        <button type="button" class="alert-close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <h2 class="section-title"><?php _e('send_message'); ?></h2>
                
                <form action="<?php echo $appUrl . '/' . $currentLang; ?>/contact" method="post" class="contact-form-content">
                    <div class="form-group">
                        <label for="name" class="form-label"><?php _e('your_name'); ?> <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="material-icons">person</i>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label"><?php _e('your_email'); ?> <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="material-icons">email</i>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject" class="form-label"><?php _e('subject'); ?> <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <i class="material-icons">subject</i>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label"><?php _e('message'); ?> <span class="text-danger">*</span></label>
                        <div class="input-with-icon textarea">
                            <i class="material-icons">message</i>
                            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons">send</i>
                            <?php _e('send_message'); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="contact-map" data-aos="fade-up">
                <div id="map" style="width: 100%; height: 100%;" 
                     data-lat="38.642335" 
                     data-lng="34.827335" 
                     data-zoom="12"
                     data-title="<?php echo $settings['site_title']; ?>"></div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('frequently_asked_questions'); ?></h2>
            <p class="section-subtitle"><?php _e('faq_subtitle'); ?></p>
        </div>
        
        <div class="faq-wrapper">
            <div class="faq-list" data-aos="fade-up">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3><?php _e('faq_question_1'); ?></h3>
                        <i class="material-icons">expand_more</i>
                    </div>
                    <div class="faq-answer">
                        <p><?php _e('faq_answer_1'); ?></p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3><?php _e('faq_question_2'); ?></h3>
                        <i class="material-icons">expand_more</i>
                    </div>
                    <div class="faq-answer">
                        <p><?php _e('faq_answer_2'); ?></p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3><?php _e('faq_question_3'); ?></h3>
                        <i class="material-icons">expand_more</i>
                    </div>
                    <div class="faq-answer">
                        <p><?php _e('faq_answer_3'); ?></p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3><?php _e('faq_question_4'); ?></h3>
                        <i class="material-icons">expand_more</i>
                    </div>
                    <div class="faq-answer">
                        <p><?php _e('faq_answer_4'); ?></p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3><?php _e('faq_question_5'); ?></h3>
                        <i class="material-icons">expand_more</i>
                    </div>
                    <div class="faq-answer">
                        <p><?php _e('faq_answer_5'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Maps Script -->
<script>
function initMap() {
    const mapElement = document.getElementById('map');
    
    if (mapElement) {
        const lat = parseFloat(mapElement.dataset.lat || 0);
        const lng = parseFloat(mapElement.dataset.lng || 0);
        const zoom = parseInt(mapElement.dataset.zoom || 12);
        const title = mapElement.dataset.title || '';
        
        const position = { lat: lat, lng: lng };
        
        const map = new google.maps.Map(mapElement, {
            center: position,
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#444444"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": 45
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#46bcec"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                }
            ]
        });
        
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: title,
            animation: google.maps.Animation.DROP
        });
        
        // Add info window if title is provided
        if (title) {
            const infoWindow = new google.maps.InfoWindow({
                content: `<div class="map-info-window"><h4>${title}</h4><p><?php echo $settings['address']; ?></p></div>`
            });
            
            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
            
            // Open info window by default
            infoWindow.open(map, marker);
        }
    }
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsApiKey; ?>&callback=initMap" async defer></script>

<!-- FAQ Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(function(item) {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.material-icons');
        
        question.addEventListener('click', function() {
            // Close all other FAQ items
            faqItems.forEach(function(otherItem) {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.faq-answer').style.maxHeight = null;
                    otherItem.querySelector('.material-icons').textContent = 'expand_more';
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
            
            if (item.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.textContent = 'expand_less';
            } else {
                answer.style.maxHeight = null;
                icon.textContent = 'expand_more';
            }
        });
    });
    
    // Open first FAQ item by default
    if (faqItems.length > 0) {
        faqItems[0].querySelector('.faq-question').click();
    }
});
</script>

<style>
/* Contact Info Cards */
.contact-info-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xxl);
}

.contact-info-card {
    text-align: center;
    padding: var(--spacing-xl);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    background-color: var(--white-color);
    transition: transform var(--transition-medium);
}

.contact-info-card:hover {
    transform: translateY(-10px);
}

.contact-info-icon {
    width: 70px;
    height: 70px;
    border-radius: var(--border-radius-circle);
    background-color: var(--primary-color);
    color: var(--white-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
    font-size: 2rem;
}

.contact-info-title {
    margin-bottom: var(--spacing-sm);
}

.contact-info-text {
    color: #777;
}

/* Contact Form Wrapper */
.contact-form-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
}

.contact-form {
    padding: var(--spacing-xl);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    background-color: var(--white-color);
}

.contact-map {
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    height: 100%;
    min-height: 400px;
    box-shadow: var(--shadow-md);
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
    color: #999;
}

.input-with-icon input,
.input-with-icon textarea {
    padding-left: 3rem;
}

.input-with-icon.textarea i {
    top: 1.5rem;
    transform: none;
}

.map-info-window {
    padding: 0.5rem;
}

.map-info-window h4 {
    margin-bottom: 0.25rem;
}

/* FAQ Styles */
.faq-section {
    background-color: var(--light-color);
}

.faq-wrapper {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    margin-bottom: var(--spacing-md);
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-sm);
    background-color: var(--white-color);
    overflow: hidden;
}

.faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-md) var(--spacing-lg);
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.faq-question h3 {
    margin-bottom: 0;
    font-size: var(--font-size-md);
    flex: 1;
}

.faq-question i {
    font-size: 1.5rem;
    color: var(--primary-color);
    transition: transform var(--transition-fast);
}

.faq-item.active .faq-question {
    background-color: var(--primary-color);
    color: var(--white-color);
}

.faq-item.active .faq-question h3 {
    color: var(--white-color);
}

.faq-item.active .faq-question i {
    color: var(--white-color);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height var(--transition-medium);
    padding: 0 var(--spacing-lg);
}

.faq-item.active .faq-answer {
    padding: var(--spacing-md) var(--spacing-lg);
}

/* Responsive Styles */
@media (max-width: 992px) {
    .contact-info-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .contact-form-wrapper {
        grid-template-columns: 1fr;
    }
    
    .contact-map {
        height: 400px;
    }
}

@media (max-width: 768px) {
    .contact-info-cards {
        grid-template-columns: 1fr;
    }
}
</style>