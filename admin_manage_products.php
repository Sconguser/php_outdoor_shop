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
    <title>Panel zarządzania produktami</title>
</head>
<body>
admin products  admin admin admin
<br/><br/>

<a href="add_new_category.php">Dodaj nową kategorię produktu</a>
<br />
<br />
<a href="add_new_product.php">Dodaj nowy produkt</a>
<br />
<br />
<a href="update_product.php">Edytuj produkt</a>
</body>

</html>