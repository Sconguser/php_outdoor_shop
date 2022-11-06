<?php
session_start();
require_once "bootstrap_include.php";
if (!isset($_SESSION['user_data']) || !$_SESSION['user_data']['is_admin']) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";

if (isset($_POST['name'])) {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $quantity = floor($_POST['quantity']);
    $price = $_POST['price'];
    try {
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0 && $debug == 1) {
            echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
        } else {
            if ($result = $connection->query("INSERT INTO items VALUES (NULL, $quantity, $category, '$name', $price)")) {
                unset($_POST['name']);
                unset($_POST['category']);
                unset($_POST['quantity']);
                unset($_POST['price']);

            } else {
                throw new Exception('Failed to create new item');
            }
        }
        $connection->close();
    } catch (Exception $e) {
        echo '<p> error' . $e . '</p>';
    }
}

if (!isset($_SESSION['category_list'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        try {
            if ($result = $connection->query("SELECT * FROM product_categories")) {
                if ($result->num_rows > 0) {
                    $int = 0;
                    while ($category = $result->fetch_assoc()) {
                        $_SESSION['category_list'][$category['id']] = $category;
                        $int++;
                    }

                } else {
                    throw new Exception('Category list is empty');
                }
            } else {
                throw new Exception('Failed to get Category list');
            }
        } catch (Exception $e) {
            echo "<p>" . $e . "</p>";
        }
    }
}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Dodawanie produktu</title>
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
    <b>Panel administratora - dodawanie nowego produktu</b>
    <br/> <br/>
    Kategoria:
    <?php
    if (isset($_SESSION['category_list'])) {
        echo '<form method="POST">';
        echo '<select name="category">';
        echo ' <option value="select"> Wybierz z listy </option>';
        foreach ($_SESSION['category_list'] as $item => $cur) {
            echo '<option value="' . $cur['id'] . '">' . $cur['category_name'] . '</option>';
        }
        echo '</select>';
        echo '<br />';
        echo 'Nazwa: <br /><input type="text" name="name"/>';
        echo '<br/>';
        echo 'Ilość: <br /><input type="number" name="quantity"/>';
        echo '<br />';
        echo 'Cena: <br /><input type="number" step="0.01" name="price"/>';
        echo '<br />';
        echo '<button type="submit" class="btn btn-primary btn-sm">Dodaj nowy produkt</button>';
        echo '</form>';
        unset($_SESSION['category_list']);
    }
    ?>
</div>
</body>
</html>