<?php

include '../config/function.php';

$id = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : '';

if (!$id || !isset($_GET['gameId']) || !isset($_GET['quantity'])) {
    http_response_code(400);
    echo "Missing parameters.";
    exit;
}

$gameId = htmlspecialchars($_GET['gameId']);
$quantity = (int) $_GET['quantity'];

$xml = new DOMDocument();
$xml->load('../data/carts.xml');
$carts = $xml->getElementsByTagName('cart');

foreach ($carts as $cart) {
    if ($cart->getAttribute('userId') == $id) {
        $items = $cart->getElementsByTagName('item');
        foreach ($items as $item) {
            if ($item->getAttribute('gameId') == $gameId) {
                $item->setAttribute('quantity', max(1, $quantity));
                $xml->save('../data/carts.xml');
                echo "Quantity updated.";
                exit;
            }
        }
    }
}

http_response_code(404);
echo "Item not found.";
