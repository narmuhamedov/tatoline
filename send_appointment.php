<?php
include "db.php";
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $master = mysqli_real_escape_string($conn, $_POST['master']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    
    // Проверка на пустые поля
    if(empty($name) || empty($phone) || empty($appointment_date) || empty($appointment_time)) {
        $_SESSION['error_message'] = "Заполните все обязательные поля!";
        header("Location: make_an_appointment.php");
        exit();
    }
    
    // Проверка, что время выбрано корректно
    if($appointment_time == 'Выберите время') {
        $_SESSION['error_message'] = "Пожалуйста, выберите время из списка!";
        header("Location: make_an_appointment.php");
        exit();
    }
    
    // Объединяем дату и время
    $appointment_datetime = $appointment_date . ' ' . $appointment_time . ':00';
    
    // Проверяем, не занято ли это время
    $check = mysqli_query($conn, "SELECT id FROM appointments WHERE appointment_date = '$appointment_datetime' AND status != 'completed'");
    
    if(mysqli_num_rows($check) > 0) {
        $_SESSION['error_message'] = "Извините, это время уже занято. Выберите другое время.";
        header("Location: make_an_appointment.php");
        exit();
    }
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';
    
    $sql = "INSERT INTO appointments (user_id, name, phone, master, message, appointment_date, status) 
            VALUES ($user_id, '$name', '$phone', '$master', '$message', '$appointment_datetime', 'new')";
    
    if(mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Заявка отправлена! Мы ждем вас " . date('d.m.Y H:i', strtotime($appointment_datetime));
    } else {
        $_SESSION['error_message'] = "Ошибка: " . mysqli_error($conn);
    }
}

header("Location: make_an_appointment.php");
exit();
?>