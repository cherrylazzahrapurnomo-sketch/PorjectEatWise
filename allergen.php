
<?php
$food = [
    "name" => "Chicken Bowl",
    "calories" => 420,
    "price" => 60000,
    "carbs" => 40,
    "fat" => 12,
    "allergen" => "Seafood",
    "ingredients" => [
        "Chicken",
        "Mixed Greens",
        "Cherry Tomato",
        "Olive Oil",
        "Shrimp Extract",
        "Seasoning"
    ],
    "allergen_detail" => "E.G. Shrimp, Fish, Oyster Sauce"
];

function rupiah($number) {
    return "Rp. " . number_format($number, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Allergen Warning | EatWise</title>

    <link rel="stylesheet" href="allergen-warning.css">
</head>
<body>

<div class="page-wrapper">

    <!-- NAVIGATION BAR -->
    <header class="navbar">
        <a href="home.php" class="brand">
            <span class="brand-leaf">🍃</span>
            <span>EatWise</span>
        </a>

        <nav class="nav-menu">
            <a href="home.php">HOME</a>
            <a href="categories.php">CATEGORIES</a>
            <a href="cart.php">CARTS</a>
            <a href="profile.php">ACCOUNT</a>
            <a href="login.php" class="logout">LOGOUT</a>
        </nav>
    </header>

    <!-- FOOD DETAIL PAGE -->
    <main class="detail-page">

        <a href="categories.php" class="back-button" aria-label="Go back">
            &#8249;
        </a>

        <div class="detail-layout">

            <!-- FOOD IMAGE -->
            <div class="food-image-wrapper">
                <img
                    src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=1000&q=85"
                    alt="Chicken Bowl"
                    class="food-image"
                >
            </div>

            <!-- FOOD INFORMATION -->
            <section class="food-information">

                <h1><?= htmlspecialchars($food["name"]) ?></h1>

                <div class="calorie-badge">
                    <?= $food["calories"] ?> Kcalorie
                </div>

                <div class="separator"></div>

                <div class="ingredients-preview">
                    <p>
                        Chicken, Mixed Greens, Cherry Tomato,
                        Olive Oil, Shrimp Extract, Seasoning
                    </p>
                </div>

                <div class="nutrition">
                    <div>
                        <span>Carbs</span>
                        <strong><?= $food["carbs"] ?>g</strong>
                    </div>

                    <div class="nutrition-divider"></div>

                    <div>
                        <span>Fat</span>
                        <strong><?= $food["fat"] ?>g</strong>
                    </div>
                </div>

                <a href="cart.php" class="add-to-cart">
                    Add to Cart
                </a>

            </section>

        </div>
    </main>

    <!-- BOTTOM SUMMARY -->
    <footer class="bottom-summary">
        <div class="summary-item">
            <span class="cart-icon">🛒</span>
            <span>Total Calories:</span>
            <strong><?= $food["calories"] ?> kcal</strong>
        </div>

        <span class="summary-divider">|</span>

        <div class="summary-item">
            <span>Total Payment:</span>
            <strong><?= rupiah($food["price"]) ?></strong>
        </div>
    </footer>

    <!-- ALLERGEN WARNING MODAL -->
    <div class="modal-overlay" id="allergenModal">

        <div
            class="warning-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="warningTitle"
        >

            <!-- WARNING ICON -->
            <div class="warning-icon">
                <svg viewBox="0 0 64 58" aria-hidden="true">
                    <path d="M32 2 L62 54 H2 Z" fill="#ff0808"/>
                    <rect x="29" y="18" width="6" height="19"
                          rx="2" fill="#501b1b"/>
                    <circle cx="32" cy="44" r="3" fill="#501b1b"/>
                </svg>
            </div>

            <h2 id="warningTitle">Allergen Warning</h2>

            <div class="warning-description">
                <p>This Food Contains Seafood</p>
                <p>(According To Our Ingredient Data),</p>
                <p>Which Matches Your Allergen Preference.</p>
            </div>

            <!-- DETECTED ALLERGEN -->
            <div class="allergen-card">

                <div class="shrimp-icon" aria-hidden="true">🦐</div>

                <div class="allergen-text">
                    <h3><?= htmlspecialchars($food["allergen"]) ?> Detected</h3>

                    <p>
                        <?= htmlspecialchars($food["allergen_detail"]) ?>
                    </p>
                </div>

            </div>

            <!-- VIEW INGREDIENTS -->
            <button
                type="button"
                class="modal-button primary-button"
                id="viewIngredients"
            >
                View Ingredients
            </button>

            <!-- CHOOSE ANOTHER FOOD -->
            <a href="categories.php" class="modal-button secondary-button">
                Choose Another Food
            </a>

        </div>
    </div>

    <!-- INGREDIENT DETAILS MODAL -->
    <div class="ingredients-overlay" id="ingredientsModal" hidden>
        <div class="ingredients-dialog">

            <button
                type="button"
                class="close-button"
                id="closeIngredients"
                aria-label="Close ingredients"
            >
                &times;
            </button>

            <h2>Food Ingredients</h2>

            <p class="ingredients-note">
                This menu contains seafood-related ingredients.
                Please check each ingredient before ordering.
            </p>

            <ul>
                <?php foreach ($food["ingredients"] as $ingredient): ?>
                    <li>
                        <?= htmlspecialchars($ingredient) ?>

                        <?php if ($ingredient === "Shrimp Extract"): ?>
                            <span class="allergen-label">ALLERGEN</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <button
                type="button"
                class="modal-button secondary-button"
                id="backToWarning"
            >
                Back
            </button>

        </div>
    </div>

</div>

<script>
    const viewIngredients = document.getElementById("viewIngredients");
    const warningModal = document.getElementById("allergenModal");
    const ingredientsModal = document.getElementById("ingredientsModal");
    const closeIngredients = document.getElementById("closeIngredients");
    const backToWarning = document.getElementById("backToWarning");

    viewIngredients.addEventListener("click", () => {
        ingredientsModal.hidden = false;
    });

    function closeIngredientDetails() {
        ingredientsModal.hidden = true;
    }

    closeIngredients.addEventListener("click", closeIngredientDetails);
    backToWarning.addEventListener("click", closeIngredientDetails);

    ingredientsModal.addEventListener("click", (event) => {
        if (event.target === ingredientsModal) {
            closeIngredientDetails();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeIngredientDetails();
        }
    });
</script>

</body>
</html>
