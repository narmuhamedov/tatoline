<?php
include "../db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// ДОБАВЛЕНИЕ СЕРТИФИКАТА
if(isset($_POST['add'])) {
    $name_serticate = mysqli_real_escape_string($conn, $_POST['name_serticate']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $year = (int)$_POST['year'];
    
    // Загрузка фото сертификата
    $url_sertificate = '';
    if(isset($_FILES['url_sertificate']) && $_FILES['url_sertificate']['error'] == 0) {
        $target_dir = "../images/";
        $file_extension = pathinfo($_FILES['url_sertificate']['name'], PATHINFO_EXTENSION);
        $filename = 'cert_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $filename;
        
        if(move_uploaded_file($_FILES['url_sertificate']['tmp_name'], $target_file)) {
            $url_sertificate = $target_file;
        }
    }
    
    $sql = "INSERT INTO sertificate (name_serticate, description, year, url_sertificate) 
            VALUES ('$name_serticate', '$description', $year, '$url_sertificate')";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Сертификат добавлен!";
        $msg_type = "success";
    } else {
        $msg = "Ошибка: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// УДАЛЕНИЕ СЕРТИФИКАТА
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $result = mysqli_query($conn, "SELECT url_sertificate FROM sertificate WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    if($row['url_sertificate'] && file_exists($row['url_sertificate'])) {
        unlink($row['url_sertificate']);
    }
    
    mysqli_query($conn, "DELETE FROM sertificate WHERE id=$id");
    $msg = "Сертификат удален!";
    $msg_type = "success";
}

$certificates = mysqli_query($conn, "SELECT * FROM sertificate ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сертификаты - Админ панель</title>
    <link rel="stylesheet" href="admin_style/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">← Назад в админку</a>
        <a href="../index.php">На сайт</a>
        <a href="../logout.php" style="float: right;">Выйти</a>
    </div>
    
    <div class="container">
        <h1 style="margin-bottom: 20px;">Управление сертификатами</h1>
        
        <?php if(isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Добавить сертификат</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Название сертификата *</label>
                    <input type="text" name="name_serticate" required>
                </div>
                
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Год *</label>
                    <input type="number" name="year" required>
                </div>
                
                <div class="form-group">
                    <label>Изображение сертификата *</label>
                    <input type="file" name="url_sertificate" accept="image/*" required>
                </div>
                
                <button type="submit" name="add" class="btn btn-primary">Добавить сертификат</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Список сертификатов</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Изображение</th><th>Название</th><th>Описание</th><th>Год</th><th>Действие</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($certificates)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php if($row['url_sertificate']): ?><img src="<?php echo $row['url_sertificate']; ?>" width="50" height="50" style="object-fit: cover;"><?php else: ?>Нет фото<?php endif; ?></td>
                        <td><?php echo htmlspecialchars($row['name_serticate']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo $row['year']; ?></td>
                        <td><a href="?delete=<?php echo $row['id']; ?>" class="btn-danger" style="padding: 5px 10px; text-decoration: none;" onclick="return confirm('Удалить сертификат?')">Удалить</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>