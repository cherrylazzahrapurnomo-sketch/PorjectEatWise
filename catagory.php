<?php
$foods = [
    [
        "name" => "Chicken Rice Bowl",
        "calories" => 400,
        "price" => 50000,
        "image" => "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=500&q=80"
    ],
    [
        "name" => "Saladish",
        "calories" => 280,
        "price" => 45000,
        "image" => "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=500&q=80"
    ],
    [
        "name" => "Oat Berries Meal",
        "calories" => 150,
        "price" => 35000,
        "image" => "https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=500&q=80"
    ],
    [
        "name" => "Tomato Egg",
        "calories" => 200,
        "price" => 30000,
        "image" => "https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&fit=crop&w=500&q=80"
    ],
    [
        "name" => "Pancake Nutty Berry",
        "calories" => 230,
        "price" => 40000,
        "image" => "https://images.unsplash.com/photo-1528207776546-365bb710ee93?auto=format&fit=crop&w=500&q=80"
    ],
    [
        "name" => "Infused Water",
        "calories" => 0,
        "price" => 20000,
        "image" => "https://images.unsplash.com/photo-1513558161293-cdaf765edfd7?auto=format&fit=crop&w=500&q=80"
    ]
];

$category = $_GET["category"] ?? "All";

$categories = ["All", "Healthy", "Breakfast", "Drinks"];

if (!in_array($category, $categories, true)) {
    $category = "All";
}

$filteredFoods = $foods;

if ($category === "Breakfast") {
    $filteredFoods = array_values(array_filter($foods, function ($food) {
        return in_array($food["name"], [
            "Oat Berries Meal",
            "Pancake Nutty Berry"
        ], true);
    }));
} elseif ($category === "Drinks") {
    $filteredFoods = array_values(array_filter($foods, function ($food) {
        return $food["name"] === "Infused Water";
    }));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categories | EatWise</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">

    <!-- NAVBAR -->
    <header class="navbar">
        <a href="home.php" class="logo">
            <span>🍃</span> EatWise
        </a>

        <nav>
            <a href="home.php">HOME</a>
            <a href="categories.php" class="active">CATEGORIES</a>
            <a href="cart.php">CARTS <span id="cart-count">0</span></a>
            <a href="profile.php">ACCOUNT</a>
            <a href="login.php">LOGOUT</a>
        </nav>
    </header>

    <!-- CONTENT -->
    <main class="content">

        <h1 class="page-title">Categories</h1>

        <!-- CATEGORY FILTER -->
        <div class="category-filter">
            <?php foreach ($categories as $item): ?>
                <a
                    href="?category=<?= urlencode($item) ?>"
                    class="category-btn <?= $category === $item ? 'selected' : '' ?>"
                >
                    <?= htmlspecialchars($item) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- FILTER & SORT -->
        <div class="filter-row">
            <div>
                <label for="sort">Filters</label>
                <select id="sort">
                    <option value="default">Featured food</option>
                    <option value="low">Lowest price</option>
                    <option value="high">Highest price</option>
                    <option value="calories">Lowest calories</option>
                </select>
            </div>

            <p><?= count($filteredFoods) ?> menu tersedia</p>
        </div>

        <!-- FOOD CARDS -->
        <section class="food-grid" id="food-grid">

            <?php foreach ($filteredFoods as $food): ?>

                <article
                    class="food-card"
                    data-price="<?= $food['price'] ?>"
                    data-calories="<?= $food['calories'] ?>"
                >
                    <a href="detail.php" class="food-image-link">
                        <img
                            src="<?= htmlspecialchars($food['image']) ?>"
                            alt="<?= htmlspecialchars($food['name']) ?>"
                            class="food-image"
                        >
                    </a>

                    <div class="food-info">
                        <h2><?= htmlspecialchars($food['name']) ?></h2>

                        <div class="food-meta">
                            <span>
                                <?= number_format($food['calories']) ?> Kcal
                            </span>

                            <span>•</span>

                            <span>
                                Rp <?= number_format(
                                    $food['price'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </span>
                        </div>

                        <button
                            class="add-btn"
                            type="button"
                            data-name="<?= htmlspecialchars($food['name']) ?>"
                        >
                            + Tambah
                        </button>
                    </div>
                </article>

            <?php endforeach; ?>

        </section>

        <?php if (empty($filteredFoods)): ?>
            <p class="empty-message">Belum ada menu di kategori ini.</p>
        <?php endif; ?>

    </main>

    <!-- TOTAL BAR -->
    <footer class="total-bar">
        <div>
            Total kalori:
            <strong id="total-calories">0 Kcal</strong>
        </div>

        <div>
            Total pembayaran:
            <strong id="total-price">Rp 0</strong>
        </div>

        <a href="cart.php" class="cart-btn">Lihat Keranjang</a>
    </footer>

</div>

<script>
    const sortSelect = document.getElementById("sort");
    const foodGrid = document.getElementById("food-grid");
    const cartCount = document.getElementById("cart-count");
    const totalCalories = document.getElementById("total-calories");
    const totalPrice = document.getElementById("total-price");

    let cart = [];

    function formatRupiah(number) {
        return "Rp " + number.toLocaleString("id-ID");
    }

    function updateCart() {
        const count = cart.length;

        const calories = cart.reduce(
            (sum, item) => sum + item.calories, 0
        );

        const price = cart.reduce(
            (sum, item) => sum + item.price, 0
        );

        cartCount.textContent = count;
        totalCalories.textContent = calories + " Kcal";
        totalPrice.textContent = formatRupiah(price);
    }

    document.querySelectorAll(".add-btn").forEach(button => {
        button.addEventListener("click", () => {
            const card = button.closest(".food-card");

            cart.push({
                name: button.dataset.name,
                price: Number(card.dataset.price),
                calories: Number(card.dataset.calories)
            });

            updateCart();

            button.textContent = "✓ Ditambahkan";

            setTimeout(() => {
                button.textContent = "+ Tambah";
            }, 800);
        });
    });

    sortSelect.addEventListener("change", () => {
        const cards = [...foodGrid.querySelectorAll(".food-card")];

        cards.sort((a, b) => {
            const priceA = Number(a.dataset.price);
            const priceB = Number(b.dataset.price);
            const caloriesA = Number(a.dataset.calories);
            const caloriesB = Number(b.dataset.calories);

            switch (sortSelect.value) {
                case "low":
                    return priceA - priceB;
                case "high":
                    return priceB - priceA;
                case "calories":
                    return caloriesA - caloriesB;
                default:
                    return 0;
            }
        });

        cards.forEach(card => foodGrid.appendChild(card));
    });
</script>

</body>
</html>