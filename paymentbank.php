
<?php
$message = "";
$selectedPayment = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedPayment = $_POST["payment_method"] ?? "";

    $allowedMethods = [
        "GoPay",
        "OVO",
        "DANA",
        "PayLater",
        "Virtual Account",
        "Credit/Debit Card",
        "COD"
    ];

    if (in_array($selectedPayment, $allowedMethods, true)) {
        $message = "Payment method selected: " . $selectedPayment;
    } else {
        $message = "Please select a valid payment method.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EatWise - Payment Method</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="home.php" class="logo">
        <span class="leaf">🍃</span>
        <span>EatWise</span>
    </a>

    <div class="nav-menu">
        <a href="home.php">HOME</a>
        <a href="categories.php">CATEGORIES</a>
        <a href="cart.php">CARTS</a>
        <a href="account.php">ACCOUNT</a>
        <a href="logout.php">LOGOUT</a>
    </div>
</nav>

<main class="container">

    <!-- PAGE TITLE -->
    <a href="cart.php" class="back-link">‹</a>
    <h1 class="page-title">Payment Method</h1>

    <?php if ($message !== ""): ?>
        <div class="message">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- PAYMENT FORM -->
    <form method="POST" action="payment.php">
        <div class="payment-card">

            <!-- GOPAY -->
            <label class="payment-row">
                <input type="radio" name="payment_method"
                       value="GoPay"
                       <?= $selectedPayment === "GoPay" ? "checked" : "" ?>>

                <span class="payment-icon gopay">G</span>

                <span class="payment-info">
                    <span class="payment-name">GoPay</span>
                    <small>Get Shipping Discounts Up To 15,000</small>
                </span>

                <span class="promo">Top up your balance for free</span>
                <span class="arrow">›</span>
            </label>

            <!-- OVO -->
            <label class="payment-row">
                <input type="radio" name="payment_method"
                       value="OVO"
                       <?= $selectedPayment === "OVO" ? "checked" : "" ?>>

                <span class="payment-icon ovo">O</span>

                <span class="payment-info">
                    <span class="payment-name">OVO</span>
                </span>

                <span class="promo">Connect to OVO</span>
                <span class="arrow">›</span>
            </label>

            <!-- DANA -->
            <label class="payment-row">
                <input type="radio" name="payment_method"
                       value="DANA"
                       <?= $selectedPayment === "DANA" ? "checked" : "" ?>>

                <span class="payment-icon dana">D</span>

                <span class="payment-info">
                    <span class="payment-name">DANA</span>
                </span>

                <span class="promo">Connect to DANA</span>
                <span class="arrow">›</span>
            </label>

            <!-- PAYLATER -->
            <label class="payment-row">
                <input type="radio" name="payment_method"
                       value="PayLater"
                       <?= $selectedPayment === "PayLater" ? "checked" : "" ?>>

                <span class="payment-icon paylater">♧</span>

                <span class="payment-info">
                    <span class="payment-name">PayLater</span>
                    <small>No Admin Fees. Get 0% Installment Plans For Certain Items!</small>
                </span>

                <span class="arrow">›</span>
            </label>

            <!-- VIRTUAL ACCOUNT -->
            <label class="payment-row">
                <input type="radio" name="payment_method"
                       value="Virtual Account"
                       <?= $selectedPayment === "Virtual Account" ? "checked" : "" ?>>

                <span class="payment-icon virtual">▤</span>

                <span class="payment-info">
                    <span class="payment-name">Virtual Account</span>
                </span>

                <span class="arrow">›</span>
            </label>

            <!-- CREDIT / DEBIT CARD -->
            <div class="card-section">

                <label class="payment-row">
                    <input type="radio" name="payment_method"
                           value="Credit/Debit Card"
                           <?= $selectedPayment === "Credit/Debit Card" ? "checked" : "" ?>>

                    <span class="payment-icon bank">▤</span>

                    <span class="payment-info">
                        <span class="payment-name">Credit/Debit Card</span>
                    </span>

                    <span class="card-number">*0306</span>
                    <span class="card-check">✓</span>

                    <button type="button" class="expand-button"
                            onclick="toggleCard(event)" aria-label="Expand card details">
                        ⌃
                    </button>
                </label>

                <div class="saved-card" id="savedCard">
                    <span class="mastercard">
                        <i></i><b></b>
                    </span>

                    <span class="bank-name">BNI</span>
                    <span class="card-number">*0306</span>
                    <span class="selected-check">✓</span>
                </div>

            </div>

            <!-- COD -->
            <label class="payment-row">
                <input type="radio" name="payment_method"
                       value="COD"
                       <?= $selectedPayment === "COD" ? "checked" : "" ?>>

                <span class="payment-icon cod">▣</span>

                <span class="payment-info">
                    <span class="payment-name">COD</span>
                </span>

                <span class="arrow">›</span>
            </label>

        </div>

        <button type="submit" class="continue-button">
            Continue
        </button>
    </form>

</main>

<script>
function toggleCard(event) {
    event.preventDefault();
    event.stopPropagation();

    const savedCard = document.getElementById("savedCard");
    const button = event.currentTarget;

    if (savedCard.style.display === "none") {
        savedCard.style.display = "flex";
        button.textContent = "⌃";
    } else {
        savedCard.style.display = "none";
        button.textContent = "⌄";
    }
}
</script>

</body>
</html>
```