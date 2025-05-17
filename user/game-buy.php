<?php
include 'includes/head.php';
// Get the game ID from the URL
$gameId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
?>

<div class="container mt-5">
    <h2>Buy Game</h2>
    <?php
    // Find the game name, price, and image by ID
    $gameName = '';
    $gamePrice = '';
    $gameImage = '';
    if ($gameId !== '') {
        $xml = new DOMDocument();
        $xml->load('../data/games.xml');
        $games = $xml->getElementsByTagName('game');
        foreach ($games as $game) {
            if ($game->getAttribute('id') === $gameId) {
                $gameName = $game->getElementsByTagName('title')->item(0)->nodeValue;
                $gamePrice = $game->getElementsByTagName('price')->item(0)->nodeValue;
                $imageNode = $game->getElementsByTagName('image')->item(0);
                $gameImage = $imageNode ? $imageNode->nodeValue : '';
                break;
            }
        }
    }
    ?>

    <script>
        function minus() {
            var qty = document.getElementById('quantity');
            if (qty.value > 1) {
                qty.value--;
            }
            updateCartTotal();
        }

        function plus() {
            var qty = document.getElementById('quantity');
            qty.value++;
            updateCartTotal();
        }

        function updateCartTotal() {
            var unitPrice = parseFloat(document.getElementById('unitPrice').innerText.replace(/,/g, '')) || 0;
            var quantity = parseInt(document.getElementById('quantity').value) || 1;
            var cartTotal = unitPrice * quantity;
            document.getElementById('cartTotal').innerText = cartTotal.toFixed(2);
            document.getElementById('summaryTotal').innerText = cartTotal.toFixed(2);
        }
    </script>

    <div class="container my-5">
        <h2 class="text-center mb-4">Shopping Cart</h2>
        <div class="row g-4">
            <!-- Cart Table -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="row fw-bold text-secondary border-bottom pb-2 mb-3">
                            <div class="col-6">Product</div>
                            <div class="col-2 text-end">Price</div>
                            <div class="col-2 text-center">Quantity</div>
                            <div class="col-2 text-end">Cart Total</div>
                        </div>
                        <div class="row align-items-center py-3 border-bottom">
                            <div class="col-6 d-flex align-items-center">
                                <button class="btn btn-link text-danger p-0 me-3" title="Remove"><i class="fa fa-times fa-lg"></i></button>
                                <img src="../assets/images/cover/<?php echo htmlspecialchars($gameImage); ?>" alt="<?php echo htmlspecialchars($gameName); ?>" style="width:90px; height:auto; object-fit:contain;" class="me-3">
                                <span class="fw-semibold"><?php echo htmlspecialchars($gameName); ?></span>
                            </div>
                            <div class="col-2 text-end">
                                ₱<span id="unitPrice"><?php echo number_format((float)$gamePrice, 2); ?></span>
                            </div>
                            <div class="col-2 text-center">
                                <div class="input-group input-group-sm justify-content-center" style="max-width:110px; margin:auto;">
                                    <button class="btn btn-outline-secondary" type="button" id="qtyMinus" onclick="minus()"><i class="fa fa-minus"></i></button>
                                    <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" style="width:45px; -moz-appearance: textfield;" oninput="updateCartTotal()">
                                    <style>
                                        /* Hide number input arrows for Chrome, Safari, Edge, Opera */
                                        input[type=number]::-webkit-inner-spin-button,
                                        input[type=number]::-webkit-outer-spin-button {
                                            -webkit-appearance: none;
                                            margin: 0;
                                        }

                                        /* Hide number input arrows for Firefox */
                                        input[type=number] {
                                            -moz-appearance: textfield;
                                        }
                                    </style>
                                    <button class="btn btn-outline-secondary" type="button" id="qtyPlus" onclick="plus()"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-2 text-end">
                                ₱<span id="cartTotal"><?php echo number_format((float)$gamePrice, 2); ?></span>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col d-flex justify-content-end gap-3">
                                <a href="shop.php" class="btn btn-light px-4">Continue Shopping</a>
                                <button class="btn btn-dark px-4">Clear cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cart Summary / Checkout -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold fs-5">Cart Total</span>
                            <span class="fw-bold fs-4 text-dark">₱<span id="summaryTotal"><?php echo number_format((float)$gamePrice, 2); ?></span></span>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <button class="btn btn-outline-secondary flex-fill py-2" type="button">
                                <i class="fa fa-truck fa-lg me-2"></i>Local Delivery
                            </button>
                            <button class="btn btn-outline-secondary flex-fill py-2" type="button">
                                <i class="fa fa-store fa-lg me-2"></i>Store Pickup
                            </button>
                        </div>
                        <button class="btn btn-warning w-100 fw-bold py-2 fs-5" type="button">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('quantity').addEventListener('input', function() {
            var price = parseFloat(document.getElementById('price').value) || 0;
            var qty = parseInt(this.value) || 1;
            document.getElementById('total').value = (price * qty).toFixed(2);
        });
    </script>
</div>

<?php include 'includes/foot.php'; ?>