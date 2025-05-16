<?php
$pageTitle = 'Add User';
include 'includes/head.php';
$error = '';
$success = '';
// Handle form submission
if (isset($_POST['btnAddUser'])) {
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $status = $_POST['status'] ?? '';

    // Generate a random default password
    $password = bin2hex(random_bytes(4)); // 8 hex chars

    if (empty($email) || empty($role) || empty($status)) {
        $error = 'All fields are required.';
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Email already exists.' . $email;
        } else {
            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO user (email, userType, status, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $email, $role, $status, $hashedPassword);
            if ($stmt->execute()) {
                // Send email with password
                $subject = "Your Account Has Been Created";
                $body = "
                    <h2>Your Account Has Been Created</h2>
                    <p>Email: <strong>{$email}</strong></p>
                    <p>Password: <strong>{$password}</strong></p>
                    <p>Please change your password after logging in.</p>
                    <hr>
                ";
                if (sendMail($email, $subject, $body)) {
                    $success = 'User added successfully! Password sent to email.';
                } else {
                    $success = 'User added, but failed to send email.';
                }
            } else {
                $error = 'Failed to add user.';
            }
        }
        $stmt->close();
    }
}
?>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Add User</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="verified">Verified</option>
                        <option value="unverified">Unverified</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" name="btnAddUser" class="btn btn-danger">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>