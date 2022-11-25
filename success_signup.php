<?php
require_once "bootstrap_include.php";
require_once "aux_func.php";
session_start();
if (!isset($_SESSION['signup_success'])) {
    header('Location: index.php');
    exit();
} else {
    unset($_SESSION['signup_success']);
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Rejestracja powiodła się</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="container">
    <?php
    require_once "noauthnavbar.php";
    ?>
    <br/><br/>
    <b>Dziękujemy za rejestrację. Możesz się teraz zalogować.</b>
    <br/>
    <a href="index.php">Zaloguj się na swoje konto</a>
    <br/>
</div>
</body>
</html>