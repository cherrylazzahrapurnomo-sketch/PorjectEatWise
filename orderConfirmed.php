<?php
// ============================================================================
// EatWise - Order Confirmed Page (Pure Procedural PHP Native)
// Payment Success: #4E5134, Error Prevention / Cancelled: #752E2A
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

require_login();
$conn = db_connect();
$user_id = get_logged_in_user_id();

$order_id = (int)($_GET['order_id'] ?? 0);

// Handle Cancel Order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $cancel_id = (int)($_POST['order_id'] ?? 0);
    $upd = mysqli_query($conn, "UPDATE orders SET status = 'Cancelled' WHERE id = $cancel_id AND user_id = $user_id");
    if ($upd) {
        set_flash('info', 'Your order has been cancelled.');
    }
    header("Location: orderConfirmed.php?order_id=$cancel_id");
    exit;
}

// Retrieve order data from database
$sql = "SELECT o.*, a.recipient_name, a.phone, a.full_address, a.city, pm.payment_method 
        FROM orders o
        LEFT JOIN address a ON o.address_id = a.id
        LEFT JOIN payment_method pm ON o.payment_id = pm.id
        WHERE o.id = $order_id AND o.user_id = $user_id LIMIT 1";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    // Try retrieving user's latest order as fallback
    $last_res = mysqli_query($conn, "SELECT o.*, a.recipient_name, a.phone, a.full_address, a.city, pm.payment_method 
                                     FROM orders o
                                     LEFT JOIN address a ON o.address_id = a.id
                                     LEFT JOIN payment_method pm ON o.payment_id = pm.id
                                     WHERE o.user_id = $user_id 
                                     ORDER BY o.id DESC LIMIT 1");
    if ($last_res && mysqli_num_rows($last_res) > 0) {
        $order = mysqli_fetch_assoc($last_res);
        $order_id = (int)$order['id'];
    } else {
        set_flash('warning', 'Order record not found.');
        header('Location: home.php');
        exit;
    }
} else {
    $order = mysqli_fetch_assoc($res);
}

// Retrieve order items
$items_sql = "SELECT oi.*, f.food_name, f.img, c.category_name 
              FROM order_item oi
              JOIN food f ON oi.food_id = f.id
              LEFT JOIN category c ON f.category_id = c.id
              WHERE oi.order_id = $order_id";
$items_res = mysqli_query($conn, $items_sql);
$order_items = [];
$total_amount = 0;
$total_calories = 0;
if ($items_res) {
    while ($ir = mysqli_fetch_assoc($items_res)) {
        $ir['calories'] = get_food_calories($ir['food_id'], $ir['food_name']);
        $ir['line_total'] = (float)$ir['unit_price'] * (int)$ir['quantity'];
        $total_amount += $ir['line_total'];
        $total_calories += ((int)$ir['calories'] * (int)$ir['quantity']);
        $order_items[] = $ir;
    }
}

$is_cancelled = strtolower($order['status']) === 'cancelled';

$page_title = $is_cancelled ? 'Order Cancelled' : 'Order Confirmed';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white text-center">

                <!-- STATUS ICON -->
                <div class="mb-4">
                    <?php if ($is_cancelled): ?>
                        <div class="status-success-circle bg-maroon-subtle text-maroon border border-maroon mx-auto">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <h2 class="fw-bold text-maroon font-playfair mb-1">Order Cancelled</h2>
                        <p class="text-muted small">This order has been cancelled upon your request.</p>
                    <?php else: ?>
                        <!-- PAYMENT SUCCESS GREEN: #4E5134 -->
                        <div class="status-success-circle mx-auto">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h2 class="fw-bold text-maroon font-playfair mb-1">Order Successfully Confirmed!</h2>
                        <p class="text-muted small">
                            Thank you! Your payment is verified and our kitchen is carefully preparing your nutritious meal.
                        </p>
                    <?php endif; ?>
                </div>

                <!-- ORDER INFO BAR -->
                <div class="row g-2 p-3 bg-light rounded-4 text-start mb-4">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block" style="font-size:0.75rem;">Order Number</small>
                        <strong class="text-maroon">#EW-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block" style="font-size:0.75rem;">Order Date</small>
                        <strong class="text-dark"><?= date('d M Y, H:i', strtotime($order['order_date'])) ?></strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block" style="font-size:0.75rem;">Order Status</small>
                        <?php if ($is_cancelled): ?>
                            <span class="badge bg-maroon text-white">Cancelled</span>
                        <?php elseif (strtolower($order['status']) === 'delivered'): ?>
                            <span class="badge bg-olive text-white">Delivered</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i> Processing</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block" style="font-size:0.75rem;">Estimated Arrival</small>
                        <strong class="text-olive"><i class="bi bi-truck me-1"></i> 25 - 30 Mins</strong>
                    </div>
                </div>

                <!-- ORDER DETAILS LIST -->
                <div class="text-start mb-4">
                    <h5 class="fw-bold text-maroon font-playfair mb-3">Ordered Menu Items:</h5>
                    <div class="list-group rounded-3">
                        <?php foreach ($order_items as $oi): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars(get_food_image_url($oi['img'], $oi['food_name'])) ?>" 
                                         alt="<?= htmlspecialchars($oi['food_name']) ?>" 
                                         class="rounded-3 me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?= htmlspecialchars($oi['food_name']) ?></h6>
                                        <small class="text-muted"><?= $oi['quantity'] ?> portion(s) &bull; <?= (int)$oi['calories'] * (int)$oi['quantity'] ?> kcal</small>
                                    </div>
                                </div>
                                <span class="fw-bold text-maroon font-playfair"><?= format_rupiah($oi['line_total']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- DELIVERY & PAYMENT SUMMARY -->
                <div class="row g-3 text-start mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100 bg-light">
                            <span class="small fw-bold text-maroon d-block mb-1">
                                <i class="bi bi-geo-alt-fill me-1"></i> Delivery Details
                            </span>
                            <div class="fw-bold text-dark small"><?= htmlspecialchars($order['recipient_name'] ?? 'Recipient') ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($order['full_address'] ?? 'EatWise Central Kitchen') ?></div>
                            <div class="text-muted small"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($order['phone'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100 bg-light">
                            <span class="small fw-bold text-olive d-block mb-1">
                                <i class="bi bi-credit-card-2-front me-1"></i> Payment Summary
                            </span>
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Method</span>
                                <strong class="text-dark"><?= htmlspecialchars($order['payment_method'] ?? 'E-Wallet') ?></strong>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Total Calories</span>
                                <strong class="text-danger"><i class="bi bi-fire"></i> <?= $total_calories ?> kcal</strong>
                            </div>
                            <div class="d-flex justify-content-between fw-bold text-maroon border-top pt-1 mt-1">
                                <span>Total Paid</span>
                                <span class="font-playfair"><?= format_rupiah($total_amount) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="orderhistory.php" class="btn btn-ew-primary px-4 py-2">
                        <i class="bi bi-receipt me-1"></i> View All Orders
                    </a>
                    <a href="home.php" class="btn btn-ew-outline px-4 py-2">
                        <i class="bi bi-house me-1"></i> Back to Home
                    </a>
                    <?php if (!$is_cancelled && strtolower($order['status']) === 'pending'): ?>
                        <form method="POST" action="orderConfirmed.php?order_id=<?= $order['id'] ?>" class="d-inline m-0" onsubmit="return confirm('Cancel this order?');">
                            <input type="hidden" name="action" value="cancel">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-outline-danger border-maroon text-maroon px-4 py-2 rounded-3">
                                <i class="bi bi-x-circle me-1"></i> Cancel Order
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
