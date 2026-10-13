
<?php
$food = [
    "name" => "Grilled Chicken Bowl",
    "rating" => "4.5",
    "sold" => 100,
    "likes" => 25,
    "calories" => 420,
    "protein" => 38,
    "carbs" => 40,
    "fat" => 12,
    "price" => 60000,
    "image" => "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=1200&q=90",
    "ingredients" => [
        "Chicken Breast",
        "Quinoa",
        "Mixed Greens",
        "Cherry Tomatoes",
        "Cucumber",
        "Olive Oil",
        "Shrimp Extract",
        "Lemon Juice",
        "Herbs"
    ],
    "tags" => ["Gluten Free", "Low Calorie"]
];

function rupiah($amount) {
    return "Rp. " . number_format($amount, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($food["name"]) ?> | EatWise</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-container">

    <!-- NAVBAR -->
    <header class="navbar">

        <a href="home.php" class="brand">
            <span class="brand-leaf">
                <svg viewBox="0 0 60 50" aria-hidden="true">
                    <path d="M4 44C18 12 32 6 56 4C54 28 36 45 4 44Z"
                          fill="#72844c"/>
                    <path d="M8 42L43 14"
                          stroke="#b2b98a"
                          stroke-width="1.5"
                          fill="none"/>
                </svg>
            </span>
            <span>EatWise</span>
        </a>

        <nav class="nav-links">
            <a href="home.php">HOME</a>
            <a href="categories.php">CATEGORIES</a>
            <a href="cart.php">CARTS</a>
            <a href="profile.php">ACCOUNT</a>
            <a href="login.php" class="logout">LOGOUT</a>
        </nav>

    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <a href="categories.php" class="back-button" aria-label="Back">
            &#8249;
        </a>

        <div class="detail-layout">

            <!-- LEFT: FOOD IMAGE -->
            <section class="image-section">
                <img
                    src="<?= htmlspecialchars($food["image"]) ?>"
                    alt="<?= htmlspecialchars($food["name"]) ?>"
                    class="main-food-image"
                >
            </section>

            <!-- RIGHT: FOOD INFORMATION -->
            <section class="information-section">

                <h1><?= htmlspecialchars($food["name"]) ?></h1>

                <!-- RATING -->
                <div class="rating-row">
                    <span class="stars" aria-label="Rating 4.5 out of 5">
                        ★★★★<span class="half-star">★</span>
                    </span>

                    <span class="rating-number">
                        <?= htmlspecialchars($food["rating"]) ?> (50 Rating)
                    </span>
                </div>

                <!-- SOLD AND LIKES -->
                <div class="social-info">
                    <span><?= $food["sold"] ?> Sold</span>
                    <span class="social-divider">|</span>
                    <span>Liked by <?= $food["likes"] ?></span>
                </div>

                <!-- FOOD TAGS -->
                <div class="food-tags">
                    <?php foreach ($food["tags"] as $tag): ?>
                        <span class="food-tag">
                            <?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>

                <div class="horizontal-line"></div>

                <!-- INGREDIENTS -->
                <section class="ingredients-section">
                    <h2>Ingredients</h2>

                    <p>
                        <?= htmlspecialchars(implode(", ", $food["ingredients"])) ?>.
                    </p>
                </section>

                <!-- NUTRITION FACT -->
                <section class="nutrition-section">

                    <h2>Nutrition Fact</h2>

                    <div class="nutrition-grid">

                        <div class="nutrition-item">
                            <span>Calories</span>
                            <strong><?= $food["calories"] ?> kcal</strong>
                        </div>

                        <div class="nutrition-item">
                            <span>Protein</span>
                            <strong><?= $food["protein"] ?>g</strong>
                        </div>

                        <div class="nutrition-item">
                            <span>Carbs</span>
                            <strong><?= $food["carbs"] ?>g</strong>
                        </div>

                        <div class="nutrition-item">
                            <span>Fat</span>
                            <strong><?= $food["fat"] ?>g</strong>
                        </div>

                    </div>
                </section>

                <!-- PRICE -->
                <div class="price">
                    <?= rupiah($food["price"]) ?>
                </div>

                <!-- ADD TO CART -->
                <form action="allergen-warning.php" method="get">
                    <input
                        type="hidden"
                        name="food"
                        value="grilled-chicken-bowl"
                    >

                    <button type="submit" class="add-to-cart">
                        Add to Cart
                    </button>
                </form>

            </section>

        </div>
    </main>

    <!-- BOTTOM SUMMARY -->
    <footer class="bottom-summary">

        <span class="cart-symbol" aria-hidden="true">
            <svg viewBox="0 0 32 32">
                <path d="M3 5h4l3.5 15h14l4-11H9"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2.5"
                      stroke-linejoin="round"/>
                <circle cx="13" cy="25" r="2.5" fill="currentColor"/>
                <circle cx="23" cy="25" r="2.5" fill="currentColor"/>
            </svg>
        </span>

        <div class="summary-text">
            <span>Total Calories: <strong><?= $food["calories"] ?> kcal</strong></span>
            <span class="summary-divider">|</span>
            <span>Total Payment: <strong><?= rupiah($food["price"]) ?></strong></span>
        </div>

    </footer>

</div>

</body>
</html>
