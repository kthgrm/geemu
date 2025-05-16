<?php

if (isset($_SESSION['user'])) {
    if (isset($_SESSION['user']['userType']) && $_SESSION['user']['userType'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: user/shop.php');
    }
    exit;
}
