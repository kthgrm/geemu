<?php

require '../config/function.php';
include 'includes/auth.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if (isset($pageTitle)) {
            echo $pageTitle;
        } else {
            echo 'Geemu';
        }
        ?>
    </title>
    <link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/enterprise.js?render=<?php echo getenv('RECAPTCHA_SECRET_KEY'); ?>"></script>
</head>

<body>