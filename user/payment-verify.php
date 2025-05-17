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
    $total = $pay['attributes']['amount'];

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

        $paymentParent->appendChild($payment);
        $xml->save('../data/payments.xml');

        $cartxml = new DOMDocument();
        $cartxml->load('../data/carts.xml');
        $carts = $cartxml->getElementsByTagName('cart');
        $cartParent = $cartxml->getElementsByTagName('carts')[0];
        foreach ($carts as $cart) {
            $cartId = $cart->getAttribute('userId');
            if ($cartId == $id) {

                $cartxml->documentElement->removeChild($cart);
                $cartxml->save('../data/carts.xml');
                break;
            }
        }
        echo "<script>alert('Payment Successful.'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Payment Failed.'); window.location.href='cart.php';</script>";
    }
} else {
    echo "<script>alert('Something Went Wrong.'); window.location.href='cart.php';</script>";
}
