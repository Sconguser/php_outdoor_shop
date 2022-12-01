<?php
require_once "bootstrap_include.php";
require_once "db_converters.php";
require_once "aux_func.php";
session_start();
if (!isset($_SESSION['user_data']) || !isset($_SESSION['user_data']['basket'])) {
    $_SESSION['e_mainRedirect'] = "Coś poszło nie tak";
    header('Location: index.php');
    exit();
}
require_once "connect.php";
$connection = @new mysqli($host, $db_user, $db_password, $db_name);
/**
 * @param mysqli $connection
 * @param int $debug
 * @return void
 */
function getBasketItems(mysqli $connection, int $debug): void
{
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        try {
            if(isset($_SESSION['basket_item_list'])){
                unset($_SESSION['basket_item_list']);
            }
            $basket_id = $_SESSION['user_data']['basket']['id'];
            if ($result = $connection->query("SELECT * FROM basket_items WHERE basket_id = $basket_id")) {
                if ($result->num_rows > 0) {
                    $int = 0;
                    while ($basket_item = $result->fetch_assoc()) {
                        $_SESSION['basket_item_list'][$basket_item['id']] = $basket_item;
                        $int++;
                    }
                } else {
                    /// do nothing, it's ok
                }
            } else {
                throw new Exception('Failed to get basket item list');
            }
        } catch (Exception $e) {
            echo "<p>" . $e . "</p>";
        }
    }
}

if (isset($_POST['basket_item_id'])) {
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        try {
            $basket_item_id = $_POST['basket_item_id'];
            $item_id = $_POST['item_id'];
            $quantity = $_POST['item_quantity'];
            $result = $connection->query("DELETE FROM basket_items where id=$basket_item_id");
            $result = $connection->query("SELECT * FROM items where id=$item_id");
            $item = $result->fetch_assoc();
            $total_price = $quantity * $item['price'];
            $_SESSION['user_data']['basket']['total_price'] = $_SESSION['user_data']['basket']['total_price'] - $total_price;
            $basket_id = $_SESSION['user_data']['basket']['id'];
            $result = $connection->query("UPDATE basket SET total_price = total_price-$total_price where id=$basket_id");
            $_SESSION['e_removeFromBasket'] = 'Usunięto produkt z koszyka';

        } catch (Exception $e) {
            $_SESSION['e_removeFromBasket'] = 'Nie udało się usunąć produktu z koszyka';
        }
    }
}
getBasketItems($connection, $debug);

$connection->close();
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Koszyk</title>
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
    <br/>
    <div style="background-color: #f4f6f2">
    <b>Twój koszyk</b>
    </div>
    <br/>

    <?php
    if (isset($_SESSION['basket_item_list'])) {
        foreach ($_SESSION['basket_item_list'] as $item => $cur) {
            echo '<div class="card">';
                echo '<div class="row">';
                    echo '<div class="col-sm" style="margin: 90px">';
                        echo productIdToName($cur['item_id']);
                        echo '<br />';
                        echo  'Ilość: ' . $cur['amount'] ;
                        echo '<form method="POST">';
                        echo '<input type="hidden" name="basket_item_id" value="' . $cur['id'] . '"/>';
                        echo '<input type="hidden" name="item_id" value="' . $cur['item_id'] . '"/>';
                        echo '<input type="hidden" name="item_quantity" value="' . $cur['amount'] . '"/>';
                        echo '<button type="submit" class="btn btn-primary btn-sm">Usuń z koszyka</button>';
                        echo '</form>';
                    echo '</div>';
                    echo '<div class="col-sm">';
                        echo showProductThumb($cur['item_id']);
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<br/>';
        }
        echo '<br/>';
        echo '<div style="background-color: #f4f6f2">';
        echo '<b>Cena:</b> ' . $_SESSION['user_data']['basket']['total_price'] . 'zł';
        echo '<br/>';
        echo '<a class="btn btn-primary btn-sm" href="summary.php" role="button" style="margin: 10px">Przejdź do podsumowania</a>';
        echo '</div>';
    } else {
        echo '<div style="background-color: #f4f6f2">';
        echo '<br/>';
        echo '<b>Koszyk jest pusty</b>';
        echo '</br>';
        echo '<a class="btn btn-primary btn-sm" href="shop.php" role="button">Idź do sklepu</a>';
        echo '<br/>';
        echo '<br/>';
        echo "</div>";
    }
    ?>
    <br/><br/>
<!--    <a href="main.php">Wróć do strony głównej</a>-->
</div>
</body>

</html>
