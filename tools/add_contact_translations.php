<?php
/**
 * Add missing translation keys for contact form
 * 
 * This script adds the missing translation keys to the database
 * Run once: php add_contact_translations.php
 */

// Define base path
define('BASE_PATH', dirname(dirname(__FILE__)));

// Include required files
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/core/Database.php';

// Create database instance
$db = new Database();

// Translation keys to add
$translations = [
    'message_sent_success' => [
        'en' => 'Your message has been sent successfully!',
        'tr' => 'Mesajınız başarıyla gönderildi!',
        'de' => 'Ihre Nachricht wurde erfolgreich gesendet!',
        'ru' => 'Ваше сообщение успешно отправлено!'
    ],
    'message_sent_error' => [
        'en' => 'An error occurred while sending your message. Please try again.',
        'tr' => 'Mesajınız gönderilirken bir hata oluştu. Lütfen tekrar deneyin.',
        'de' => 'Beim Senden Ihrer Nachricht ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.',
        'ru' => 'При отправке сообщения произошла ошибка. Пожалуйста, попробуйте еще раз.'
    ],
    'name_required' => [
        'en' => 'Name is required',
        'tr' => 'İsim alanı zorunludur',
        'de' => 'Name ist erforderlich',
        'ru' => 'Имя обязательно'
    ],
    'email_required' => [
        'en' => 'Email is required',
        'tr' => 'E-posta alanı zorunludur',
        'de' => 'E-Mail ist erforderlich',
        'ru' => 'Email обязателен'
    ],
    'invalid_email' => [
        'en' => 'Please enter a valid email address',
        'tr' => 'Lütfen geçerli bir e-posta adresi girin',
        'de' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein',
        'ru' => 'Пожалуйста, введите действительный адрес электронной почты'
    ],
    'subject_required' => [
        'en' => 'Subject is required',
        'tr' => 'Konu alanı zorunludur',
        'de' => 'Betreff ist erforderlich',
        'ru' => 'Тема обязательна'
    ],
    'message_required' => [
        'en' => 'Message is required',
        'tr' => 'Mesaj alanı zorunludur',
        'de' => 'Nachricht ist erforderlich',
        'ru' => 'Сообщение обязательно'
    ],
    'frequently_asked_questions' => [
        'en' => 'Frequently Asked Questions',
        'tr' => 'Sıkça Sorulan Sorular',
        'de' => 'Häufig gestellte Fragen',
        'ru' => 'Часто задаваемые вопросы'
    ],
    'faq_subtitle' => [
        'en' => 'Find answers to the most common questions about our tours and services',
        'tr' => 'Turlarımız ve hizmetlerimiz hakkında en sık sorulan soruların cevaplarını bulun',
        'de' => 'Finden Sie Antworten auf die häufigsten Fragen zu unseren Touren und Dienstleistungen',
        'ru' => 'Найдите ответы на наиболее часто задаваемые вопросы о наших турах и услугах'
    ],
    'faq_question_1' => [
        'en' => 'What is included in the tour price?',
        'tr' => 'Tur fiyatına neler dahildir?',
        'de' => 'Was ist im Tourpreis enthalten?',
        'ru' => 'Что включено в стоимость тура?'
    ],
    'faq_answer_1' => [
        'en' => 'Our tour prices typically include professional guide services, transportation, entrance fees to attractions mentioned in the itinerary, and specified meals. Please check each tour description for specific inclusions.',
        'tr' => 'Tur fiyatlarımıza genellikle profesyonel rehberlik hizmetleri, ulaşım, güzergahta belirtilen yerlerin giriş ücretleri ve belirtilen öğünler dahildir. Lütfen her turun açıklamasını kontrol edin.',
        'de' => 'Unsere Tourpreise beinhalten in der Regel professionelle Reiseleitung, Transport, Eintrittsgelder für die im Reiseplan genannten Attraktionen und spezifizierte Mahlzeiten.',
        'ru' => 'В стоимость наших туров обычно входят услуги профессионального гида, транспорт, входные билеты на достопримечательности и указанное питание.'
    ],
    'faq_question_2' => [
        'en' => 'How can I book a tour?',
        'tr' => 'Nasıl tur rezervasyonu yapabilirim?',
        'de' => 'Wie kann ich eine Tour buchen?',
        'ru' => 'Как я могу забронировать тур?'
    ],
    'faq_answer_2' => [
        'en' => 'You can book a tour directly through our website by selecting your preferred tour and date, then filling out the booking form. You can also contact us via phone or email for assistance with your booking.',
        'tr' => 'Web sitemiz üzerinden tercih ettiğiniz turu ve tarihi seçerek doğrudan rezervasyon yapabilirsiniz. Ayrıca telefon veya e-posta yoluyla bizimle iletişime geçebilirsiniz.',
        'de' => 'Sie können eine Tour direkt über unsere Website buchen, indem Sie Ihre bevorzugte Tour und Ihr Datum auswählen und dann das Buchungsformular ausfüllen.',
        'ru' => 'Вы можете забронировать тур прямо на нашем сайте, выбрав предпочитаемый тур и дату, а затем заполнив форму бронирования.'
    ],
    'faq_question_3' => [
        'en' => 'What is your cancellation policy?',
        'tr' => 'İptal politikanız nedir?',
        'de' => 'Was ist Ihre Stornierungsrichtlinie?',
        'ru' => 'Какова ваша политика отмены?'
    ],
    'faq_answer_3' => [
        'en' => 'Free cancellation is available up to 24 hours before the tour start time. Cancellations made less than 24 hours before the tour are subject to a cancellation fee. Please refer to our terms and conditions for detailed information.',
        'tr' => 'Tur başlangıç saatinden 24 saat öncesine kadar ücretsiz iptal mümkündür. 24 saatten az bir süre kala yapılan iptaller iptal ücretine tabidir. Detaylı bilgi için şartlar ve koşullarımızı inceleyiniz.',
        'de' => 'Eine kostenlose Stornierung ist bis zu 24 Stunden vor Tourbeginn möglich. Bei Stornierungen weniger als 24 Stunden vor der Tour fallen Stornogebühren an.',
        'ru' => 'Бесплатная отмена возможна за 24 часа до начала тура. При отмене менее чем за 24 часа взимается плата за отмену.'
    ],
    'faq_question_4' => [
        'en' => 'Are your tours suitable for children?',
        'tr' => 'Turlarınız çocuklar için uygun mu?',
        'de' => 'Sind Ihre Touren für Kinder geeignet?',
        'ru' => 'Подходят ли ваши туры для детей?'
    ],
    'faq_answer_4' => [
        'en' => 'Most of our tours are family-friendly and suitable for children. However, some tours may have age restrictions or physical requirements. Please check the specific tour details or contact us for recommendations on child-friendly tours.',
        'tr' => 'Turlarımızın çoğu aile dostu ve çocuklar için uygundur. Ancak bazı turların yaş sınırlamaları veya fiziksel gereksinimleri olabilir. Lütfen tur detaylarını kontrol edin veya çocuk dostu turlar için bizimle iletişime geçin.',
        'de' => 'Die meisten unserer Touren sind familienfreundlich und für Kinder geeignet. Einige Touren können jedoch Altersbeschränkungen oder körperliche Anforderungen haben.',
        'ru' => 'Большинство наших туров подходят для семей с детьми. Однако некоторые туры могут иметь возрастные ограничения или физические требования.'
    ],
    'faq_question_5' => [
        'en' => 'Do you offer private tours?',
        'tr' => 'Özel turlar sunuyor musunuz?',
        'de' => 'Bieten Sie private Touren an?',
        'ru' => 'Предлагаете ли вы частные туры?'
    ],
    'faq_answer_5' => [
        'en' => 'Yes, we offer private tours that can be customized according to your preferences. Private tours provide a more personalized experience with flexible itineraries. Please contact us for pricing and availability.',
        'tr' => 'Evet, tercihlerinize göre özelleştirilebilen özel turlar sunuyoruz. Özel turlar, esnek güzergahlarla daha kişisel bir deneyim sağlar. Fiyat ve müsaitlik için lütfen bizimle iletişime geçin.',
        'de' => 'Ja, wir bieten private Touren an, die nach Ihren Wünschen angepasst werden können. Private Touren bieten ein persönlicheres Erlebnis mit flexiblen Reiserouten.',
        'ru' => 'Да, мы предлагаем частные туры, которые могут быть настроены в соответствии с вашими предпочтениями. Частные туры обеспечивают более персонализированный опыт.'
    ]
];

// Begin transaction
$db->beginTransaction();

try {
    foreach ($translations as $key => $values) {
        // Check if key already exists
        $sql = "SELECT id FROM translation_keys WHERE key_name = :key";
        $existingKeyId = $db->getValue($sql, ['key' => $key]);
        
        if (!$existingKeyId) {
            // Insert new key
            $keyId = $db->insert('translation_keys', ['key_name' => $key]);
            echo "Added translation key: $key\n";
        } else {
            $keyId = $existingKeyId;
            echo "Translation key already exists: $key\n";
        }
        
        // Add translations for each language
        foreach ($values as $langCode => $value) {
            // Get language ID
            $sql = "SELECT id FROM languages WHERE code = :code";
            $langId = $db->getValue($sql, ['code' => $langCode]);
            
            if (!$langId) {
                echo "  - Language not found: $langCode\n";
                continue;
            }
            
            // Check if translation already exists
            $sql = "SELECT id FROM translations WHERE key_id = :keyId AND language_id = :langId";
            $existingTransId = $db->getValue($sql, ['keyId' => $keyId, 'langId' => $langId]);
            
            if (!$existingTransId) {
                // Insert translation
                $db->insert('translations', [
                    'key_id' => $keyId,
                    'language_id' => $langId,
                    'value' => $value
                ]);
                echo "  - Added translation for $langCode: $value\n";
            } else {
                // Update existing translation
                $db->update('translations', 
                    ['value' => $value],
                    ['id' => $existingTransId]
                );
                echo "  - Updated translation for $langCode: $value\n";
            }
        }
    }
    
    // Commit transaction
    $db->endTransaction();
    echo "\nAll translations added/updated successfully!\n";
    
} catch (Exception $e) {
    // Rollback transaction
    $db->cancelTransaction();
    echo "Error: " . $e->getMessage() . "\n";
}
