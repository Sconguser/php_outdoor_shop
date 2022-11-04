<?php
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
</head>
<body>
summary <br/><br/>
<?php
    $_SESSION['e_summaryRedirect'] = '';
    echo '<a href="edit_user_address.php"> Edytuj dane adresowe</a>';
    echo '<br />';
    echo 'Cena: '.$_SESSION['user_data']['basket']['total_price'].'zł';
    echo '<form action="order_made.php" method="post" name="order">';
    echo  '<input type="submit" value="Zapłać i złóż zamówienie"/>';
    echo '</form>';
    echo '<br/>';
    if(isset($_SESSION['e_orderMade'])){
        echo $_SESSION['e_orderMade'];
        unset($_SESSION['e_orderMade']);
    }
?>
<br/><br/>
<a href="main.php">Wróć do strony głównej</a>
</body>


</html>
