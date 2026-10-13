<?php
// ============================================================================
// EatWise - Shopping Cart Page (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

$conn = db_connect();
$user_id = get_logged_in_user_id();

// Handle Actions (Increase, Decrease, Remove, Clear)
$req_action = $_POST['action'] ?? $_GET['action'] ?? '';
$item_id = $_POST['item_id'] ?? $_GET['item_id'] ?? '';

if (!empty($req_action)) {
    if ($req_action === 'clear') {
        if ($user_id) {
            // Delete all cart items across all carts associated with this user
            mysqli_query($conn, "DELETE FROM cart_item WHERE cart_id IN (SELECT id FROM cart WHERE user_id = $user_id)");
        }
        $_SESSION['guest_cart'] = [];
        $_SESSION['cart'] = [];
        set_flash('info', 'Your shopping cart has been emptied.');
    } elseif ($req_action === 'increase') {
        if ($user_id && is_numeric($item_id)) {
            $c_item_id = (int)$item_id;
            mysqli_query($conn, "UPDATE cart_item SET qty = qty + 1 WHERE id = $c_item_id");
        } else {
            $f_id = (int)str_replace('guest_', '', $item_id);
            if (isset($_SESSION['guest_cart'][$f_id])) {
                $_SESSION['guest_cart'][$f_id]++;
            }
        }
    } elseif ($req_action === 'decrease') {
        if ($user_id && is_numeric($item_id)) {
            $c_item_id = (int)$item_id;
            $check = mysqli_query($conn, "SELECT qty FROM cart_item WHERE id = $c_item_id LIMIT 1");
            if ($check && $row = mysqli_fetch_assoc($check)) {
                if ((int)$row['qty'] <= 1) {
                    mysqli_query($conn, "DELETE FROM cart_item WHERE id = $c_item_id");
                } else {
                    mysqli_query($conn, "UPDATE cart_item SET qty = qty - 1 WHERE id = $c_item_id");
                }
            }
        } else {
            $f_id = (int)str_replace('guest_', '', $item_id);
            if (isset($_SESSION['guest_cart'][$f_id])) {
                if ($_SESSION['guest_cart'][$f_id] <= 1) {
                    unset($_SESSION['guest_cart'][$f_id]);
                } else {
                    $_SESSION['guest_cart'][$f_id]--;
                }
            }
        }
    } elseif ($req_action === 'remove') {
        if ($user_id && is_numeric($item_id)) {
            $c_item_id = (int)$item_id;
            mysqli_query($conn, "DELETE FROM cart_item WHERE id = $c_item_id");
        } else {
            $f_id = (int)str_replace('guest_', '', $item_id);
            unset($_SESSION['guest_cart'][$f_id]);
        }
        set_flash('info', 'Item removed from your shopping cart.');
    }

    header('Location: cart.php');
    exit;
}

// Fetch all items currently in cart
$cart_items = get_cart_items();

// Compute totals
$total_qty = 0;
$total_calories = 0;
$subtotal_price = 0;

foreach ($cart_items as $item) {
    $total_qty += (int)$item['qty'];
    $total_calories += ((int)$item['calories'] * (int)$item['qty']);
    $subtotal_price += (float)$item['subtotal'];
}

// Free delivery threshold: Rp 100.000
$delivery_fee = ($subtotal_price > 0 && $subtotal_price < 100000) ? 10000 : 0;
$total_payment = $subtotal_price + $delivery_fee;

$page_title = 'Shopping Cart';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <!-- PAGE TITLE -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold fs-3 text-maroon font-playfair mb-1">Your Shopping Cart</h1>
            <p class="text-muted small mb-0">Review your wholesome meals before proceeding to secure checkout.</p>
        </div>
        <?php if (!empty($cart_items)): ?>
            <button type="button" class="btn btn-sm btn-outline-danger border-maroon text-maroon rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#clearCartModal">
                <i class="bi bi-trash3 me-1"></i> Clear Cart
            </button>

            <!-- CLEAR CART MODAL -->
            <div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-maroon font-playfair" id="clearCartModalLabel">
                                <i class="bi bi-trash3 text-maroon me-2"></i>Clear Shopping Cart?
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-3">
                            <p class="text-muted mb-0">Are you sure you want to remove all meals from your cart? This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="cart.php" class="m-0">
                                <input type="hidden" name="action" value="clear">
                                <button type="submit" class="btn btn-danger bg-maroon border-maroon rounded-pill px-3">
                                    <i class="bi bi-trash3 me-1"></i> Yes, Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($cart_items)): ?>
        <!-- EMPTY CART STATE -->
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 px-3 bg-white">
            <div class="mb-3 text-maroon fs-1">
                <i class="bi bi-bag-x"></i>
            </div>
            <h4 class="fw-bold text-maroon font-playfair">Your Cart is Currently Empty</h4>
            <p class="text-muted small mx-auto" style="max-width: 420px;">
                You haven't added any healthy meals to your order yet. Explore our nutritious and wholesome menu now!
            </p>
            <div class="mt-3">
                <a href="home.php" class="btn btn-ew-primary px-4 py-2">
                    <i class="bi bi-compass me-1"></i> Start Ordering Meals
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">

            <!-- LEFT: CART ITEMS TABLE -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 cart-table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Meal Item</th>
                                    <th scope="col" class="text-center">Calories</th>
                                    <th scope="col" class="text-center">Unit Price</th>
                                    <th scope="col" class="text-center">Portions</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                    <th scope="col" class="pe-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars(get_food_image_url($item['img'], $item['food_name'])) ?>" 
                                                     alt="<?= htmlspecialchars($item['food_name']) ?>" 
                                                     class="rounded-3 shadow-sm me-3" style="width: 58px; height: 58px; object-fit: cover;">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0 fs-6">
                                                        <a href="detail.php?id=<?= $item['food_id'] ?>" class="text-dark text-decoration-none">
                                                            <?= htmlspecialchars($item['food_name']) ?>
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted"><?= htmlspecialchars($item['category_name'] ?? 'Healthy') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-olive-subtle text-olive border border-success-subtle rounded-pill">
                                                <i class="bi bi-fire text-danger"></i> <?= (int)$item['calories'] * (int)$item['qty'] ?> kcal
                                            </span>
                                        </td>
                                        <td class="text-center text-muted fw-semibold">
                                            <?= format_rupiah($item['price']) ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="qty-control">
                                                <form method="POST" action="cart.php" class="d-inline m-0">
                                                    <input type="hidden" name="action" value="decrease">
                                                    <input type="hidden" name="item_id" value="<?= $item['cart_item_id'] ?>">
                                                    <button type="submit" title="Decrease">-</button>
                                                </form>
                                                <span><?= $item['qty'] ?></span>
                                                <form method="POST" action="cart.php" class="d-inline m-0">
                                                    <input type="hidden" name="action" value="increase">
                                                    <input type="hidden" name="item_id" value="<?= $item['cart_item_id'] ?>">
                                                    <button type="submit" title="Increase">+</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-maroon">
                                            <?= format_rupiah($item['subtotal']) ?>
                                        </td>
                                        <td class="pe-4 text-center">
                                            <form method="POST" action="cart.php" class="m-0" onsubmit="return confirm('Remove this meal from cart?');">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="item_id" value="<?= $item['cart_item_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" title="Remove Item">
                                                    <i class="bi bi-trash3 fs-6"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="home.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i> Add More Dishes
                        </a>
                        <span class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Free delivery on orders over <?= format_rupiah(100000) ?>!
                        </span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: ORDER SUMMARY -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 90px;">
                    <h5 class="fw-bold text-maroon font-playfair mb-3">Order Summary</h5>

                    <div class="summary-row">
                        <span class="text-muted">Total Portions</span>
                        <strong class="text-dark"><?= $total_qty ?> meals</strong>
                    </div>

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
                        <?php if ($delivery_fee === 0): ?>
                            <span class="badge bg-olive text-white">FREE</span>
                        <?php else: ?>
                            <strong class="text-dark"><?= format_rupiah($delivery_fee) ?></strong>
                        <?php endif; ?>
                    </div>

                    <div class="summary-row total-row">
                        <span>Total Payment</span>
                        <span class="text-maroon fs-5 font-playfair"><?= format_rupiah($total_payment) ?></span>
                    </div>

                    <div class="mt-4">
                        <?php if ($user_id): ?>
                            <a href="checkout.php" class="btn btn-ew-primary w-100 py-3 fs-6 shadow">
                                <i class="bi bi-credit-card-2-front me-2"></i> Proceed to Checkout
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-ew-primary w-100 py-3 fs-6 shadow">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In to Checkout
                            </a>
                            <div class="text-center mt-2">
                                <small class="text-muted">Sign in required to proceed to checkout.</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-3 text-center">
                        <small class="text-muted" style="font-size:0.75rem;">
                            <i class="bi bi-shield-check text-olive me-1"></i> Hygienic packaging &amp; allergen safety ensured
                        </small>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
