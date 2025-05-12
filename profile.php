<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="navbar">
    <div class="logo">iKarRental</div>
    <div class="nav-links">
        <a href="logout.php" class="btn">Logout</a>
        <a href="index.php" class="btn">Home</a>
    </div>
</div>

<div class="hero">
    <h1><?= $_SESSION['user']['full_name'] ?></h1>
    <p>Email: <?= $_SESSION['user']['email'] ?></p>
</div>

</body>
</html>
