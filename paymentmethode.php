
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EatWise - Payment Methods</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg eatwise-navbar">
    <div class="container">
        <a class="navbar-brand brand" href="home.php">
            <i class="bi bi-leaf-fill brand-leaf"></i>
            <span>EatWise</span>
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="home.php">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">CATEGORIES</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">CARTS</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">ACCOUNT</a></li>
                <li class="nav-item"><a class="nav-link logout" href="logout.php">LOGOUT</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main class="payment-container">

    <!-- PAGE TITLE -->
    <div class="payment-heading">
        <a href="checkout.php" class="back-icon" aria-label="Back to checkout">
            <i class="bi bi-chevron-left"></i>
        </a>
        <h1>Payment Method</h1>
    </div>

    <!-- PAYMENT LIST -->
    <form action="checkout.php" method="GET" id="paymentForm">

        <div class="payment-card">

            <!-- GOPAY -->
            <label class="payment-option selected">
                <input type="radio" name="payment_method" value="GoPay" checked>

                <span class="payment-logo gopay">
                    <i class="bi bi-wallet-fill"></i>
                </span>

                <span class="payment-info">
                    <strong>GoPay</strong>
                    <small>Get Shipping Discounts Up To 15.000</small>
                </span>

                <span class="payment-badge">Top up your balance for free</span>

                <i class="bi bi-chevron-right payment-arrow"></i>
            </label>

            <!-- OVO -->
            <label class="payment-option">
                <input type="radio" name="payment_method" value="OVO">

                <span class="payment-logo ovo">
                    <i class="bi bi-circle-fill"></i>
                </span>

                <span class="payment-info">
                    <strong>OVO</strong>
                </span>

                <span class="payment-badge">Connect to OVO</span>

                <i class="bi bi-chevron-right payment-arrow"></i>
            </label>

            <!-- DANA -->
            <label class="payment-option">
                <input type="radio" name="payment_method" value="DANA">

                <span class="payment-logo dana">
                    <i class="bi bi-wallet2"></i>
                </span>

                <span class="payment-info">
                    <strong>DANA</strong>
                </span>

                <span class="payment-badge">Connect to DANA</span>

                <i class="bi bi-chevron-right payment-arrow"></i>
            </label>

            <!-- PAYLATER -->
            <label class="payment-option">
                <input type="radio" name="payment_method" value="PayLater">

                <span class="payment-logo paylater">
                    <i class="bi bi-wallet"></i>
                    <i class="bi bi-clock-fill mini-icon"></i>
                </span>

                <span class="payment-info">
                    <strong>PayLater</strong>
                    <small>No Admin Fees. Get 0% Installment Plans For Certain Terms!</small>
                </span>

                <i class="bi bi-chevron-right payment-arrow"></i>
            </label>

            <!-- VIRTUAL ACCOUNT -->
            <label class="payment-option">
                <input type="radio" name="payment_method" value="Virtual Account">

                <span class="payment-logo bank">
                    <i class="bi bi-credit-card"></i>
                </span>

                <span class="payment-info">
                    <strong>Virtual Account</strong>
                </span>

                <i class="bi bi-chevron-right payment-arrow"></i>
            </label>

            <!-- CREDIT / DEBIT CARD -->
            <label class="payment-option">
                <input type="radio" name="payment_method" value="Credit/Debit Card">

                <span class="payment-logo bank">
                    <i class="bi bi-bank"></i>
                </span>

                <span class="payment-info">
                    <strong>Credit/Debit Card</strong>
                </span>

                <span class="payment-badge">Link debit/credit card</span>

                <i class="bi bi-chevron-right payment-arrow"></i>
            </label>

            <!-- COD -->
            <label class="payment-option">
                <input type="radio" name="payment_method" value="COD">

                <span class="payment-logo bank">
                    <i class="bi bi-cash-coin"></i>
                </span>

                <span class="payment-info">
                    <strong>COD</strong>
                </span>

                <i class="bi bi-chevron-right payment-arrow"></i>
            </label>

        </div>

        <div class="payment-footer">
            <button type="submit" class="save-payment-button">
                Use This Payment Method
            </button>
        </div>

    </form>

</main>

<script>
    const options = document.querySelectorAll(".payment-option");

    options.forEach(option => {
        option.addEventListener("change", function () {
            options.forEach(item => item.classList.remove("selected"));
            this.classList.add("selected");
        });

        option.querySelector("input").addEventListener("change", function () {
            options.forEach(item => item.classList.remove("selected"));

            if (this.checked) {
                option.classList.add("selected");
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
