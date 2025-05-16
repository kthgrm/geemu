<?php

include 'includes/head.php';
// include '../includes/foot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logoutSession();
}
?>

<form action="" method="post">
    <button type="submit">Logout</button>
</form>