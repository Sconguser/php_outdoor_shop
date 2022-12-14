<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['user_data']) || !$_SESSION['user_data']['is_admin']) {
    header('Location: index.php');
    exit();
}
require_once "connect.php";
if (isset($_POST['admin_privileges'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        $id = $_POST['admin_privileges'];
        try {
            if ($result = $connection->query("UPDATE users
                SET is_admin = true
                 WHERE id='$id'")) {
                unset($_SESSION['user_list']);
                unset($_POST['admin_privileges']);
            } else {
                throw new Exception('Failed to give user admin privileges');
            }
        } catch (Exception $e) {
            echo "<p>" . $e . "</p>";
        }
    }
    $connection->close();
}

if (isset($_POST['delete_user'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        $id = $_POST['delete_user'];
        try {
            if ($result = $connection->query("DELETE FROM users
                 WHERE id='$id'")) {
                unset($_SESSION['user_list']);
                unset($_POST['delete_user']);
            } else {
                throw new Exception('Failed to delete user');
            }
        } catch (Exception $e) {
            echo "<p>" . $e . "</p>";
        }
    }
    $connection->close();
}


if (!isset($_SESSION['user_list'])) {
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        echo "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    } else {
        try {
            if ($result = $connection->query("SELECT * FROM users")) {
                if ($result->num_rows > 0) {
                    $int = 0;
                    while ($user = $result->fetch_assoc()) {
                        $_SESSION['user_list'][$user['id']] = $user;
                        $int++;
                    }

                } else {
                    throw new Exception('User list is empty');
                }
            } else {
                throw new Exception('Failed to get user list');
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
    <title>Zarz??dzaj u??ytkownikami</title>
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
    <b>Panel administratora - zarz??dzanie u??ytkownikami</b>
    </div>
    <br/><br/>

    <?php
    if (isset($_SESSION['user_list'])) {
        foreach ($_SESSION['user_list'] as $item => $cur) {
            echo '<div class = "card" style="padding: 10px;">';
            echo 'Id: ' . $cur['id'];
            echo '<br/>';
            echo 'Imi??: ' . $cur['name'];
            echo '<br/>';
            echo 'Nazwisko: ' . $cur['lastname'];
            echo '<br/>';
            if (!$cur['is_admin']) {
                echo '<form method="POST">';
                echo '<input type="hidden" name="admin_privileges" value="' . $cur['id'] . '"/>';
                echo '<button type="submit" class="btn btn-primary btn-sm">Nadaj uprawnienia adminsitratora</button>';
                echo '</form>';

                echo '<form method="POST">';
                echo '<input type="hidden" name="delete_user" value="' . $cur['id'] . '"/>';
                echo '<button type="submit" class="btn btn-primary btn-sm">Usu?? tego u??ytkownika</button>';
                echo '</form>';

                echo '<form action="user_orders.php" method="POST">';
                echo '<input type="hidden" name="user_id" value="' . $cur['id'] . '"/>';
                echo '<button type="submit" class="btn btn-primary btn-sm">Zarz??dzaj statusem zam??wie??</button>';
                echo '</form>';
            } else {
//                echo '<br />';
                echo '<b>Administrator</b>';
                echo '<br />';
            }
            echo '</div>';
            echo '<br/>';
        }
        unset($_SESSION['user_list']);
    }
    ?>
<!--    <br/><br/>-->
<!--    <a href="main.php">Wr???? do strony g????wnej</a>-->

</div>
</body>

</html>