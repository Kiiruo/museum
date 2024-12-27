<?php
include 'connect.php';
$cardId = $_POST['card_id'];
$badge = $_POST['badge'];
$title = $_POST['title'];
$description = $_POST['description'];
$image = $_POST['image'];

if (empty($cardId)) {
    // Создание новой карточки
    $sql = "INSERT INTO cards (badge, title, description, image) VALUES ('$badge', '$title', '$description', '$image')";
} else {
    // Обновление существующей карточки
    $sql = "UPDATE cards SET badge='$badge', title='$title', description='$description', image='$image' WHERE id='$cardId'";
}

$conn->query($sql);
?>