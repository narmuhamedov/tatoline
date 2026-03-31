<?php
include "../db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// РЕДАКТИРОВАНИЕ КОНТАКТОВ
if(isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $adress = mysqli_real_escape_string($conn, $_POST['adress']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $work_time = mysqli_real_escape_string($conn, $_POST['work_time']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
    
    $sql = "UPDATE contacts SET 
            adress='$adress',
            phone='$phone',
            email='$email',
            work_time='$work_time',
            whatsapp='$whatsapp',
            instagram='$instagram'
            WHERE id=$id";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Контакты обновлены!";
        $msg_type = "success";
    } else {
        $msg = "Ошибка: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

$result = mysqli_query($conn, "SELECT * FROM contacts LIMIT 1");
$contact = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - Админ панель</title>
    <link rel="stylesheet" href="admin_style/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">← Назад в админку</a>
        <a href="../index.php">На сайт</a>
        <a href="../logout.php" style="float: right;">Выйти</a>
    </div>
    
    <div class="container">
        <h1 style="margin-bottom: 20px;">Управление контактами</h1>
        
        <?php if(isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Редактировать контактную информацию</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                
                <div class="form-group">
                    <label>Адрес</label>
                    <input type="text" name="adress" value="<?php echo htmlspecialchars($contact['adress']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Режим работы</label>
                    <input type="text" name="work_time" value="<?php echo htmlspecialchars($contact['work_time']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>WhatsApp ссылка</label>
                    <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($contact['whatsapp']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Instagram ссылка</label>
                    <input type="text" name="instagram" value="<?php echo htmlspecialchars($contact['instagram']); ?>">
                </div>
                
                <button type="submit" name="edit" class="btn btn-primary">Сохранить изменения</button>
            </form>
        </div>
    </div>
</body>
</html>