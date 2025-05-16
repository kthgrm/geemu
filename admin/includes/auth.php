<?php
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['userType']) {
        $role = $_SESSION['user']['userType'];
        $email = $_SESSION['user']['email'];

        $query = "SELECT * FROM user WHERE email = '$email' AND userType = '$role' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) == 0) {
                logoutSession();
                header('Location: ../login.php');
                exit;
            } else {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if ($row['userType'] != 'admin') {
                    header('Location: ../user/shop.php');
                    exit;
                }
            }
        } else {
            logoutSession();
            header('Location: ../login.php');
            exit;
        }
    }
} else {
    header('Location: ../login.php');
    exit;
}
