
<?php
session_start();

$menu = [
    "chicken" => [
        "name" => "Chicken Grill Bowl",
        "price" => 60000,
        "calories" => 400,
        "image" => "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=300&q=85"
    ],
    "salad" => [
        "name" => "Caesar Salad",
        "price" => 45000,
        "calories" => 200,
        "image" => "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=300&q=85"
    ]
];

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [
        "chicken" => 1
    ];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $id = $_POST["id"] ?? "";

    if ($action === "clear") {
        $_SESSION["cart"] = [];
    } elseif (isset($menu[$id])) {
        $qty = (int)($_SESSION["cart"][$id] ?? 0);

        if ($action === "increase") {
            $_SESSION["cart"][$id] = min(99, $qty + 1);
        } elseif ($action === "decrease") {
            if ($qty <= 1) {
                unset($_SESSION["cart"][$id]);
            } else {
                $_SESSION["cart"][$id] = $qty - 1;
            }
        } elseif ($action === "remove") {
            unset($_SESSION["cart"][$id]);
        }
    }

    header("Location: cart.php");
    exit;
}

$cart = $_SESSION["cart"];
$totalItems = 0;
$totalCalories = 0;
$totalPayment = 0;

foreach ($cart as $id => $qty) {
    if (!isset($menu[$id])) continue;

    $totalItems += $qty;
    $totalCalories += $menu[$id]["calories"] * $qty;
    $totalPayment += $menu[$id]["price"] * $qty;
}

function rupiah($amount) {
    return "Rp " . number_format($amount, 0, ",", ".");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | EatWise</title>
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
            <a href="profile.php">ACCOUNT</a>
            <a href="login.php" class="logout">LOGOUT</a>
        </nav>
    </header>

    <main class="cart-main">

        <!-- PAGE TITLE -->
        <div class="cart-heading">
            <div class="heading-left">
                <a href="categories.php" class="back-button"
                   aria-label="Kembali">&#8249;</a>

                <h1>Your Cart (<?= $totalItems ?>)</h1>
            </div>

            <form method="post">
                <button type="submit" name="action" value="clear"
                        class="clear-button">
                    Hapus semua
                </button>
            </form>
        </div>

        <!-- CART LIST -->
        <section class="cart-items">

            <?php if (empty($cart)): ?>

                <div class="empty-cart">
                    <h2>Your cart is empty</h2>
                    <p>Belum ada makanan yang dipilih.</p>
                    <a href="categories.php" class="add-more-button">
                        Add more
                    </a>
                </div>

            <?php else: ?>

                <?php foreach ($cart as $id => $qty): ?>
                    <?php if (!isset($menu[$id])) continue; ?>
                    <?php $item = $menu[$id]; ?>

                    <article class="cart-item">

                        <img
                            src="<?= htmlspecialchars($item["image"]) ?>"
                            alt="<?= htmlspecialchars($item["name"]) ?>"
                            class="item-image"
                        >

                        <div class="item-information">
                            <h2><?= htmlspecialchars($item["name"]) ?></h2>

                            <p class="item-price">
                                <?= rupiah($item["price"]) ?>
                            </p>

                            <form method="post" class="quantity-control">
                                <input type="hidden" name="id"
                                       value="<?= htmlspecialchars($id) ?>">

                                <button type="submit" name="action"
                                        value="decrease" aria-label="Kurangi">-</button>

                                <span><?= $qty ?></span>

                                <button type="submit" name="action"
                                        value="increase" aria-label="Tambah">+</button>
                            </form>
                        </div>

                        <div class="item-right">
                            <form method="post">
                                <input type="hidden" name="id"
                                       value="<?= htmlspecialchars($id) ?>">

                                <button type="submit" name="action"
                                        value="remove" class="delete-button"
                                        aria-label="Hapus makanan">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M4 7h16M9 7V4h6v3m-9 0 1 13h10l1-13M10 10v7m4-7v7"
                                              fill="none" stroke="currentColor"
                                              stroke-width="1.7"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </form>

                            <p class="item-calories">
                                <?= $item["calories"] * $qty ?> kcal
                            </p>
                        </div>

                    </article>
                <?php endforeach; ?>

            <?php endif; ?>

        </section>

        <?php if (!empty($cart)): ?>

        <!-- ADD MORE -->
        <section class="add-more-card">
            <div>
                <h2>Need anything else?</h2>
                <p>Add other dishes, if you want.</p>
            </div>

            <a href="categories.php" class="add-more-button">
                Add more
            </a>
        </section>

        <!-- DIETARY NOTE -->
        <section class="dietary-note">
            <div class="note-icon">
                <svg viewBox="0 0 40 40">
                    <circle cx="17" cy="21" r="13"
                            fill="none" stroke="currentColor"
                            stroke-width="1.8"/>
                    <path d="m15 25 3.5-.8L31 11a3 3 0 0 0-4-4L14.5 20.5Z"
                          fill="none" stroke="currentColor"
                          stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
            </div>

            <div class="note-text">
                <h2>Dietary Note</h2>
                <p>No seafood, please.</p>
            </div>
        </section>

        <!-- ORDER SUMMARY -->
        <section class="order-summary">

            <?php foreach ($cart as $id => $qty): ?>
                <?php if (!isset($menu[$id])) continue; ?>
                <?php $item = $menu[$id]; ?>

                <div class="summary-row">
                    <strong class="summary-name">
                        <?= htmlspecialchars($item["name"]) ?>
                        <?php if ($qty > 1): ?>
                            × <?= $qty ?>
                        <?php endif; ?>
                    </strong>

                    <strong class="summary-calories">
                        <?= $item["calories"] * $qty ?> kcal
                    </strong>

                    <strong class="summary-price">
                        <?= rupiah($item["price"] * $qty) ?>
                    </strong>
                </div>
            <?php endforeach; ?>

            <div class="summary-divider"></div>

            <div class="summary-row total-calories">
                <strong>Total Calories</strong>
                <strong><?= $totalCalories ?> kcal</strong>
                <span></span>
            </div>

            <div class="summary-row total-payment">
                <strong>Total Payment</strong>
                <span></span>
                <strong><?= rupiah($totalPayment) ?></strong>
            </div>

        </section>

        <!-- CHECKOUT -->
        <div class="checkout-container">
            <a href="checkout.php" class="checkout-button">
                Checkout
            </a>
        </div>

        <?php endif; ?>

    </main>
</div>

</body>
</html>
