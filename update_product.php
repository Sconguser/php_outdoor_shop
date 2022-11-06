<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['user_data']) || !$_SESSION['user_data']['is_admin']) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";
if (!isset($_SESSION['product_list'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        try {
            if ($result = $connection->query("SELECT * FROM items")) {
                if ($result->num_rows > 0) {
                    $int = 0;
                    while ($product = $result->fetch_assoc()) {
                        $_SESSION['product_list'][$product['id']] = $product;
                        $int++;
                    }

                } else {
                    throw new Exception('Product list is empty');
                }
            } else {
                throw new Exception('Failed to get Product list');
            }
        } catch (Exception $e) {
            echo "<p>" . $e . "</p>";
        }
    }
    $connection->close();
}
if (isset($_POST['product_id'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        $item_id = $_POST['product_id'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        try {
            if ($result = $connection->query("UPDATE items SET price=$price, quantity = $quantity WHERE id=$item_id"));
            {
                $_SESSION['e_productUpdate'] = 'Zaktualizowano produkt';
            }
        } catch (Exception $e) {
            $_SESSION['e_productUpdate'] = 'Nie udało się zaktualizować produktu';
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
    <title>Edycja produktu</title>
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
    <b>Panel administratora - edycja produktu</b>
    <?php
    if (isset($_SESSION['product_list'])) {
        echo '<form method="POST">';
        echo 'Nazwa towaru: <select name="product_id">';
        echo '<option value=-1>Brak</option>';
        foreach ($_SESSION['product_list'] as $item => $cur) {
            echo '<option value="' . $cur['id'] . '">' . $cur['name'] . '</option>';
        }
        echo '</select>';
        echo '<br/>';
        echo 'Ilość: <input type="number" name="quantity" value=0>';
//        echo '<input type="submit" value="Wyszukaj po kategorii" />';
        echo '<br/>';
        echo 'Cena: <input type="number" name="price" step="0.01" value=0.0>';
        echo '<br/>';
        echo '<button type="submit" class="btn btn-primary btn-sm">Zapisz zmiany</button>';
        echo '</form>';
        unset($_SESSION['product_list']);
    }
    if(isset($_SESSION['e_productUpdate'])){
        echo '<p>'.$_SESSION['e_productUpdate'].'</p>';
        unset($_SESSION['e_productUpdate']);
    }
    ?>

    <br/><br/>
    <!--    <a href="main.php">Wróć do strony głównej</a>-->
</div>
</body>

</html>