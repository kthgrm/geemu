<?php

include '../config/function.php';

$id = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : '';

if ($id) {
    $xmlFile = '../data/carts.xml';
    if (file_exists($xmlFile)) {
        $xml = new DOMDocument();
        $xml->load($xmlFile);
        $carts = $xml->getElementsByTagName('cart');
        foreach ($carts as $cart) {
            if ($cart->getAttribute('userId') == $id) {
                $itemParent = $cart->getElementsByTagName('items')[0];
                $items = $itemParent->getElementsByTagName('item');
                for ($i = $items->length - 1; $i >= 0; $i--) {
                    $itemParent->removeChild($items->item($i));
                }
                $xml->save($xmlFile);
                header('Location: cart.php');
                exit();
            }
        }
    }
}
