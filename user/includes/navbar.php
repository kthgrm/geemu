<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top px-5 py-3">
    <div class="container">
        <a class="navbar-brand" href="shop.php">
            <img src="../assets/images/logo.png" alt="GameStore Logo" width="100" class="d-inline-block">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="shop.php"><i class="fa-solid fa-gamepad"></i> Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="purchases.php"><i class="fa-solid fa-box"></i> Purchases</a></li>
                <li class="nav-item">
                    <form method="POST" class="ms-auto" action="">
                        <button type="submit" class="btn btn-outline-light" name="logout">
                            <i class="fa-solid fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>