<?php
$pageTitle = 'Users Report';
include 'includes/head.php';
// Get filter inputs
$userType = isset($_GET['user_type']) ? trim($_GET['user_type']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

// Build query
$query = "SELECT userId, email, userType, status FROM user WHERE 1";
$params = [];
$types = '';

if ($userType !== '') {
    $query .= " AND userType = ?";
    $params[] = $userType;
    $types .= 's';
}
if ($status !== '') {
    $query .= " AND status = ?";
    $params[] = $status;
    $types .= 's';
}

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
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
            <h2 class="text-center mb-4">Users Report</h2>

            <!-- Filter Form -->
            <form method="GET" class="filter-form mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="user_type" class="form-label">User Type</label>
                        <select id="user_type" name="user_type" class="form-control">
                            <option value="">All</option>
                            <option value="admin" <?= $userType === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= $userType === 'user' ? 'selected' : '' ?>>User</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">All</option>
                            <option value="verified" <?= $status === 'verified' ? 'selected' : '' ?>>Verified</option>
                            <option value="unverified" <?= $status === 'unverified' ? 'selected' : '' ?>>Unverified</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill">Filter</button>
                        <a href="report-users.php" class="btn btn-secondary flex-fill">Reset</a>
                    </div>
                </div>
            </form>

            <button class="btn btn-primary print-btn mb-4" onclick="printTable()">Print Report</button>
            <div id="print-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>User ID</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['userId']) . "</td>
                                    <td>" . htmlspecialchars($row['email']) . "</td>
                                    <td>" . htmlspecialchars($row['userType']) . "</td>
                                    <td>" . htmlspecialchars($row['status']) . "</td>
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
        window.location.reload();
    }
</script>