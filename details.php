<!DOCTYPE html>
<?php

session_start();

$carId = $_GET['id'] ?? null;

$jsonFile = file_get_contents("cars.json");
$cars = json_decode($jsonFile, true);

$selectedCar = null;
foreach ($cars as $car) {
    if ($car['id'] == $carId) {
        $selectedCar = $car;
        break;
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">iKarRental</div>

            <div class="nav-links">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="profile.php" class="profile-btn">
                    <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp" alt="Profile Icon" class="profile-icon">
                    <span><?php echo $_SESSION['user']['full_name']; ?></span>
                    </a>
                    <a href="logout.php" class="btn">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Login</a>
                <?php endif; ?>
                <a href="index.php" class="btn">Home</a>
            </div>
            
        </nav>
    </header>

    <main>
        <section class="card-details">
            <div class="card-card">
                <div class="card-image">
                    <img src="<?= $selectedCar['image'] ?>" alt="<?= $selectedCar['brand'] . ' ' . $selectedCar['model'] ?>">
                </div>
                <div class="card-info">
                    <h1><?= $selectedCar['brand'] . ' ' . $selectedCar['model'] ?></h1>
                    <p><strong>Fuel:</strong> <?= $selectedCar['fuel_type'] ?></p>
                    <p><strong>Gear:</strong> <?= $selectedCar['transmission'] ?></p>
                    <p><strong>Year of manufacture:</strong> <?= $selectedCar['year'] ?></p>
                    <p><strong>Number of seats:</strong> <?= $selectedCar['passengers'] ?></p>
                    <h2 class="price">HUF <?= number_format($selectedCar['daily_price_huf']) ?>/day</h2>
                    
                    <button class="form-btn">Select a date</button>
                    
                    <?php if (!isset($_SESSION['user']) || empty($_SESSION['user'])): ?>
                        <a href="register.php">
                            <button class="form-btn">Book</button>
                        </a>
                    <?php else: ?>
                        <button class="form-btn">Book</button>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>
</body>
</html>
