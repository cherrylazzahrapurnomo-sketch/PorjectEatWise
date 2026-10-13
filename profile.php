
<?php
session_start();

$profile = [
    "name" => $_SESSION["user_name"] ?? "Jeje Jenner",
    "email" => $_SESSION["user_email"] ?? "jennnerzje@gmail.com",
    "photo" => "assets/profile.jpg"
];

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$menuItems = [
    [
        "icon" => "list",
        "title" => "My Order",
        "description" => "View order",
        "link" => "order-history.php"
    ],
    [
        "icon" => "filter",
        "title" => "Dietary & Allergen Preferences",
        "description" => "Set preferences",
        "link" => "select-criteria.php"
    ],
    [
        "icon" => "card",
        "title" => "Payment Method",
        "description" => "GoPay, 0812******12",
        "link" => "payment-method.php"
    ],
    [
        "icon" => "pin",
        "title" => "Address",
        "description" => "Home",
        "link" => "address.php"
    ],
    [
        "icon" => "bell",
        "title" => "Settings",
        "description" => "Notification, language, etc.",
        "link" => "settings.php"
    ],
    [
        "icon" => "help",
        "title" => "Help & Support",
        "description" => "Help Center",
        "link" => "help-support.php"
    ]
];

function menuIcon($name) {
    $icons = [
        "list" => '<svg viewBox="0 0 24 24"><path d="M9 5h12M9 12h12M9 19h12"/><path d="M3 5h.01M3 12h.01M3 19h.01" stroke-width="3"/></svg>',
        "filter" => '<svg viewBox="0 0 24 24"><path d="M4 5h16M6 5v5l5 5v4l4 2v-6l3-3V5"/><path d="m17 16 5 5m0-5-5 5"/></svg>',
        "card" => '<svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>',
        "pin" => '<svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>',
        "bell" => '<svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/><path d="M12 1v2"/></svg>',
        "help" => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 1 1 4.3 1.7c-1.1 1-1.8 1.3-1.8 2.8M12 17h.01"/></svg>'
    ];

    return $icons[$name] ?? "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | EatWise</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-container">

    <!-- NAVBAR -->
    <header class="navbar">
        <a href="home.php" class="brand">
            <svg class="brand-leaf" viewBox="0 0 60 50" aria-hidden="true">
                <path d="M4 44C18 12 32 6 56 4C54 28 36 45 4 44Z"
                      fill="#72844c"/>
                <path d="M8 42L43 14" stroke="#b2b98a"
                      stroke-width="1.5" fill="none"/>
            </svg>
            <span>EatWise</span>
        </a>

        <nav class="nav-links">
            <a href="home.php">HOME</a>
            <a href="categories.php">CATEGORIES</a>
            <a href="cart.php">CARTS</a>
            <a href="profile.php" class="active">ACCOUNT</a>
            <a href="login.php" class="logout">LOGOUT</a>
        </nav>
    </header>

    <!-- PROFILE CONTENT -->
    <main class="profile-main">

        <a href="home.php" class="back-button" aria-label="Back">
            &#8249;
        </a>

        <!-- USER INFORMATION -->
        <section class="user-profile">
            <div class="avatar">
                <img
                    src="<?= e($profile['photo']) ?>"
                    alt="Profile photo"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                >
                <span class="avatar-fallback">
                    <?= e(strtoupper(substr($profile["name"], 0, 1))) ?>
                </span>
            </div>

            <div class="user-info">
                <h1><?= e($profile["name"]) ?></h1>
                <p><?= e($profile["email"]) ?></p>
            </div>
        </section>

        <!-- ACCOUNT MENU -->
        <section class="account-card">

            <?php foreach ($menuItems as $item): ?>
                <a class="menu-row"
                   href="<?= e($item["link"]) ?>">

                    <span class="menu-icon">
                        <?= menuIcon($item["icon"]) ?>
                    </span>

                    <span class="menu-title">
                        <?= e($item["title"]) ?>
                    </span>

                    <span class="menu-description">
                        <?= e($item["description"]) ?>
                    </span>

                    <span class="menu-arrow" aria-hidden="true"></span>
                </a>
            <?php endforeach; ?>

            <!-- LOG OUT -->
            <a href="login.php" class="logout-row">
                <span class="logout-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M10 4H4v16h6"/>
                        <path d="M14 8l4 4-4 4M7 12h11"/>
                    </svg>
                </span>
                <span>Log Out</span>
            </a>

        </section>

    </main>

</div>

</body>
</html>
