<?php
$pageTitle = 'Admin Dashboard';
include 'includes/head.php';
?>

<div class="card mb-4">
    <div class="card-body">
        <h2 class="text-dark">Admin Dashboard</h2>
        <hr>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="fa-solid fa-layer-group"></i> Manage Categories
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <div class="col-md-6 d-grid">
                                <a href="categories.php" class="btn btn-m btn-primary">
                                    <i class="fa-solid fa-list"></i> View Categories
                                </a>
                            </div>
                            <div class="col-md-6 d-grid">
                                <a href="category-add.php" class="btn btn-m btn-outline-primary">
                                    <i class="fa-solid fa-plus-square"></i> Add Category
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <i class="fa-solid fa-cube"></i> Manage Products
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <div class="col-md-6 d-grid">
                                <a href="products.php" class="btn btn-m btn-success">
                                    <i class="fa-solid fa-table"></i> View Products
                                </a>
                            </div>
                            <div class="col-md-6 d-grid">
                                <a href="product-add.php" class="btn btn-m btn-outline-success">
                                    <i class="fa-solid fa-plus-circle"></i> Add Product
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <i class="fa-solid fa-user-gear"></i> User Management
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <div class="col-md-6 d-grid">
                                <a href="users.php" class="btn btn-m btn-info">
                                    <i class="fa-solid fa-address-book"></i> View Users
                                </a>
                            </div>
                            <div class="col-md-6 d-grid">
                                <a href="user-add.php" class="btn btn-m btn-outline-info">
                                    <i class="fa-solid fa-user-plus"></i> Add User
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <i class="fa-solid fa-money-bill-wave"></i> Payments
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <div class="col-md-6 d-grid">
                                <a href="view_payments.php" class="btn btn-m btn-danger">
                                    <i class="fa-solid fa-file-invoice-dollar"></i> View Payments
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>