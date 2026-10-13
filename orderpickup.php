
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EatWise - Checkout</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Custom -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-eatwise">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <span class="logo-leaf">🍃</span>
            <span>EatWise</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">CATEGORIES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">CARTS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">ACCOUNT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link logout" href="#">LOGOUT</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main class="container checkout-container">
    <div class="row g-3">

        <!-- KOLOM KIRI -->
        <div class="col-lg-6">

            <!-- DELIVERY / PICKUP -->
            <section class="delivery-card mb-3">
                <div class="delivery-tabs">
                    <button class="delivery-tab" id="deliveryBtn"
                            onclick="selectMethod('Delivery')">
                        Delivery
                    </button>

                    <button class="delivery-tab active" id="pickupBtn"
                            onclick="selectMethod('Pickup')">
                        Pickup
                    </button>
                </div>

                <div class="restaurant-preview">
                    <div class="restaurant-title">
                        <i class="bi bi-shop text-success"></i>
                        <strong>EatWise</strong>
                        <small>Good Food, Smart Choices</small>
                    </div>

                    <p class="address">
                        <i class="bi bi-geo-alt"></i>
                        Jl. Sukamaja No.13, Tapos, Cimpaeun, Depok, Jawa Barat
                    </p>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="pickup-label" id="methodLabel">
                            <i class="bi bi-bag-check"></i> Pickup
                        </span>

                        <span class="pickup-time" id="timeLabel">
                            <i class="bi bi-clock-history"></i> Pick up in ±15 minutes
                        </span>
                    </div>
                </div>
            </section>

            <!-- YOUR ORDER -->
            <section class="order-card mb-3">
                <div class="section-heading">
                    <div>
                        <i class="bi bi-cart3 text-success"></i>
                        <strong>Your order</strong>
                    </div>
                    <a href="#" class="small-link">Add Order</a>
                </div>

                <!-- ITEM 1 -->
                <div class="order-item">
                   <img
    src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&h=200&fit=crop"
    alt="Chicken Grill Bowl"
    class="food-image"
                         alt="Chicken Grill Bowl"
                         class="food-image">
                       

                    <div class="food-info">
                        <div class="food-name">
                            Chicken Grill Bowl
                            <small>400 kcal</small>
                        </div>
                        <p>Rp 60.000</p>
                    </div>

                    <div class="quantity-control">
                        <small>Change</small>
                        <div class="quantity-box">
                            <button onclick="changeQty('chicken', -1)">-</button>
                            <span id="chickenQty">1</span>
                            <button onclick="changeQty('chicken', 1)">+</button>
                        </div>
                    </div>
                </div>

                <!-- ITEM 2 -->
                <div class="order-item">
<img
    src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=200&h=200&fit=crop"
    alt="Caesar Salad"
    class="food-image"
>


                    <div class="food-info">
                        <div class="food-name">
                            Caesar Salad
                            <small>200 kcal</small>
                        </div>
                        <p>Rp 45.000</p>
                    </div>

                    <div class="quantity-control">
                        <small>Change</small>
                        <div class="quantity-box">
                            <button onclick="changeQty('salad', -1)">-</button>
                            <span id="saladQty">1</span>
                            <button onclick="changeQty('salad', 1)">+</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ORDER SUMMARY -->
            <section class="summary-card">
                <h6>Order summary</h6>

                <div class="summary-inner">
                    <div class="summary-row">
                        <span>Total Items (2 Menu's)</span>
                        <strong id="totalItems">Rp 105.000</strong>
                    </div>

                    <div class="summary-row calories">
                        <span>Total Calories</span>
                        <strong id="totalCalories">600 kcal</strong>
                    </div>

                    <div class="summary-row">
                        <span>Discount</span>
                        <strong>Rp 0</strong>
                    </div>

                    <div class="summary-row">
                        <span id="feeLabel">Pickup Fee</span>
                        <strong id="pickupFee">Rp 0</strong>
                    </div>

                    <hr>

                    <div class="summary-row total-payment">
                        <span>Total Payment</span>
                        <strong id="totalPayment">Rp 105.000</strong>
                    </div>
                </div>
            </section>
        </div>

        <!-- KOLOM KANAN -->
        <div class="col-lg-6">

            <!-- CONFIRM ORDER -->
            <section class="confirm-card mb-3">
                <div class="confirm-header">
                    <span class="leaf-icon">🍃</span>
                    <div>
                        <strong>Confirm your order</strong>
                        <p>Please Double Check Your Order Before Proceeding To Checkout.</p>
                    </div>
                </div>

                <!-- PICKUP INSTRUCTION -->
                <div class="instruction-box">
                    <h6>
                        <i class="bi bi-shop-window"></i>
                        How to pick up your order
                    </h6>

                    <div class="instruction-message">
                        <i class="bi bi-info-circle"></i>
                        <span id="instructionText">
                            Show This Order Number When You Arrive At The Location.
                        </span>
                    </div>

                    <!-- TRANSACTION NUMBER -->
                    <div class="transaction-box">
                        <div>
                            <span>Transaction No.</span>
                            <strong>#PKT123456</strong>
                        </div>

                        <button class="copy-btn" onclick="copyTransaction()">
                            <i class="bi bi-clipboard"></i>
                            Copy
                        </button>
                    </div>

                    <!-- RESTAURANT INFORMATION -->
                    <div class="restaurant-information">
                        <h6>
                            <i class="bi bi-shop text-success"></i>
                            Restaurant information
                        </h6>

                        <div class="restaurant-detail">
                            <i class="bi bi-shop"></i>
                            <div>
                                <strong>EatWise</strong>
                                <small>Good Food, Smart Choices</small>
                                <p>Jl. Sukamaja No.13, Tapos, Cimpaeun, Depok, Jawa Barat</p>
                            </div>
                        </div>

                        <div class="open-time">
                            <i class="bi bi-clock"></i>
                            Open until 10 pm
                        </div>
                    </div>
                </div>
            </section>

            <!-- BUTTONS -->
            <div class="action-buttons">
                <button class="btn btn-confirm" onclick="confirmPayment()">
                    Confirm &amp; Pay
                </button>

                <button class="btn btn-back" onclick="backToCart()">
                    Back to Cart
                </button>
            </div>

        </div>
    </div>
</main>

<!-- NOTIFICATION -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="notification" class="toast" role="alert">
        <div class="toast-body" id="notificationText">
            Notification
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let chickenQty = 1;
    let saladQty = 1;
    let method = "Pickup";

    const chickenPrice = 60000;
    const saladPrice = 45000;
    const deliveryFee = 10000;

    function formatRupiah(number) {
        return "Rp " + number.toLocaleString("id-ID");
    }

    function changeQty(item, amount) {
        if (item === "chicken") {
            chickenQty = Math.max(0, chickenQty + amount);
            document.getElementById("chickenQty").textContent = chickenQty;
        } else {
            saladQty = Math.max(0, saladQty + amount);
            document.getElementById("saladQty").textContent = saladQty;
        }

        updateSummary();
    }

    function updateSummary() {
        const subtotal =
            chickenQty * chickenPrice +
            saladQty * saladPrice;

        const totalItems = chickenQty + saladQty;
        const calories = chickenQty * 400 + saladQty * 200;
        const fee = method === "Delivery" && totalItems > 0
            ? deliveryFee : 0;

        document.getElementById("totalItems").textContent =
            formatRupiah(subtotal);

        document.getElementById("totalCalories").textContent =
            calories + " kcal";

        document.querySelector(".summary-row:first-child span").textContent =
            "Total Items (" + totalItems + " Menu)";

        document.getElementById("pickupFee").textContent =
            formatRupiah(fee);

        document.getElementById("totalPayment").textContent =
            formatRupiah(subtotal + fee);
    }

    function selectMethod(selectedMethod) {
        method = selectedMethod;

        document.getElementById("deliveryBtn")
            .classList.toggle("active", method === "Delivery");

        document.getElementById("pickupBtn")
            .classList.toggle("active", method === "Pickup");

        document.getElementById("methodLabel").innerHTML =
            method === "Pickup"
            ? '<i class="bi bi-bag-check"></i> Pickup'
            : '<i class="bi bi-truck"></i> Delivery';

        document.getElementById("timeLabel").innerHTML =
            method === "Pickup"
            ? '<i class="bi bi-clock-history"></i> Pick up in ±15 minutes'
            : '<i class="bi bi-clock-history"></i> Delivery in ±30 minutes';

        document.getElementById("instructionText").textContent =
            method === "Pickup"
            ? "Show This Order Number When You Arrive At The Location."
            : "Your order will be delivered to your registered address.";

        document.getElementById("feeLabel").textContent =
            method === "Pickup" ? "Pickup Fee" : "Delivery Fee";

        updateSummary();
    }

    function showNotification(message) {
        document.getElementById("notificationText").textContent = message;

        const toastElement = document.getElementById("notification");
        bootstrap.Toast.getOrCreateInstance(toastElement).show();
    }

    function copyTransaction() {
        const transaction = "#PKT123456";

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(transaction)
                .then(() => showNotification("Transaction number copied!"))
                .catch(() => showNotification("Nomor transaksi: " + transaction));
        } else {
            showNotification("Nomor transaksi: " + transaction);
        }
    }

    function confirmPayment() {
        if (chickenQty + saladQty === 0) {
            showNotification("Silakan pilih minimal satu menu.");
            return;
        }

        showNotification("Pesanan siap dikonfirmasi. Total: " +
            document.getElementById("totalPayment").textContent);
    }

    function backToCart() {
        showNotification("Kembali ke halaman cart.");
    }

    updateSummary();
</script>

</body>
</html>
