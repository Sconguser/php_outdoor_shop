<?php
    session_start();
    require_once "connect.php";
    require_once "db_converters.php";

    if(!isset($_SESSION['user_data']) || !isset($_POST['item_id']) || !isset($_POST['quantity'])){
        $_SESSION['e_addToBasket'] = 'Coś poszło nie tak';
        header('Location:shop.php');
    }
    else{
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0 && $debug == 1) {
            echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
        } else {
            try {
                $basket_id = $_SESSION['user_data']['basket']['id'];
                $item_id = $_POST['item_id'];
                $quantity = $_POST['quantity'];
                $result = $connection->query("SELECT * FROM items WHERE id = $item_id");
                $item = $result->fetch_assoc();
                $item_stock = $item['quantity'];
                if($quantity > $item_stock){
                    $_SESSION['e_addToBasket'] = "Nie mamy tyle sztuk tego produktu w magazynach";
                    header("Location:shop.php");
                    exit();
                }
                if ($result = $connection->query("INSERT INTO basket_items VALUES (NULL, $basket_id, $item_id, $quantity)")) {
                    $total_price = getTotalPrice($_SESSION['user_data']['basket']['id']);
                    $item = $connection->query("SELECT * FROM items WHERE id=$item_id")->fetch_assoc();
                    $total_price = $total_price + ($item['price']*$quantity);
                    $connection->query("UPDATE basket SET total_price=$total_price WHERE id=$basket_id");
                    $_SESSION['user_data']['basket']['total_price'] = $total_price;
                    $_SESSION['e_addToBasket'] = "Dodano produkt do koszyka";
                    header('Location:shop.php');
                }else {
                    throw new Exception('Failed to get Category list');
                }
            } catch (Exception $e) {
                $_SESSION['e_addToBasket'] = $e;
                header('Location:shop.php');
            }
        }
        $connection->close();
    }
?>