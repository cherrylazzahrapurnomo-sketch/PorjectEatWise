
<?php
session_start();

$defaults = [
    "calories" => "1800",
    "allergies" => "None",
    "restrictions" => "None",
    "food_type" => "All",
    "diet" => "Healthy & Balanced"
];

if (!isset($_SESSION["criteria"])) {
    $_SESSION["criteria"] = $defaults;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $allowed = [
        "calories" => ["1200", "1500", "1800", "2000", "2200", "2500"],
        "allergies" => ["None", "Seafood", "Peanuts", "Dairy", "Eggs", "Gluten", "Other"],
        "restrictions" => ["None", "Vegetarian", "Vegan", "Gluten Free", "Dairy Free", "Halal"],
        "food_type" => ["All", "Vegetarian", "Non-Vegetarian", "Vegan"],
        "diet" => ["Healthy & Balanced", "Low Calorie", "High Protein", "Low Carb", "Keto"]
    ];

    foreach ($allowed as $key => $options) {
        $value = $_POST[$key] ?? $_SESSION["criteria"][$key];

        if (in_array($value, $options, true)) {
            $_SESSION["criteria"][$key] = $value;
        }
    }

    $_SESSION["criteria_saved"] = true;

    header("Location: select-criteria.php?saved=1");
    exit;
}

$criteria = $_SESSION["criteria"];
$saved = isset($_GET["saved"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Select Criteria | EatWise</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-container">

    <!-- NAVBAR -->
    <header class="navbar">
        <a href="home.php" class="brand">
            <span class="brand-leaf">
                <svg viewBox="0 0 60 50">
                    <path d="M4 44C18 12 32 6 56 4C54 28 36 45 4 44Z"
                          fill="#72844c"/>
                    <path d="M8 42L43 14" stroke="#b2b98a"
                          stroke-width="1.5" fill="none"/>
                </svg>
            </span>
            <span>EatWise</span>
        </a>

        <nav class="nav-links">
            <a href="home.php">HOME</a>
            <a href="categories.php">CATEGORIES</a>
            <a href="cart.php">CARTS</a>
            <a href="profile.php" class="active">ACCOUNT</a>
            <a href="login.php" class="logout">LOGOUT</a>
        </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="criteria-main">

        <div class="page-heading">
            <a href="profile.php" class="back-button"
               aria-label="Back">&#8249;</a>
            <h1>Select Criteria</h1>
        </div>

        <?php if ($saved): ?>
            <div class="success-message">
                Preferences saved successfully!
            </div>
        <?php endif; ?>

        <form method="post" action="select-criteria.php">

            <section class="criteria-card">

                <!-- MAXIMUM CALORIES -->
                <div class="criteria-row">
                    <label for="calories" class="criteria-label">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="5" y="3" width="14" height="18"
                                  rx="2" fill="none" stroke="currentColor"
                                  stroke-width="1.5"/>
                            <circle cx="12" cy="10" r="3"
                                    fill="none" stroke="currentColor"
                                    stroke-width="1.4"/>
                            <path d="M9 16h6M12 13v3"
                                  stroke="currentColor" stroke-width="1.4"/>
                        </svg>
                        <span>Maximum Calories per Day</span>
                    </label>

                    <div class="criteria-control">
                        <select name="calories" id="calories">
                            <?php foreach (["1200","1500","1800","2000","2200","2500"] as $value): ?>
                                <option value="<?= $value ?>"
                                    <?= $criteria["calories"] === $value ? "selected" : "" ?>>
                                    <?= $value ?> kcal
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="chevron"></span>
                    </div>
                </div>

                <!-- FOOD ALLERGIES -->
                <div class="criteria-row">
                    <label for="allergies" class="criteria-label">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"
                                    fill="none" stroke="currentColor"
                                    stroke-width="1.5"/>
                            <path d="M6 6l12 12"
                                  stroke="currentColor"
                                  stroke-width="1.5"/>
                            <path d="M9 8l2 2m2 4 2 2"
                                  stroke="currentColor"
                                  stroke-width="1.3"/>
                        </svg>
                        <span>Food Allergies</span>
                    </label>

                    <div class="criteria-control">
                        <select name="allergies" id="allergies">
                            <?php foreach (["None","Seafood","Peanuts","Dairy","Eggs","Gluten","Other"] as $value): ?>
                                <option value="<?= $value ?>"
                                    <?= $criteria["allergies"] === $value ? "selected" : "" ?>>
                                    <?= $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="chevron"></span>
                    </div>
                </div>

                <!-- FOOD RESTRICTIONS -->
                <div class="criteria-row">
                    <label for="restrictions" class="criteria-label">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 5l16 14M7 3v5m-2-5v5m4-5v5M17 3c-2 2-2 5 0 7v11M14 3v5"
                                  fill="none" stroke="currentColor"
                                  stroke-width="1.4" stroke-linecap="round"/>
                            <path d="M3 12l3-2 3 3-2 4H4z"
                                  fill="none" stroke="currentColor"
                                  stroke-width="1.2"/>
                        </svg>
                        <span>Food restrictions</span>
                    </label>

                    <div class="criteria-control">
                        <select name="restrictions" id="restrictions">
                            <?php foreach (["None","Vegetarian","Vegan","Gluten Free","Dairy Free","Halal"] as $value): ?>
                                <option value="<?= $value ?>"
                                    <?= $criteria["restrictions"] === $value ? "selected" : "" ?>>
                                    <?= $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="chevron"></span>
                    </div>
                </div>

                <!-- FOOD TYPE -->
                <div class="criteria-row">
                    <label for="food_type" class="criteria-label">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="5" y="3" width="14" height="18"
                                  rx="1" fill="none" stroke="currentColor"
                                  stroke-width="1.5"/>
                            <path d="M8 7h8M8 10h8M8 15h5"
                                  stroke="currentColor"
                                  stroke-width="1.3"/>
                            <path d="M3 7v10m-1-10h2"
                                  stroke="currentColor"
                                  stroke-width="1.4"/>
                        </svg>
                        <span>Food type</span>
                    </label>

                    <div class="criteria-control">
                        <select name="food_type" id="food_type">
                            <?php foreach (["All","Vegetarian","Non-Vegetarian","Vegan"] as $value): ?>
                                <option value="<?= $value ?>"
                                    <?= $criteria["food_type"] === $value ? "selected" : "" ?>>
                                    <?= $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="chevron"></span>
                    </div>
                </div>

                <!-- DIET PREFERENCES -->
                <div class="criteria-row">
                    <label for="diet" class="criteria-label">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"
                                    fill="none" stroke="currentColor"
                                    stroke-width="1.5"/>
                            <path d="M15.5 7.5c-5-1-8 3-7 8 4 2 9-2 7-8Z"
                                  fill="none" stroke="currentColor"
                                  stroke-width="1.3"/>
                            <path d="M9 15l5-5"
                                  stroke="currentColor" stroke-width="1.3"/>
                        </svg>
                        <span>Diet preferences</span>
                    </label>

                    <div class="criteria-control">
                        <select name="diet" id="diet">
                            <?php foreach (["Healthy & Balanced","Low Calorie","High Protein","Low Carb","Keto"] as $value): ?>
                                <option value="<?= $value ?>"
                                    <?= $criteria["diet"] === $value ? "selected" : "" ?>>
                                    <?= $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="chevron"></span>
                    </div>
                </div>

            </section>

            <!-- SAVE BUTTON -->
            <button type="submit" class="save-button">
                Save Changes
            </button>

        </form>

    </main>
</div>

</body>
</html>
