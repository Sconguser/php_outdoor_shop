<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_data'])) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";
if (!isset($_SESSION['user_address'])) {
    try {
        $alreadyHasAddress = false;
        mysqli_report(MYSQLI_REPORT_STRICT);
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $id = $_SESSION['user_data']['id'];
            $result = $connection->query("SELECT * FROM addresses WHERE user_id='$id'");
            if ($result->num_rows > 0) {
                $alreadyHasAddress = true;
                $_SESSION['user_address'] = $result->fetch_assoc();
                $result->close();
            }
        }
        $connection->close();
    } catch (Exception $e) {
        if ($debug) {
            echo '<p>' . $e . '</p>';
        }
    }
} else {
    if (isset($_POST['street']) && isset($_POST['city']) && isset($_POST['post_code'])) {
        try {
            mysqli_report(MYSQLI_REPORT_STRICT);
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            if ($connection->connect_errno != 0) {
                throw new Exception(mysqli_connect_errno());
            } else {
                $id = $_SESSION['user_data']['id'];
                $street = htmlentities($_POST['street'], ENT_QUOTES, "UTF-8");
                $city = htmlentities($_POST['city'], ENT_QUOTES, "UTF-8");
                $post_code = htmlentities($_POST['post_code'], ENT_QUOTES, "UTF-8");
//                echo $street;
//                exit();
                $result = $connection->query("UPDATE addresses
                SET city='$city', street='$street', post_code='$post_code'
                 WHERE user_id='$id'");
                $_SESSION['user_address']['street'] = $street;
                $_SESSION['user_address']['city'] = $city;
                $_SESSION['user_address']['post_code'] = $post_code;
                $_SESSION['success_change_address'] = true;
                header('Location: success_change_address.php');
                $connection->close();
            }
        } catch (Exception $e) {
            if ($debug) {
                echo '<p>' . $e . '</p>';
            }
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Strona do edycji danych adresowych</title>
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
    </br>
    <div style="background-color: #f4f6f2">
        <?php
        if (isset($_SESSION['e_summaryRedirect'])) {
            echo '<a class="btn btn-primary btn-sm" href="summary.php" role="button">Wróć</a>';
        } else {
            echo '<a class="btn btn-primary btn-sm" href="edit_user_info.php" role="button">Wróć</a>';
        }
        ?>
        <br/>
        <form method="POST">
            <br/>
            <b>Miasto:</b> <input type="text" value="<?php
            echo $_SESSION['user_address']['city'];
            ?>" name="city"/>
            <br/>
            <br/>
            <b>Ulica:</b> <input type="text" value="<?php
            echo $_SESSION['user_address']['street'];
            ?>" name="street"/>
            <br/>
            <br/>
            <b>Kod pocztowy:</b> <input type="text" value="<?php
            echo $_SESSION['user_address']['post_code'];
            ?>" name="post_code"/>
            <br/>
            <!--        <input type="submit" value="Zapisz dane"/>-->
            <button type="submit" class="btn btn-primary btn-sm">Zapisz dane</button>
            <br/>
        </form>
        <br/>
    </div>
    <br/><br/>
    <!--    <a href="main.php">Wróć do strony głównej</a>-->
</div>
</body>
</html>