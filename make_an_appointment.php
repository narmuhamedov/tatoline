<?php
include "db.php";
session_start();
include "layouts/header.php";

// ПРОВЕРКА АВТОРИЗАЦИИ
if(!isset($_SESSION['user_id'])) {
    ?>
    <div class="container">
        <div class="appointment-header">
            <h1 class="appointment-title">Запись на прием</h1>
            <p class="appointment-subtitle">Для записи необходимо авторизоваться</p>
        </div>
        
        <div class="appointment-form-container" style="text-align: center;">
            <div class="alert alert-warning" style="font-size: 18px; margin-bottom: 20px;">
                <i class="bi bi-exclamation-triangle-fill"></i> 
                Для записи на прием необходимо <strong>авторизоваться</strong> или <strong>зарегистрироваться</strong>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="login.php" class="btn btn-primary btn-lg">Войти</a>
                <a href="register.php" class="btn btn-success btn-lg">Регистрация</a>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="index.php" class="btn btn-secondary">Вернуться на главную</a>
            </div>
        </div>
    </div>
    
    <style>
    .appointment-form-container {
        max-width: 500px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .appointment-title {
        text-align: center;
        margin-bottom: 10px;
    }
    .appointment-subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 30px;
    }
    </style>
    
    <?php
    include "layouts/footer.php";
    exit();
}

// Если выбран дата, получаем свободные слоты
$selected_date = isset($_POST['preview_date']) ? $_POST['preview_date'] : (isset($_GET['date']) ? $_GET['date'] : '');
$available_slots = [];

if($selected_date) {
    // Получаем занятые слоты
    $query = "SELECT appointment_date FROM appointments WHERE DATE(appointment_date) = '$selected_date' AND status != 'completed'";
    $result = mysqli_query($conn, $query);
    
    $booked_slots = [];
    while($row = mysqli_fetch_assoc($result)) {
        $booked_slots[] = date('H:i', strtotime($row['appointment_date']));
    }
    
    // Доступные слоты (каждый час с 10:00 до 20:00)
    for($i = 10; $i <= 20; $i++) {
        $time = sprintf("%02d:00", $i);
        if(!in_array($time, $booked_slots)) {
            $available_slots[] = $time;
        }
    }
}

$name = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$phone = isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '';
?>

<div class="container">
    <div class="appointment-header">
        <h1 class="appointment-title">Запись на прием</h1>
        <p class="appointment-subtitle">Выберите дату и время, и мы ждем вас!</p>
    </div>

    <div class="appointment-form-container">
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        
        <!-- Форма предпросмотра свободных слотов -->
        <form method="POST" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
            <div class="row">
                <div class="col-md-8">
                    <label>Выберите дату для просмотра свободного времени</label>
                    <input type="date" class="form-control" name="preview_date" value="<?php echo $selected_date; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-4" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn btn-secondary" style="width: 100%;">Показать свободное время</button>
                </div>
            </div>
        </form>
        
        <!-- Основная форма записи -->
        <form action="send_appointment.php" method="POST">
            <div class="form-group mb-3">
                <label>Ваше имя *</label>
                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label>Телефон *</label>
                <input type="tel" class="form-control" name="phone" value="<?php echo $phone; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label>К какому мастеру?</label>
                <select class="form-control" name="master">
                    <option value="">Выберите мастера</option>
                    <?php
                    $masters = mysqli_query($conn, "SELECT name_master FROM masters_tatoo");
                    while($master = mysqli_fetch_assoc($masters)) {
                        echo '<option value="' . htmlspecialchars($master['name_master']) . '">' . htmlspecialchars($master['name_master']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <label>Дата записи *</label>
                <input type="date" class="form-control" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo $selected_date; ?>">
                <?php if(!$selected_date): ?>
                    <small class="text-muted">Сначала выберите дату в форме выше, чтобы увидеть свободное время</small>
                <?php endif; ?>
            </div>
            
            <div class="form-group mb-3">
                <label>Время записи *</label>
                <select class="form-control" name="appointment_time" required>
                    <option value="">Выберите время</option>
                    <?php if($selected_date && count($available_slots) > 0): ?>
                        <?php foreach($available_slots as $slot): ?>
                            <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                        <?php endforeach; ?>
                    <?php elseif($selected_date): ?>
                        <option value="" disabled>Нет свободного времени на эту дату</option>
                    <?php else: ?>
                        <option value="" disabled>Сначала выберите дату</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Опишите идею</label>
                <textarea class="form-control" name="message" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Записаться</button>
        </form>
    </div>
</div>

<style>
.appointment-form-container {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
.appointment-title {
    text-align: center;
    margin-bottom: 10px;
}
.appointment-subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}
</style>

<?php include "layouts/footer.php"; ?>