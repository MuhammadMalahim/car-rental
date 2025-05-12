<?php
session_start();
require_once 'storage.php';

$usersFile = new JsonIO("users.json");
$storage = new Storage($usersFile);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $errors[] = "Both fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    } else {
        $user = $storage->findOne(['email' => $email]);
        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Invalid credentials.";
        } else {
            $_SESSION['user'] = $user; 
            header("Location: index.php");
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
    <title>Login</title>
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
    <h1>Login</h1>
    
    <?php foreach ($errors as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>
    

    <section class="filters">
        <form method="post">
            <div class="form-container">    
                <div class="form-item">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="user@exaple.com">
                </div>
                <div class="form-item">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="******">
                </div>
                
                <div class="form-item">
                    <button type="submit" class="form-btn">Login</button>
                </div>
                
            <div>
        </form>
    </section>

    <a href="register.php">Don't have an account? Register</a>
    
</div>
</body>
</html>
