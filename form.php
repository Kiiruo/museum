<?php

include 'db/connect.php'; // Подключаем файл для работы с базой данных

// Обработка данных формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $excursion_id = $_POST['excursion'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];

    // Получение цены экскурсии для расчета общей стоимости
    $stmt = $conn->prepare("SELECT price FROM excursions WHERE id = :excursion_id");
    $stmt->execute(['excursion_id' => $excursion_id]);
    $price = $stmt->fetchColumn();
    $total_price = $price * $quantity;

    // Вставка данных в таблицу orders
    $insert_stmt = $conn->prepare("INSERT INTO orders (name, phone, email, excursion_id, quantity, date, total_price) VALUES (:name, :phone, :email, :excursion_id, :quantity, :date, :total_price)");
    $insert_stmt->execute([
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'excursion_id' => $excursion_id,
        'quantity' => $quantity,
        'date' => $date,
        'total_price' => $total_price
    ]);

    // Сообщение о успешной отправке данных (всплывающее окно)
    echo '<div id="success-modal" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: rgba(100, 0, 0, 0.8); color: #fff; padding: 20px; border-radius: 5px; z-index: 1000; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.3);  animation: fadeIn 0.3s ease-in-out;">
            <div style="font-size: 1.2em; margin-bottom: 10px;">Ваш заказ успешно оформлен!</div>
            <div style="font-size: 0.9em; opacity: 0.8;">Удачной экскурсии.</div>
          </div>
          <script>
            window.onload = function() {
              var modal = document.getElementById("success-modal");
              modal.style.display = "block";
              setTimeout(function() {
                  modal.style.display = "none";
              }, 3000);
            };
          </script>
          <style>
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                    to { opacity: 1; transform: translateX(-50%); }
                }
              </style>';
}

// Получение экскурсий из базы данных
$excursions = $conn->query("SELECT id, name, price FROM excursions")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Покупка билетов</title>
    <link rel="stylesheet" href="css/ff.css">
    <script src="script.js" defer></script>
</head>
<body>
    <h1>Покупка билетов в музей Боевой Славы</h1>
    <form id="ticketForm" method="POST">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Номер телефона:</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="email">Gmail:</label>
        <input type="email" id="email" name="email" required>

        <label for="excursion">Экскурсия:</label>
        <select id="excursion" name="excursion" required>
            <option value="" disabled selected>Выберите экскурсию</option>
            <?php foreach ($excursions as $excursion): ?>
                <option value="<?php echo $excursion['id']; ?>" data-price="<?php echo $excursion['price']; ?>">
                    <?php echo $excursion['name']; ?> - <?php echo $excursion['price']; ?> руб.
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantity">Количество человек:</label>
        <input type="number" id="quantity" name="quantity" min="1" max="25" value="1" required>

        <label for="date">Дата:</label>
        <input type="date" id="date" name="date" required>

        <h2>Итого: <span id="totalPrice">0</span>₽</h2>
        <button type="submit">Купить билеты</button>
    </form>

    <script>
        // Ваш JavaScript код здесь (как было указано ранее)
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const excursionSelect = document.getElementById('excursion');
            const totalPriceElement = document.getElementById('totalPrice');

            function calculateTotal() {
                const selectedOption = excursionSelect.options[excursionSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price'));
                const quantity = parseInt(quantityInput.value);
                const total = price * quantity;

                totalPriceElement.textContent = total;
            }

            quantityInput.addEventListener('input', calculateTotal);
            excursionSelect.addEventListener('change', calculateTotal);
            
            // Инициализация значения при загрузке страницы
            calculateTotal();
        });
    </script>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Ссылки</h3>
                <ul>
                    <li><a href="sait.php">Главная</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Контакты</h3>
                <p>Телефон: +7 (904) 835-13-80</p>
                <p>Email: <a href="mailto:info@touragency.com">Sascha@mail.ru</a></p>
            </div>
            <div class="footer-section">
                <h3>Социальные сети</h3>
                <a href="#"><img src="icons/telegram.png" alt="Telegram"></a>
                <a href="#"><img src="icons/vk.png" alt="VK"></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Музей Боевой Славы. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>
