<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['user_data']) || !isset($_SESSION['user_data']['basket'])) {
    $_SESSION['e_mainRedirect'] = "Coś poszło nie tak";
    header('Location: index.php');
    exit();
}
require_once "connect.php";


?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Podsumowanie</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="icon" type="image/ico" href="images/favicon.ico">
</head>
<body>
<div class="container">
    <?php
    if ($_SESSION['user_data']['is_admin']) {
        require_once "admin_navbar.php";
    } else {
        require_once "navbar.php";
    }
    ?>
    <br/>
    <div style="background-color: #f4f6f2">
    <b>Podsumowanie zamówienia:</b>
    <br/>
    <?php
    $_SESSION['e_summaryRedirect'] = '';
    echo '<b>Cena: </b>' . $_SESSION['user_data']['basket']['total_price'] . 'zł';
    echo '<form action="order_made.php" method="post" name="order">';
//    echo '<a href="edit_user_address.php"> Edytuj dane adresowe</a>';
    //    echo '<input type="submit" value="Zapłać i złóż zamówienie"/>';
    echo '<a class="btn btn-primary btn-sm" href="edit_user_address.php" role="button">Edytuj dane adresowe</a>';
    echo '<br/>';
    echo '<br/>';
    echo '<button type="submit" class="btn btn-primary btn-sm">Zapłać i złóż zamówienie</button>';
    echo '</form>';
    echo '<br/>';
    if (isset($_SESSION['e_orderMade'])) {
        echo $_SESSION['e_orderMade'];
        unset($_SESSION['e_orderMade']);
    }
    ?>
    </div>
    <br/><br/>
<!--    <a href="main.php">Wróć do strony głównej</a>-->
</div>
</body>


</html>
