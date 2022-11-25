<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['user_data']) || !$_SESSION['user_data']['is_admin']) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";

if (isset($_POST['category_name']) && strlen($_POST['category_name']) > 1) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        $category_name = $_POST['category_name'];
        try {
            $category_name = htmlentities($category_name, ENT_QUOTES, "UTF-8");
            if ($result = $connection->query(sprintf("INSERT INTO product_categories VALUES (NULL, '%s')",
                mysqli_real_escape_string($connection, $category_name)))) {
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
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
<div class="container">
    <?php
    if ($_SESSION['user_data']['is_admin']) {
        require_once "admin_navbar.php";
    } else {
        require_once "navbar.php";
    }
    ?>
    <b>Panel administratora - dodawanie nowej kategorii produktu</b>
    <form method="POST">
        Category name: <input type="text" name="category_name"/>
<!--        <input type="submit" value="Dodaj nową kategorię"/>-->
        <br/>
        <button type="submit" class="btn btn-primary btn-sm">Dodaj nową kategorię</button>
    </form>
    </form>
    <br/><br/>
<!--    <a href="main.php">Wróć do strony głównej</a>-->
</div>
</body>

</html>