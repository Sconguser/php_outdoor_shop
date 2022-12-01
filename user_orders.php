<?php
require_once "bootstrap_include.php";
session_start();
require_once "connect.php";
$connection = @new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_errno != 0 && $debug == 1) {
    echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
} else {
    if (isset($_POST['order_status'])) {
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0 && $debug == 1) {
            echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
        } else {
            try {
                $order_id = $_POST['order_id'];
                $order_status = $_POST['order_status'];
//            echo $order_id;
//            exit();
                $result = $connection->query("UPDATE orders SET order_status = '$order_status' WHERE id=$order_id");
//            header("Location: admin_manage_users.php");
            } catch (Exception $e) {
                $_SESSION['e_ChangeOrderStatus'] = $e;
            }
        }
    }
    try {
        $user_id = $_SESSION['user_data']['id'];
        if (isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
        }
        $result = $connection->query("SELECT * FROM orders WHERE user_id = $user_id");
        if ($result->num_rows > 0) {
            $int = 0;
            while ($user_order = $result->fetch_assoc()) {
                $_SESSION['user_orders'][$user_order['id']] = $user_order;
                $int++;
            }
        } else {
            /// do nothing, it's ok
        }
    } catch (Exception $e) {
        $_SESSION['e_userOrders'] = 'Something went wrong';
        $connection->close();
        header("Location: index.php");
    }
    $connection->close();
}


?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Zamówienia</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="icon" type="image/ico" href="images/favicon.ico">
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
        <b>Historia zamówień:</b>
    </div>
    <br/>

    <?php
    if (isset($_SESSION['user_orders'])) {
        foreach ($_SESSION['user_orders'] as $item => $cur) {
            $defaultStatus = $cur['order_status'];
            if ($_SESSION['user_data']['is_admin']) {
                echo '<div class="card">';
                echo '<form method="POST">';
                echo '<select name="order_status">';
                echo ' <option value=' . $defaultStatus . '>' . $defaultStatus . '</option>';
                echo ' <option value="Wysłane">Wysłane</option>';
                echo ' <option value="Dostarczone">Dostarczone</option>';
                echo ' <option value="Zwrócone">Zwrócone</option>';
                echo '</select>';
                echo '<br/>';
                echo '<button type="submit" class="btn btn-primary btn-sm">Zmień status zamówienia</button>';
                echo '<br/>';
                echo 'Id zamówienia: '.$cur['id'];
                echo '<br/>';
                echo 'Cena: ' . $cur['price'].'zł' ;
                echo '<br/>';
                echo 'Data zamówienia: ' . $cur['date_of_order'];
                echo '<br/>';
                echo 'Status: ' . $cur['order_status'];
                echo '<input type="hidden" name="order_id" value="' . $cur['id'] . '"/>';
                echo '</form>';
//                echo '<br/>';
            } else {
                echo '<div class = "card">';
                echo 'Cena: ' . $cur['price'].'zł' ;
                echo '<br/>';
                echo 'Data zamówienia: ' . $cur['date_of_order'];
                echo '<br/>';
                echo 'Status: ' . $cur['order_status'];
                echo '<input type="hidden" name="order_id" value="' . $cur['id'] . '"/>';
//                echo '</div>';
//                echo '<br/>';
            }
            echo '</div>';
            echo '</br>';
        }
        echo '<br/>';
        unset($_SESSION['user_orders']);
        if (isset($_SESSION['e_ChangeOrderStatus'])) {
            echo $_SESSION['e_ChangeOrderStatus'];
            unset($_SESSION['e_ChangeOrderStatus']);
        }
    } else {
        echo 'Nie masz jeszcze żadnych zamówień';
    }
    ?>
    <br/><br/>
</div>
</body>


</html>
