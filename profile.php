<?php
// ============================================================================
// EatWise - Profile & Account Page (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

require_login();
$conn = db_connect();
$user_id = get_logged_in_user_id();
$user = get_logged_in_user();

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nama = trim($_POST['nama'] ?? '');
    $new_password = trim($_POST['password'] ?? '');

    if (empty($nama)) {
        set_flash('danger', 'Full name cannot be empty.');
    } else {
        $safe_nama = db_escape($nama);
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                set_flash('danger', 'New password must be at least 6 characters.');
            } else {
                $hash = db_escape(password_hash($new_password, PASSWORD_DEFAULT));
                mysqli_query($conn, "UPDATE users SET nama = '$safe_nama', password = '$hash' WHERE id = $user_id");
                $_SESSION['user_name'] = $nama;
                set_flash('success', 'Profile and password successfully updated!');
            }
        } else {
            mysqli_query($conn, "UPDATE users SET nama = '$safe_nama' WHERE id = $user_id");
            $_SESSION['user_name'] = $nama;
            set_flash('success', 'Profile name successfully updated!');
        }
    }
    header('Location: profile.php');
    exit;
}

// Retrieve user's allergen preferences
$user_allergens = [];
$ua_res = mysqli_query($conn, "SELECT a.allergen_name 
                               FROM user_allergen ua 
                               JOIN allergen a ON ua.allergen_id = a.id 
                               WHERE ua.user_id = $user_id");
if ($ua_res) {
    while ($r = mysqli_fetch_assoc($ua_res)) {
        $user_allergens[] = $r['allergen_name'];
    }
}

// Retrieve user's dietary preferences
$user_dietary = [];
$ud_res = mysqli_query($conn, "SELECT dp.preferences_name 
                              FROM user_dietary ud 
                              JOIN dietary_preferences dp ON ud.dietary_id = dp.id 
                              WHERE ud.user_id = $user_id");
if ($ud_res) {
    while ($r = mysqli_fetch_assoc($ud_res)) {
        $user_dietary[] = $r['preferences_name'];
    }
}

// Count total orders and addresses
$order_count_res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM orders WHERE user_id = $user_id");
$order_count = ($order_count_res && $or = mysqli_fetch_assoc($order_count_res)) ? (int)$or['cnt'] : 0;

$addr_count_res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM address WHERE user_id = $user_id");
$addr_count = ($addr_count_res && $ar = mysqli_fetch_assoc($addr_count_res)) ? (int)$ar['cnt'] : 0;

$page_title = 'My Account & Profile';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <div class="row g-4">

        <!-- LEFT COLUMN: USER AVATAR & QUICK LINKS -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center mb-4">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <div class="rounded-circle bg-maroon-subtle text-maroon d-flex align-items-center justify-content-center mx-auto" style="width: 88px; height: 88px; font-size: 2.5rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>

                <h4 class="fw-bold text-dark font-playfair mb-1"><?= htmlspecialchars($user['nama']) ?></h4>
                <p class="text-muted small mb-3"><?= htmlspecialchars($user['email']) ?></p>

                <div class="d-flex justify-content-around p-2 bg-light rounded-3 text-start">
                    <div>
                        <small class="text-muted d-block" style="font-size:0.75rem;">Total Orders</small>
                        <strong class="text-maroon fs-6"><?= $order_count ?> Orders</strong>
                    </div>
                    <div class="border-start ps-3">
                        <small class="text-muted d-block" style="font-size:0.75rem;">Addresses</small>
                        <strong class="text-maroon fs-6"><?= $addr_count ?> Saved</strong>
                    </div>
                </div>
            </div>

            <!-- QUICK NAVIGATION MENU -->
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="list-group list-group-flush">
                    <a href="orderhistory.php" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="bi bi-receipt text-maroon me-3 fs-5"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Order History</h6>
                            <small class="text-muted">Track meal delivery status</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>

                    <a href="address.php" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="bi bi-geo-alt text-maroon me-3 fs-5"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Saved Addresses</h6>
                            <small class="text-muted">Manage home &amp; office locations</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>

                    <a href="dietery.php" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="bi bi-sliders text-maroon me-3 fs-5"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Allergy &amp; Diet Settings</h6>
                            <small class="text-muted">Edit ingredients you must avoid</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>

                    <a href="paymentmethode.php" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="bi bi-credit-card-2-front text-maroon me-3 fs-5"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold">Payment Methods</h6>
                            <small class="text-muted">View accepted payment options</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PROFILE EDIT & ACTIVE HEALTH PREFERENCES -->
        <div class="col-lg-8">

            <!-- EDIT PROFILE CARD -->
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                <h5 class="fw-bold text-maroon font-playfair mb-3">
                    <i class="bi bi-person-lines-fill text-maroon me-2"></i>Personal Profile Information
                </h5>

                <form method="POST" action="profile.php">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Full Name</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Email Address (Read-only)</label>
                        <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        <div class="form-text small">Your registered email address is fixed for security purposes.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">New Password (Optional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep your current password">
                    </div>

                    <button type="submit" class="btn btn-ew-primary px-4 py-2">
                        <i class="bi bi-save me-1"></i> Save Profile Changes
                    </button>
                </form>
            </div>

            <!-- ACTIVE HEALTH & ALLERGY PREFERENCES SUMMARY -->
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-maroon font-playfair mb-0">
                        <i class="bi bi-shield-check text-olive me-2"></i>Active Health Profile
                    </h5>
                    <a href="dietery.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-pencil me-1"></i> Change
                    </a>
                </div>

                <!-- ALLERGENS (MAROON PRECAUTION) -->
                <div class="mb-3">
                    <label class="small fw-semibold text-maroon d-block mb-2">
                        <i class="bi bi-shield-exclamation me-1"></i> Avoided Food Allergens:
                    </label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($user_allergens)): ?>
                            <?php foreach ($user_allergens as $al): ?>
                                <span class="badge bg-maroon-subtle text-maroon border border-maroon px-3 py-2 rounded-pill small">
                                    <span class="me-1"><?= get_allergen_emoji($al) ?></span>
                                    <i class="bi <?= get_allergen_icon($al) ?> me-1"></i> <?= htmlspecialchars($al) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted small">No specific allergen restrictions set yet.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- DIETARY GOALS (OLIVE SUCCESS) -->
                <div>
                    <label class="small fw-semibold text-olive d-block mb-2">
                        <i class="bi bi-heart-pulse me-1"></i> Lifestyle &amp; Dietary Goals:
                    </label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($user_dietary)): ?>
                            <?php foreach ($user_dietary as $dt): ?>
                                <span class="badge bg-olive-subtle text-olive border border-success-subtle px-3 py-2 rounded-pill small">
                                    <i class="bi bi-check2-circle me-1"></i> <?= htmlspecialchars($dt) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted small">No dietary goals set yet.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
