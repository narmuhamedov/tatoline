<?php include ("layouts/header.php"); ?>

<?php 
include ("db.php");  
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM masters_tatoo WHERE id=$id");
$myrow = mysqli_fetch_array($result);

?>

<div class="container">
    <div class="master-detail-card">
        <div class="master-detail-header">
            <h1><?php echo $myrow['name_master'] ?></h1>
            <span class="master-detail-exp">Опыт: <?php echo $myrow['experience']; ?> лет</span>
        </div>
        
        <div class="master-detail-content">
            <div class="master-detail-photo">
                <img src="<?php echo $myrow['photo_master']; ?>" alt="Александр Волков">
            </div>
            
            <div class="master-detail-info">
                <div class="info-block">
                    <h3>Специализация</h3>
                    <p><?php echo $myrow['specialization']; ?></p>
                </div>
                
                <div class="info-block">
                    <h3>О мастере</h3>
                    <p><?php echo $myrow['specialization'];?></p>
                </div>
                
                <div class="info-block">
                    <h3>Основная работа</h3>
                    <p class="master-price"><?php echo $myrow['main_job'];?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ("layouts/footer.php") ?>

