```php
<?php
session_start();

$orders = [
    [
        "id" => "EW123456",
        "name" => "Chicken Grill Bowl",
        "image" => "assets/chicken-bowl.jpg",
        "price" => 60000,
        "calories" => 400,
        "date" => "22 Apr 2026, 13:06",
        "status" => "Shipped"
    ],
    [
        "id" => "EW123456",
        "name" => "Caesar Salad",
        "image" => "assets/caesar-salad.jpg",
        "price" => 45000,
        "calories" => 200,
        "date" => "22 Apr 2026, 13:06",
        "status" => "Shipped"
    ],
    [
        "id" => "EW060324",
        "name" => "Oat Berries Meal",
        "image" => "assets/oat-berries.jpg",
        "price" => 35000,
        "calories" => 150,
        "date" => "13 Mar 2026, 15:06",
        "status" => "Completed"
    ]
];

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}

function rupiah($amount) {
    return "Rp " . number_format($amount, 0, ",", ".");
}

/* FILTER STATUS */
$allowedFilters = [
    "All",
    "Completed",
    "Cancelled",
    "Shipped"
];

$filter = $_GET["status"] ?? "All";

if (!in_array($filter, $allowedFilters, true)) {
    $filter = "All";
}

$filteredOrders = array_filter(
    $orders,
    function ($order) use ($filter) {
        return $filter === "All"
            || $order["status"] === $filter;
    }
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Order History | EatWise</title>

    <!-- Gunakan nama CSS yang benar -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-container">

    <!-- NAVBAR -->
    <header class="navbar">
        <a href="home.php" class="brand">
            <svg class="brand-leaf"
                 viewBox="0 0 60 50"
                 aria-hidden="true">
                <path
                    d="M4 44C18 12 32 6 56 4C54 28 36 45 4 44Z"
                    fill="#72844c"
                />
                <path
                    d="M8 42L43 14"
                    stroke="#b2b98a"
                    stroke-width="1.5"
                    fill="none"
                />
            </svg>
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
    <main class="history-main">

        <h1>Order History</h1>

        <!-- FILTER TABS -->
        <nav class="filter-tabs" aria-label="Filter order status">
            <?php foreach ($allowedFilters as $status): ?>
                <a
                    href="orderHistory.php?status=<?= urlencode($status) ?>"
                    class="filter-tab <?= $filter === $status ? 'active' : '' ?>"
                    <?= $filter === $status ? 'aria-current="page"' : '' ?>
                >
                    <?= e($status) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- ORDER LIST -->
        <section class="order-list">

            <?php if (empty($filteredOrders)): ?>

                <div class="empty-state">
                    <p>
                        No <?= e(strtolower($filter)) ?> orders found.
                    </p>

                    <a href="orderHistory.php?status=All">
                        View All Orders
                    </a>
                </div>

            <?php else: ?>

                <?php foreach ($filteredOrders as $order): ?>

                    <a
                        class="order-item"
                        href="order-detail.php?id=<?= urlencode($order['id']) ?>"
                    >

                        <!-- FOOD IMAGE -->
                        <div class="food-image">
                            <img
                                src="<?= e($order['image']) ?>"
                                alt="<?= e($order['name']) ?>"
                            >
                        </div>

                        <!-- ORDER INFORMATION -->
                        <div class="order-info">
                            <h2>#<?= e($order['id']) ?></h2>

                            <p class="food-name">
                                <?= e($order['name']) ?>
                            </p>

                            <p class="order-date">
                                <?= e($order['date']) ?>
                            </p>
                        </div>

                        <!-- STATUS BADGE -->
                        <div class="order-status">
                            <span class="status-badge <?= strtolower(e($order['status'])) ?>">
                                <?= e($order['status']) ?>
                            </span>
                        </div>

                        <!-- PRICE AND CALORIES -->
                        <div class="order-summary">
                            <span class="price">
                                <?= rupiah($order['price']) ?>
                            </span>

                            <span class="calories">
                                <?= number_format($order['calories']) ?> kcal
                            </span>
                        </div>

                        <!-- ARROW -->
                        <span class="order-arrow" aria-hidden="true">
                            ›
                        </span>

                    </a>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </main>

</div>

</body>
</html>
```