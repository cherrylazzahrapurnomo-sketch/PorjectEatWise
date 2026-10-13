<?php
// ============================================================================
// EatWise - Payment Methods (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

require_login();
$conn = db_connect();

// Retrieve payment methods from table
$methods = [];
$res = mysqli_query($conn, "SELECT * FROM payment_method ORDER BY id ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $methods[] = $row;
    }
}

$page_title = 'Payment Methods';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">

                <div class="d-flex align-items-center mb-4">
                    <a href="checkout.php" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <div>
                        <h3 class="fw-bold text-maroon font-playfair mb-0 fs-4">Payment Methods</h3>
                        <p class="text-muted small mb-0">Supported and verified checkout options at EatWise</p>
                    </div>
                </div>

                <div class="d-flex flex-column gap-3 mb-4">
                    <?php foreach ($methods as $idx => $m): ?>
                        <div class="selectable-card p-3 rounded-4 border bg-white d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="rounded-circle bg-maroon-subtle text-maroon p-3 me-3 fs-5 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <?php if ($m['id'] == 1): ?>
                                        <i class="bi bi-bank2"></i>
                                    <?php elseif ($m['id'] == 2): ?>
                                        <i class="bi bi-wallet2"></i>
                                    <?php else: ?>
                                        <i class="bi bi-cash-stack"></i>
                                    <?php endif; ?>
                                </span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0"><?= htmlspecialchars($m['payment_method']) ?></h6>
                                    <small class="text-muted">
                                        <?php if ($m['id'] == 1): ?>
                                            Virtual Account BCA, BNI, Mandiri, BRI
                                        <?php elseif ($m['id'] == 2): ?>
                                            GoPay, OVO, ShopeePay, DANA (Zero Admin Fee)
                                        <?php else: ?>
                                            Cash on delivery upon courier arrival
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                            <span class="badge bg-olive text-white rounded-pill px-3 py-2">Active</span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center">
                    <a href="checkout.php" class="btn btn-ew-primary w-100 py-3">
                        <i class="bi bi-arrow-left me-1"></i> Return to Checkout
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
