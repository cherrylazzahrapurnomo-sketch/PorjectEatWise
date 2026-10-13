
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EatWise - Checkout</title>

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
                <li class="nav-item"><a class="nav-link active" href="cart.php">CARTS</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">ACCOUNT</a></li>
                <li class="nav-item"><a class="nav-link logout" href="logout.php">LOGOUT</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="checkout-container">

    <div class="checkout-grid">

        <!-- LEFT COLUMN -->
        <section class="left-column">

            <!-- DELIVERY / PICKUP -->
            <div class="delivery-card">

                <div class="delivery-tabs">
                    <button class="delivery-tab active" type="button"
                        onclick="selectService(this, 'Delivery')">
                        Delivery
                    </button>

                    <button class="delivery-tab" type="button"
                        onclick="selectService(this, 'Pickup')">
                        Pickup
                    </button>
                </div>

                <div class="delivery-content">

                    <div class="address-heading">
                        <i class="bi bi-geo-alt"></i>
                        <strong id="addressTitle">
                            Jl. Sujatmoko No.25, Cilodong, Cilodong, Depok, Jawa Barat
                        </strong>
                    </div>

                    <div class="recipient">
                        Jeje Jenner <span>|</span> (+62)81234567891
                    </div>

                    <div class="address-detail">
                        <span>Add Address Details...</span>
                        <a href="address.php">Change</a>
                    </div>

                    <div class="delivery-label">
                        <span>
                            <i class="bi bi-truck"></i>
                            <strong id="serviceLabel">Delivery</strong>
                            <small id="serviceDistance">(2.5 km)</small>
                            <i class="bi bi-info-circle"></i>
                        </span>
                        <a href="address.php">Change</a>
                    </div>

                    <div class="shipping-option">
                        <strong>Priority &lt; 20 minutes</strong>
                        <small>Get a 25k Discount Voucher If Late.</small>
                    </div>

                    <div class="shipping-option selected">
                        <strong><i class="bi bi-check-lg"></i> Standard - 25 minutes</strong>
                    </div>

                    <div class="shipping-option">
                        <strong>Economy - 45 minutes</strong>
                        <small>Cheaper Shipping Costs</small>
                    </div>

                </div>
            </div>

            <!-- ORDERED FOOD -->
            <div class="food-order-card">

                <div class="restaurant-heading">
                    <div>
                        <i class="bi bi-shop"></i>
                        <strong>EatWise</strong>
                        <span>Good Food, Smart Choices</span>
                    </div>
                    <a href="home.php">Add Order</a>
                </div>

                <!-- FOOD 1 -->
                <div class="food-item">
                    <img
                        src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=200&q=80"
                        alt="Chicken Grill Bowl"
                    >

                    <div class="food-details">
                        <div class="food-title">
                            <h3>Chicken Grill Bowl</h3>
                            <span>400 kcal</span>
                        </div>
                        <p>Rp 60.000</p>
                    </div>

                    <div class="quantity-area">
                        <a href="food-detail.php">Change</a>
                        <div class="quantity-control">
                            <button type="button" onclick="changeQuantity('qty1', -1)">-</button>
                            <span id="qty1">1</span>
                            <button type="button" onclick="changeQuantity('qty1', 1)">+</button>
                        </div>
                    </div>
                </div>

                <!-- FOOD 2 -->
                <div class="food-item">
                    <img
                        src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=200&q=80"
                        alt="Caesar Salad"
                    >

                    <div class="food-details">
                        <div class="food-title">
                            <h3>Caesar Salad</h3>
                            <span>200 kcal</span>
                        </div>
                        <p>Rp 45.000</p>
                    </div>

                    <div class="quantity-area">
                        <a href="food-detail.php">Change</a>
                        <div class="quantity-control">
                            <button type="button" onclick="changeQuantity('qty2', -1)">-</button>
                            <span id="qty2">1</span>
                            <button type="button" onclick="changeQuantity('qty2', 1)">+</button>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- RIGHT COLUMN -->
        <section class="right-column">

            <div class="summary-card">

                <div class="summary-heading">
                    <i class="bi bi-leaf-fill summary-leaf"></i>
                    <div>
                        <h2>Confirm your order</h2>
                        <p>Please Double Check Your Order Before Proceeding To Checkout.</p>
                    </div>
                </div>

                <h3 class="summary-title">Order summary</h3>

                <!-- PAYMENT -->
                <div class="summary-option">
                    <i class="bi bi-wallet2 option-icon"></i>
                    <div class="option-details">
                        <strong>Payment Method</strong>
                        <span>GoPay (E-Wallet)</span>
                    </div>
                    <a href="payment.php">Change</a>
                </div>

                <!-- DIETARY NOTE -->
                <div class="summary-option dietary-option">
                    <i class="bi bi-egg option-icon"></i>
                    <div class="option-details">
                        <strong>Dietary Note</strong>
                        <span>No seafood, please.</span>
                    </div>
                </div>

                <!-- TOTAL -->
                <div class="total-card">

                    <div class="total-row">
                        <strong>Total Items (2 Menus)</strong>
                        <strong id="totalItems">Rp 105.000</strong>
                    </div>

                    <div class="total-row calories-row">
                        <strong>Total Calories</strong>
                        <strong id="totalCalories">600 kcal</strong>
                    </div>

                    <div class="total-row">
                        <span>Discount</span>
                        <span>Rp 0</span>
                    </div>

                    <div class="total-row">
                        <span id="feeLabel">Delivery Fee</span>
                        <span id="deliveryFee">Rp 0</span>
                    </div>

                    <div class="total-divider"></div>

                    <div class="total-row final-total">
                        <strong>Total Payment</strong>
                        <strong id="totalPayment">Rp 105.000</strong>
                    </div>

                </div>
            </div>

            <div class="checkout-actions">
                <button class="confirm-button" type="button"
                    onclick="confirmOrder()">
                    Confirm &amp; Pay
                </button>

                <a href="cart.php" class="back-button">Back to Cart</a>
            </div>

        </section>

    </div>
</main>

<script>
    const prices = {
        qty1: 60000,
        qty2: 45000
    };

    const calories = {
        qty1: 400,
        qty2: 200
    };

    let service = "Delivery";

    function formatRupiah(number) {
        return "Rp " + number.toLocaleString("id-ID");
    }

    function changeQuantity(id, amount) {
        const element = document.getElementById(id);
        let quantity = parseInt(element.textContent);

        quantity = Math.max(1, quantity + amount);
        element.textContent = quantity;

        updateSummary();
    }

    function updateSummary() {
        const q1 = parseInt(document.getElementById("qty1").textContent);
        const q2 = parseInt(document.getElementById("qty2").textContent);

        const subtotal = (q1 * prices.qty1) + (q2 * prices.qty2);
        const totalCalories = (q1 * calories.qty1) + (q2 * calories.qty2);
        const totalMenus = q1 + q2;

        document.getElementById("totalItems").textContent = formatRupiah(subtotal);
        document.getElementById("totalCalories").textContent = totalCalories + " kcal";

        const fee = service === "Delivery" ? 0 : 0;

        document.getElementById("feeLabel").textContent =
            service === "Delivery" ? "Delivery Fee" : "Pickup Fee";

        document.getElementById("deliveryFee").textContent = formatRupiah(fee);
        document.getElementById("totalPayment").textContent = formatRupiah(subtotal + fee);

        document.querySelector(".total-row:first-child strong:first-child").textContent =
            "Total Items (" + totalMenus + " Menus)";
    }

    function selectService(button, selectedService) {
        service = selectedService;

        document.querySelectorAll(".delivery-tab").forEach(tab => {
            tab.classList.remove("active");
        });

        button.classList.add("active");

        document.getElementById("serviceLabel").textContent = selectedService;
        document.getElementById("serviceDistance").textContent =
            selectedService === "Delivery" ? "(2.5 km)" : "(Self pickup)";

        document.getElementById("addressTitle").textContent =
            selectedService === "Delivery"
                ? "Jl. Sujatmoko No.25, Cilodong, Cilodong, Depok, Jawa Barat"
                : "EatWise Restaurant - Pickup Counter";

        updateSummary();
    }

    function confirmOrder() {
        alert("Checkout demo: tombol berhasil diklik. Hubungkan ke proses pemesanan untuk melanjutkan pembayaran.");
    }

    updateSummary();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
