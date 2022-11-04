<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Strona główna</title>
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
<!--    --><?php
    echo "<p>Hej " . $_SESSION['user_data']['name'] . "!";
    echo "<br />";
//    echo '<a href="logout.php">Wyloguj się</a>';
//    echo "<br />";
//    echo '<a href="edit_user_info.php">Zmień dane osobowe</a>';
//    echo "<br />";
//    echo '<a href="shop.php">Przejdź do sklepu</a>';
//    echo "<br />";
//    echo '<a href="basket.php"> Przejdź do koszyka</a>';
//    echo "<br />";
//    echo '<a href="user_orders.php">Zobacz swoje zamówienia</a>';
//    echo "<br />";
//    if ($_SESSION['user_data']['is_admin']) {
//        echo '<a href="panel_admin.php">Panel administratora</a>';
//        echo "<br />";
//    }
    if (isset($_SESSION['e_mainRedirect'])) {
        echo '<p>' . $_SESSION['e_mainRedirect'] . '</p>';
        unset($_SESSION['e_mainRedirect']);
    }
    if (isset($_SESSION['e_summaryRedirect'])) {
        unset($_SESSION['e_summaryRedirect']);
    }
    if (isset($_SESSION['e_userOrders'])) {
        echo '<p>' . $_SESSION['e_userOrders'] . '</p>';
        unset($_SESSION['e_userOrders']);
    }
    ?>
</div>
</body>

</html>