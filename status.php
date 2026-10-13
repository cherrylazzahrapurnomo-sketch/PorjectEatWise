<?php
// ============================================================================
// EatWise - Payment Status Page (Pure Procedural PHP Native)
// Green Payment Success: #4E5134, Secondary Maroon: #752E2A
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

require_login();
$conn = db_connect();
$user_id = get_logged_in_user_id();

// Retrieve latest user order
$sql = "SELECT o.*, pm.payment_method 
        FROM orders o 
        LEFT JOIN payment_method pm ON o.payment_id = pm.id 
        WHERE o.user_id = $user_id 
        ORDER BY o.id DESC LIMIT 1";
$res = mysqli_query($conn, $sql);
$order = ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;

$order_id = $order ? (int)$order['id'] : 0;
$trx_no = '#EW-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);

$page_title = 'Payment Success Status';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white text-center">

                <!-- PAYMENT SUCCESS GREEN: #4E5134 -->
                <div class="status-success-circle mx-auto">
                    <i class="bi bi-check-lg"></i>
                </div>

                <h2 class="fw-bold text-maroon font-playfair mb-2">Payment Successful!</h2>
                <p class="text-muted small mb-4">
                    Thank you! Your payment is confirmed and our kitchen has commenced preparing your order.
                </p>

                <div class="bg-light p-3 rounded-4 text-start mb-4">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Transaction ID</span>
                        <strong class="text-maroon"><?= $trx_no ?></strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Date &amp; Time</span>
                        <strong class="text-dark"><?= date('d M Y, H:i') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Payment Method</span>
                        <strong class="text-olive"><?= htmlspecialchars($order['payment_method'] ?? 'E-Wallet') ?></strong>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="orderConfirmed.php?order_id=<?= $order_id ?>" class="btn btn-ew-primary py-3">
                        <i class="bi bi-receipt me-1"></i> View Order Details
                    </a>
                    <a href="home.php" class="btn btn-ew-outline py-2">
                        <i class="bi bi-house me-1"></i> Back to Home
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>