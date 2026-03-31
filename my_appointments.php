<?php
include "db.php";
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if($_SESSION['user_role'] == 'client') {
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT * FROM appointments WHERE user_id = $user_id ORDER BY created_at DESC");
} else {
    header("Location: admin_appointments.php");
    exit();
}

include "layouts/header.php";
?>

<div class="container">
    <h1 class="my-4">Мои записи</h1>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Мастер</th>
                        <th>Описание</th>
                        <th>Статус</th>
                        <th>Сообщение от администратора</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo date('d.m.Y H:i', strtotime($row['created_at'])); ?></td>
                            <td><?php echo $row['master'] ?: 'Не указан'; ?></td>
                            <td><?php echo $row['message'] ?: 'Без описания'; ?></td>
                            <td>
                                <?php
                                $status_text = '';
                                $status_class = '';
                                if($row['status'] == 'new') {
                                    $status_text = 'Новая';
                                    $status_class = 'warning';
                                } elseif($row['status'] == 'confirmed') {
                                    $status_text = 'Подтверждена';
                                    $status_class = 'success';
                                } else {
                                    $status_text = 'Выполнена';
                                    $status_class = 'secondary';
                                }
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <?php if(!empty($row['admin_message'])): ?>
                                    <div class="alert alert-info mb-0" style="padding: 10px;">
                                        <i class="bi bi-envelope"></i> 
                                        <strong>Сообщение от администратора:</strong><br>
                                        <?php echo nl2br(htmlspecialchars($row['admin_message'])); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">Нет сообщений</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            У вас пока нет записей. <a href="make_an_appointment.php">Записаться на прием</a>
        </div>
    <?php endif; ?>
</div>

<?php include "layouts/footer.php"; ?>