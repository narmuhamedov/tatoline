<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!-- Подключение header -навигационную панель сайта -->
<?php  include ('layouts/header.php');  

?>
<!-- Контент -->
<?php include ("db.php");  ?>

    <!-- Контент с фактами о тату -->
    <main class="container facts-section">
        <h2 class="section-title">Интересные факты о тату</h2>
        <p class="section-subtitle">Что вы могли не знать о мире татуировки</p>
        
        <div class="row g-4">

        <!-- Скрипт php для вывода данных фактов из БД -->
        <?php 
        $result = mysqli_query($conn, "SELECT * FROM facts_tatoo");
        $myrow = mysqli_fetch_array($result);

        do {
            printf('<div class="col-lg-4 col-md-6">
                <div class="fact-card">
                    <div class="fact-icon">
                        <i class="bi bi-clock-history">😎</i>
                    </div>
                    <h3 class="fact-title">%s</h3>
                    <p class="fact-text">
                        %s
                    </p>
                    <div class="fact-number">fact</div>
                </div>
            </div>', $myrow['title'], $myrow['description']);
        } while ($myrow=mysqli_fetch_array($result));
        
        
        ?>


          

        </div>
    </main>

<!-- Контент 2 -->
    <!-- Секция со звездами и тату -->
    <section class="container stars-section">
        <h2 class="section-title">Звезды и их тату</h2>
        <p class="section-subtitle">Кто из знаменитостей носит тату и кто фанатеет от татуировок</p>
        
        <div class="table-responsive">
            <table class="stars-table">
                <thead>
                    <tr>
                        <th>Звезда</th>
                        <th>Фото</th>
                        <th>Количество тату</th>
                        <th>Самая известная тату</th>
                        <th>Фанат тату?</th>
                    </tr>
                </thead>
                <tbody>
               
             <?php 
$result_tatoo = mysqli_query($conn, "SELECT * FROM population_people");

while($myrow_tatoo = mysqli_fetch_array($result_tatoo)) {
    // ОЧИЩАЕМ ЗНАЧЕНИЕ
    $fan_value = trim($myrow_tatoo['fan_tatoo']);
    
    // УНИВЕРСАЛЬНАЯ ПРОВЕРКА
    if($fan_value == 'Фанат' || $fan_value == 'фанат' || $fan_value == 'ФАНАТ') {
        $fan_class = 'fan-yes';
        $fan_text = 'Фанат';
    } else {
        $fan_class = 'fan-no';
        $fan_text = 'Не фанат';
    }
    
    printf('
        <tr>
            <td class="star-name">
                <div class="star-info">
                    <i class="bi bi-person-circle"></i>
                    <span>%s</span>
                </div>
            </td>
            <td class="star-image">
                <img src="%s" alt="%s" class="star-img">
            </td>
            <td><span class="badge-count">%s+</span></td>
            <td>%s</td>
            <td><span class="fan-badge %s">%s</span></td>
        </tr>', 
        $myrow_tatoo['name'], 
        $myrow_tatoo['photo'], 
        $myrow_tatoo['name'],
        $myrow_tatoo['count_tatoo'],
        $myrow_tatoo['famous_tatoo'], 
        $fan_class, 
        $fan_text
    );
}
?>
               

                </tbody>
            </table>
        </div>
        
        <!-- Легенда -->
        <div class="table-legend">
            <div class="legend-item">
                <span class="legend-color fan-yes-legend"></span>
                <span>Фанат тату</span>
            </div>
            <div class="legend-item">
                <span class="legend-color fan-no-legend"></span>
                <span>Не фанат</span>
            </div>
        </div>
    </section>


<!-- Подключение footer -подвала сайта -->
 <?php include ("layouts/footer.php"); ?>