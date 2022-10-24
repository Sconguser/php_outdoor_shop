<?php
    session_start();
    if(!isset($_SESSION['logged_in'])){
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

</form>
    <?php
    echo "<p>Hej ".$_SESSION['user_data']['name']."!";
    echo "<br />";
    echo '<a href="logout.php">Wyloguj się</a>';
    echo "<br />";
    echo '<a href="edit_user_info.php">Zmień dane osobowe</a>';
    echo "<br />";
    ?>
</body>

</html>