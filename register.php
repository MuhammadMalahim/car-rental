<?php
session_start();
require_once 'storage.php';

$usersFile = new JsonIO("users.json");
$storage = new Storage($usersFile);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($fullName) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (!preg_match('/[a-zA-Z]/', $fullName)) {
        $errors[] = "Full name must contain at least one alphabetic character.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    } else {
        $existingUser = $storage->findOne(['email' => $email]);
        if ($existingUser) {
            $errors[] = "User with this email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $storage->add(["full_name" => $fullName, "email" => $email, "password" => $hashedPassword, "is_admin" => false]);
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="navbar">
    <div class="logo">iKarRental</div>
    <div class="nav-links">
        <a href="index.php" class="btn">Home</a>
    </div>
</div>
<div class="hero">

    <h1>Register</h1>
    
    <?php foreach ($errors as $error): ?>
        <p class="error"><?= $error ?></p>
    <?php endforeach; ?>

    <section class="filters">
        <form method="post">
            <div class="form-container">    

                <div class="form-item">
                    <label for="full_name">Full Name:</label>
                    <input type="text" name="full_name" id="full_name" placeholder="type name here">
                </div>
                    
                <div class="form-item">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="user@exaple.com">
                </div>

                <div class="form-item">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="*****">
                </div>

                <div class="form-item">
                    <button type="submit" class="form-btn">Register</button>
                </div>
            
            <div>
        </form>
    </section>

    <a href="login.php">Already have an account? Login</a>
    
</div>
</body>
</html>
