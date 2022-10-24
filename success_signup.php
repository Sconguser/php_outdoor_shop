<?php
session_start();
if (!isset($_SESSION['signup_success'])){
    header('Location: index.php');
    exit();
}
else{
    unset($_SESSION['signup_success']);
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Rejestracja powiodła się gówno gówno gówno gówno</title>
</head>
<body> <br/><br/>
    Dziękujemy za rejestrację. Możesz się teraz zalogować.
    <a href="index.php">Zaloguj się na swoje konto</a>
    <br/>
</form>
</body>
</html>