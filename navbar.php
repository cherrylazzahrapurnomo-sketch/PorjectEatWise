<?php
// ============================================================================
// EatWise - Procedural Header & Navbar
// Background: Primary (#F8F4E9), Navbar: Secondary (#752E2A)
// Font: Playfair Display & Inter (English Localization)
// ============================================================================
require_once __DIR__ . '/db.php';

$current_page = basename($_SERVER['PHP_SELF']);
$logged_user = get_logged_in_user();
$cart_count = get_cart_count();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | EatWise' : 'EatWise - Healthy & Nutritious Meals' ?></title>

    <!-- Google Fonts: Inter & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons 1.11.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- EatWise Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-wrapper">

    <!-- NAVBAR (Secondary Color: #752E2A) -->
    <nav class="navbar navbar-expand-lg navbar-dark ew-navbar">
        <div class="container">
            <a class="navbar-brand brand-logo" href="home.php">
                <span class="brand-leaf-icon"><i class="bi bi-flower1"></i></span>
                <span class="font-playfair">EatWise</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEatWise" aria-controls="navbarEatWise" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarEatWise">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($current_page, ['home.php', 'index.php']) ? 'active' : '' ?>" href="home.php">
                            <i class="bi bi-house-door me-1"></i> HOME
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($current_page, ['catagory.php', 'categories.php']) ? 'active' : '' ?>" href="catagory.php">
                            <i class="bi bi-grid me-1"></i> CATEGORIES
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'cart.php') ? 'active' : '' ?>" href="cart.php">
                            <i class="bi bi-bag me-1"></i> CARTS
                            <?php if ($cart_count > 0): ?>
                                <span class="cart-badge-pill"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($current_page, ['profile.php', 'account.php', 'address.php', 'dietery.php', 'allergen.php', 'editPreferences.php', 'selectCriteria.php']) ? 'active' : '' ?>" href="profile.php">
                            <i class="bi bi-person me-1"></i> ACCOUNT
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <?php if ($logged_user): ?>
                        <div class="dropdown">
                            <button class="btn nav-user-btn dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-6"></i>
                                <span><?= htmlspecialchars($logged_user['nama']) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2 py-2">
                                <li class="px-3 py-2 border-bottom">
                                    <small class="text-muted d-block">Signed in as:</small>
                                    <strong class="text-maroon"><?= htmlspecialchars($logged_user['email']) ?></strong>
                                </li>
                                <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2 text-maroon"></i> My Profile</a></li>
                                <li><a class="dropdown-item py-2" href="orderhistory.php"><i class="bi bi-receipt me-2 text-maroon"></i> Order History</a></li>
                                <li><a class="dropdown-item py-2" href="address.php"><i class="bi bi-geo-alt me-2 text-maroon"></i> Saved Addresses</a></li>
                                <li><a class="dropdown-item py-2" href="dietery.php"><i class="bi bi-sliders me-2 text-maroon"></i> Diet &amp; Allergens</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <!-- LOGOUT MODAL TRIGGER -->
                                    <button type="button" class="dropdown-item py-2 text-maroon fw-semibold" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                        <i class="bi bi-box-arrow-right me-2 text-maroon"></i> Log Out
                                    </button>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn nav-auth-login-btn">Sign In</a>
                        <a href="signup.php" class="btn nav-auth-signup-btn">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    <?php if ($flash): 
        $alert_type = htmlspecialchars($flash['type']);
        $is_danger = in_array($alert_type, ['danger', 'error']);
    ?>
        <div class="container mt-3">
            <div class="alert <?= $is_danger ? 'alert-danger border-maroon text-maroon bg-maroon-subtle' : 'alert-' . $alert_type ?> alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                <i class="bi <?= $is_danger ? 'bi-exclamation-octagon-fill' : 'bi-check-circle-fill' ?> me-2"></i>
                <?= htmlspecialchars($flash['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- UNIVERSAL LOGOUT CONFIRMATION MODAL -->
    <div class="modal fade modal-eatwise" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg p-3 bg-white">
                <div class="modal-body text-center pt-4 pb-3">
                    <div class="modal-eatwise-icon mx-auto">
                        <i class="bi bi-box-arrow-right text-maroon"></i>
                    </div>
                    <h4 class="fw-bold text-maroon mb-2 font-playfair" id="logoutModalLabel">Confirm Logout</h4>
                    <p class="text-muted small px-3 mb-0">
                        Are you sure you want to log out of your EatWise account? Your session will end and you can sign back in anytime.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="logout.php" class="btn btn-ew-primary rounded-pill px-4 py-2">
                        <i class="bi bi-box-arrow-right me-1"></i> Yes, Log Out
                    </a>
                </div>
            </div>
        </div>
    </div>
