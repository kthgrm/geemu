<?php
include 'includes/head.php';
?>

<!-- Main Content -->
<div class="container">
    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-3">
            <!-- Categories Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fa fa-list me-2"></i>Categories</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <?php
                    $xml = new DOMDocument();
                    $xml->load('../data/categories.xml');
                    $categories = $xml->getElementsByTagName('category');
                    foreach ($categories as $category) {
                        $categoryName = $category->getElementsByTagName('name')[0]->nodeValue;
                        echo '<li class="list-group-item">';
                        echo '<a href="shop.php?q=' . urlencode($categoryName) . '" class="text-decoration-none text-dark d-block py-1 px-2 rounded hover-bg-light">';
                        echo htmlspecialchars($categoryName);
                        echo '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="col-12 col-md-9">
            <h2 class="mb-3">Shop Products</h2>
            <form class="d-flex mb-4" method="get" role="search">
                <input class="form-control me-2" type="search" name="q" placeholder="Search products..." aria-label="Search" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <button class="btn btn-outline-danger" type="submit">Search</button>
            </form>
            <div class="row">
                <?php
                $xml = new DOMDocument();
                $xml->load('../data/games.xml');
                $games = $xml->getElementsByTagName('game');
                $search = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
                $found = false;
                $id = "";

                $gamesPerPage = 6;
                $totalGames = $games->length;
                $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                $start = ($page - 1) * $gamesPerPage;
                $end = $start + $gamesPerPage;

                foreach ($games as $game) {
                    $id = $game->getAttribute('id');
                    $title = $game->getElementsByTagName('title')[0]->nodeValue;
                    $price = $game->getElementsByTagName('price')[0]->nodeValue;
                    $image = $game->getElementsByTagName('image')[0]->nodeValue;
                    $category = $game->getElementsByTagName('category')[0]->nodeValue;
                    $tag = $game->getElementsByTagName('tag')->length > 0 ? $game->getElementsByTagName('tag')[0]->nodeValue : '';

                    if (
                        $search &&
                        stripos($title, $search) === false &&
                        stripos($category, $search) === false &&
                        stripos($tag, $search) === false
                    ) {
                        continue;
                    }
                    $filteredGames[] = [
                        'id' => $id,
                        'title' => $title,
                        'price' => $price,
                        'image' => $image,
                        'category' => $category,
                        'tag' => $tag
                    ];
                }
                $totalFiltered = count($filteredGames);
                $gamesToShow = array_slice($filteredGames, $start, $gamesPerPage);
                ?>
                <div class="row">
                    <?php
                    if ($totalFiltered > 0) {
                        foreach ($gamesToShow as $game) {
                    ?>
                            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                                <div class="card h-100">
                                    <a href="game.php?id=<?php echo urlencode($game['id']); ?>" class="text-decoration-none text-dark">
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-dark">
                                            <img src="../assets/images/cover/<?php echo htmlspecialchars($game['image']); ?>"
                                                alt="<?php echo htmlspecialchars($game['title']); ?>"
                                                style="max-height:170px; width:auto; max-width:100%; object-fit:cover; margin:auto; display:block;">
                                        </div>
                                    </a>
                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($game['title']); ?></h5>
                                        <span class="fw-bold">₱<?php echo number_format((float)$game['price'], 2); ?></span>
                                        <div class="mt-auto d-flex justify-content-end align-items-center gap-1">
                                            <a href="cart-add.php?id=<?php echo urlencode($game['id']); ?>" class="btn btn-danger btn-sm">Add to Cart <i class="fa-solid fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12"><div class="alert alert-danger text-white">No products found.</div></div>';
                    }
                    ?>
                </div>
            </div>
            <?php
            $totalPages = ceil($totalFiltered / $gamesPerPage);
            if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++):
                            // Keep search query in pagination links
                            $query = $_GET;
                            $query['page'] = $i;
                            $url = '?' . http_build_query($query);
                        ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="<?php echo $url; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>