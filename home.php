
<?php
$foods = [
    [
        "name" => "Chicken Rice Bowl",
        "calories" => 400,
        "price" => 50000,
        "category" => "seafood-free",
        "image" => "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "name" => "Saladish",
        "calories" => 200,
        "price" => 45000,
        "category" => "low-calorie",
        "image" => "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "name" => "Oat Berries Meal",
        "calories" => 150,
        "price" => 35000,
        "category" => "gluten-free",
        "image" => "https://images.unsplash.com/photo-1511690743698-d9d85f2fbf38?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "name" => "Tomato Egg",
        "calories" => 200,
        "price" => 30000,
        "category" => "seafood-free",
        "image" => "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "name" => "Pancake Nutty Berry",
        "calories" => 230,
        "price" => 40000,
        "category" => "low-calorie",
        "image" => "https://images.unsplash.com/photo-1528207776546-365bb710ee93?auto=format&fit=crop&w=600&q=80"
    ],
    [
        "name" => "Infused Water",
        "calories" => 0,
        "price" => 20000,
        "category" => "gluten-free",
        "image" => "https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=600&q=80"
    ]
];

$search = trim($_GET["search"] ?? "");
$category = $_GET["category"] ?? "all";

$filteredFoods = array_filter($foods, function ($food) use ($search, $category) {
    $matchSearch = $search === "" ||
        stripos($food["name"], $search) !== false;

    $matchCategory = $category === "all" ||
        $food["category"] === $category;

    return $matchSearch && $matchCategory;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EatWise - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg eatwise-navbar">
        <div class="container-fluid px-4 px-lg-5">

            <a class="navbar-brand brand" href="home.php">
                <i class="bi bi-leaf-fill brand-leaf"></i>
                <span>EatWise</span>
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">CATEGORIES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">CARTS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">ACCOUNT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">LOGOUT</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>


    <main class="container-fluid home-content">

        <!-- BANNER AND SEARCH -->
        <section class="top-section">

            <div class="welcome-banner">
                <h1>Healthy Choices<br>for a Safer You</h1>
                <p>
                    Filter by Your Dietary Needs,<br>
                    and Allergen Preferences.
                </p>
            </div>

            <form action="home.php" method="GET" class="search-form">

                <?php if ($category !== "all"): ?>
                    <input type="hidden" name="category"
                        value="<?= htmlspecialchars($category) ?>">
                <?php endif; ?>

                <input
                    type="search"
                    name="search"
                    class="form-control"
                    placeholder="Search for Healthy Food"
                    value="<?= htmlspecialchars($search) ?>"
                >

                <button type="submit" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>

            </form>

        </section>


        <!-- CATEGORY FILTER -->
        <section class="category-section" id="categories">

            <a href="?category=seafood-free"
               class="category-link <?= $category === 'seafood-free' ? 'selected' : '' ?>">
                <span class="category-icon">
                    <i class="bi bi-emoji-sunglasses"></i>
                </span>
                <span>Seafood Free</span>
            </a>

            <a href="?category=low-calorie"
               class="category-link <?= $category === 'low-calorie' ? 'selected' : '' ?>">
                <span class="category-icon">
                    <i class="bi bi-fire"></i>
                </span>
                <span>Low-Calorie</span>
            </a>

            <a href="?category=gluten-free"
               class="category-link <?= $category === 'gluten-free' ? 'selected' : '' ?>">
                <span class="category-icon">
                    <i class="bi bi-flower1"></i>
                </span>
                <span>Gluten Free</span>
            </a>

            <a href="home.php" class="category-link">
                <span class="category-icon">
                    <i class="bi bi-grid"></i>
                </span>
                <span>All Foods</span>
            </a>

        </section>


        <!-- FOOD CARDS -->
        <section class="food-section">

            <div class="section-heading">
                <h2>Recommended for You</h2>
                <span><?= count($filteredFoods) ?> foods</span>
            </div>

            <div class="row g-3">

                <?php if (count($filteredFoods) > 0): ?>

                    <?php foreach ($filteredFoods as $food): ?>

                        <div class="col-6 col-md-4 col-lg-3 col-xl-2">

                            <a href="food-detail.php" class="food-card">

                                <img
                                    src="<?= htmlspecialchars($food['image']) ?>"
                                    alt="<?= htmlspecialchars($food['name']) ?>"
                                    class="food-image"
                                >

                                <div class="food-info">

                                    <h3>
                                        <?= htmlspecialchars($food['name']) ?>
                                    </h3>

                                    <div class="food-meta">
                                        <span>
                                            <?= $food['calories'] ?> Kcal
                                        </span>

                                        <span class="meta-dot">•</span>

                                        <span>
                                            Rp <?= number_format($food['price'], 0, ',', '.') ?>
                                        </span>
                                    </div>

                                </div>

                            </a>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="col-12 empty-state">
                        <i class="bi bi-search"></i>
                        <h3>No food found</h3>
                        <p>Try another keyword.</p>
                        <a href="home.php" class="btn reset-button">
                            Show All Foods
                        </a>
                    </div>

                <?php endif; ?>

            </div>

        </section>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
