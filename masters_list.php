<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?php include ("layouts/header.php"); ?>

<?php include ("db.php");  ?>
<!-- Заголовок страницы в красивой рамке -->
<div class="container">
    <div class="masters-header">
        <h1 class="masters-title">Наши мастера</h1>
        <p class="masters-subtitle">Профессионалы своего дела с душой художника</p>
    </div>
    
    <!-- Простые карточки мастеров -->
    <div class="row g-4">
        <!-- Мастер 1 -->


        <?php 
        $result = mysqli_query($conn, "SELECT * FROM masters_tatoo");
        $myrow = mysqli_fetch_array($result);

        do {
            printf('<div class="col-lg-4 col-md-6">
            <div class="simple-card">
                <img src="%s" class="simple-card-img" alt="Александр Волков">
                <div class="simple-card-body">
                    <h3 class="simple-card-title">%s</h3>
                    <p class="simple-card-exp">Опыт: %s лет</p>
                    <a href="master_detail.php?id=%s" class="simple-card-btn">Подробнее</a>
                </div>
            </div>
        </div>', $myrow['photo_master'], $myrow['name_master'], $myrow['experience'], $myrow['id']);
        } while ($myrow = mysqli_fetch_array($result));
        
        ?>

        
         
                
        
        

    </div>
</div>

<?php include ("layouts/footer.php") ?>