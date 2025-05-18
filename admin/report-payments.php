<?php
$pageTitle = 'Payments Report';
include 'includes/head.php';

// Load XML
$xml = new DOMDocument();
$xml->load('../data/payments.xml');
$payments = $xml->getElementsByTagName('payment');

// Get filter inputs
$startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
$minAmount = isset($_GET['min_amount']) && is_numeric($_GET['min_amount']) ? (float)$_GET['min_amount'] : null;
?>

<style>
    @media print {

        .print-btn,
        .filter-form {
            display: none !important;
        }
    }
</style>

<div class="container">
    <div class="card mt-4">
        <div class="card-body">
            <h2 class="text-center mb-4">Payments Report</h2>

            <!-- Filter Form -->
            <form method="GET" class="filter-form mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="min_amount" class="form-label">Min Amount</label>
                        <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount" value="<?= htmlspecialchars($minAmount) ?>" min="0" step="0.01">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill">Filter</button>
                        <a href="report-payments.php" class="btn btn-secondary flex-fill">Reset</a>
                    </div>
                </div>
            </form>

            <button class="btn btn-primary print-btn mb-4" onclick="printTable()">Print Report</button>
            <div id="print-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Id</th>
                                <th>User ID</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($payments as $payment) {
                                $userId = $payment->getAttribute('userId');
                                $email = '';
                                if ($userId) {
                                    $stmt = $conn->prepare("SELECT email FROM user WHERE userId = ?");
                                    $stmt->bind_param("s", $userId);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $email = $row['email'];
                                    }
                                    $stmt->close();
                                }
                                $amount = (float)$payment->getAttribute('amount');
                                $date = $payment->getAttribute('date');

                                // Normalize date to Y-m-d for comparison
                                $paymentDate = date('Y-m-d', strtotime($date));

                                // Apply filters
                                if ($startDate && $paymentDate < $startDate) {
                                    continue;
                                }
                                if ($endDate && $paymentDate > $endDate) {
                                    continue;
                                }
                                if ($minAmount !== null && $amount < $minAmount) {
                                    continue;
                                }

                                echo "<tr>
                                <td>" . htmlspecialchars($payment->getAttribute('id')) . "</td>
                                <td>" . htmlspecialchars($userId) . "</td>
                                <td>" . htmlspecialchars($email) . "</td>
                                <td>" . htmlspecialchars($amount) . "</td>
                                <td>" . htmlspecialchars($date) . "</td>
                            </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printTable() {
        const printContents = document.getElementById('print-area').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload(); // Optional: refresh to restore event bindings
    }
</script>