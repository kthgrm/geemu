<?php
include 'includes/head.php';

if (isset($_GET['id'])) {
    $gameId = $_GET['id'];
    $xml = new DOMDocument();
    $xml->load('../data/games.xml');
    $games = $xml->getElementsByTagName('game');

    foreach ($games as $game) {
        $id = $game->getAttribute('id');
        if ($id == $gameId) {
            $title = $game->getElementsByTagName('title')[0]->nodeValue;
            $description = $game->getElementsByTagName('description')[0]->nodeValue;
            $price = $game->getElementsByTagName('price')[0]->nodeValue;
            $quantity = $game->getElementsByTagName('quantity')[0]->nodeValue;
            $category = $game->getElementsByTagName('category')[0]->nodeValue;
            $tags = $game->getElementsByTagName('tag')[0]->nodeValue;
            $image = $game->getElementsByTagName('image')[0]->nodeValue;
            break;
        }
    }

    if ($game) {
?>
        <div class="container my-5">
            <div class="row g-4">
                <div class="col-lg-4 h-100">
                    <div class="bg-white rounded shadow-sm p-4 h-100 d-flex flex-column align-items-center">
                        <div id="mainImage" class="mb-3 flex-grow-1 d-flex align-items-center justify-content-center w-100" style="max-width:400px; max-height:400px; height:100%;">
                            <img src="../assets/images/cover/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="img-fluid w-100 h-100" style="object-fit:contain; max-height:400px;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="bg-white rounded shadow-sm p-4 h-100 d-flex flex-column">
                        <span class="text-uppercase text-secondary fw-bold small mb-1"><?php echo htmlspecialchars($category); ?></span>
                        <h2 class="fw-bold mb-2"><?php echo htmlspecialchars($title); ?></h2>
                        <div class="mb-2">
                            <span class="text-success fw-bold">In Stock: <?php echo (int)$quantity; ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="fs-3 fw-bold text-danger">₱<?php echo number_format((float)$price, 2); ?></span>
                            <?php if (!empty($oldPrice) && $oldPrice > $price): ?>
                                <span class="text-muted text-decoration-line-through ms-2">₱<?php echo number_format((float)$oldPrice, 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><strong>Description:</strong></p>
                            <div class="text-secondary"><?php echo nl2br(htmlspecialchars($description)); ?></div>
                        </div>
                        <div class="mb-3">
                            <span class="me-2"><strong>Tags:</strong> <?php echo htmlspecialchars($tags); ?></span>
                        </div>
                        <form class="d-flex align-items-center mb-3" style="max-width:200px;">
                            <label for="quantity" class="me-2 mb-0"><strong>Quantity</strong></label>
                            <input type="number" id="quantity" name="quantity" class="form-control me-2" value="1" min="1" max="<?php echo (int)$quantity; ?>" style="width:70px;"
                                oninput="if (this.value < 1) this.value = 1; if (this.value > <?php echo (int)$quantity; ?>) this.value = <?php echo (int)$quantity; ?>;">
                        </form>
                        <div class="d-flex gap-2 mb-3">
                            <form action="cart-add.php" method="post" class="d-inline">
                                <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($gameId); ?>">
                                <input type="hidden" name="quantity" id="formQuantity" value="1">
                                <button class="btn btn-warning fw-bold px-4" type="submit">
                                    <i class="fa-solid fa-cart-plus me-2"></i>Add to cart
                                </button>
                            </form>
                            <script>
                                // Syncs the quantity input with the form's hidden input
                                document.getElementById('quantity').addEventListener('input', function() {
                                    document.getElementById('formQuantity').value = this.value;
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Fetch related games by category
        $relatedGames = [];
        foreach ($games as $otherGame) {
            if ($otherGame->getAttribute('id') != $gameId) {
                $otherCategory = $otherGame->getElementsByTagName('category')[0]->nodeValue;
                if ($otherCategory === $category) {
                    $relatedGames[] = [
                        'id' => $otherGame->getAttribute('id'),
                        'title' => $otherGame->getElementsByTagName('title')[0]->nodeValue,
                        'image' => $otherGame->getElementsByTagName('image')[0]->nodeValue,
                        'price' => $otherGame->getElementsByTagName('price')[0]->nodeValue
                    ];
                    if (count($relatedGames) >= 4) break;
                }
            }
        }
        if (!empty($relatedGames)): ?>
            <div class="container my-5">
                <h4 class="mb-4">You Might Also Like in <?php echo htmlspecialchars($category); ?> Games</h4>
                <div class="row g-4">
                    <?php foreach ($relatedGames as $rel): ?>
                        <div class="col-6 col-md-3">
                            <div class="card h-100 shadow-sm">
                                <a href="game.php?id=<?php echo urlencode($rel['id']); ?>" class="text-decoration-none text-dark bg-dark">
                                    <img src="../assets/images/cover/<?php echo htmlspecialchars($rel['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($rel['title']); ?>" style="object-fit:contain; height:180px;">
                                    <div class="card-body bg-white">
                                        <h6 class="card-title mb-2"><?php echo htmlspecialchars($rel['title']); ?></h6>
                                        <div class="fw-bold text-danger">₱<?php echo number_format((float)$rel['price'], 2); ?></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
<?php
    } else {
        echo '<div class="alert alert-danger mt-5 container">Game not found.</div>';
    }
} else {
    echo '<div class="alert alert-warning mt-5 container">No game ID specified.</div>';
}
?>

<?php include 'includes/foot.php'; ?>