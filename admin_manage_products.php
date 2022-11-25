<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['user_data']) || !$_SESSION['user_data']['is_admin']) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Panel zarządzania produktami</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="container">
    <?php
    if($_SESSION['user_data']['is_admin']){
        require_once "admin_navbar.php";
    }else {
        require_once "navbar.php";
    }
    ?>

    <b>Panel administratora - zarządzanie produktami</b>
    <br/><br/>

    <a href="add_new_category.php">Dodaj nową kategorię produktu</a>
    <br/>
    <br/>
    <a href="add_new_product.php">Dodaj nowy produkt</a>
    <br/>
    <br/>
    <a href="update_product.php">Edytuj produkt</a>
</div>
</body>
</html>