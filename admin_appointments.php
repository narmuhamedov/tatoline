<?php
include "db.php";
session_start();

// Проверяем, авторизован ли пользователь
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Проверяем, является ли пользователь админом
if($_SESSION['user_role'] != 'admin') {
    // Если не админ, перенаправляем на главную
    header("Location: index.php");
    exit();
}

// Обработка изменения статуса
if(isset($_GET['change_status'])) {
    $appointment_id = (int)$_GET['change_status'];
    $new_status = mysqli_real_escape_string($conn, $_GET['status']);
    
    mysqli_query($conn, "UPDATE appointments SET status='$new_status' WHERE id=$appointment_id");
    header("Location: admin_appointments.php");
    exit();
}

// Получаем все записи
$result = mysqli_query($conn, "SELECT a.*, u.username as user_name 
                               FROM appointments a 
                               LEFT JOIN users u ON a.user_id = u.id 
                               ORDER BY a.created_at DESC");
?>

<?php include "layouts/header.php"; ?>

<div class="container">
    <h1 class="my-4">Управление записями</h1>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>Клиент</th>
                    <th>Телефон</th>
                    <th>Мастер</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result && mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($row['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo $row['master'] ? htmlspecialchars($row['master']) : 'Не указан'; ?></td>
                            <td><?php echo $row['message'] ? htmlspecialchars($row['message']) : 'Без описания'; ?></td>
                            <td>
                                <?php 
                                $status_class = '';
                                $status_text = '';
                                if($row['status'] == 'new') {
                                    $status_class = 'badge bg-warning';
                                    $status_text = 'Новая';
                                } elseif($row['status'] == 'confirmed') {
                                    $status_class = 'badge bg-success';
                                    $status_text = 'Подтверждена';
                                } elseif($row['status'] == 'completed') {
                                    $status_class = 'badge bg-secondary';
                                    $status_text = 'Выполнена';
                                }
                                ?>
                                <span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="?change_status=<?php echo $row['id']; ?>&status=confirmed" class="btn btn-sm btn-success">Подтвердить</a>
                                    <a href="?change_status=<?php echo $row['id']; ?>&status=completed" class="btn btn-sm btn-secondary">Завершить</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Пока нет записей</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "layouts/footer.php"; ?>