<?php

$pageTitle = 'Reset Password';
include 'includes/head.php';

$error = '';
$success = '';

if (isset($_POST['codeBtn'])) {
    $email = $_SESSION['email'] ?? '';
    $resetCode = $_POST['reset_code'] ?? '';

    if ($resetCode && $email) {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND otp = ?");
        $stmt->bind_param("ss", $email, $resetCode);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            // Code is valid, proceed with password reset
            $updateStmt = $conn->prepare("UPDATE user SET otp = NULL WHERE email = ?");
            $updateStmt->bind_param("s", $email);
            $updateStmt->execute();
            $updateStmt->close();
            header('Location: resetPassword.php?email=' . urlencode($email));
            exit;
        } else {
            $error = 'Invalid reset code.';
        }
        $stmt->close();
    } else {
        $error = 'Please enter the reset code and make sure your email is provided.';
    }
}

?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: #181c24;">
    <div class="row w-100 justify-content-center align-items-center" style="max-width: 950px; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.25); background: url('assets/images/hero-bg.jpg') no-repeat center center/cover;">
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-start p-5 h-100">
        </div>
        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center align-items-center p-5 bg-dark" style="min-height: 540px;">
            <div class="w-100" style="max-width: 370px;">
                <h2 class="text-white fw-semibold mb-4 text-center">Forgot Password</h2>
                <?php if (isset($error) && $error): ?>
                    <div class="alert alert-danger text-white"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (isset($success) && $success): ?>
                    <div class="alert alert-success text-white"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <form id="codeForm" method="post" action="">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="reset_code" placeholder="Enter 6-digit code" maxlength="6" required>
                    </div>
                    <button type="submit" name="codeBtn" class="btn btn-danger w-100 mb-3">Verify Code</button>
                </form>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <a href="login.php" class="small text-danger text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>