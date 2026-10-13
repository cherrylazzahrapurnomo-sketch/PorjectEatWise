
<?php
session_start();

// Data contoh mengikuti desain Figma.
// Nantinya bisa diisi dari proses checkout.
$order = $_SESSION["order"] ?? [
    "id" => "EW123456",
    "delivery" => "25-30 Minutes",
    "calories" => 600,
    "payment" => 105000
];

if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["cancel_order"])) {
    $_SESSION["order_status"] = "cancelled";
}

$isCancelled = ($_SESSION["order_status"] ?? "") === "cancelled";

$orderId = htmlspecialchars($order["id"] ?? "EW123456");
$delivery = htmlspecialchars($order["delivery"] ?? "25-30 Minutes");
$calories = (int) ($order["calories"] ?? 600);
$payment = (int) ($order["payment"] ?? 105000);

function formatRupiah($amount) {
    return "Rp " . number_format($amount, 0, ",", ".");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $isCancelled ? "Order Cancelled" : "Order Confirmed" ?> | EatWise
    </title>

    <link rel="stylesheet" href="order-confirmed.css">
</head>

<body>

<div class="page-background">
    <main class="confirmation-container">

        <!-- SUCCESS ICON -->
        <div class="status-icon <?= $isCancelled ? 'cancelled' : '' ?>">
            <?php if ($isCancelled): ?>
                <svg viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M15 15L33 33M33 15L15 33"
                          fill="none" stroke="currentColor"
                          stroke-width="4" stroke-linecap="round"/>
                </svg>
            <?php else: ?>
                <svg viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M11 24L20 33L38 14"
                          fill="none" stroke="currentColor"
                          stroke-width="4.5" stroke-linecap="square"
                          stroke-linejoin="miter"/>
                </svg>
            <?php endif; ?>
        </div>

        <!-- HEADING -->
        <h1>
            <?= $isCancelled ? "Order Cancelled" : "Order Confirmed!" ?>
        </h1>

        <div class="subtitle">
            <?php if ($isCancelled): ?>
                Your order has been cancelled.
                <br>
                We hope to see you again soon.
            <?php else: ?>
                Thank You! Your Order Is Being Processed And
                <br>
                Will Be Shipped Shortly
            <?php endif; ?>
        </div>

        <!-- ORDER DETAILS -->
        <section class="order-details">

            <div class="detail-row">
                <span class="detail-label">Order ID</span>
                <strong>#<?= $orderId ?></strong>
            </div>

            <div class="detail-row">
                <span class="detail-label">Estimated Delivery</span>
                <strong>
                    <?= $isCancelled ? "Cancelled" : $delivery ?>
                </strong>
            </div>

            <div class="detail-row calories-row">
                <span class="detail-label">Total Calories</span>
                <strong><?= number_format($calories) ?> kcal</strong>
            </div>

            <div class="detail-row payment-row">
                <span class="detail-label">Total Payment</span>
                <strong><?= formatRupiah($payment) ?></strong>
            </div>

        </section>

        <!-- ACTION BUTTONS -->
        <div class="confirmation-actions">

            <?php if (!$isCancelled): ?>
                <form method="post" class="cancel-form"
                      onsubmit="return confirm('Are you sure you want to cancel this order?');">

                    <button
                        type="submit"
                        name="cancel_order"
                        value="1"
                        class="cancel-button"
                    >
                        Cancel Order
                    </button>
                </form>
            <?php endif; ?>

            <a href="home.php" class="home-button">
                Back to Home
            </a>

        </div>

    </main>
</div>

</body>
</html>
