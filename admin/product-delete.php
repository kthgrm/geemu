<?php
$xml = new DOMDocument();
$xml->load('../data/games.xml');
$games = $xml->getElementsByTagName('game');

$id = $_GET['id'];

foreach ($games as $game) {
    if ($game->getAttribute('id') == $id) {
        // Remove associated image
        $imageNode = $game->getElementsByTagName('image')->item(0);
        if ($imageNode) {
            $imagePath = '../assets/images/cover/' . $imageNode->nodeValue;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $xml->documentElement->removeChild($game);
        $xml->save('../data/games.xml');
        echo '<script>alert("Product deleted successfully!"); window.location.href="products.php";</script>';
        break;
    }
}
