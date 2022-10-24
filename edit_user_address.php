<?php
session_start();
if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_data'])){
    header('Location: index.php');
    exit();
}
require_once "connect.php";
try{
    $alreadyHasAddress = false;
    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    }else{
        $id = $_SESSION['user_data']['id'];
        $result = $connection->query("SELECT * FROM addressed WHERE user_id='$id'");
        if($result->num_rows>0){
            $alreadyHasAddress = true;
            $_SESSION['address'] = $result->fetch_assoc();
            $result->close();
        }
    }
}catch(Exception $e){
    if($debug){
        echo '<p>'.$e.'</p>';
    }
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Strona do edycji danych adresowych</title>
</head>
<body>

<?php
echo '<a href="main.php">Wróć na stronę główną</a>';
echo '<br />';
echo '<a href="edit_user_info.php">Wróć</a>';
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