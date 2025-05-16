<?php

$pageTitle = 'Forgot Password';
include 'includes/head.php';

$error = '';
$success = '';

if (isset($_POST['sendBtn'])) {
    $email = $_POST['email'] ?? '';

    $error = '';
    $success = '';

    if ($email) {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $_SESSION['email'] = $email;
            $row = $result->fetch_assoc();
            // Generate a 6-digit reset code
            $resetCode = random_int(100000, 999999);
            // Update the database with the reset code
            $stmt = $conn->prepare("UPDATE user SET otp = ? WHERE email = ?");
            $stmt->bind_param("is", $resetCode, $email);
            if ($stmt->execute()) {
                sendMail(
                    $email,
                    'Password Reset',
                    "
                        <h2>Your Password Reset Code</h2>
                        <div style='font-size:2em; font-weight:bold; color:#dc3545; text-align:center; margin:24px 0;'>{$resetCode}</div>
                        <p>Enter this 6-digit code on the website to reset your password.</p>
                        <hr>
                    "
                );
                $success = 'A password reset link has been sent to your email.';
                header('Location: resetCode.php');
                exit;
            } else {
                $error = 'Something went wrong while sending the reset link.';
            }
        } else {
            $error = 'Email not found.';
        }
        $stmt->close();
    } else {
        $error = 'Please enter your email.';
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
                    <div class="alert alert-info text-black"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <form method="post" action="" id="forgotForm">
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required autofocus>
                    </div>
                    <input type="submit" name="sendBtn" id="sendBtn" class="btn btn-danger w-100 mb-3" value="Send Reset Code">
                </form>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <a href="login.php" class="small text-danger text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>