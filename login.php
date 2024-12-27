<?php
session_start(); // Запуск сессии

// Проверка, если уже вошли в систему
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin.php"); // Если уже вошли, перенаправляем на admin.php
    exit;
}

$message = ""; // Сообщение о статусе входа

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Предположим, что логин "admin" и пароль "password"
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка логина и пароля
    if ($username === 'admin' && $password === '12345678') { // Замените на безопасную проверку
        $_SESSION['loggedin'] = true;  // Установка переменной сессии
        header("Location: admin.php"); // Перенаправление на админ панель
        exit;
    } else {
        $message = "Неверный логин или пароль!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма входа администратора</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
        }
        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
            margin-left: auto; 
            margin-right: auto;
        }
        input[type="submit"] {
            width: 90%;
            padding: 10px;
            background: rgb(85, 0, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin-left: auto; 
            margin-right: auto;
            transition: background 0.5s;
        }
        input[type="submit"]:hover {
            background: rgb(185, 1, 1);
        }
        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Вход администратора</h2>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="submit" value="Войти">
        </form>
    </div>
</body>
</html>
