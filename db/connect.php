<?php
$host = 'WP'; // хост
$db = 'GG';      // имя базы данных
$user = 'root';  // имя пользователя
$pass = '';  // пароль

try {
    // Установка соединения с базой данных
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}

?>