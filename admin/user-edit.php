<?php
$pageTitle = 'Edit User';
include 'includes/head.php';

$error = '';
$success = '';
$currentEmail = '';
$currentUserType = '';
$currentStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $email = trim($_POST['user_email']);
    $userType = $_POST['user_type'];
    $status = $_POST['status'];
    $userId = $_GET['id'];

    if ($email === '' || $userType === '' || $status === '') {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE user SET email=?, userType=?, status=? WHERE userId=?");
        $stmt->bind_param("sssi", $email, $userType, $status, $userId);
        if ($stmt->execute()) {
            $success = "User updated successfully!";
            $currentEmail = $email;
            $currentUserType = $userType;
            $currentStatus = $status;
        } else {
            $error = "Failed to update user.";
        }
        $stmt->close();
    }
}

?>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Edit User</h2>
            <?php
            $userId = $_GET['id'];

            // Load current user data for form population (if not just updated)
            if (empty($currentEmail)) {
                $stmt = $conn->prepare("SELECT email, userType, status FROM user WHERE userId=?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $stmt->bind_result($currentEmail, $currentUserType, $currentStatus);
                $stmt->fetch();
                $stmt->close();
            }
            ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="user_email" class="form-label">Email</label>
                    <input type="email" id="user_email" name="user_email" class="form-control" required value="<?php echo isset($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : htmlspecialchars($currentEmail); ?>">
                </div>
                <div class="mb-3">
                    <label for="user_type" class="form-label">User Type</label>
                    <select id="user_type" name="user_type" class="form-select" required>
                        <option value="admin" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'admin') || (isset($currentUserType) && $currentUserType === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'user') || (isset($currentUserType) && $currentUserType === 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="verified" <?php echo (isset($_POST['status']) && $_POST['status'] === 'verified') || (isset($currentStatus) && $currentStatus === 'verified') ? 'selected' : ''; ?>>Verified</option>
                        <option value="unverified" <?php echo (isset($_POST['status']) && $_POST['status'] === 'unverified') || (isset($currentStatus) && $currentStatus === 'unverified') ? 'selected' : ''; ?>>Unverified</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>