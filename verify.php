<?php

$pageTitle = 'Verify Email';
include 'includes/head.php';

if (isset($_GET['email']) && $_GET['email']) {
    $_SESSION['email'] = $_GET['email'];
}

if (isset($_POST['btnVerify'])) {
    $verificationCode = $_POST['codeInput'] ?? '';
    $email = $_SESSION['email'] ?? '';
    if ($email) {
        $sql = "SELECT otp FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $storedCode = $row['otp'] ?? '';
        $stmt->close();

        if ($verificationCode && $storedCode) {
            if ($verificationCode === $storedCode) {
                // Verification successful
                $_SESSION['message'] = 'Email verified successfully!';

                $sql = "UPDATE user SET otp = NULL, status = 'verified' WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->close();

                // Remove email from session after successful verification
                unset($_SESSION['email']);
                echo "<script>alert('Verification successful!');</script>";
                header('Location: login.php');
                exit;
            } else {
                $_SESSION['message'] = 'Invalid verification code.';
            }
        } else {
            $_SESSION['message'] = 'Please enter the verification code.';
        }
    } else {
        $_SESSION['message'] = 'Session expired. Please sign up again.';
    }
}
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: #181c24;">
    <div class="row w-100 justify-content-center align-items-center" style="max-width: 950px; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.25); background: url('assets/images/hero-bg.jpg') no-repeat center center/cover;">
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-start p-5 h-100">
        </div>
        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center align-items-center p-5 bg-dark" style="min-height: 540px;">
            <div class="w-100 p-4" style="max-width: 370px;">
                <h2 class="text-white fw-semibold mb-4 text-center">Verify Your Email</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-danger text-white"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                <form method="post" action="verify.php">
                    <div class="mb-3">
                        <input type="text" name="codeInput" id="verification_code" class="form-control" placeholder="Enter Verification Code" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" name="btnVerify">Verify</button>
                </form>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <span class="text-secondary small mx-1">Didn't receive a code?</span>
                    <a href="resend_code.php" class="small text-danger text-decoration-none">Resend Code</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>