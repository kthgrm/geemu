<?php
include 'includes/head.php';

$userId = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : '';

$paymentsXml = new DOMDocument();
$paymentsXml->load('../data/payments.xml');
$payments = $paymentsXml->getElementsByTagName('payment');

$gamesXml = new DOMDocument();
$gamesXml->load('../data/games.xml');
$games = [];
foreach ($gamesXml->getElementsByTagName('game') as $game) {
    $games[$game->getAttribute('id')] = [
        'title' => $game->getElementsByTagName('title')[0]->nodeValue,
        'image' => $game->getElementsByTagName('image')[0]->nodeValue,
        'price' => $game->getElementsByTagName('price')[0]->nodeValue,
        'category' => $game->getElementsByTagName('category')[0]->nodeValue,
    ];
}
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Purchase History</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <?php
            $hasPurchases = false;
            foreach ($payments as $payment) {
                if ($payment->getAttribute('userId') != $userId) continue;
                $hasPurchases = true;
                $date = $payment->getAttribute('date');
                $amount = $payment->getAttribute('amount');
                $items = $payment->getElementsByTagName('item');
            ?>
                <div class="mb-4 border-bottom pb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold">Order #<?= htmlspecialchars($payment->getAttribute('id')) ?></span>
                            <span class="text-muted ms-3"><?= htmlspecialchars($date) ?></span>
                        </div>
                        <span class="fw-bold text-success">₱<?= number_format((float)$amount, 2) ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Game</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($items as $item) {
                                    $gameId = $item->getAttribute('gameId');
                                    $qty = (int)$item->getAttribute('quantity');
                                    if (!isset($games[$gameId])) continue;
                                    $game = $games[$gameId];
                                    $subtotal = $game['price'] * $qty;
                                ?>
                                    <tr>
                                        <td>
                                            <img src="../assets/images/cover/<?= htmlspecialchars($game['image']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" style="width:60px; height:auto; object-fit:contain;">
                                        </td>
                                        <td><?= htmlspecialchars($game['title']) ?></td>
                                        <td><?= htmlspecialchars($game['category']) ?></td>
                                        <td>₱<?= number_format((float)$game['price'], 2) ?></td>
                                        <td><?= $qty ?></td>
                                        <td>₱<?= number_format((float)$subtotal, 2) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            }
            if (!$hasPurchases) {
                echo '<div class="alert alert-info text-center mb-0">No purchases found.</div>';
            }
            ?>
        </div>
    </div>
</div>
<?php include 'includes/foot.php'; ?>