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
</head>
<body>
<div class="container">
    Zaloguj się:<br/><br/>

    <form action="login.php" method="post">
        Login:<br/> <input type="text" name="login"/> <br/>
        Hasło:<br/> <input type="password" name="password"/> <br/>
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

</div>
</body>
</html>