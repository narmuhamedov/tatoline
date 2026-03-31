<?php
include "../db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// ДОБАВЛЕНИЕ
if(isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $count_tatoo = (int)$_POST['count_tatoo'];
    $famous_tatoo = mysqli_real_escape_string($conn, $_POST['famous_tatoo']);
    $fan_tatoo = mysqli_real_escape_string($conn, $_POST['fan_tatoo']);
    
    // Загрузка фото
    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "../images/";
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = 'star_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $filename;
        
        if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo = $target_file;
        }
    }
    
    $sql = "INSERT INTO population_people (name, count_tatoo, famous_tatoo, fan_tatoo, photo) 
            VALUES ('$name', $count_tatoo, '$famous_tatoo', '$fan_tatoo', '$photo')";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Знаменитость добавлена!";
        $msg_type = "success";
    } else {
        $msg = "Ошибка: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// РЕДАКТИРОВАНИЕ
if(isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $count_tatoo = (int)$_POST['count_tatoo'];
    $famous_tatoo = mysqli_real_escape_string($conn, $_POST['famous_tatoo']);
    $fan_tatoo = mysqli_real_escape_string($conn, $_POST['fan_tatoo']);
    
    $sql = "UPDATE population_people SET 
            name='$name',
            count_tatoo=$count_tatoo,
            famous_tatoo='$famous_tatoo',
            fan_tatoo='$fan_tatoo'";
    
    // Если загружено новое фото
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Получаем старое фото
        $result = mysqli_query($conn, "SELECT photo FROM population_people WHERE id=$id");
        $old = mysqli_fetch_assoc($result);
        if($old['photo'] && file_exists($old['photo'])) {
            unlink($old['photo']);
        }
        
        $target_dir = "../images/";
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = 'star_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $filename;
        
        if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $sql .= ", photo='$target_file'";
        }
    }
    
    $sql .= " WHERE id=$id";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Знаменитость обновлена!";
        $msg_type = "success";
    } else {
        $msg = "Ошибка: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// УДАЛЕНИЕ
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $result = mysqli_query($conn, "SELECT photo FROM population_people WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    if($row['photo'] && file_exists($row['photo'])) {
        unlink($row['photo']);
    }
    
    mysqli_query($conn, "DELETE FROM population_people WHERE id=$id");
    $msg = "Знаменитость удалена!";
    $msg_type = "success";
}

// ПОЛУЧАЕМ ВСЕХ ЗНАМЕНИТОСТЕЙ
$celebrities = mysqli_query($conn, "SELECT * FROM population_people ORDER BY id DESC");

// ПОЛУЧАЕМ ДАННЫЕ ДЛЯ РЕДАКТИРОВАНИЯ
$edit_data = null;
if(isset($_GET['edit_id'])) {
    $id = (int)$_GET['edit_id'];
    $result = mysqli_query($conn, "SELECT * FROM population_people WHERE id=$id");
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Звезды и тату - Админ панель</title>
    <link rel="stylesheet" href="admin_style/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">← Назад в админку</a>
        <a href="../index.php">На сайт</a>
        <a href="../logout.php" style="float: right;">Выйти</a>
    </div>
    
    <div class="container">
        <h1 style="margin-bottom: 20px;">Управление звездами и их тату</h1>
        
        <?php if(isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        
        <!-- ФОРМА ДОБАВЛЕНИЯ/РЕДАКТИРОВАНИЯ -->
        <div class="card">
            <h2><?php echo $edit_data ? 'Редактировать знаменитость' : 'Добавить знаменитость'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <?php if($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Имя знаменитости *</label>
                    <input type="text" name="name" value="<?php echo $edit_data ? htmlspecialchars($edit_data['name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Количество тату *</label>
                    <input type="number" name="count_tatoo" value="<?php echo $edit_data ? $edit_data['count_tatoo'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Самая известная тату *</label>
                    <input type="text" name="famous_tatoo" value="<?php echo $edit_data ? htmlspecialchars($edit_data['famous_tatoo']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Фанат тату? *</label>
                    <select name="fan_tatoo" required>
                        <option value="Фанат" <?php echo $edit_data && $edit_data['fan_tatoo'] == 'Фанат' ? 'selected' : ''; ?>>Фанат</option>
                        <option value="Не фанат" <?php echo $edit_data && $edit_data['fan_tatoo'] == 'Не фанат' ? 'selected' : ''; ?>>Не фанат</option>
                    </select>
                </div>
                
                <?php if($edit_data && $edit_data['photo']): ?>
                <div class="form-group">
                    <label>Текущее фото</label><br>
                    <img src="<?php echo $edit_data['photo']; ?>" class="photo-preview">
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label><?php echo $edit_data ? 'Новое фото (оставьте пустым, чтобы не менять)' : 'Фото знаменитости *'; ?></label>
                    <input type="file" name="photo" accept="image/*" <?php echo !$edit_data ? 'required' : ''; ?>>
                </div>
                
                <button type="submit" name="<?php echo $edit_data ? 'edit' : 'add'; ?>" class="btn btn-primary">
                    <?php echo $edit_data ? 'Сохранить изменения' : 'Добавить знаменитость'; ?>
                </button>
                
                <?php if($edit_data): ?>
                    <a href="celebrities.php" class="btn-secondary" style="margin-left: 10px; padding: 10px 20px;">Отмена</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- ТАБЛИЦА ЗНАМЕНИТОСТЕЙ -->
        <div class="card">
            <h2>Список знаменитостей</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Фото</th>
                        <th>Имя</th>
                        <th>Кол-во тату</th>
                        <th>Известная тату</th>
                        <th>Фанат</th>
                        <th width="150">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($celebrities)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <?php if($row['photo']): ?>
                                <img src="<?php echo $row['photo']; ?>" width="50" height="50" style="object-fit: cover;">
                            <?php else: ?>
                                Нет фото
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo $row['count_tatoo']; ?>+</td>
                        <td><?php echo htmlspecialchars($row['famous_tatoo']); ?></td>
                        <td><?php echo $row['fan_tatoo']; ?></td>
                        <td class="actions">
                            <a href="?edit_id=<?php echo $row['id']; ?>" class="btn-warning">Редакт</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Удалить знаменитость?')">Удалить</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>