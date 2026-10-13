
<?php
session_start();

$defaultPreferences = [
    "allergens" => ["Seafood"],
    "other_allergen" => "",
    "dietary" => [
        "gluten_free" => true,
        "sugar_free" => true,
        "low_calorie" => false,
        "vegetarian" => false
    ]
];

if (!isset($_SESSION["edit_preferences"])) {
    $_SESSION["edit_preferences"] = $defaultPreferences;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $allowedAllergens = [
        "Seafood", "Shellfish", "Fish", "Peanuts", "Tree Nuts"
    ];

    $submittedAllergens = $_POST["allergens"] ?? [];
    $submittedAllergens = is_array($submittedAllergens)
        ? $submittedAllergens : [];

    $_SESSION["edit_preferences"] = [
        "allergens" => array_values(array_intersect(
            $allowedAllergens,
            $submittedAllergens
        )),
        "other_allergen" => trim(
            substr((string)($_POST["other_allergen"] ?? ""), 0, 100)
        ),
        "dietary" => [
            "gluten_free" => isset($_POST["gluten_free"]),
            "sugar_free" => isset($_POST["sugar_free"]),
            "low_calorie" => isset($_POST["low_calorie"]),
            "vegetarian" => isset($_POST["vegetarian"])
        ]
    ];

    header("Location: edit-preferences.php?saved=1");
    exit;
}

$prefs = $_SESSION["edit_preferences"];
$selectedAllergens = $prefs["allergens"];
$dietary = $prefs["dietary"];
$otherAllergen = $prefs["other_allergen"];
$saved = isset($_GET["saved"]);

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$allergens = [
    "Seafood",
    "Shellfish",
    "Fish",
    "Peanuts",
    "Tree Nuts"
];

$dietOptions = [
    "gluten_free" => "Gluten Free",
    "sugar_free" => "Sugar Free",
    "low_calorie" => "Low Calorie",
    "vegetarian" => "Vegetarian"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Preferences | EatWise</title>
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
            <svg class="brand-leaf" viewBox="0 0 60 50"
                 aria-hidden="true">
                <path d="M4 44C18 12 32 6 56 4C54 28 36 45 4 44Z"
                      fill="#72844c"/>
                <path d="M8 42L43 14" stroke="#b2b98a"
                      stroke-width="1.5" fill="none"/>
            </svg>
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

    <!-- MAIN -->
    <main class="preferences-main">

        <div class="page-heading">
            <a href="select-criteria.php" class="back-button"
               aria-label="Back">&#8249;</a>

            <h1>Edit Preferences</h1>

            <button type="submit" form="preferences-form"
                    class="save-button">
                Save Changes
            </button>
        </div>

        <?php if ($saved): ?>
            <div class="success-message" role="status">
                Preferences saved successfully!
            </div>
        <?php endif; ?>

        <form method="post" id="preferences-form">

            <!-- ALLERGENS -->
            <section class="allergens-section">
                <h2>Allergens</h2>

                <div class="allergen-list">
                    <?php foreach ($allergens as $allergen): ?>
                        <?php $isSelected = in_array(
                            $allergen, $selectedAllergens, true
                        ); ?>

                        <label class="allergen-row">
                            <input
                                type="checkbox"
                                name="allergens[]"
                                value="<?= e($allergen) ?>"
                                <?= $isSelected ? "checked" : "" ?>
                            >

                            <span class="custom-checkbox"></span>

                            <span class="allergen-name <?= $isSelected ? 'selected' : '' ?>">
                                <?= e($allergen) ?>
                            </span>

                            <?php if ($isSelected): ?>
                                <span class="remove-allergen"
                                      aria-hidden="true">×</span>
                            <?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- OTHER ALLERGEN -->
                <div class="other-allergen">
                    <div class="other-label">
                        <span class="plus-icon">＋</span>
                        <label for="other_allergen">Other Allergen</label>
                    </div>

                    <input
                        type="text"
                        id="other_allergen"
                        name="other_allergen"
                        value="<?= e($otherAllergen) ?>"
                        placeholder="Type here......"
                        maxlength="100"
                    >
                </div>
            </section>

            <!-- DIETARY PREFERENCES -->
            <section class="dietary-section">
                <h2>Dietary Preferences</h2>

                <div class="dietary-card">

                    <div class="dietary-row">
                        <span class="diet-icon wheat-icon">♧</span>
                        <span class="diet-name">Gluten Free</span>

                        <label class="switch">
                            <input type="checkbox" name="gluten_free"
                                <?= $dietary["gluten_free"] ? "checked" : "" ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="dietary-row">
                        <span class="diet-icon">♧</span>
                        <span class="diet-name">Sugar Free</span>

                        <label class="switch">
                            <input type="checkbox" name="sugar_free"
                                <?= $dietary["sugar_free"] ? "checked" : "" ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="dietary-row">
                        <span class="diet-icon">▤</span>
                        <span class="diet-name">Low Calorie</span>

                        <label class="switch">
                            <input type="checkbox" name="low_calorie"
                                <?= $dietary["low_calorie"] ? "checked" : "" ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="dietary-row">
                        <span class="diet-icon vegetarian-icon">♧</span>
                        <span class="diet-name">Vegetarian</span>

                        <label class="switch">
                            <input type="checkbox" name="vegetarian"
                                <?= $dietary["vegetarian"] ? "checked" : "" ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="dietary-row last-row">
                        <span class="diet-icon nut-icon">❋</span>
                        <span class="diet-name">Tree Nuts</span>
                    </div>

                </div>
            </section>

        </form>

    </main>
</div>

</body>
</html>
