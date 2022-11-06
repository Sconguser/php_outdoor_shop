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
    if ($_SESSION['user_data']['is_admin']) {
        require_once "admin_navbar.php";
    } else {
        require_once "navbar.php";
    }
    //        header("Location:shop.php");
    ?>
    <?php
    echo "<p>Hej " . $_SESSION['user_data']['name'] . "!";
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
    <div class="container">
        <p>Witamy na stronie Sklepu Turystycznego.</p>
        <p>Znajdziesz tutaj sprzęt turystyczny i tylko to.</p>
    </div>
</div>
</body>

</html>