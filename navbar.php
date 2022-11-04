<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="main.php">Sklep turystyczny</a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="main.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="shop.php">Sklep</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="basket.php">Koszyk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user_orders.php">Historia</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                   aria-expanded="false">
                    Twój Profil
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="edit_user_info.php">Edytuj dane</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Wyloguj się</a></li>
                </ul>
            </li>
</nav>