<?php

$pageTitle = 'Login';
include 'includes/head.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password using password_verify if passwords are hashed
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row;
            $status = isset($row['status']) ? strtolower(trim($row['status'])) : '';
            if ($status !== 'verified') {
                unset($_SESSION['user']);
                $_SESSION['email'] = $email;
                header('Location: verify.php?email=' . urlencode($email));
                exit;
            }
            if (isset($row['userType']) && $row['userType'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: user/shop.php');
            }
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Invalid email or password.';
    }
    $stmt->close();
}
?>
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: #181c24;">
    <div class="row w-100 justify-content-center align-items-center" style="max-width: 950px; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.25); background: url('assets/images/hero-bg.jpg') no-repeat center center/cover;">
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-start p-5 h-100">
        </div>
        <div class="col-lg-6 col-12 d-flex flex-column justify-content-center align-items-center p-5 bg-dark" style="min-height: 540px;">
            <div class="w-100" style="max-width: 370px;">
                <h2 class="text-white fw-semibold mb-4 text-center">Login</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger text-white"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="d-flex justify-content-end mt-1">
                            <a href="forgotPassword.php" class="small text-danger text-decoration-none">Forgot password?</a>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-danger w-100 mb-3" value="Login">
                </form>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <span class="text-secondary small mx-1">Don't have an account?</span>
                    <a href="signup.php" class="small text-danger text-decoration-none">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>