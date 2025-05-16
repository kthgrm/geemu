<?php

$pageTitle = 'Reset Password';
include 'includes/head.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetBtn'])) {
    $email = $_SESSION['email'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            // Update the password in the database
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $updateStmt = $conn->prepare("UPDATE user SET password = ?, otp = NULL WHERE email = ?");
            $updateStmt->bind_param("ss", $hashed_password, $email);
            if ($updateStmt->execute()) {
                $success = 'Password reset successfully. You can now log in.';
                header('Location: login.php');
                exit;
            } else {
                $error = 'Something went wrong while resetting your password.';
            }
            $updateStmt->close();
        } else {
            $error = 'Email not found.';
        }
    }
}
?>
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: #181c24;">
    <div class="row w-100 justify-content-center align-items-center" style="max-width: 950px; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.25); background: url('assets/images/hero-bg.jpg') no-repeat center center/cover;">
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-start p-5 h-100">
        </div>
        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center align-items-center p-5 bg-dark" style="min-height: 540px;">
            <div class="w-100" style="max-width: 370px;">
                <h2 class="text-white fw-semibold mb-4 text-center">Reset Password</h2>
                <?php if (isset($error) && $error): ?>
                    <div class="alert alert-danger text-white"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (isset($success) && $success): ?>
                    <div class="alert alert-success text-white"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <form id="resetForm" method="post" action="resetPassword.php">
                    <div class="mb-3">
                        <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" name="resetBtn" class="btn btn-danger w-100 mb-3">Reset Password</button>
                </form>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <a href="login.php" class="small text-danger text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>