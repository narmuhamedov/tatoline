<?php
include "../db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link rel="stylesheet" href="admin_style/style.css">
</head>
<body>
    <div class="admin-header">
        <h1>Панель управления</h1>
        <p>Добро пожаловать, <?php echo $_SESSION['username']; ?></p>
    </div>
    
    <div class="admin-container">
        <div class="admin-grid">
            <a href="masters.php" class="admin-card">
                <div class="icon">👨‍🎨</div>
                <h3>Мастера</h3>
                <p>Управление мастерами (добавление, редактирование, удаление)</p>
            </a>
            
            <a href="certificates.php" class="admin-card">
                <div class="icon">🏆</div>
                <h3>Сертификаты</h3>
                <p>Управление сертификатами (добавление, удаление)</p>
            </a>
            
            <a href="facts.php" class="admin-card">
                <div class="icon">📝</div>
                <h3>Факты о тату</h3>
                <p>Управление фактами (добавление, удаление)</p>
            </a>
            
            <a href="celebrities.php" class="admin-card">
                <div class="icon">⭐</div>
                <h3>Звезды и тату</h3>
                <p>Управление знаменитостями (добавление, редактирование, удаление)</p>
            </a>
            
            <a href="contacts.php" class="admin-card">
                <div class="icon">📞</div>
                <h3>Контакты</h3>
                <p>Редактирование контактной информации</p>
            </a>
            
            <a href="../admin_appointments.php" class="admin-card">
                <div class="icon">📅</div>
                <h3>Записи клиентов</h3>
                <p>Просмотр и управление записями</p>
            </a>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="../logout.php" class="logout">Выйти</a>
            <a href="../index.php" target="_blank" style="display: inline-block; margin-left: 10px; background: #666; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">На сайт</a>
        </div>
    </div>
</body>
</html>