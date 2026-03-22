<?php
include "db.php";
session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if(empty($username) || empty($phone) || empty($password)) {
        $error = "Все поля обязательны для заполнения";
    } elseif($password != $confirm_password) {
        $error = "Пароли не совпадают";
    } else {
        // Проверка существующего телефона
        $check = mysqli_query($conn, "SELECT id FROM users WHERE phone='$phone'");
        if(mysqli_num_rows($check) > 0) {
            $error = "Пользователь с таким телефоном уже зарегистрирован";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (username, phone, password, role) VALUES ('$username', '$phone', '$hashed_password', 'client')";
            
            if(mysqli_query($conn, $sql)) {
                // Сразу авторизуем пользователя
                $user_id = mysqli_insert_id($conn);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = 'client';
                $_SESSION['user_phone'] = $phone;
                
                header("Location: index.php");
                exit();
            } else {
                $error = "Ошибка регистрации";
            }
        }
    }
}
?>

<?php include "layouts/header.php"; ?>

<div class="container">
    <div class="auth-form-container" style="max-width: 400px; margin: 50px auto;">
        <h2 class="text-center mb-4">Регистрация</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group mb-3">
                <label>Имя</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            
            <div class="form-group mb-3">
                <label>Телефон</label>
                <input type="tel" class="form-control" name="phone" required>
            </div>
            
            <div class="form-group mb-3">
                <label>Пароль</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            
            <div class="form-group mb-3">
                <label>Подтвердите пароль</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
        </form>
        
        <p class="text-center mt-3">
            Уже есть аккаунт? <a href="login.php">Войти</a>
        </p>
    </div>
</div>

<?php include "layouts/footer.php"; ?>