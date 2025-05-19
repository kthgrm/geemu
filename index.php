<?php include 'includes/head.php'; ?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top px-5 py-3">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="assets/images/logo.png" alt="GameStore Logo" width="100" class="d-inline-block">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="user/shop.php"><i class="fa-solid fa-gamepad"></i> Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="user/cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php"><i class="fa-solid fa-user"></i> Login/Signup</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="bg-dark text-white text-center py-5 d-flex align-items-center" style="background: url('assets/images/hero-bg.jpg') no-repeat top center/cover; height: 80vh">
    <div class="container d-flex justify-content-end align-items-center ">
        <div class="text-center">
            <h1 class="display-5 fw-bold">Discover the Best Console Games</h1>
            <p class="lead">Explore top-rated titles and new releases today.</p>
            <a href="user/shop.php" class="btn btn-dark btn-lg mt-3">Shop Now</a>
        </div>
    </div>
</section>

<!-- Featured Games Section -->
<section class="py-5 text-center">
    <div class="container">
        <h2>Featured Games</h2>
        <div class="row mt-5">
            <?php
            $games = simplexml_load_file('data/games.xml');
            $count = 0;
            foreach ($games->game as $game) {
                if ($count >= 4) break;
                $image = htmlspecialchars($game->image);
                $title = htmlspecialchars($game->title);
                $price = htmlspecialchars($game->price);
                echo '
            <div class="col-md-3 d-flex align-items-stretch">
                <div class="card h-100">
                <img src="assets/images/cover/' . $image . '" class="card-img-top" alt="' . $title . '">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">' . $title . '</h5>
                    <p class="card-text mt-auto">₱' . $price . '</p>
                </div>
                </div>
            </div>
            ';
                $count++;
            }
            ?>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section class="bg-light py-5 text-center">
    <div class="container">
        <h2>About Geemu</h2>
        <p class="mx-auto" style="max-width: 600px;">Geemu is your one-stop destination for the best console games. We offer a curated collection of top-rated titles, indie favorites, and everything in between. Enjoy secure checkout, instant downloads, and unbeatable prices.</p>
    </div>
</section>

<?php include 'includes/foot.php'; ?>