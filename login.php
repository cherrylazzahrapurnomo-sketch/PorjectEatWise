<?php
// ============================================================================
// EatWise - Sign In Page (Pure Procedural PHP Native)
// Secondary Maroon: #752E2A, Primary: #F8F4E9
// English Localization & Social Sign-In Mocks (Google, Apple, Facebook)
// ============================================================================
require_once __DIR__ . '/db.php';

// If already logged in, redirect to home.php
if (get_logged_in_user_id()) {
    header('Location: home.php');
    exit;
}

$error = '';
$email = '';

// Quick demo login handler
if (isset($_GET['demo_login'])) {
    $demo_id = (int)$_GET['demo_login'];
    $conn = db_connect();
    $q = mysqli_query($conn, "SELECT id, nama, email FROM users WHERE id = $demo_id LIMIT 1");
    if ($q && $user = mysqli_fetch_assoc($q)) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['nama'];
        $_SESSION['user_email'] = $user['email'];
        set_flash('success', "Welcome back, {$user['nama']}!");
        header('Location: home.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Email and password fields are required.';
    } else {
        $conn = db_connect();
        $safe_email = db_escape($email);
        $sql = "SELECT id, nama, email, password FROM users WHERE email = '$safe_email' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $db_password = $user['password'];

            // Support password_hash as well as plain-text fallback for seed users
            $password_valid = password_verify($password, $db_password) || ($password === $db_password);

            if ($password_valid) {
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['user_email'] = $user['email'];

                // Sync guest_cart to database cart if exists
                if (!empty($_SESSION['guest_cart'])) {
                    foreach ($_SESSION['guest_cart'] as $fid => $fqty) {
                        add_to_cart($fid, $fqty);
                    }
                    unset($_SESSION['guest_cart']);
                }

                set_flash('success', "Welcome back, {$user['nama']}!");
                $redirect = $_SESSION['redirect_after_login'] ?? 'home.php';
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit;
            } else {
                $error = 'The password you entered is incorrect.';
            }
        } else {
            $error = 'No registered account found with that email.';
        }
    }
}

$page_title = 'Sign In to Your Account';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-sm-5 bg-white">
                <div class="text-center mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-maroon-subtle text-maroon p-2 fs-3 mb-2" style="width: 54px; height: 54px;">
                        <i class="bi bi-flower1"></i>
                    </span>
                    <h3 class="fw-bold text-maroon font-playfair mt-2">Welcome Back</h3>
                    <p class="text-muted small">Sign in to your EatWise account to order nutritious healthy meals.</p>
                </div>

                <!-- ERROR PREVISION ALERT (MAROON: #752E2A) -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger bg-maroon-subtle border border-maroon text-maroon py-2 px-3 small rounded-3 mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- SOCIAL LOGIN BUTTONS (MOCK ONLY: GOOGLE, APPLE, FB) -->
                <div class="d-flex flex-column gap-2 mb-3">
                    <button type="button" class="btn btn-outline-secondary btn-social w-100 py-2 d-flex align-items-center justify-content-center bg-white shadow-sm" onclick="alert('Google Sign-In is a visual preview mockup.');">
                        <i class="bi bi-google text-danger me-2 fs-5"></i>
                        <span class="small fw-semibold text-dark">Continue with Google</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-social w-100 py-2 d-flex align-items-center justify-content-center bg-white shadow-sm" onclick="alert('Apple Sign-In is a visual preview mockup.');">
                        <i class="bi bi-apple text-dark me-2 fs-5"></i>
                        <span class="small fw-semibold text-dark">Continue with Apple</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-social w-100 py-2 d-flex align-items-center justify-content-center bg-white shadow-sm" onclick="alert('Facebook Sign-In is a visual preview mockup.');">
                        <i class="bi bi-facebook text-primary me-2 fs-5"></i>
                        <span class="small fw-semibold text-dark">Continue with Facebook</span>
                    </button>
                </div>

                <div class="position-relative text-center my-3">
                    <hr class="text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">or sign in with email</span>
                </div>

                <!-- STANDARD EMAIL/PASSWORD FORM -->
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="name@example.com" value="<?= htmlspecialchars($email) ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-semibold text-muted mb-0">Password</label>
                            <a href="#" class="small text-decoration-none text-muted" onclick="alert('Demo password: password123'); return false;">Forgot password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ew-primary w-100 py-2 mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                    </button>
                </form>

                <div class="border-top pt-3 text-center">
                    <p class="small text-muted mb-3">
                        Don't have an account yet? <a href="signup.php" class="fw-bold text-maroon text-decoration-none">Sign up here</a>
                    </p>

                    <!-- Quick 1-Click Demo Login for Testing -->
                    <div class="bg-light p-3 rounded-3 text-start">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-bold text-dark"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick 1-Click Demo Accounts:</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="login.php?demo_login=1" class="btn btn-outline-secondary btn-sm w-50 text-truncate" title="Alya Putri (Has Fish & Shrimp Allergens)">
                                <i class="bi bi-person-fill text-maroon"></i> Alya Putri
                            </a>
                            <a href="login.php?demo_login=2" class="btn btn-outline-secondary btn-sm w-50 text-truncate" title="Raka Pratama">
                                <i class="bi bi-person-fill text-maroon"></i> Raka Pratama
                            </a>
                        </div>
                        <small class="text-muted d-block mt-2" style="font-size:0.75rem;">Default Demo Password: <code>password123</code></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
