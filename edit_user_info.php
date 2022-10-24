<?php
session_start();
if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_data'])){
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Strona do edycji danych osobowych</title>
</head>
<body>

<?php
    echo '<a href="main.php">Wróć na stronę główną</a>';
    echo '<br />';
    echo '<a href="edit_user_address.php">Zmień dane adresowe</a>';
?>
<form method="POST">
    Name: <input type="text" value="<?php
    echo $_SESSION['user_data']['name'];
?>" name="name" />
    <br />
    <br />
    Lastname: <input type="text" value="<?php
    echo $_SESSION['user_data']['lastname'];
    ?>" name="lastname" />
</form>
</body>