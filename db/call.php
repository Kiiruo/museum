<?php
require_once ('connect.php');

$name = $_POST['name'];
$tel = $_POST['tel'];

$sql = "INSERT INTO `callers` (name, phone) VALUES ('$name', '$tel')";
$conn -> query($sql)
?>

