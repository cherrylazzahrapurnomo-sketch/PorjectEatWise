<?php
// ============================================================================
// EatWise - Sign Up Page (Pure Procedural PHP Native)
// Secondary Maroon: #752E2A, Primary: #F8F4E9
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

// If already logged in, redirect to home
if (get_logged_in_user_id()) {
    header('Location: home.php');
    exit;
}

$error = '';
$fullname = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($fullname) || empty($email) || empty($password)) {
        $error = 'All form fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address format.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $conn = db_connect();
        $safe_email = db_escape($email);
        $safe_name = db_escape($fullname);

        // Check if email already registered
        $check_sql = "SELECT id FROM users WHERE email = '$safe_email' LIMIT 1";
        $check_res = mysqli_query($conn, $check_sql);

        if ($check_res && mysqli_num_rows($check_res) > 0) {
            $error = 'Email is already registered. Please sign in or use another email.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $safe_hash = db_escape($hashed_password);

            $insert_sql = "INSERT INTO users (nama, email, password) VALUES ('$safe_name', '$safe_email', '$safe_hash')";
            if (mysqli_query($conn, $insert_sql)) {
                $new_user_id = mysqli_insert_id($conn);

                // Initialize cart record in `cart` table
                mysqli_query($conn, "INSERT INTO cart (user_id) VALUES ($new_user_id)");

                // Set session
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_name'] = $fullname;
                $_SESSION['user_email'] = $email;

                // Sync guest_cart if any
                if (!empty($_SESSION['guest_cart'])) {
                    foreach ($_SESSION['guest_cart'] as $fid => $fqty) {
                        add_to_cart($fid, $fqty);
                    }
                    unset($_SESSION['guest_cart']);
                }

                set_flash('success', 'Account created successfully! Welcome to EatWise. Now set your dietary preferences.');
                header('Location: dietery.php');
                exit;
            } else {
                $error = 'Error saving account: ' . mysqli_error($conn);
            }
        }
    }
}

$page_title = 'Create an Account';
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
                    <h3 class="fw-bold text-maroon font-playfair mt-2">Create Account</h3>
                    <p class="text-muted small">Begin your wholesome healthy lifestyle journey with EatWise.</p>
                </div>

                <!-- ERROR PREVISION ALERT (MAROON: #752E2A) -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger bg-maroon-subtle border border-maroon text-maroon py-2 px-3 small rounded-3 mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="signup.php">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="fullname" class="form-control bg-light border-start-0" placeholder="e.g. Jane Doe" value="<?= htmlspecialchars($fullname) ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="name@example.com" value="<?= htmlspecialchars($email) ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="At least 6 characters" required>
                        </div>
                        <div class="form-text small">Must be at least 6 characters long.</div>
                    </div>

                    <button type="submit" class="btn btn-ew-primary w-100 py-2 mb-3">
                        <i class="bi bi-check-circle me-1"></i> Create My Account
                    </button>
                </form>

                <div class="border-top pt-3 text-center">
                    <p class="small text-muted mb-0">
                        Already have an account? <a href="login.php" class="fw-bold text-maroon text-decoration-none">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>