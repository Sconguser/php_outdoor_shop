<?php
session_start();
if (!isset($_SESSION['success_change_address'])){
    header('Location: index.php');
    exit();
}
else{
    unset($_SESSION['success_change_address']);
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Zmiana adresu się powiodła</title>
</head>
<body> <br/><br/>
    Zmiana adresu przebiegła pomyślnie.
    <a href="index.php">Wróć na stronę główną</a>
    <br/>
</form>
</body>
</html>