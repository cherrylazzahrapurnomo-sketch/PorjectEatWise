<?php
// ============================================================================
// EatWise - Order History Page (Pure Procedural PHP Native)
// Delivered / Success: #4E5134, Cancelled: #752E2A
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

require_login();
$conn = db_connect();
$user_id = get_logged_in_user_id();

// Status filter
$filter = trim($_GET['status'] ?? 'All');
$where_filter = "";
if ($filter !== 'All') {
    $safe_filter = db_escape($filter);
    $where_filter = " AND LOWER(o.status) = LOWER('$safe_filter')";
}

// Retrieve user's order history
$orders_sql = "SELECT o.*, a.recipient_name, a.full_address, a.city, pm.payment_method 
               FROM orders o
               LEFT JOIN address a ON o.address_id = a.id
               LEFT JOIN payment_method pm ON o.payment_id = pm.id
               WHERE o.user_id = $user_id $where_filter
               ORDER BY o.id DESC";
$orders_res = mysqli_query($conn, $orders_sql);

$orders = [];
if ($orders_res) {
    while ($ord = mysqli_fetch_assoc($orders_res)) {
        $ord_id = (int)$ord['id'];
        
        // Retrieve items per order
        $items_sql = "SELECT oi.*, f.food_name, f.img 
                      FROM order_item oi
                      JOIN food f ON oi.food_id = f.id
                      WHERE oi.order_id = $ord_id";
        $i_res = mysqli_query($conn, $items_sql);
        
        $items = [];
        $order_total = 0;
        $order_qty = 0;
        if ($i_res) {
            while ($item = mysqli_fetch_assoc($i_res)) {
                $sub = (float)$item['unit_price'] * (int)$item['quantity'];
                $item['subtotal'] = $sub;
                $order_total += $sub;
                $order_qty += (int)$item['quantity'];
                $items[] = $item;
            }
        }
        $ord['items'] = $items;
        $ord['total_amount'] = $order_total;
        $ord['total_qty'] = $order_qty;
        $orders[] = $ord;
    }
}

$page_title = 'Order History';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold fs-3 text-maroon font-playfair mb-1">Your Order History</h1>
            <p class="text-muted small mb-0">Track order statuses and revisit previous nutritious meals.</p>
        </div>
        <a href="home.php" class="btn btn-ew-primary btn-sm">
            <i class="bi bi-cart-plus me-1"></i> Order New Dishes
        </a>
    </div>

    <!-- FILTER TABS -->
    <div class="category-pills mb-4">
        <a href="orderhistory.php" class="category-pill <?= ($filter === 'All') ? 'active' : '' ?>">
            All (<?= count($orders) ?>)
        </a>
        <a href="orderhistory.php?status=Delivered" class="category-pill <?= (strcasecmp($filter, 'Delivered') === 0) ? 'active' : '' ?>">
            <i class="bi bi-check2-all me-1"></i> Delivered
        </a>
        <a href="orderhistory.php?status=Pending" class="category-pill <?= (strcasecmp($filter, 'Pending') === 0) ? 'active' : '' ?>">
            <i class="bi bi-clock-history me-1"></i> Processing / In Transit
        </a>
        <a href="orderhistory.php?status=Cancelled" class="category-pill <?= (strcasecmp($filter, 'Cancelled') === 0) ? 'active' : '' ?>">
            <i class="bi bi-x-circle me-1"></i> Cancelled
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 px-3 bg-white">
            <div class="mb-3 text-muted fs-1"><i class="bi bi-receipt-cutoff"></i></div>
            <h5 class="fw-bold text-maroon font-playfair">No Orders Found</h5>
            <p class="text-muted small">You do not have any orders matching this status filter.</p>
            <div class="mt-2">
                <a href="home.php" class="btn btn-ew-primary px-4 py-2">Start Ordering Now</a>
            </div>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($orders as $ord): 
                $st = strtolower($ord['status']);
                $badge_class = 'bg-warning text-dark';
                $badge_icon = 'bi-clock-history';
                if ($st === 'delivered') {
                    $badge_class = 'bg-olive text-white';
                    $badge_icon = 'bi-check2-circle';
                } elseif ($st === 'cancelled') {
                    $badge_class = 'bg-maroon text-white';
                    $badge_icon = 'bi-x-circle';
                }
            ?>
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3 p-md-4 ew-card">
                    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3 flex-wrap gap-2">
                        <div>
                            <span class="fw-bold text-maroon fs-6 me-2">#EW-<?= str_pad($ord['id'], 6, '0', STR_PAD_LEFT) ?></span>
                            <span class="text-muted small">&bull; <?= date('d M Y, H:i', strtotime($ord['order_date'])) ?></span>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-credit-card me-1"></i><?= htmlspecialchars($ord['payment_method'] ?? 'E-Wallet') ?>
                                &bull; <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($ord['recipient_name'] ?? 'Recipient') ?> (<?= htmlspecialchars($ord['city'] ?? 'City') ?>)
                            </div>
                        </div>
                        <div>
                            <span class="badge <?= $badge_class ?> rounded-pill px-3 py-2 small">
                                <i class="bi <?= $badge_icon ?> me-1"></i> <?= ucfirst($ord['status']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- ORDER ITEMS PREVIEW -->
                    <div class="row g-2 align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-3">
                                <?php foreach ($ord['items'] as $it): ?>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars(get_food_image_url($it['img'], $it['food_name'])) ?>" 
                                             alt="<?= htmlspecialchars($it['food_name']) ?>" 
                                             class="rounded-3 me-2" style="width: 44px; height: 44px; object-fit: cover;">
                                        <div>
                                            <div class="fw-semibold text-dark small"><?= htmlspecialchars($it['food_name']) ?></div>
                                            <div class="text-muted" style="font-size: 0.72rem;"><?= $it['quantity'] ?>x @ <?= format_rupiah($it['unit_price']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-md-4 text-md-end pt-3 pt-md-0 border-top border-md-top-0">
                            <small class="text-muted d-block" style="font-size:0.75rem;">Total Amount</small>
                            <span class="fw-bold fs-5 text-maroon font-playfair"><?= format_rupiah($ord['total_amount']) ?></span>
                            <div class="mt-2 d-flex justify-content-md-end gap-2">
                                <a href="orderConfirmed.php?order_id=<?= $ord['id'] ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    <i class="bi bi-receipt me-1"></i> Details
                                </a>
                                <?php if ($st === 'delivered'): ?>
                                    <a href="home.php" class="btn btn-ew-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Reorder
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>