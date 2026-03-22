<?php
include "db.php";
session_start();
include "layouts/header.php";
?>

<div class="container">
    <div class="appointment-header">
        <h1 class="appointment-title">Запись на прием</h1>
        <p class="appointment-subtitle">Оставьте свои контакты, и мы свяжемся с вами</p>
    </div>

    <div class="appointment-form-container">
        <?php
        $name = isset($_SESSION['username']) ? $_SESSION['username'] : '';
        $phone = isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '';
        ?>
        
        <form class="appointment-form" action="send_appointment.php" method="POST">
            <div class="form-group mb-3">
                <label>Ваше имя</label>
                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label>Телефон</label>
                <input type="tel" class="form-control" name="phone" value="<?php echo $phone; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label>К какому мастеру?</label>
                <input type="text" class="form-control" name="master" placeholder="Например: Александр">
            </div>

            <div class="form-group mb-3">
                <label>Опишите идею</label>
                <textarea class="form-control" name="message" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
</div>

<?php include "layouts/footer.php"; ?>