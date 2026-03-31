<?php
include "../db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// ДОБАВЛЕНИЕ ФАКТА
if(isset($_POST['add'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $sql = "INSERT INTO facts_tatoo (title, description) VALUES ('$title', '$description')";
    
    if(mysqli_query($conn, $sql)) {
        $msg = "Факт добавлен!";
        $msg_type = "success";
    } else {
        $msg = "Ошибка: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// УДАЛЕНИЕ ФАКТА
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM facts_tatoo WHERE id=$id");
    $msg = "Факт удален!";
    $msg_type = "success";
}

// ПОЛУЧАЕМ ВСЕ ФАКТЫ
$facts = mysqli_query($conn, "SELECT * FROM facts_tatoo ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Факты о тату - Админ панель</title>
    <link rel="stylesheet" href="admin_style/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">← Назад в админку</a>
        <a href="../index.php">На сайт</a>
        <a href="../logout.php" style="float: right;">Выйти</a>
    </div>
    
    <div class="container">
        <h1 style="margin-bottom: 20px;">Управление фактами о тату</h1>
        
        <?php if(isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        
        <!-- ФОРМА ДОБАВЛЕНИЯ -->
        <div class="card">
            <h2>Добавить новый факт</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Заголовок</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Добавить факт</button>
            </form>
        </div>
        
        <!-- ТАБЛИЦА ФАКТОВ -->
        <div class="card">
            <h2>Список фактов</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Описание</th>
                        <th width="100">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($facts)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn-danger" style="padding: 5px 10px; text-decoration: none;" onclick="return confirm('Удалить факт?')">Удалить</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>