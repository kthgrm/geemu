<?php
include '../config/function.php';
// Get user ID from query parameter
if (
    isset($_POST['btnUserDelete']) &&
    isset($_GET['id']) && is_numeric($_GET['id']) &&
    isset($_POST['admin_password'])
) {
    $adminId = isset($_SESSION['user']['userId']) ? intval($_SESSION['user']['userId']) : 0;
    $userIdToDelete = intval($_GET['id']);
    $password = $_POST['admin_password'];

    // Fetch the admin's hashed password from the database
    $stmt = $conn->prepare("SELECT password FROM user WHERE userId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    if ($stmt->fetch()) {
        $stmt->close();
        // Validate the password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, proceed to delete the requested user
            $stmt = $conn->prepare("DELETE FROM user WHERE userId = ?");
            $stmt->bind_param("i", $userIdToDelete);
            if ($stmt->execute()) {
                echo "<script>alert('User deleted successfully.'); window.location.href='users.php';</script>";
            } else {
                echo "<script>alert('Error deleting user: " . addslashes($conn->error) . "'); window.location.href='users.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='users.php';</script>";
        }
    } else {
        $stmt->close();
        echo "<script>alert('Admin user not found.'); window.location.href='users.php';</script>";
    }
} else {
    echo "Invalid request.";
}
