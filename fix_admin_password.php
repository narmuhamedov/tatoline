<?php
include "db.php";

$phone = '+996555350234'; // Телефон админа
$new_password = '123'; // Пароль

// Сначала удалим старого админа
mysqli_query($conn, "DELETE FROM users WHERE phone='$phone'");

// Создадим нового с правильным хешем
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, phone, password, role) 
        VALUES ('Admin', '$phone', '$hashed_password', 'admin')";

if(mysqli_query($conn, $sql)) {
    echo "✅ Админ успешно создан!<br>";
    echo "Телефон: $phone<br>";
    echo "Пароль: $new_password<br>";
    echo "Хеш пароля: $hashed_password<br>";
    
    // Проверим, работает ли
    $check = mysqli_query($conn, "SELECT * FROM users WHERE phone='$phone'");
    $user = mysqli_fetch_assoc($check);
    
    if(password_verify($new_password, $user['password'])) {
        echo "<br>✅ ПРОВЕРКА: Пароль '$new_password' подходит!<br>";
        echo "Можете входить с этими данными.<br>";
        echo "<a href='login.php'>Перейти к входу</a>";
    } else {
        echo "<br>❌ Что-то пошло не так с хешированием";
    }
} else {
    echo "❌ Ошибка: " . mysqli_error($conn);
}

// Покажем всех пользователей
echo "<h3>Все пользователи в базе:</h3>";
$users = mysqli_query($conn, "SELECT id, username, phone, role, password FROM users");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Имя</th><th>Телефон</th><th>Роль</th><th>Пароль (первые 50 символов)</th></tr>";
while($row = mysqli_fetch_assoc($users)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['phone'] . "</td>";
    echo "<td>" . $row['role'] . "</td>";
    echo "<td>" . substr($row['password'], 0, 50) . "...</td>";
    echo "</tr>";
}
echo "</table>";
?>