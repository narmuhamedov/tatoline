<?php
include "db.php";
session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    if(empty($phone) || empty($password)) {
        $error = "Заполните все поля";
    } else {
        $phone = mysqli_real_escape_string($conn, $phone);
        $result = mysqli_query($conn, "SELECT * FROM users WHERE phone='$phone'");
        
        if(mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_phone'] = $user['phone'];
                
                header("Location: index.php");
                exit();
            } else {
                $error = "Неверный пароль";
            }
        } else {
            $error = "Пользователь не найден";
        }
    }
}
?>

<?php include "layouts/header.php"; ?>

<div class="container">
    <div class="auth-form-container" style="max-width: 400px; margin: 50px auto;">
        <h2 class="text-center mb-4">Вход</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group mb-3">
                <label>Телефон</label>
                <input type="tel" class="form-control" name="phone" required>
            </div>
            
            <div class="form-group mb-3">
                <label>Пароль</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Войти</button>
        </form>
        
        <p class="text-center mt-3">
            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </p>
    </div>
</div>

<?php include "layouts/footer.php"; ?>