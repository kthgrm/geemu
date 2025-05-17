<?php
include 'includes/head.php';

$id = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : null;
if (isset($_POST['remove_game_id'])) {
    $gameId = htmlspecialchars($_POST['remove_game_id']);
    $xml = new DOMDocument();
    $xml->load('../data/carts.xml');
    $carts = $xml->getElementsByTagName('cart');
    foreach ($carts as $cart) {
        if ($cart->getAttribute('userId') == $id) {
            $items = $cart->getElementsByTagName('item');
            $itemParent = $cart->getElementsByTagName('items')[0];
            foreach ($items as $item) {
                if ($item->getAttribute('gameId') == $gameId) {
                    $itemParent->removeChild($item);
                    break;
                }
            }
            break;
        }
    }
    $xml->save('../data/carts.xml');
}
?>
<div class="container my-5">
    <h2 class="text-center mb-4"><?= isset($_GET['id']) ? "Order Summary" : "Shopping Cart" ?></h2>
    <div class="row g-4">
        <!-- Cart Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="row fw-bold text-secondary border-bottom pb-2 mb-3">
                        <div class="col-6">Product</div>
                        <div class="col-2 text-end">Price</div>
                        <div class="col-2 text-center">Quantity</div>
                        <div class="col-2 text-end">Subtotal</div>
                    </div>
                    <?php
                    // Cart from XML
                    $xml = new DOMDocument();
                    $xml->load('../data/carts.xml');
                    $carts = $xml->getElementsByTagName('cart');
                    $found = false;
                    $cartTotal = 0;
                    foreach ($carts as $cart) {
                        if ($cart->getAttribute('userId') == $id) {
                            $items = $cart->getElementsByTagName('item');
                            if ($items->length == 0) {
                                echo "<p>Your cart is empty.</p>";
                            } else {
                                foreach ($items as $item) {
                                    $gameId = $item->getAttribute('gameId');
                                    $quantity = $item->getAttribute('quantity');
                                    // Load game details from XML
                                    $gamesXml = new DOMDocument();
                                    $gamesXml->load('../data/games.xml');
                                    $games = $gamesXml->getElementsByTagName('game');
                                    $gameFound = false;

                                    // Initialize variables
                                    $gameName = '';
                                    $gamePrice = 0;
                                    $gameImage = '';
                                    $lineTotal = 0;

                                    foreach ($games as $game) {
                                        if ($game->getAttribute('id') == $gameId) {
                                            $gameFound = true;
                                            $gameName = $game->getElementsByTagName('title')[0]->nodeValue;
                                            $gamePrice = $game->getElementsByTagName('price')[0]->nodeValue;
                                            $gameImage = $game->getElementsByTagName('image')[0]->nodeValue;
                                            break;
                                        }
                                    }
                                    $lineTotal = $gamePrice * $quantity;
                                    $cartTotal += $lineTotal;
                    ?>
                                    <div class="row align-items-center py-3 border-bottom">
                                        <div class="col-6 d-flex align-items-center">
                                            <form method="post" action="" style="display:inline;">
                                                <input type="hidden" name="remove_game_id" value="<?php echo htmlspecialchars($gameId); ?>">
                                                <button class="btn btn-link text-danger p-0 me-3" title="Remove" name="btnRemoveGame" type="submit" onclick="return confirm('Remove this item from cart?');">
                                                    <i class="fa fa-times fa-lg"></i>
                                                </button>
                                            </form>
                                            <img src="../assets/images/cover/<?php echo htmlspecialchars($gameImage); ?>" alt="<?php echo htmlspecialchars($gameName); ?>" style="width:90px; height:auto; object-fit:contain;" class="me-3">
                                            <span class="fw-semibold"><?php echo htmlspecialchars($gameName); ?></span>
                                        </div>
                                        <div class="col-2 text-end">
                                            ₱<span data-unit-price="<?php echo htmlspecialchars($gamePrice); ?>"><?php echo number_format((float)$gamePrice, 2); ?></span>
                                        </div>
                                        <div class="col-2 text-center">
                                            <div class="input-group input-group-sm justify-content-center" style="max-width:110px; margin:auto;">
                                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, -1)"><i class="fa fa-minus"></i></button>
                                                <input
                                                    type="number"
                                                    class="form-control text-center"
                                                    name="quantity[]"
                                                    value="<?php echo (int)$quantity; ?>"
                                                    min="1"
                                                    style="width:45px; -moz-appearance: textfield;"
                                                    readonly>
                                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, 1)"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <style>
                                                input[type=number]::-webkit-inner-spin-button,
                                                input[type=number]::-webkit-outer-spin-button {
                                                    -webkit-appearance: none;
                                                    margin: 0;
                                                }

                                                input[type=number] {
                                                    -moz-appearance: textfield;
                                                }
                                            </style>
                                        </div>
                                        <div class="col-2 text-end">
                                            ₱<span data-line-total><?php echo number_format((float)$lineTotal, 2); ?></span>
                                        </div>
                                    </div>
                    <?php
                                }
                            }
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        echo "<p>Your cart is empty.</p>";
                    }
                    ?>
                    <div class="row mt-4">
                        <div class="col d-flex justify-content-end gap-3">
                            <a href="shop.php" class="btn btn-light px-4">Continue Shopping</a>
                            <form method="post" action="cart-clear.php" style="display:inline;">
                                <button class="btn btn-dark px-4" type="submit" onclick="return confirm('Clear all items from your cart?');">Clear cart</button>
                            </form>
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
                        <span class="fw-bold fs-4 text-dark">
                            ₱<span id="summaryTotal">
                                <?php echo number_format((float)$cartTotal, 2); ?>
                            </span>
                        </span>
                    </div>
                    <form method="post" action="checkout.php">
                        <input type="hidden" name="cartTotal" value="<?php echo htmlspecialchars($cartTotal); ?>">
                        <button class="btn btn-warning w-100 fw-bold py-2 fs-5" name="btnCheckout" type="submit">Proceed to Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function changeQuantity(btn, delta) {
        const input = btn.parentNode.querySelector('input[type=number]');
        let val = parseInt(input.value) || 1;
        val += delta;
        if (val < 1) val = 1;
        input.value = val;
        updateCartTotal(input);

        // Get the gameId for this row
        const row = btn.closest('.row');
        const removeInput = row.querySelector('input[name="remove_game_id"]');
        if (!removeInput) return;
        const gameId = removeInput.value;

        let http = new XMLHttpRequest();
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Handle the response if needed
                console.log(this.responseText);
            }
        };
        http.open("POST", "cart-update.php?gameId=" + gameId + "&quantity=" + val, true);
        http.send();
    }

    function updateCartTotal(input) {
        const row = input.closest('.row');
        const unitPrice = parseFloat(row.querySelector('.col-2.text-end span[data-unit-price]').getAttribute('data-unit-price')) || 0;
        const quantity = parseInt(input.value) || 1;
        const lineTotal = unitPrice * quantity;
        row.querySelector('.col-2.text-end span[data-line-total]').innerText = Number(lineTotal).toLocaleString(undefined, {
            minimumFractionDigits: 2
        });
        // Update summary total
        const lineTotals = Array.from(document.querySelectorAll('.col-2.text-end span[data-line-total]'));
        const summaryTotal = lineTotals.reduce((total, span) => total + parseFloat(span.innerText.replace(/,/g, '')), 0);
        document.getElementById('summaryTotal').innerText = summaryTotal.toLocaleString(undefined, {
            minimumFractionDigits: 2
        });
    }
</script>
<?php include 'includes/foot.php'; ?>