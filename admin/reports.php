<?php
$pageTitle = 'Reports';
include 'includes/head.php';
?>
<!-- Example: Display a simple reports dashboard with Bootstrap styling -->
<div class="container">
    <div class="card bg-white text-dark">
        <div class="card-header">
            <h1 class="mb-0">Reports Dashboard</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 my-3">
                    <a href="report-products.php" class="btn btn-danger w-100 h-100 py-4">
                        <i class="fa-solid fa-box-open fa-2x mb-2"></i><br>
                        Products Report
                    </a>
                </div>
                <div class="col-md-4 my-3">
                    <a href="report-users.php" class="btn btn-danger w-100 h-100 py-4">
                        <i class="fa-solid fa-users fa-2x mb-2"></i><br>
                        Users Report
                    </a>
                </div>
                <div class="col-md-4 my-3">
                    <a href="report-payments.php" class="btn btn-danger w-100 h-100 py-4">
                        <i class="fa-solid fa-credit-card fa-2x mb-2"></i><br>
                        Payments Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>