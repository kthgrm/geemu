<?php
$pageTitle = 'Products';
include 'includes/head.php';
?>

<div class="card mb-4">
    <div class="card-body">
        <div class="row d-flex justify-content-between align-items-center mb-3">
            <div class="col-auto">
                <h2 class="text-dark mb-0">Payments</h2>
            </div>
        </div>
        <hr>
        <table id="myTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $xml = new DOMDocument();
                $xml->load('../data/payments.xml');

                $payments = $xml->getElementsByTagName('payment');

                foreach ($payments as $payment) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($payment->getAttribute('id')) . '</td>';
                    echo '<td>' . htmlspecialchars($payment->getAttribute('userId')) . '</td>';
                    echo '<td>' . htmlspecialchars($payment->getAttribute('amount')) . '</td>';
                    echo '<td>' . htmlspecialchars($payment->getAttribute('date')) . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/foot.php'; ?>