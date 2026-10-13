```php
<?php
session_start();

// Data profil pengguna
$user = [
    "name" => "Jeje Jenner",
    "email" => "jennerzje@gmail.com",
    "photo" => "assets/profile.jpg"
];

// Proses logout
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Account | EatWise</title>

    <link rel="stylesheet" href="account.css">
</head>
<body>

<div class="page-container">

    <!-- HEADER / NAVBAR -->
    <header class="navbar">

        <a href="home.php" class="brand">
            <svg class="brand-leaf"
                 viewBox="0 0 60 50"
                 xmlns="http://www.w3.org/2000/svg"
                 aria-hidden="true">

                <path
                    d="M4 44C18 12 32 6 56 4C54 28 36 45 4 44Z"
                    fill="#71834B"/>

                <path
                    d="M8 42L43 14"
                    stroke="#B2B98A"
                    stroke-width="1.5"
                    fill="none"/>
            </svg>

            <span>EatWise</span>
        </a>

        <nav class="nav-links">
            <a href="home.php">HOME</a>
            <a href="categories.php">CATEGORIES</a>
            <a href="cart.php">CARTS</a>
            <a href="account.php" class="active">ACCOUNT</a>
            <a href="login.php" class="logout-link">LOGOUT</a>
        </nav>

    </header>

    <!-- MAIN CONTENT -->
    <main class="account-main">

        <!-- BACK BUTTON -->
        <a href="home.php" class="back-button" aria-label="Back">
            <span></span>
        </a>

        <!-- PROFILE -->
        <section class="profile-section">

            <img
                src="<?= htmlspecialchars($user['photo'], ENT_QUOTES, 'UTF-8') ?>"
                alt="Profile Jeje Jenner"
                class="profile-photo"
                onerror="this.style.backgroundColor='#d8cfc0';"
            >

            <div class="profile-info">
                <h1>
                    <?= htmlspecialchars($user["name"], ENT_QUOTES, "UTF-8") ?>
                </h1>

                <p>
                    <?= htmlspecialchars($user["email"], ENT_QUOTES, "UTF-8") ?>
                </p>
            </div>

        </section>

        <!-- ACCOUNT MENU -->
        <section class="account-menu">

            <!-- MY ORDER -->
            <a href="order-history.php" class="menu-item">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 6H21"/>
                        <path d="M9 12H21"/>
                        <path d="M9 18H21"/>
                        <path d="M3 6h.01"/>
                        <path d="M3 12h.01"/>
                        <path d="M3 18h.01"/>
                    </svg>
                </span>

                <span class="menu-label">My Order</span>

                <span class="menu-arrow"></span>
            </a>

            <!-- DIETARY PREFERENCES -->
            <a href="dietary-preferences.php" class="menu-item">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 4h18v5l-7 7v4l-4-2v-2l-7-7z"/>
                        <path d="m16 5 6 6"/>
                        <path d="m22 5-6 6"/>
                    </svg>
                </span>

                <span class="menu-label">
                    Dietary &amp; Allergen Preferences
                </span>

                <span class="menu-arrow"></span>
            </a>

            <!-- PAYMENT METHOD -->
            <a href="payment-method.php" class="menu-item">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                        <path d="M2 10h20"/>
                    </svg>
                </span>

                <span class="menu-label">Payment Method</span>

                <span class="menu-arrow"></span>
            </a>

            <!-- ADDRESS -->
            <a href="address.php" class="menu-item">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                </span>

                <span class="menu-label">Address</span>

                <span class="menu-arrow"></span>
            </a>

            <!-- NOTIFICATION -->
            <a href="notification.php" class="menu-item">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                        <path d="M10 2h4"/>
                    </svg>
                </span>

                <span class="menu-label">Notification</span>

                <span class="menu-arrow"></span>
            </a>

            <!-- HELP & SUPPORT -->
            <a href="help-support.php" class="menu-item">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.6 9a2.5 2.5 0 1 1 4.4 1.6c-1.2 1.1-2 1.4-2 3"/>
                        <path d="M12 17.5h.01"/>
                    </svg>
                </span>

                <span class="menu-label">Help &amp; Support</span>

                <span class="menu-arrow"></span>
            </a>

            <!-- LOG OUT -->
            <button type="button" class="menu-item logout-item"
                    id="logoutButton">

                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M10 17l5-5-5-5"/>
                        <path d="M15 12H3"/>
                        <path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>
                    </svg>
                </span>

                <span class="menu-label">Log Out</span>

            </button>

        </section>

    </main>

</div>

<!-- LOGOUT CONFIRMATION OVERLAY -->
<div class="modal-overlay" id="logoutModal" hidden>

    <div class="logout-modal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="logoutTitle">

        <h2 id="logoutTitle">ARE YOU SURE TO LOG OUT?</h2>

        <form method="POST" action="account.php" class="logout-form">

            <button type="submit"
                    name="logout"
                    value="yes"
                    class="modal-button yes-button">
                YES
            </button>

            <button type="button"
                    class="modal-button no-button"
                    id="cancelLogout">
                NO
            </button>

        </form>

    </div>

</div>

<script>
const logoutButton = document.getElementById("logoutButton");
const logoutModal = document.getElementById("logoutModal");
const cancelLogout = document.getElementById("cancelLogout");

logoutButton.addEventListener("click", function () {
    logoutModal.hidden = false;
    cancelLogout.focus();
});

cancelLogout.addEventListener("click", function () {
    logoutModal.hidden = true;
    logoutButton.focus();
});

// Tutup modal jika area luar diklik
logoutModal.addEventListener("click", function (event) {
    if (event.target === logoutModal) {
        logoutModal.hidden = true;
        logoutButton.focus();
    }
});

// Tutup modal dengan tombol Escape
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && !logoutModal.hidden) {
        logoutModal.hidden = true;
        logoutButton.focus();
    }
});
</script>

</body>
</html>
```