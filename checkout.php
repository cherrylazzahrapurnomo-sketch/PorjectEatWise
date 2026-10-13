<?php
// ============================================================================
// EatWise - Checkout Page (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

// Login required for checkout
require_login();

$conn = db_connect();
$user_id = get_logged_in_user_id();
$user = get_logged_in_user();

// Retrieve cart items
$cart_items = get_cart_items();
if (empty($cart_items)) {
    set_flash('warning', 'Your shopping cart is empty. Please select healthy meals first.');
    header('Location: home.php');
    exit;
}

// Retrieve user's saved addresses
$addresses = [];
$addr_res = mysqli_query($conn, "SELECT * FROM address WHERE user_id = $user_id ORDER BY id DESC");
if ($addr_res) {
    while ($ar = mysqli_fetch_assoc($addr_res)) {
        $addresses[] = $ar;
    }
}

// Retrieve payment methods
$payment_methods = [];
$pm_res = mysqli_query($conn, "SELECT * FROM payment_method ORDER BY id ASC");
if ($pm_res) {
    while ($pm = mysqli_fetch_assoc($pm_res)) {
        $payment_methods[] = $pm;
    }
}

// Calculate totals
$subtotal_price = 0;
$total_calories = 0;
$total_qty = 0;
foreach ($cart_items as $item) {
    $subtotal_price += (float)$item['subtotal'];
    $total_calories += ((int)$item['calories'] * (int)$item['qty']);
    $total_qty += (int)$item['qty'];
}

// Handle Order Placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    $service_type = $_POST['service_type'] ?? 'Delivery'; // Delivery / Pickup
    $address_id = (int)($_POST['address_id'] ?? 0);
    $payment_id = (int)($_POST['payment_id'] ?? 1);
    $shipping_speed = $_POST['shipping_speed'] ?? 'standard';
    $dietary_note = trim($_POST['dietary_note'] ?? '');

    // If new address entered
    if ($service_type === 'Delivery' && $address_id <= 0) {
        $recipient_name = db_escape(trim($_POST['new_recipient'] ?? $user['nama']));
        $phone = db_escape(trim($_POST['new_phone'] ?? '08123456789'));
        $full_address = db_escape(trim($_POST['new_address'] ?? 'Jl. Melati No. 10'));
        $city = db_escape(trim($_POST['new_city'] ?? 'South Jakarta'));
        $notes = db_escape(trim($_POST['new_notes'] ?? ''));

        $ins_addr = "INSERT INTO address (user_id, recipient_name, phone, full_address, city, notes) 
                    VALUES ($user_id, '$recipient_name', '$phone', '$full_address', '$city', '$notes')";
        if (mysqli_query($conn, $ins_addr)) {
            $address_id = mysqli_insert_id($conn);
        }
    }

    // Fallback if Pickup or no address found
    if ($address_id <= 0 && !empty($addresses)) {
        $address_id = (int)$addresses[0]['id'];
    } elseif ($address_id <= 0) {
        $ins_def = "INSERT INTO address (user_id, recipient_name, phone, full_address, city, notes) 
                    VALUES ($user_id, 'EatWise Central Kitchen', '081234567890', 'EatWise Kitchen, Jl. Healthy Life No. 10', 'South Jakarta', 'Pickup')";
        mysqli_query($conn, $ins_def);
        $address_id = mysqli_insert_id($conn);
    }

    // Delivery fee
    $fee = 0;
    if ($service_type === 'Delivery') {
        if ($shipping_speed === 'priority') {
            $fee = 15000;
        } elseif ($shipping_speed === 'economy') {
            $fee = 5000;
        } else {
            $fee = ($subtotal_price >= 100000) ? 0 : 10000;
        }
    }

    $final_total = $subtotal_price + $fee;

    // Insert order record
    $order_sql = "INSERT INTO orders (user_id, address_id, payment_id, order_date, status) 
                  VALUES ($user_id, $address_id, $payment_id, NOW(), 'Pending')";

    if (mysqli_query($conn, $order_sql)) {
        $order_id = mysqli_insert_id($conn);

        // Insert items into order_item
        foreach ($cart_items as $ci) {
            $f_id = (int)$ci['food_id'];
            $f_qty = (int)$ci['qty'];
            $f_price = (float)$ci['price'];
            mysqli_query($conn, "INSERT INTO order_item (order_id, food_id, quantity, unit_price) 
                                 VALUES ($order_id, $f_id, $f_qty, $f_price)");
        }

        // Empty database cart items
        $cart_id = get_or_create_user_cart_id($user_id);
        if ($cart_id) {
            mysqli_query($conn, "DELETE FROM cart_item WHERE cart_id = $cart_id");
        }
        $_SESSION['guest_cart'] = [];
        $_SESSION['cart'] = [];

        // Save order session summary
        $_SESSION['last_order'] = [
            'order_id' => $order_id,
            'service_type' => $service_type,
            'shipping_speed' => $shipping_speed,
            'dietary_note' => $dietary_note,
            'fee' => $fee,
            'subtotal' => $subtotal_price,
            'total' => $final_total,
            'calories' => $total_calories
        ];

        set_flash('success', 'Your order has been placed successfully! Thank you for choosing EatWise.');
        header("Location: orderConfirmed.php?order_id=$order_id");
        exit;
    } else {
        $error = 'Failed to process order: ' . mysqli_error($conn);
    }
}

$page_title = 'Order Confirmation (Checkout)';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="cart.php" class="text-decoration-none text-muted">Cart</a></li>
            <li class="breadcrumb-item active text-maroon fw-semibold" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <div class="row g-4">

        <!-- LEFT COLUMN: ADDRESS, DELIVERY & PAYMENT DETAILS -->
        <div class="col-lg-7">

            <form method="POST" action="checkout.php" id="checkoutForm">
                <input type="hidden" name="action" value="place_order">

                <!-- 1. SERVICE TYPE: DELIVERY OR PICKUP -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-maroon font-playfair mb-3">
                        <i class="bi bi-bicycle text-maroon me-2"></i>1. Fulfillment Method
                    </h5>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="fulfillment-card d-block p-3 rounded-3 border text-center cursor-pointer">
                                <input type="radio" name="service_type" value="Delivery" id="serviceDelivery" checked class="form-check-input me-2" onchange="toggleServiceType()">
                                <strong class="text-dark"><i class="bi bi-truck me-1 text-maroon"></i> Home Delivery</strong>
                                <small class="d-block text-muted" style="font-size: 0.75rem;">Dispatched to your address</small>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="fulfillment-card d-block p-3 rounded-3 border text-center cursor-pointer">
                                <input type="radio" name="service_type" value="Pickup" id="servicePickup" class="form-check-input me-2" onchange="toggleServiceType()">
                                <strong class="text-dark"><i class="bi bi-shop me-1 text-olive"></i> Self Pickup</strong>
                                <small class="d-block text-muted" style="font-size: 0.75rem;">Pick up at Central Kitchen</small>
                            </label>
                        </div>
                    </div>

                    <!-- DELIVERY ADDRESS SECTION -->
                    <div id="deliveryAddressSection">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-bold small text-muted mb-0">Delivery Address</label>
                            <a href="address.php" class="small text-maroon text-decoration-none fw-semibold">
                                <i class="bi bi-gear-fill me-1"></i> Manage Addresses
                            </a>
                        </div>

                        <?php if (!empty($addresses)): ?>
                            <div class="d-flex flex-column gap-2 mb-3">
                                <?php foreach ($addresses as $idx => $addr): ?>
                                    <label class="selectable-card address-card <?= ($idx === 0) ? 'selected' : '' ?>">
                                        <div class="d-flex align-items-start">
                                            <input type="radio" name="address_id" value="<?= $addr['id'] ?>" class="form-check-input me-3 mt-1" <?= ($idx === 0) ? 'checked' : '' ?>>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <strong class="text-dark"><?= htmlspecialchars($addr['recipient_name']) ?></strong>
                                                    <span class="badge bg-light text-muted border" style="font-size:0.7rem;"><?= htmlspecialchars($addr['city']) ?></span>
                                                </div>
                                                <small class="d-block text-muted"><?= htmlspecialchars($addr['full_address']) ?></small>
                                                <small class="text-muted"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($addr['phone']) ?></small>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Fallback New Address Input -->
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <p class="small text-muted mb-2"><i class="bi bi-info-circle me-1"></i> No saved address found. Please enter your delivery address:</p>
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <input type="text" name="new_recipient" class="form-control form-control-sm bg-white" placeholder="Recipient Name" value="<?= htmlspecialchars($user['nama']) ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="new_phone" class="form-control form-control-sm bg-white" placeholder="Phone Number" value="08123456789" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="new_address" class="form-control form-control-sm bg-white" placeholder="Full Street Address" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="new_city" class="form-control form-control-sm bg-white" placeholder="City (e.g., South Jakarta)" required>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- SHIPPING SPEED TILES -->
                        <label class="fw-bold small text-muted mb-2 d-block">Delivery Speed</label>
                        <div class="row g-2 mb-2">
                            <div class="col-4">
                                <div class="card p-2 text-center border rounded-3 h-100">
                                    <input type="radio" class="btn-check" name="shipping_speed" id="shipStandard" value="standard" checked onchange="updateShippingFee(0)">
                                    <label class="btn btn-outline-secondary border-0 p-1 w-100 h-100" for="shipStandard">
                                        <small class="d-block fw-bold text-dark">Standard</small>
                                        <span class="d-block text-muted" style="font-size: 0.72rem;">25 - 30 Mins</span>
                                        <span class="badge bg-olive text-white mt-1"><?= ($subtotal_price >= 100000) ? 'FREE' : '+Rp 10.000' ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card p-2 text-center border rounded-3 h-100">
                                    <input type="radio" class="btn-check" name="shipping_speed" id="shipPriority" value="priority" onchange="updateShippingFee(15000)">
                                    <label class="btn btn-outline-secondary border-0 p-1 w-100 h-100" for="shipPriority">
                                        <small class="d-block fw-bold text-dark">Priority</small>
                                        <span class="d-block text-muted" style="font-size: 0.72rem;">15 - 20 Mins</span>
                                        <span class="badge bg-maroon text-white mt-1">+Rp 15.000</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card p-2 text-center border rounded-3 h-100">
                                    <input type="radio" class="btn-check" name="shipping_speed" id="shipEconomy" value="economy" onchange="updateShippingFee(5000)">
                                    <label class="btn btn-outline-secondary border-0 p-1 w-100 h-100" for="shipEconomy">
                                        <small class="d-block fw-bold text-dark">Economy</small>
                                        <span class="d-block text-muted" style="font-size: 0.72rem;">45 Mins</span>
                                        <span class="badge bg-secondary text-white mt-1">+Rp 5.000</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PICKUP OUTLET INFO (HIDDEN BY DEFAULT) -->
                    <div id="pickupOutletSection" style="display: none;" class="p-3 bg-light rounded-3 mb-2">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-shop-window text-maroon fs-4 me-2"></i>
                            <div>
                                <strong class="text-dark">EatWise Central Kitchen Outlet</strong>
                                <p class="text-muted small mb-0">Jl. Healthy Life No. 10, Kebayoran, South Jakarta</p>
                            </div>
                        </div>
                        <small class="badge bg-olive text-white">Order ready for pickup in 15-20 minutes</small>
                    </div>

                </div>

                <!-- 2. PAYMENT METHOD SELECTION -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-maroon font-playfair mb-3">
                        <i class="bi bi-credit-card-2-front-fill text-maroon me-2"></i>2. Payment Method
                    </h5>
                    
                    <div class="d-flex flex-column gap-2" id="paymentOptionsContainer">
                        <?php foreach ($payment_methods as $idx => $pm): ?>
                            <label class="selectable-card payment-card <?= ($idx === 0) ? 'selected' : '' ?>">
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="payment_id" value="<?= $pm['id'] ?>" class="form-check-input me-3" <?= ($idx === 0) ? 'checked' : '' ?>>
                                    <div class="flex-grow-1">
                                        <strong class="text-dark"><?= htmlspecialchars($pm['payment_method']) ?></strong>
                                        <small class="d-block text-muted" style="font-size:0.75rem;">
                                            <?php if ($pm['id'] == 1): ?>
                                                BCA, Mandiri, BNI, BRI Virtual Account
                                            <?php elseif ($pm['id'] == 2): ?>
                                                GoPay, OVO, ShopeePay, DANA (Zero Admin Fee)
                                            <?php else: ?>
                                                Cash on Delivery (Pay cash when your meal arrives)
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-light text-muted border">Active</span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 3. DIETARY NOTE -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-maroon font-playfair mb-2">
                        <i class="bi bi-chat-heart-fill text-maroon me-2"></i>3. Kitchen &amp; Dietary Notes
                    </h5>
                    <p class="text-muted small mb-2">Specify special instructions (e.g. sauce on the side, extra dressing, less salt).</p>
                    <textarea name="dietary_note" class="form-control bg-light" rows="2" placeholder="Example: Please pack dressing separately and keep it mild..."></textarea>
                </div>

                <!-- SUBMIT BUTTON ON MOBILE -->
                <div class="d-lg-none mb-4">
                    <button type="submit" class="btn btn-ew-primary w-100 py-3 fs-6 shadow">
                        <i class="bi bi-check-circle-fill me-2"></i> Confirm &amp; Place Order
                    </button>
                </div>

            </form>

        </div>

        <!-- RIGHT COLUMN: ORDER SUMMARY -->
        <div class="col-lg-5">
            <div class="order-summary-card sticky-top" style="top: 90px;">
                <h5 class="fw-bold text-maroon font-playfair mb-3">Your Order (<?= $total_qty ?> items)</h5>

                <!-- LIST OF ITEMS -->
                <div class="mb-3" style="max-height: 240px; overflow-y: auto;">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="<?= htmlspecialchars(get_food_image_url($item['img'], $item['food_name'])) ?>" 
                                     alt="<?= htmlspecialchars($item['food_name']) ?>" 
                                     class="rounded-3 me-2" style="width: 44px; height: 44px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small"><?= htmlspecialchars($item['food_name']) ?></h6>
                                    <small class="text-muted"><?= $item['qty'] ?>x @ <?= format_rupiah($item['price']) ?></small>
                                </div>
                            </div>
                            <span class="fw-bold text-maroon small"><?= format_rupiah($item['subtotal']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- TOTALS -->
                <div class="summary-row">
                    <span class="text-muted">Total Calories</span>
                    <strong class="text-danger"><i class="bi bi-fire"></i> <?= $total_calories ?> kcal</strong>
                </div>

                <div class="summary-row">
                    <span class="text-muted">Meal Subtotal</span>
                    <strong class="text-dark"><?= format_rupiah($subtotal_price) ?></strong>
                </div>

                <div class="summary-row">
                    <span class="text-muted">Delivery Fee</span>
                    <span id="shippingFeeLabel"><?= ($subtotal_price >= 100000) ? '<span class="badge bg-olive text-white">FREE</span>' : format_rupiah(10000) ?></span>
                </div>

                <div class="summary-row total-row">
                    <span>Total Payment</span>
                    <span class="text-maroon fs-5 font-playfair" id="totalPaymentLabel"><?= format_rupiah($subtotal_price + (($subtotal_price >= 100000) ? 0 : 10000)) ?></span>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-ew-primary w-100 py-3 fs-6 shadow" onclick="document.getElementById('checkoutForm').submit();">
                        <i class="bi bi-check-circle-fill me-2"></i> Confirm &amp; Place Order
                    </button>
                    <a href="cart.php" class="btn btn-outline-secondary w-100 mt-2 py-2 small rounded-3">
                        <i class="bi bi-arrow-left me-1"></i> Return to Cart
                    </a>
                </div>

                <div class="mt-3 text-center">
                    <small class="text-muted" style="font-size:0.75rem;">
                        <i class="bi bi-shield-lock-fill text-maroon me-1"></i> 100% Encrypted &amp; Secure Checkout
                    </small>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
const baseSubtotal = <?= (float)$subtotal_price ?>;
let currentShippingFee = <?= ($subtotal_price >= 100000) ? 0 : 10000 ?>;
let isDelivery = true;

// Toggle Delivery / Pickup
function toggleServiceType() {
    const isDeliv = document.getElementById('serviceDelivery').checked;
    isDelivery = isDeliv;
    const addrSec = document.getElementById('deliveryAddressSection');
    const pickSec = document.getElementById('pickupOutletSection');

    if (isDeliv) {
        addrSec.style.display = 'block';
        pickSec.style.display = 'none';
        currentShippingFee = (baseSubtotal >= 100000) ? 0 : 10000;
    } else {
        addrSec.style.display = 'none';
        pickSec.style.display = 'block';
        currentShippingFee = 0;
    }
    recalculateTotal();
}

function updateShippingFee(extra) {
    if (!isDelivery) return;
    if (extra === 15000) {
        currentShippingFee = 15000;
    } else if (extra === 5000) {
        currentShippingFee = 5000;
    } else {
        currentShippingFee = (baseSubtotal >= 100000) ? 0 : 10000;
    }
    recalculateTotal();
}

function recalculateTotal() {
    const feeLabel = document.getElementById('shippingFeeLabel');
    const totalLabel = document.getElementById('totalPaymentLabel');

    if (currentShippingFee === 0) {
        feeLabel.innerHTML = '<span class="badge bg-olive text-white">FREE</span>';
    } else {
        feeLabel.innerText = 'Rp ' + currentShippingFee.toLocaleString('id-ID');
    }

    const total = baseSubtotal + currentShippingFee;
    totalLabel.innerText = 'Rp ' + total.toLocaleString('id-ID');
}

// Synchronize Payment Method Card Selection (.selected state)
document.querySelectorAll('input[name="payment_id"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-card').forEach(function(card) {
            card.classList.remove('selected');
        });
        const parentCard = this.closest('.payment-card');
        if (parentCard) {
            parentCard.classList.add('selected');
        }
    });
});

// Synchronize Address Card Selection
document.querySelectorAll('input[name="address_id"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.address-card').forEach(function(card) {
            card.classList.remove('selected');
        });
        const parentCard = this.closest('.address-card');
        if (parentCard) {
            parentCard.classList.add('selected');
        }
    });
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
