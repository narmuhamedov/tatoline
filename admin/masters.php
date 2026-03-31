<?php
include "../db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// ДОБАВЛЕНИЕ
if(isset($_POST['add'])) {
    $name_master = mysqli_real_escape_string($conn, $_POST['name_master']);
    $experience = (int)$_POST['experience'];
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $main_job = mysqli_real_escape_string($conn, $_POST['main_job']);
    
    $photo_master = '';
    if(isset($_FILES['photo_master']) && $_FILES['photo_master']['error'] == 0) {
        $target_dir = "../images/";
        $file_extension = pathinfo($_FILES['photo_master']['name'], PATHINFO_EXTENSION);
        $filename = 'master_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $filename;
        
        if(move_uploaded_file($_FILES['photo_master']['tmp_name'], $target_file)) {
            $photo_master = $target_file;
        }
    }
    
    $sql = "INSERT INTO masters_tatoo (name_master, experience, specialization, main_job, photo_master) 
            VALUES ('$name_master', $experience, '$specialization', '$main_job', '$photo_master')";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Мастер добавлен!";
        $msg_type = "success";
    } else {
        $msg = "Ошибка: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// РЕДАКТИРОВАНИЕ
if(isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $name_master = mysqli_real_escape_string($conn, $_POST['name_master']);
    $experience = (int)$_POST['experience'];
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $main_job = mysqli_real_escape_string($conn, $_POST['main_job']);
    
    $sql = "UPDATE masters_tatoo SET 
            name_master='$name_master',
            experience=$experience,
            specialization='$specialization',
            main_job='$main_job'";
    
    if(isset($_FILES['photo_master']) && $_FILES['photo_master']['error'] == 0) {
        $result = mysqli_query($conn, "SELECT photo_master FROM masters_tatoo WHERE id=$id");
        $old = mysqli_fetch_assoc($result);
        if($old['photo_master'] && file_exists($old['photo_master'])) {
            unlink($old['photo_master']);
        }
        
        $target_dir = "../images/";
        $file_extension = pathinfo($_FILES['photo_master']['name'], PATHINFO_EXTENSION);
        $filename = 'master_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $filename;
        
        if(move_uploaded_file($_FILES['photo_master']['tmp_name'], $target_file)) {
            $sql .= ", photo_master='$target_file'";
        }
    }
    
    $sql .= " WHERE id=$id";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Мастер обновлен!";
        $msg_type = "success";
    } else {
        $msg = "Ошибка: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// УДАЛЕНИЕ
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $result = mysqli_query($conn, "SELECT photo_master FROM masters_tatoo WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    if($row['photo_master'] && file_exists($row['photo_master'])) {
        unlink($row['photo_master']);
    }
    
    mysqli_query($conn, "DELETE FROM masters_tatoo WHERE id=$id");
    $msg = "Мастер удален!";
    $msg_type = "success";
}

$masters = mysqli_query($conn, "SELECT * FROM masters_tatoo ORDER BY id DESC");

$edit_data = null;
if(isset($_GET['edit_id'])) {
    $id = (int)$_GET['edit_id'];
    $result = mysqli_query($conn, "SELECT * FROM masters_tatoo WHERE id=$id");
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мастера - Админ панель</title>
     <link rel="stylesheet" href="admin_style/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">← Назад в админку</a>
        <a href="../index.php">На сайт</a>
        <a href="../logout.php" style="float: right;">Выйти</a>
    </div>
    
    <div class="container">
        <h1 style="margin-bottom: 20px;">Управление мастерами</h1>
        
        <?php if(isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2><?php echo $edit_data ? 'Редактировать мастера' : 'Добавить мастера'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <?php if($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Имя мастера *</label>
                    <input type="text" name="name_master" value="<?php echo $edit_data ? htmlspecialchars($edit_data['name_master']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Опыт (лет) *</label>
                    <input type="number" name="experience" value="<?php echo $edit_data ? $edit_data['experience'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Специализация *</label>
                    <textarea name="specialization" rows="2" required><?php echo $edit_data ? htmlspecialchars($edit_data['specialization']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Основная работа *</label>
                    <input type="text" name="main_job" value="<?php echo $edit_data ? htmlspecialchars($edit_data['main_job']) : ''; ?>" required>
                </div>
                
                <?php if($edit_data && $edit_data['photo_master']): ?>
                <div class="form-group">
                    <label>Текущее фото</label><br>
                    <img src="<?php echo $edit_data['photo_master']; ?>" class="photo-preview">
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label><?php echo $edit_data ? 'Новое фото (оставьте пустым, чтобы не менять)' : 'Фото мастера'; ?></label>
                    <input type="file" name="photo_master" accept="image/*">
                </div>
                
                <button type="submit" name="<?php echo $edit_data ? 'edit' : 'add'; ?>" class="btn btn-primary">
                    <?php echo $edit_data ? 'Сохранить изменения' : 'Добавить мастера'; ?>
                </button>
                
                <?php if($edit_data): ?>
                    <a href="masters.php" class="btn-secondary" style="margin-left: 10px; padding: 10px 20px;">Отмена</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="card">
            <h2>Список мастеров</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Фото</th><th>Имя</th><th>Опыт</th><th>Специализация</th><th>Основная работа</th><th>Действия</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($masters)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php if($row['photo_master']): ?><img src="<?php echo $row['photo_master']; ?>" width="50" height="50" style="object-fit: cover;"><?php else: ?>Нет фото<?php endif; ?></td>
                        <td><?php echo htmlspecialchars($row['name_master']); ?></td>
                        <td><?php echo $row['experience']; ?> лет</td>
                        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                        <td><?php echo htmlspecialchars($row['main_job']); ?></td>
                        <td class="actions">
                            <a href="?edit_id=<?php echo $row['id']; ?>" class="btn-warning">Редакт</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Удалить мастера?')">Удалить</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>