<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";

/// get category list
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
    $connection->close();

}
/// get product list
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
if (isset($_POST['chosen_category']) && $_POST['chosen_category']!=-1) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        try {
            $category_id = $_POST['chosen_category'];
            if ($result = $connection->query("SELECT * FROM items WHERE category_id = $category_id")) {
                unset($_SESSION['product_list']);
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


?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Sklep</title>
</head>
<body>
Kategoria:
<?php
if (isset($_SESSION['category_list'])) {
    echo '<form method="POST">';
    echo '<select name="chosen_category">';
    echo ' <option value=-1>Brak</option>';
    foreach ($_SESSION['category_list'] as $item => $cur) {
        echo '<option value="' . $cur['id'] . '">' . $cur['category_name'] . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" value="Wyszukaj po kategorii" />';
    echo '</form>';
    unset($_SESSION['category_list']);
}
if (isset($_SESSION['product_list'])) {
    foreach ($_SESSION['product_list'] as $item => $cur) {
        echo '<form method="POST" action="add_to_basket.php">';
        echo 'Id: ' . $cur['id'] . ' Nazwa: ' . $cur['name'] . ' Cena: ' . $cur['price'] . ' Ilość: ' . $cur['quantity'] . ' ';
        echo '<input type="hidden" name="item_id" value="' . $cur['id'] . '"/>';
        echo '</br>';
        echo 'Ilość:<input type="number" name="quantity"/>';
        echo '</br>';
        echo '<input type="submit" value="Dodaj do koszyka" />';
        echo '</form>';
    }
    unset($_SESSION['product_list']);
}
?>
<br/><br/>
<?php
if(isset($_SESSION['e_addToBasket'])){
    echo '<p>'.$_SESSION['e_addToBasket'].'</p>';
    unset($_SESSION['e_addToBasket']);
}
?>
<br/><br/>
<a href="main.php">Wróć do strony głównej</a>
</body>

</html>