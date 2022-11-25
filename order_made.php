
<?php
function getBasketItems(mysqli $connection, int $debug): void
{
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        try {
            $basket_id = $_SESSION['user_data']['basket']['id'];
            if ($result = $connection->query("SELECT * FROM basket_items WHERE basket_id = $basket_id")) {
                if ($result->num_rows > 0) {
                    $int = 0;
                    while ($basket_item = $result->fetch_assoc()) {
                        $_SESSION['basket_item_list'][$basket_item['id']] = $basket_item;
                        $int++;
                    }
                } else {
                    throw new Exception('Basket item list is empty');
                }
            } else {
                throw new Exception('Failed to get basket item list');
            }
        } catch (Exception $e) {
            echo "<p>" . $e . "</p>";
        }
    }
}


session_start();
require_once "bootstrap_include.php";
if (!isset($_SESSION['user_data']) || !isset($_SESSION['user_data']['basket']) || !isset($_SESSION['basket_item_list'])) {
    $_SESSION['e_orderMade'] = "Coś poszło nie tak";
    header('Location: summary.php');
    exit();
}
require_once "connect.php";
$connection = @new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_errno != 0 && $debug == 1) {
    echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
} else {
    try {
        $user_id = $_SESSION['user_data']['id'];
        $price = $_SESSION['user_data']['basket']['total_price'];
        $order_status = 'W trakcie przetwarzania';
        $basket_id = $_SESSION['user_data']['basket']['id'];
        if ($result = $connection->query("INSERT INTO orders VALUES(NULL, $user_id, $price, now(),'$order_status')")) {
            getBasketItems($connection, $debug);
            $result = $connection->query("SELECT * FROM orders WHERE user_id = $user_id");
            $_SESSION['current_order'] = $result->fetch_assoc();
            $order_id = $_SESSION['current_order']['id'];

            foreach ($_SESSION['basket_item_list'] as $item => $cur) {
                $basket_item_id = $cur['id'];
                $item_id = $cur['item_id'];
                $quantity = $cur['amount'];
                $result = $connection->query("SELECT * FROM items WHERE id=$item_id");
                $item_from_db = $result->fetch_assoc();
                if ($quantity > $item_from_db['quantity']) {
                    $_SESSION['e_orderMade'] = 'Jednego lub więcej przedmiotów nie ma już w naszych magazynach. Przepraszamy :(';
                    header('Location:summary.php');
                    exit();
                }
            }
            foreach ($_SESSION['basket_item_list'] as $item => $cur) {
                $basket_item_id = $cur['id'];
                $item_id = $cur['item_id'];
                $quantity = $cur['amount'];
                $connection->query("INSERT INTO order_items VALUES(NULL,$order_id, $item_id, $user_id, $quantity)");
                $connection->query("UPDATE items SET quantity = quantity - $quantity WHERE id = $item_id ");
                $connection->query("DELETE FROM basket_items where id=$basket_item_id");
            }
            $connection->query("UPDATE basket SET total_price = 0 WHERE id=$basket_id");
            $_SESSION['user_data']['basket']['total_price'] = 0;
            unset($_SESSION['current_order']);
            unset($_SESSION['basket_item_list']);
        }
    } catch (Exception $e) {
        unset($_SESSION['current_order']);
        $_SESSION['e_orderMade'] = $e;
        header('Location:summary.php');
        $connection->close();
        exit();
    }
    $connection->close();
}

?>


<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Zamówienie złożone</title>
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
    Dziękujemy za złożenie zamówienia <br/><br/>
    <a href="user_orders.php">Zobacz swoje zamówienia</a>
    <br/><br/>
    <a href="main.php">Wróć do strony głównej</a>
</div>
</body>

</html>
