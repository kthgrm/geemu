<?php
include 'includes/head.php';

// Get the game ID and quantity from POST
$gameId = isset($_POST['game_id']) ? htmlspecialchars($_POST['game_id']) : '';
$quantityToAdd = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;
$id = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : null;

$xml = new DOMDocument();
$xml->load('../data/carts.xml');
$carts = $xml->getElementsByTagName('cart');

$cartFound = false;
foreach ($carts as $cart) {
    $cartUserId = $cart->getAttribute('userId');
    if ($cartUserId == $id) {
        $cartFound = true;
        $cartItems = $cart->getElementsByTagName('item');
        foreach ($cartItems as $cartItem) {
            $cartGameId = $cartItem->getAttribute('gameId');
            if ($cartGameId == $gameId) {
                // Game already in cart, increase quantity
                $quantity = $cartItem->getAttribute('quantity');
                $newQuantity = $quantity ? intval($quantity) + $quantityToAdd : $quantityToAdd;
                $cartItem->setAttribute('quantity', $newQuantity);
                $xml->save('../data/carts.xml');
                echo "<script>alert('Added to cart successfully!'); window.location.href='shop.php';</script>";
                exit();
            }
        }
        // Game not in cart, add new item
        $newItem = $xml->createElement('item');
        $newItem->setAttribute('gameId', $gameId);
        $newItem->setAttribute('quantity', $quantityToAdd);
        $items = $cart->getElementsByTagName('items')[0];
        $items->appendChild($newItem);
        $xml->save('../data/carts.xml');
        echo "<script>alert('Added to cart successfully!'); window.location.href='shop.php';</script>";
        exit();
    }
}

// If no cart found for user, create a new cart
if (!$cartFound && $id !== null && $gameId !== '') {
    $newCart = $xml->createElement('cart');
    $newCart->setAttribute('userId', $id);

    $items = $xml->createElement('items');
    $newItem = $xml->createElement('item');
    $newItem->setAttribute('gameId', $gameId);
    $newItem->setAttribute('quantity', $quantityToAdd);

    $items->appendChild($newItem);
    $newCart->appendChild($items);

    $xml->documentElement->appendChild($newCart);
    $xml->save('../data/carts.xml');
    echo "<script>alert('Added to cart successfully!'); window.location.href='shop.php';</script>";
    exit();
}
