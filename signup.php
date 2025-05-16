<?php

$pageTitle = 'Signup';
include 'includes/head.php';


if (isset($_POST['btnSignup'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($email && $password && $confirmPassword) {
        if ($password !== $confirmPassword) {
            $_SESSION['message'] = 'Passwords do not match.';
            header('Location: signup.php');
            exit;
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                $recaptchaSecret = $_ENV['RECAPTCHA_SECRET_KEY'];
                $recaptchaResponse = $_POST['g-recaptcha-response'];

                // Verify the reCAPTCHA response
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
                $responseKeys = json_decode($response, true);

                if ($responseKeys['success'] == true) {
                    $_SESSION['email'] = $email;
                    // Generate a 6-digit verification code
                    $verificationCode = random_int(100000, 999999);
                    // Proceed with the signup process
                    $query = "INSERT INTO user (email, password, otp) VALUES ('$email', '$hashedPassword', '$verificationCode')";
                    $result = mysqli_query($conn, $query);
                    if (!$result) {
                        $_SESSION['message'] = 'Something went wrong while signing up.';
                        header('Location: signup.php');
                        exit;
                    }

                    sendMail(
                        $email,
                        'Email Verification',
                        "
                            <h2>Your Verification Code</h2>
                            <div style='font-size:2em; font-weight:bold; color:#dc3545; text-align:center; margin:24px 0;'>{$verificationCode}</div>
                            <p>Enter this 6-digit code on the website to verify your email address.</p>
                            <hr>
                        "
                    );
                    echo "<script>alert('A verification code has been sent to your email. Please check your inbox.');</script>";
                    header('Location: verify.php?email=' . urlencode($email));
                    exit;
                } else {
                    $_SESSION['message'] = 'Something Went Wrong.' . $responseKeys['error-codes'][0];
                }
            } else {
                $_SESSION['message'] = 'Please complete the reCAPTCHA.';
            }
        }
    } else {
        $_SESSION['message'] = 'Please fill in all fields.';
    }
}

?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: #181c24;">
    <div class="row w-100 justify-content-center align-items-center" style="max-width: 950px; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.25); background: url('assets/images/hero-bg.jpg') no-repeat center center/cover;">
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-start p-5 h-100">
        </div>
        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center align-items-center p-5 bg-dark" style="min-height: 540px;">
            <div class="w-100 p-4" style="max-width: 370px;">
                <h2 class="text-white fw-semibold mb-4 text-center">Sign Up</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-danger text-white"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="g-recaptcha" data-sitekey="<?= $_ENV['RECAPTCHA_SITE_KEY'] ?>"></div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" name="btnSignup">Signup</button>
                </form>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <span class="text-secondary small mx-1">Already have an account?</span>
                    <a href="login.php" class="small text-danger text-decoration-none">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>