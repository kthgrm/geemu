<?php

include 'includes/head.php';

use GuzzleHttp\Exception\ClientException;

$client = new \GuzzleHttp\Client();

$id = isset($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : '';
$total = isset($_POST['cartTotal']) ? $_POST['cartTotal'] : 0;

try {
    if (isset($_POST['btnCheckout'])) {
        if ($id && $total > 0) {
            $amount = $total * 100;
            $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
                'body' => json_encode([
                    'data' => [
                        'attributes' => [
                            'amount' => $amount,
                            'description' => 'Geemu Games Payment'
                        ]
                    ]
                ]),
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => 'Basic c2tfdGVzdF9qbTc1NXRKeTJIeXl2Y003dzRQU3hVbkY6',
                    'content-type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $url = $data['data']['attributes']['checkout_url'];
            $refNum = $data['data']['attributes']['reference_number'];
            $_SESSION['refNum'] = $refNum;
            echo "<script>alert('Open Payment link in a new tab.');</script>";
            echo "<script>window.open('$url', '_blank');</script>";
        } else {
            echo "<script>alert('No items to checkout.');</script>";
            echo "<script>window.location.href = 'shop.php';</script>";
        }
    }
} catch (ClientException $e) {
    // Handle the exception
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    echo "<script>window.location.href = 'shop.php';</script>";
}

?>

<div class="container">
    <div class="col-md-12 d-flex justify-content-center align-items-center" style="min-height: 60vh;">
        <div class="card shadow-lg" style="max-width: 400px; width: 100%;">
            <div class="card-header bg-danger text-white text-center">
                <h4 class="font-weight-bolder mb-0">Payment</h4>
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                <a href='payment-verify.php' class="btn btn-danger btn-lg px-4 py-2 mt-2">
                    Verify Payment
                </a>
            </div>
        </div>
    </div>
</div>