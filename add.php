<?php
session_start();
require_once 'storage.php'; 

$carsFile = new JsonIO("cars.json");
$storage = new Storage($carsFile);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $transmission = $_POST['transmission'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $passengers = $_POST['passengers'] ?? '';
    $daily_price_huf = $_POST['daily_price_huf'] ?? '';
    $image = trim($_POST['image'] ?? '');

    if (empty($brand) || empty($model) || empty($year) || empty($transmission) || empty($fuel_type) || empty($passengers) || empty($daily_price_huf) || empty($image)) {
        $errors[] = "All fields are required.";
    }

    if (!is_numeric($year) || (int)$year < 1800 || (int)$year > date("Y")) {
        $errors[] = "Please provide a valid year.";
    }

    if (!is_numeric($passengers) || (int)$passengers <= 0) {
        $errors[] = "Passengers must be a positive number.";
    }

    if (!is_numeric($daily_price_huf) || (int)$daily_price_huf <= 0) {
        $errors[] = "Daily price must be a positive number.";
    }

    if (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors[] = "Please provide a valid image URL.";
    }

    if (empty($errors)) {
        $newCar = [
            "brand" => $brand,
            "model" => $model,
            "year" => (int)$year,
            "transmission" => $transmission,
            "fuel_type" => $fuel_type,
            "passengers" => (int)$passengers,
            "daily_price_huf" => (int)$daily_price_huf,
            "image" => $image
        ];

        $storage->add($newCar);
        $successMessage = "New car added successfully!";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Add Car</title>
</head>
<body>
<div class="navbar">
    <div class="logo">iKarRental</div>
    <div class="nav-links">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="profile.php" class="profile-btn">
            <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp" alt="Profile Icon" class="profile-icon">
            <span><?php echo $_SESSION['user']['full_name']; ?></span>
            </a>
            <a href="logout.php" class="btn">Logout</a>
        <?php endif; ?>
        <a href="index.php" class="btn">Home</a>
    </div>
</div>
<div class="hero">
    <h1>Add Car</h1>


    <?php if (isset($successMessage)): ?>
        <p><?= $successMessage ?></p>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <section class="filters">
        <div class="form-container">
            <form method="POST">
                <div class="form-item">
                    <label for="brand">Brand:</label>
                    <input type="text" id="brand" name="brand">
                </div>

                <div class="form-item">
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model">
                </div>

                <div class="form-item">
                    <label for="year">Year:</label>
                    <input type="number" id="year" name="year">
                </div>

                <div class="form-item">
                    <label for="transmission">Transmission:</label>
                    <select id="transmission" name="transmission">
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                    </select>
                </div>

                <div class="form-item">
                    <label for="fuel_type">Fuel Type:</label>
                    <select id="fuel_type" name="fuel_type">
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Electric">Electric</option>
                    </select>
                </div>

                <div class="form-item">
                    <label for="passengers">Passengers:</label>
                    <input type="number" id="passengers" name="passengers">
                </div>

                <div class="form-item">
                    <label for="daily_price_huf">Daily Price (HUF):</label>
                    <input type="number" id="daily_price_huf" name="daily_price_huf">
                </div>

                <div class="form-item">
                    <label for="image">Image URL:</label>
                    <input type="url" id="image" name="image">
                </div>

                <div class="form-item">
                    <button type="submit" class="form-btn">Add Car</button>
                </div>
            </form>
        </div>
    </section>
</div>   
</body>
</html>
