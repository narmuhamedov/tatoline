<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?php include ("layouts/header.php"); ?>

<?php include ("db.php");  ?>
<div class="container">
    <!-- Заголовок страницы -->
    <div class="about-header">
        <h1 class="about-title">О нас</h1>
        <p class="about-subtitle">История и философия нашей студии</p>
    </div>

    <!-- Основной контент -->
    <div class="about-content">
        <!-- Блок с фото и описанием -->
        <div class="about-grid">
            <div class="about-image">
                <img src="http://tatooline.kg/images/logo/logo.png" alt="Наша студия">
            </div>
            <div class="about-text">
                <h2>BlackLine Tattoo Studio</h2>
                <p>Наша студия открылась в 2020 году. За это время мы стали любимым местом для тех, кто ценит качественную татуировку и индивидуальный подход.</p>
                <p>Мы собрали команду профессионалов, которые любят свое дело и постоянно развиваются. Каждый мастер имеет художественное образование и регулярно посещает мастер-классы российских и зарубежных коллег.</p>
            </div>
        </div>

        <!-- Наши преимущества -->
        <div class="advantages-section">
            <h2 class="advantages-title">Почему выбирают нас</h2>
            <div class="advantages-grid">
                <div class="advantage-item">
                    <div class="advantage-icon">🩺</div>
                    <h3>Стерильность</h3>
                    <p>Используем одноразовые расходники и многоразовый инструмент проходящий многоступенчатую стерилизацию</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">🎨</div>
                    <h3>Качественные краски</h3>
                    <p>Работаем только с профессиональными американскими и европейскими брендами</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">📝</div>
                    <h3>Индивидуальный эскиз</h3>
                    <p>Каждый рисунок создается специально для вас с учетом пожеланий и особенностей</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">🤝</div>
                    <h3>Консультация</h3>
                    <p>Поможем определиться с эскизом, размером и местом нанесения</p>
                </div>
            </div>
        </div>

        <!-- Наши мастера (кратко) -->
        <div class="team-preview">
            <h2 class="team-title">Наша команда</h2>
            <div class="team-grid">
            
            <?php 
            $result = mysqli_query($conn, "SELECT * FROM masters_tatoo LIMIT 3");
            $myrow = mysqli_fetch_array($result);

            do {
                printf('<div class="team-member">
                    <img src="%s" alt="Александр Волков">
                    <h4>%s</h4>
                    <p>%s лет опыта</p>
                </div>
            ', $myrow['photo_master'], $myrow['name_master'], $myrow['experience']);
            } while ($myrow = mysqli_fetch_array($result));

            
            ?>
            
    
            </div>
            <div class="team-link">
                <a href="masters_list.php" class="about-btn">Все мастера →</a>
            </div>
        </div>
    </div>
</div>

<?php include ("layouts/footer.php") ?>