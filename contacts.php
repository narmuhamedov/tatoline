<?php include ("layouts/header.php"); ?>
<?php include ("db.php"); ?>

<?php 
// Получаем данные контактов из БД
$result = mysqli_query($conn, "SELECT * FROM contacts LIMIT 1");
$contact = mysqli_fetch_array($result);
?>

<div class="container">
    <!-- Заголовок страницы -->
    <div class="contacts-header">
        <h1 class="contacts-title">Контакты</h1>
        <p class="contacts-subtitle">Как нас найти и связаться с нами</p>
    </div>

    <div class="contacts-grid">
        <!-- Левая колонка с информацией -->
        <div class="contacts-info">
            <div class="contact-item">
                <div class="contact-icon">📍</div>
                <div class="contact-text">
                    <h3>Адрес</h3>
                    <p><?php echo $contact['adress']; ?></p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon">📞</div>
                <div class="contact-text">
                    <h3>Телефон</h3>
                    <p><a href="tel:<?php echo $contact['phone']; ?>"><?php echo $contact['phone']; ?></a></p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon">✉️</div>
                <div class="contact-text">
                    <h3>Email</h3>
                    <p><a href="mailto:<?php echo $contact['email']; ?>"><?php echo $contact['email']; ?></a></p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon">🕒</div>
                <div class="contact-text">
                    <h3>Режим работы</h3>
                    <p><?php echo $contact['work_time']; ?></p>
                </div>
            </div>

            <div class="contact-social">
                <h3>Мы в соцсетях</h3>
                <div class="social-links">
                    <a href="<?php echo $contact['whatsapp']; ?>" target="_blank" class="social-link">
                        <img src="https://png.klev.club/uploads/posts/2024-04/png-klev-club-815q-p-whatsapp-png-26.png" alt="WhatsApp">
                    </a>
                    <a href="<?php echo $contact['instagram']; ?>" target="_blank" class="social-link">
                        <img src="https://img.freepik.com/free-vector/instagram-logo_1199-122.jpg?semt=ais_rp_progressive&w=740&q=80" alt="Instagram">
                    </a>
                </div>
            </div>
        </div>

        <!-- Правая колонка с картой (статическая) -->
        <div class="contacts-map">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d150686.9509069125!2d78.34319455!3d42.482315!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38869c6f2b2b2b2b%3A0x2b2b2b2b2b2b2b2b!2z0JrQsNGA0LDQutC-0LssINCa0YDRi9Cz0YHRgtCw0L0!5e0!3m2!1sru!2sru!4v1620000000000!5m2!1sru!2sru" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</div>

<?php include ("layouts/footer.php"); ?>