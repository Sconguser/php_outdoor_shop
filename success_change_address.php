<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['success_change_address'])) {
    header('Location: index.php');
    exit();
} else {
    unset($_SESSION['success_change_address']);
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Zmiana adresu się powiodła</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
<div class="container">
<br/>
    <div style="background-color: #f4f6f2">
        <br/>
        <b>Zmiana adresu przebiegła pomyślnie.</b>
        <br/>
        <a href="edit_user_address.php">Wróć</a>
        <br/>
        </form>
    </div>
</div>
</body>
</html>