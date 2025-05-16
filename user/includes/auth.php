<?php
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['userType']) {
        $role = $_SESSION['user']['userType'];
        $email = $_SESSION['user']['email'];

        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND userType = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($result->num_rows == 0) {
                logoutSession();
                header('Location: ../login.php');
                exit;
            } else {
                $row = $result->fetch_assoc();
                if ($row['userType'] != 'user') {
                    header('Location: ../admin/dashboard.php');
                    exit;
                }
            }
        } else {
            logoutSession();
            header('Location: ../login.php');
            exit;
        }
        $stmt->close();
    }
} else {
    header('Location: ../login.php');
    exit;
}
