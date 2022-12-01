<?php
require_once "bootstrap_include.php";
session_start();
if (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'])) {
    header('Location: main.php');
    exit();
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Logowanie</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body class="index">
<div class="container">
    <?php
    require_once "noauthnavbar.php";
    ?>
    <br/>
    <div style="background-color: #f4f6f2">
        <b>Zaloguj się</b>
        <br/>
        <form action="login.php" method="post">
            <b>Login</b><br/> <input type="text" name="login"/> <br/>
            <b>Hasło</b><br/> <input type="password" name="password"/> <br/>
            <button type="submit" class="btn btn-primary btn-sm">Zaloguj się</button>
            <br/>
            <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>
            <a href="signup.php">Zarejestruj sie</a>
            <br/>
        </form>
        <br/>
    </div>
    <br/>
</div>
</body>
</html>