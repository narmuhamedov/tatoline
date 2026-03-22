<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?php include ("layouts/header.php"); ?>

<?php include ("db.php");  ?>
<!-- Заголовок страницы в красивой рамке -->
<div class="container">
    <div class="certificates-header">
        <h1 class="certificates-title">Сертификаты и награды</h1>
        <p class="certificates-subtitle">Наши достижения и признание профессионализма</p>
    </div>

    <!-- Секция с сертификатами -->
    <div class="row g-4 mb-5">
        <!-- Сертификат 1 -->
         <?php 
         $result = mysqli_query($conn, "SELECT * FROM sertificate");
         $myrow = mysqli_fetch_array($result);

         do {

            printf('<div class="col-lg-4 col-md-6">
            <div class="certificate-card">
                <div class="certificate-img-wrapper">
                    <img src="%s" alt="Сертификат" class="certificate-img">
                    <div class="certificate-year">%s</div>
                </div>
                <div class="certificate-body">
                    <h3 class="certificate-title">%s</h3>
                    <p class="certificate-desc">%s</p>
                </div>
            </div>
        </div>', $myrow['url_sertificate'], $myrow['year'], $myrow['name_serticate'], $myrow['description']);
            
         } while ($myrow = mysqli_fetch_array($result));
         
         
         ?>
   


        


       

    </div>
</div>

<?php include ("layouts/footer.php") ?>

