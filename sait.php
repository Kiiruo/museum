<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/shapka.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <title>Музей Боевой Славы</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script>
         $(document).ready(function() {
            $('#call-form').ajaxForm(function() {
                alert("Спасибо! В скорем времени с вами свяжутся наши агенты.");
            });
        });
    </script>
    <script src="js/call.js" defer></script>
</head>
<body>
    <header>
        <div class="container header_container">
            <a href="#" class="logo">
                <img class="logo_img" src="img/logo.jpg" alt="Логотип">
            </a>
            <nav class="menu">
                <ul class="menu_list">
                    <li class="menu__item">
                        <p class="menu__number">+7 (904) 835-13-80</p>
                        <button id="open-modal-btn" class="menu__btn" href="">
                            Обратная связь
                        </button>
                        <div class="modal" id="call-modal">
                            <div class="modal-box">
                                <button id="close-call-modal-btn"> <img src="icons/close.png" alt=""></button>
                                <h2>Обратная связь</h2>
                                <h3>Спасибо, что выбрали нас!</h3>
                                <p>Пожалуйста, введите ваше имя и номер телефона, чтобы мы связались с вами.</p>
                                <form action="db/call.php" method="post" class="call-form" id="call-form">
                                    <input type="text" name="name" class="name" placeholder="Имя" required maxlength="11">
                                    <input type="tel" name="tel" class="tel" placeholder="Номер телефона" required minlength="11" maxlength="11">
                                    <input type="submit" name="submit" class="btn-submit">
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="hello">
            <div class="hello_text">
                <p id="p1">Музей Боевой Славы</p>
                <p id="p2">Михаила Тимофеевича Калашникова</p>
                <p>«Всё нужное просто, всё сложное не нужно»</p>
            </div>
            
            <div class="information">
                <p>Приобретите билет и погрузитесь в историю своей родины</p>
            </div>
            <div class="button-container">
                <button class="buying_a_ticket-btn"><a href="form.php">Приобрести билет</a></button>
            </div>
        </div>

        <div class="museum">
            <div class="museum_text">
                <p id="museum_text_1">Музей</p>
                <p id="museum_text_2">М.Т. Калашникова в г. Ижевске</p>
            </div>
        </div>

        <div class="wrapper_museum_1">
            <div class="the_museum">
                <img class="text_img_museum" src="img/ist.jpg" alt="">
                <div class="text_title_museum_1">О музее</div>
                <div class="text_title_museum_2">Музейно-выставочный комплекс стрелкового оружия имени М. Т. Калашникова находится по адресу: г. Ижевск, ул. Бородина, д. 19. Музейно-выставочный комплекс стрелкового оружия имени М. Т. Калашникова в Ижевске был основан 20 марта 1996 года. Он был назван в честь генерального конструктора стрелкового оружия М. Т. Калашникова. В том же году началось строительство здания музейно-выставочного комплекса по проекту института «Прикампромпроект». Автором проекта выступил архитектор П. И. Фомин в соавторстве с А. А. Караваевым. В 2004 году здание было передано Удмуртской Республике. В феврале того же года Музейно-выставочный комплекс был открыт как государственное учреждение культуры. Торжественное открытие музея состоялось 4 ноября и было приурочено к 85-летию М. Т. Калашникова. На церемонии присутствовали президент Удмуртии А. А. Волков, А. Б. Чубайс и сам М. Т. Калашников.</div>
            </div>
        </div>
        <div class="wrapper_museum_1">
            <div class="the_museum">
                <img class="text_img_museum" src="img/Kalashnikov.jpg" alt="">
                <div class="text_title_museum_1">М.Т. Калашников</div>
                <div class="text_title_museum_2">Михаил Калашников – гениальный конструктор стрелкового оружия, чьё имя знает весь мир. Обычный танкист, воевавший на фронтах Великой Отечественной, Калашников в кратчайшие сроки смог освоить тонкости оружейного дела. Его главным изобретением стал известный на весь мир АК-сорок семь. Конструктор смог создать настолько качественный, надежный, и при этом очень простой в устройстве автомат, что он навсегда изменил мир огнестрельного оружия.</div>
            </div>
        </div>

        <div class="Exhibits">
            <div class="Exhibits_text">
                <p id="Exhibits_text_1">Несколько экспонатов музея</p>
                <p id="Exhibits_text_2">М.Т. Калашникова в г. Ижевске</p>
            </div>
        </div>
        <section class="rere">
    <div class="container swiper">
        <div class="card-wrapper">
            <ul class="card-list swiper-wrapper">
                <?php
                // Подключение к базе данных
                include 'db/connect.php'; // Подключаем файл connect.php

                // Получение всех карточек
                $stmt = $conn->prepare("SELECT * FROM cards");
                $stmt->execute();
                $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($cards) {
                    foreach ($cards as $row) {
                        echo "<li class='card-item swiper-slide'>
                                <a href='#' class='card-link'>
                                    <img src='" . $row['image'] . "' alt='Card Image' class='card-image'>
                                    <p class='badge'>" . $row['badge'] . "</p>
                                    <h2 class='card-title'>" . $row['title'] . "</h2>
                                    <p class='description'>" . $row['description'] . "</p>
                                </a>
                            </li>";
                    }
                } else {
                    echo "Нет карточек для отображения.";
                }
                ?>
            </ul>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/Swiper.js"></script>
</section>

    </main>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Ссылки</h3>
                <ul>
                    <li><a href="form.php">Покупка билета</a></li>
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