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
    <title>Panel administratora</title>
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
    admin admin admin admin <br/><br/>

    <a href="admin_manage_users.php">Zarządzaj użytkownikami</a>
    <br/>
    <br/>
    <a href="admin_manage_products.php">Zarządzaj produktami</a>
    <br/>
    <br/>
    <a href="main.php">Wróć do strony głównej</a>
</div>
</body>

</html>