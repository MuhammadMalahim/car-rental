<?php
session_start();

$jsonFile = file_get_contents("cars.json");
$cars = json_decode($jsonFile, true);

$filteredCars = $cars;

if (isset($_GET['seats']) && $_GET['seats'] > 0) {
    $filteredCars = array_filter($filteredCars, function($car) {
        return $car['passengers'] == $_GET['seats'];
    });
}

if (isset($_GET['gear']) && $_GET['gear'] !== 'any') {
    $filteredCars = array_filter($filteredCars, function($car) {
        return strtolower($car['transmission']) === strtolower($_GET['gear']);
    });
}

if (isset($_GET['price-min']) && $_GET['price-min'] >= 0) {
    $filteredCars = array_filter($filteredCars, function($car) {
        return $car['daily_price_huf'] >= $_GET['price-min'];
    });
}

if (isset($_GET['price-max']) && $_GET['price-max'] >= 0) {
    $filteredCars = array_filter($filteredCars, function($car) {
        return $car['daily_price_huf'] <= $_GET['price-max'];
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental</title>
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
                    <span><?php echo ($_SESSION['user']['full_name']) ?></span>
                    </a>
                    <a href="logout.php" class="btn">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
        <h1>Rent cars easily!</h1>
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="register.php" class="cta-btn">Registration</a>
        <?php else: ?>
            <p>Logged in as <?php echo ($_SESSION['user']['full_name']) ?></p>
        <?php endif; ?>
        </section>

        <section class="filters">
            <div class="form-container">
                <form method="GET" action="">
                    <div class="form-item">
                        <label for="seats">Seats</label>
                        <input type="number" id="seats" name="seats" value="<?= isset($_GET['seats']) ? $_GET['seats'] : 1 ?>" min="1">
                    </div>

                    <div class="form-item">
                        <label for="from">From</label>
                        <input type="date" id="from">
                    </div>

                    <div class="form-item">
                        <label for="until">Until</label>
                        <input type="date" id="until">
                    </div>

                    <div class="form-item">
                        <label for="gear">Gear Type</label>
                        <select id="gear" name="gear">
                            <option value="any" <?= (isset($_GET['gear']) && $_GET['gear'] === 'any') ? 'selected' : '' ?>>Any</option>
                            <option value="automatic" <?= (isset($_GET['gear']) && $_GET['gear'] === 'automatic') ? 'selected' : '' ?>>Automatic</option>
                            <option value="manual" <?= (isset($_GET['gear']) && $_GET['gear'] === 'manual') ? 'selected' : '' ?>>Manual</option>
                        </select>
                    </div>

                    <div class="form-item">
                        <label for="price">Price (Ft)</label>
                        <div>
                            <input type="number" id="price-min" name="price-min" placeholder="Min" min="0" value="<?= isset($_GET['price-min']) ? $_GET['price-min'] : '' ?>">
                            <span>-</span>
                            <input type="number" id="price-max" name="price-max" placeholder="Max" min="0" value="<?= isset($_GET['price-max']) ? $_GET['price-max'] : '' ?>">
                        </div>
                    </div>

                    <div class="form-item">
                        <button type="submit" class="form-btn">Filter</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="card-list">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
                <div class="card-card">
                    <a href="add.php">
                      <img src="add.webp" alt="Add" width="50" height="50">
                    </a>
                    <div class="card-info">
                        <a href="add.php">
                            <button class="book-btn">Add</button>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($filteredCars)): ?>
                <p>No cars found based on the selected filters.</p>
            <?php else: ?>
                <?php foreach ($filteredCars as $car): ?>
                    <div class="card-card">
                        <a href="details.php?id=<?= $car['id'] ?>">
                            <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                        </a>
                        <div class="card-info">
                            <h3><?= $car['brand'] . ' ' . $car['model'] ?></h3>
                            <p><?= $car['passengers'] ?> seats - <?= $car['transmission'] ?></p>
                            <p class="price"><?= number_format($car['daily_price_huf']) ?> Ft</p>
                            <a href="details.php?id=<?= $car['id'] ?>">
                                <button class="book-btn">Book</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
