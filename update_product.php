<?php
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
    <title>Edycja produktu</title>
</head>
<body>
Edit product edit product
<br/><br/>
<a href="main.php">Wróć do strony głównej</a>
</body>

</html>