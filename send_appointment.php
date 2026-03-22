<?php
include "db.php";
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $master = mysqli_real_escape_string($conn, $_POST['master']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';
    
    $sql = "INSERT INTO appointments (user_id, name, phone, master, message, status) 
            VALUES ($user_id, '$name', '$phone', '$master', '$message', 'new')";
    
    if(mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Заявка отправлена!";
    } else {
        $_SESSION['error_message'] = "Ошибка: " . mysqli_error($conn);
    }
}

header("Location: make_an_appointment.php");
exit();
?>