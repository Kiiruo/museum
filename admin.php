<?php
// Начинаем буферизацию вывода для предотвращения ошибок заголовка
ob_start();

include 'db/connect.php';

// Удаление заказа
if (isset($_GET['delete'])) {
    $idToDelete = intval($_GET['delete']);
    $delete_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_stmt->execute([$idToDelete]);
    header("Location: " . $_SERVER['PHP_SELF']); // Перенаправление на текущую страницу
    exit();
}

// Получение всех заказов
$orders_stmt = $conn->prepare("SELECT * FROM orders");
$orders_stmt->execute();
$orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);

// Добавление карточки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $badge = $_POST['badge'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Обработка загрузки файла
    $targetDir = "img/"; // Папка, куда будут загружены изображения
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Проверка формата файла
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Извините, только JPG, JPEG, PNG и GIF файлы разрешены.";
        $uploadOk = 0;
    }

    // Проверка размера
    if ($_FILES["image"]["size"] > 500000) { // Примерный предел в 500 кб
        echo "Извините, ваш файл слишком большой.";
        $uploadOk = 0;
    }

    // Проверка $uploadOk перед загрузкой
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO cards (badge, title, description, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$badge, $title, $description, $targetFile]);
            header("Location: " . $_SERVER['PHP_SELF']); // Перенаправление после добавления
            exit();
        } else {
            echo "Извините, произошла ошибка при загрузке вашего файла.";
        }
    }
}

// Изменение карточки
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $badge = $_POST['badge'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Обновление карточки без изменения изображения
    $query = "UPDATE cards SET badge = ?, title = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$badge, $title, $description, $id]);

    // Если новая картинка была загружена
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "img/"; // Путь для хранения изображений
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Проверка формата файла
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Извините, только JPG, JPEG, PNG и GIF файлы разрешены.";
            $uploadOk = 0;
        }

        // Проверка размера
        if ($_FILES["image"]["size"] > 500000) {
            echo "Извините, ваш файл слишком большой.";
            $uploadOk = 0;
        }

        // Проверка $uploadOk перед загрузкой
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Обновить запись с новой картинкой
                $query = "UPDATE cards SET image = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$targetFile, $id]);
            } else {
                echo "Извините, произошла ошибка при загрузке вашего файла.";
            }
        }
    }
    header("Location: admin.php");
    exit();
}

// Удаление карточки
if (isset($_GET['delete_card'])) {
    $id = $_GET['delete_card'];
    $stmt = $conn->prepare("DELETE FROM cards WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit();
}

// Получение всех карточек
$stmt = $conn->prepare("SELECT * FROM cards");
$stmt->execute();
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение запросов на обратный звонок
$callers_result = $conn->query("SELECT * FROM callers");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ панель</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #2a2a2a;
        }
        .container {
            display: flex;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .left-panel {
            flex: 2;
            padding-right: 20px;
        }
        .right-panel {
            flex: 1;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }
        input[type="text"],
        textarea {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="file"] {
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #8f0000;
            border: none;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: rgb(185, 1, 1);
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #f1f1f1;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        a {
            text-decoration: none;
            color: #333;
            margin-left: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
        /* Модальное окно */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Дополнительные стили для ссылки удаления */
        td a {
            color: rgb(85, 0, 0);
        }
        td a:hover {
            text-decoration: none;
            color: rgb(185, 1, 1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left-panel">
        <h1>Управление карточками</h1>
        <h2>Добавить новую карточку</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="badge" placeholder="Бейдж" required>
            <input type="text" name="title" placeholder="Заголовок" required>
            <textarea name="description" placeholder="Описание" required></textarea>
            <input type="file" name="image" required>
            <input type="submit" value="Добавить">
        </form>

        <h2>Существующие карточки</h2>
        <ul>
            <?php foreach ($cards as $card): ?>
                <li>
                    <strong><?php echo $card['badge']; ?></strong> - <?php echo $card['title']; ?>
                    <img src="<?php echo $card['image']; ?>" alt="<?php echo $card['title']; ?>" style="width: 50px; height: auto; margin-left: 10px;">
                    <div>
                        <a class="btn-icon" href="#" onclick="openModal(<?php echo $card['id']; ?>, '<?php echo $card['badge']; ?>', '<?php echo htmlspecialchars($card['title']); ?>', '<?php echo htmlspecialchars($card['description']); ?>', '<?php echo $card['image']; ?>');"><i class="fas fa-edit"></i> Изменить</a>
                        <a class="btn-icon" href="?delete_card=<?php echo $card['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить эту карточку?');"><i class="fas fa-trash-alt"></i> Удалить</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="right-panel">
        <h3>Обратные звонки</h3>
        <ul class="list-group" id="call">
            <?php
            // Удаление запроса на обратный звонок
            if (isset($_GET['delete_caller'])) {
                $idToDelete = intval($_GET['delete_caller']);
                $delete_stmt = $conn->prepare("DELETE FROM callers WHERE id = ?");
                $delete_stmt->execute([$idToDelete]);
                header("Location: " . $_SERVER['PHP_SELF']); 
                exit();
            }

            // Проверка наличия запросов на обратный звонок
            if ($callers_result->rowCount() > 0): ?>
                <?php while ($caller = $callers_result->fetch(PDO::FETCH_ASSOC)): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($caller['name']); ?></strong><br>
                        Телефон: <?php echo htmlspecialchars($caller['phone']); ?>
                        <a href="?delete_caller=<?php echo $caller['id']; ?>" class="delete-button" onclick="return confirm('Вы уверены, что хотите удалить этот запрос?');">Удалить</a>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item">Нет запросов на обратный звонок.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Модальное окно -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Изменить карточку</h2>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="cardId">
            <input type="text" name="badge" id="badge" placeholder="Бейдж" required>
            <input type="text" name="title" id="title" placeholder="Заголовок" required>
            <textarea name="description" id="description" placeholder="Описание" required></textarea>
            <input type="file" name="image" id="image"> <!-- Поле для загрузки нового изображения -->
            <input type="submit" name="update" value="Обновить">
        </form>
    </div>
</div>

<div class="container" style="margin-top: 30px;">
    <h2>Заказы</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Экскурсия ID</th>
                <th>Количество</th>
                <th>Дата</th>
                <th>Общая цена</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                    <td><?php echo htmlspecialchars($order['excursion_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($order['date']); ?></td>
                    <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $order['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?');">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function openModal(id, badge, title, description, image) {
        document.getElementById('cardId').value = id;
        document.getElementById('badge').value = badge;
        document.getElementById('title').value = title;
        document.getElementById('description').value = description;
        document.getElementById('image').value = ''; // Очищаем поле для загрузки
        document.getElementById("editModal").style.display = "block";
    }
    
    function closeModal() {
        document.getElementById("editModal").style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById("editModal");
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

</body>
</html>
