<?php
session_start();
if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_data'])){
    header('Location: index.php');
    exit();
}
require_once "connect.php";
if(isset($_POST['name'])) {
    try {
        mysqli_report(MYSQLI_REPORT_STRICT);
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $id = $_SESSION['user_data']['id'];
            $name = htmlentities($_POST['name'], ENT_QUOTES, "UTF-8");
            $lastname = htmlentities($_POST['lastname'], ENT_QUOTES, "UTF-8");
            $result = $connection->query("UPDATE users
                SET name='$name', lastname='$lastname'
                 WHERE id='$id'");
            $_SESSION['user_data']['name'] = $name;
            $_SESSION['user_data']['lastname'] = $lastname;
            $_SESSION['namechange_success'] = "Dane użytkownika poprawnie zaktualizowane";
            $connection->close();
        }
    } catch (Exception $e) {
        if ($debug) {
            echo '<p>' . $e . '</p>';
        }
    }
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
    Lastname: <input type="text" value="<?php
    echo $_SESSION['user_data']['lastname'];
    ?>" name="lastname" />
    <br />
    <input type="submit" value="Zapisz dane"/>
</form>
<?php
    if(isset($_SESSION['namechange_success'])){
        echo $_SESSION['namechange_success'];
        unset($_SESSION['namechange_success']);
    }
?>
</body>