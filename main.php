<?php
require_once "bootstrap_include.php";
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Strona główna</title>
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
    //        header("Location:shop.php");
    ?>
    <?php
    if (isset($_SESSION['e_mainRedirect'])) {
        echo '<p>' . $_SESSION['e_mainRedirect'] . '</p>';
        unset($_SESSION['e_mainRedirect']);
    }
    if (isset($_SESSION['e_summaryRedirect'])) {
        unset($_SESSION['e_summaryRedirect']);
    }
    if (isset($_SESSION['e_userOrders'])) {
        echo '<p>' . $_SESSION['e_userOrders'] . '</p>';
        unset($_SESSION['e_userOrders']);
    }
    ?>
    <div class="container">
        <br/>
        <div class="card"
        ">
        <br/>
        <?php
        echo "<p><b>Hej " . $_SESSION['user_data']['name'] . "!</b></p>";
        ?>
        <h1>Witamy na stronie Minimalistycznego Sklepu Turystycznego.</h1>
        <b>Znajdziesz tutaj sprzęt turystyczny i tylko to. W minimalistycznym stylu.</b>
        <br/>
        <b>Inspirowane wielkimi podróżami produkty Minimalistycznego Sklepu Turystycznego
            zostały zaprojektowane z myślą o Twoich potrzebach.
            Kiedy będziesz realizować swój sen o wędrówce
            w nieznane, Minimalistyczny Sklep Turystyczny będzie tam z Tobą!
            <br/>
            Pierwsze produkty marki Minimalistycznego Sklepu Turystycznego powstały w 1996 r. zainspirowane
            historycznymi wyprawami polarnymi, wychodzącymi poza granice ówczesnej wyobraźni, których dokonał Spongebob,
            norweski podróżnik. Do dnia dzisiejszego łączymy pasję podróżowania, skandynawskie poczucie jakości i
            funkcjonalności.
            <br/>
            <br/>
            Od początku istnienia, misją i celem marki jest propagowanie podróżniczego stylu życia. Dostarczając naszym
            klientom produkty w świetnej jakości i na każdą kieszeń zachęcamy do odszukania w sobie Dzikości w Sercu i
            nowych pasji. Nie ważne czy lubisz góry, kajaki, rowery czy camping nad jeziorem, z produktami marki
            Minimalistycznego Sklepu Turystycznego przygoda nabierze nowego wymiaru.</b>
        <br/>
        <br/>
        <div class="row">
            <div class="col-sm">
                <img src="images/lifestyle.jpg" width="300" height="200"/>
            </div>
            <div class="col-sm">
                <img src="images/page.jpg" width="300" height="200"/>
            </div>
            <div class="col-sm">
                <img src="images/backpack.jpg" width="300" height="200"/>
            </div>
        </div>
    </div>

</div>
</div>
</body>

</html>