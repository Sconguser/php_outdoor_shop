<?php
session_start();

if (isset($_POST['email'])) {
    $successfulRegistration = true;
    $name = $_POST['name'];
    if (strlen($name) < 1 || strlen($name) > 20) {
        $successfulRegistration = false;
        $_SESSION['e_name'] = "Imię musi posiadać od 1 do 20 znaków";
    }

    $lastname = $_POST['lastname'];
    if (strlen($lastname) < 1 || strlen($lastname) > 20) {
        $successfulRegistration = false;
        $_SESSION['e_lastname'] = "Nazwisko musi posiadać od 1 do 20 znaków";
    }

    $email = $_POST['email'];
    $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!(filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) ||
        $email != $emailSanitized || strlen($email) < 1 || strlen($email) > 50) {
        $successfulRegistration = false;
        $_SESSION['e_email'] = "To nie jest poprawny email";
    }
    $password = $_POST['password'];
    if (strlen($password) < 8 || strlen($password) > 20) {
        $successfulRegistration = false;
        $_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków";
    }
    $passwordConfirmation = $_POST['passwordConfirmation'];
    if ($password != $passwordConfirmation) {
        $successfulRegistration = false;
        $_SESSION['e_passwordConfirmation'] = "Hasła się nie zgadzają";
    }
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if (!isset($_POST['termsOfService'])) {
        $successfulRegistration = false;
        $_SESSION['e_termsOfService'] = "Zaakceptuj regulamin!";
    }
    $_SESSION['fr_name'] = $name;
    $_SESSION['fr_lastname'] = $lastname;
    $_SESSION['fr_email'] = $email;

    if ($successfulRegistration) {
        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            if ($connection->connect_errno != 0) {
                throw new Exception(mysqli_connect_errno());
            } else {

                $result = $connection->query("SELECT id FROM users WHERE email='$email'");
//                    echo $result->num_rows;
                if (!$result) throw new Exception($connection->error);
                $how_many_emails = $result->num_rows;
                if ($how_many_emails > 0) {
                    $successfulRegistration = false;
                    $_SESSION['e_email'] = "Istnieje już konto o takim emailu";
                } else {
                    if ($connection->query("INSERT INTO users VALUES(NULL, NULL, NULL, '$name',
                         '$lastname', '$password_hash', '$email')")){
                        $result_new_user_id = $connection->query("SELECT id FROM users where email='$email'");
                        $user_id =  $result_new_user_id->fetch_assoc()['id'];
                        $connection->query("INSERT INTO addresses VALUES(NULL, $user_id, '', '', '')");
                        $connection->query("INSERT INTO basket VALUES(NULL, $user_id, 0)");
                        $_SESSION['signup_success'] = true;
                        header('Location: success_signup.php');
                    }else{
                        throw new Exception("Nie udało się stworzyć nowego użytkownika");
                    }
                    }

                $connection->close();
            }
        } catch (Exception $e) {
            echo '<span style="color:red;"> Błąd servera </span>';
            if ($debug) {
                echo '<br /> Error ' . $e;
            }
        }
//            exit();
    }
}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Rejestracja</title>
    <style>
        .error {
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<form method="POST">
    Imię: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_name']))
    {
        echo $_SESSION['fr_name'];
        unset($_SESSION['fr_name']);
    }
?>" name="name"/><br/>
    <?php
    if (isset($_SESSION['e_name'])) {
        echo '<div class="error">' . $_SESSION['e_name'] . '</div>';
        unset($_SESSION['e_name']);
    }
    ?>
    Nazwisko: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_lastname']))
    {
        echo $_SESSION['fr_lastname'];
        unset($_SESSION['fr_lastname']);
    }
    ?>" name="lastname"/><br/>
    <?php
    if (isset($_SESSION['e_lastname'])) {
        echo '<div class="error">' . $_SESSION['e_lastname'] . '</div>';
        unset($_SESSION['e_lastname']);
    }
    ?>
    Email: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_email']))
    {
        echo $_SESSION['fr_email'];
        unset($_SESSION['fr_email']);
    }
    ?>" name="email"/><br/>
    <?php
    if (isset($_SESSION['e_email'])) {
        echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
        unset($_SESSION['e_email']);
    }
    ?>
    Hasło: <br/> <input type="password"  name="password"/><br/>
    <?php
    if (isset($_SESSION['e_password'])) {
        echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
        unset($_SESSION['e_password']);
    }
    ?>
    Powtórz hasło: <br/> <input type="password"  name="passwordConfirmation"/><br/>
    <?php
    if (isset($_SESSION['e_passwordConfirmation'])) {
        echo '<div class="error">' . $_SESSION['e_passwordConfirmation'] . '</div>';
        unset($_SESSION['e_passwordConfirmation']);
    }
    ?>
    <label>
        <input type="checkbox" name="termsOfService"/> Akceptuję regulamin
    </label>
    <?php
    if (isset($_SESSION['e_termsOfService'])) {
        echo '<div class="error">' . $_SESSION['e_termsOfService'] . '</div>';
        unset($_SESSION['e_termsOfService']);
    }
    ?>
    <br/>
    <!-- <div class="g-recaptcha" data-sitekey="6LdT_KQiAAAAALNrfA3pdp7bdIlXNbDZhCw7nKgP" data-action="REGISTER"></div> -->
    <input type="submit" value="Zarejestruj się"/>
    <br/>
    <a href="index.php">Wróć na stronę logowania</a>
</form>
</body>

</html>