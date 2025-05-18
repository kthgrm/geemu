<?php

include 'includes/head.php';

$client = new \GuzzleHttp\Client();

$id = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : '';

if (isset($_SESSION['refNum'])) {
    $refNum = $_SESSION['refNum'];
    $response = $client->request('GET', 'https://api.paymongo.com/v1/links/' . $refNum, [
        'headers' => [
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF9qbTc1NXRKeTJIeXl2Y003dzRQU3hVbkY6',
        ],
    ]);

    $data = json_decode($response->getBody(), true);
    $pay = $data['data'];
    $paymentStatus = $pay['attributes']['status'];
    $paymentRefNum = $pay['attributes']['reference_number'];
    $total = number_format($pay['attributes']['amount'] / 100, 2, '.', '');

    if ($paymentStatus == 'paid' && $paymentRefNum == $refNum) {
        $xml = new DOMDocument();
        $xml->load('../data/payments.xml');
        $payment = $xml->createElement('payment');
        $payments = $xml->getElementsByTagName('payment');
        $paymentParent = $xml->getElementsByTagName('payments')[0];
        $lastId = 0;
        foreach ($payments as $p) {
            $pid = $p->getAttribute('id');
            if (is_numeric($pid) && $pid > $lastId) {
                $lastId = $pid;
            }
        }
        $newId = $lastId + 1;
        $payment->setAttribute('id', $newId);
        $payment->setAttribute('userId', $id);
        $payment->setAttribute('amount', $total);
        $payment->setAttribute('date', date('Y-m-d H:i:s'));

        $cartxml = new DOMDocument();
        $cartxml->load('../data/carts.xml');
        $carts = $cartxml->getElementsByTagName('cart');
        $cartParent = $cartxml->getElementsByTagName('carts')[0];

        // Update games.xml stock
        $gamesXml = new DOMDocument();
        $gamesXml->load('../data/games.xml');
        $games = $gamesXml->getElementsByTagName('game');

        foreach ($carts as $cart) {
            $cartId = $cart->getAttribute('userId');
            if ($cartId == $id) {
                $items = $cart->getElementsByTagName('item');
                foreach ($items as $item) {
                    $gameId = $item->getAttribute('gameId');
                    $qtyBought = (int)$item->getAttribute('quantity');

                    // Save item in payment node
                    $itemNode = $xml->createElement('item');
                    $itemNode->setAttribute('gameId', $gameId);
                    $itemNode->setAttribute('quantity', $qtyBought);
                    $payment->appendChild($itemNode);

                    // Find the game in games.xml and update quantity
                    foreach ($games as $game) {
                        if ($game->getAttribute('id') == $gameId) {
                            $qtyNode = $game->getElementsByTagName('quantity')[0];
                            $currentStock = (int)$qtyNode->nodeValue;
                            $newStock = max(0, $currentStock - $qtyBought);
                            $qtyNode->nodeValue = $newStock;
                            break;
                        }
                    }
                }
                // Save updated games.xml after all items processed
                $gamesXml->save('../data/games.xml');

                // Remove cart after updating stock
                $cartxml->documentElement->removeChild($cart);
                $cartxml->save('../data/carts.xml');
                break;
            }
        }

        $paymentParent->appendChild($payment);
        $xml->save('../data/payments.xml');

        echo "<script>alert('Payment Successful.'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Payment Failed.'); window.location.href='cart.php';</script>";
    }
} else {
    echo "<script>alert('Something Went Wrong.'); window.location.href='cart.php';</script>";
}
