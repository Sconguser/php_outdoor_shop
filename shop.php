<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['user_data'])) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";
require_once "aux_func.php";
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
if (isset($_POST['chosen_category']) && $_POST['chosen_category'] != -1) {
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
    <link rel="stylesheet" href="stylesheet.css">
    <!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->
</head>
<body>
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>-->
<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>-->

<div class="container">
    <?php
    if ($_SESSION['user_data']['is_admin']) {
        require_once "admin_navbar.php";
    } else {
        require_once "navbar.php";
    }
    ?>
    <?php
    if (isset($_SESSION['e_addToBasket'])) {
        showAlert($_SESSION['e_addToBasket']);
        unset($_SESSION['e_addToBasket']);
    }
    ?>
    <b>Kategoria:</b>
    <?php
    if (isset($_SESSION['category_list'])) {
        echo '<form method="POST">';
        echo '<select name="chosen_category">';
        echo ' <option value=-1>Brak</option>';
        foreach ($_SESSION['category_list'] as $item => $cur) {
            echo '<option value="' . $cur['id'] . '">' . $cur['category_name'] . '</option>';
        }
        echo '</select>';
        echo '<button type="submit" class="btn btn-primary btn-sm">Filtruj</button>';
        echo '</form>';
        unset($_SESSION['category_list']);
    }


    if (isset($_SESSION['product_list'])) {
        foreach ($_SESSION['product_list'] as $item => $cur) {
            echo '<div class="card">';
            echo '<div class ="row">';
            echo '<div class="col-sm">';
            productInfoSection($cur);
            buySection($cur);
            echo '<form action="product_details.php" method="POST">';
            echo '<input type="hidden" name="item_id" value="' . $cur['id'] . '"/>';
            echo '<button type="submit" class="btn btn-primary btn-sm">Szczegóły produktu</button>';
            echo '</form>';
            echo '</div>';
            echo '<div class="col-sm">';
            echo showProductThumb($cur['id']);
            echo '</div>';
            echo '</div>';
            echo '<br/>';
            echo '</div>';
            echo '<br/>';
        }
        unset($_SESSION['product_list']);
    }
    ?>
    <br/><br/>
    <!--    <a href="main.php">Wróć do strony głównej</a>-->
</div>
</body>

</html>