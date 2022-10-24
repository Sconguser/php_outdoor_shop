<?php
    session_start();

    if(!isset($_POST['login']) || !isset($_POST['password'])){
        header('Location: index.php');
        exit();
    }

    require_once "connect.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno!=0 && debug==1){
        echo "Error:  ".$connection->connect_errno." Description".$connection->connect_error;
    }
    else{
        $login = $_POST['login'];
        $password = $_POST['password'];
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        // $password = htmlentities($password, ENT_QUOTES, "UTF-8");
        if($login_result = @$connection->query(sprintf("SELECT * FROM users WHERE email='%s'",
            mysqli_real_escape_string($connection, $login),
            // mysqli_real_escape_string($connection, $password)
        ))){
            $how_many_rows = $login_result->num_rows;
            if($how_many_rows>0){
                $_SESSION['user_data'] = $login_result->fetch_assoc();
                if(password_verify($password, $_SESSION['user_data']['password'])){
                    $login_result->close();
                    $_SESSION['logged_in'] = true;
                    unset($_SESSION['error']);
                    header('Location: main.php');
                }
                else{
                    $_SESSION['error'] = '<span style="color:red"> Nieprawidłowe hasło :( </span>';
                    unset($_SESSION['user_data']);
                    header('Location: index.php');
                }
            }else{
                $_SESSION['error'] = '<span style="color:red"> Nieprawidłowy login lub hasło :( </span>';
                header('Location: index.php');
            }
        }
        $connection->close();
    }
?>