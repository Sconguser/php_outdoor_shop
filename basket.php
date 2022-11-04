<?php
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

getBasketItems($connection, $debug);
$connection->close();
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Koszyk</title>
</head>
<body>
basket <br/><br/>

<?php
if (isset($_SESSION['basket_item_list'])) {
    foreach ($_SESSION['basket_item_list'] as $item => $cur) {
        echo 'Id: ' . $cur['id'] . ' Item id: ' . $cur['item_id'] . ' Ilość: : ' . $cur['amount'] . ' ';
        echo '<br/>';
    }
    echo '<br/>';
    echo 'Cena: '.$_SESSION['user_data']['basket']['total_price'].'zł';
    echo '<br/>';
    echo '<a href="summary.php"> Przejdź do podsumowania </a>';
    unset($_SESSION['basket_item_list']);
}else{
    echo 'Koszyk jest pusty';
}
?>
<br/><br/>
<a href="main.php">Wróć do strony głównej</a>
</body>


</html>
