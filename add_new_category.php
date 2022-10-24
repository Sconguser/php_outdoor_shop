<?php
session_start();
if (!isset($_SESSION['user_data']) || !$_SESSION['user_data']['is_admin']) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";
if(isset($_POST['category_name'])){
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        $category_name = $_POST['category_name'];
        try {
            if ($result = $connection->query("INSERT INTO product_categories VALUES (NULL, '$category_name')")) {
                unset($_POST['admin_privileges']);
            } else {
                throw new Exception('Failed to create new category');
            }
        } catch (Exception $e) {
            echo "<p>" . $e . "</p>";
        }
    }
    $connection->close();
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Dodawanie kategori</title>
</head>
<body>
Add category add category
<form method="POST">
    Category name: <input type="text" name="category_name"/>
    <input type="submit" value="Dodaj nową kategorię"/>
</form>
</form>
<br/><br/>
</body>

</html>