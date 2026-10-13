<?php
// ============================================================================
// EatWise - Database & Procedural Helper Functions
// Pure PHP Native (Procedural - NO OOP, NO MVC)
// English Localization
// ============================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Procedural MySQL database connection
 */
function db_connect() {
    static $conn = null;
    if ($conn !== null) {
        return $conn;
    }

    $host = '127.0.0.1';
    $user = 'root';
    $pass = '';
    $dbname = 'eatWise';
    $port = 3306;

    $conn = @mysqli_connect($host, $user, $pass, $dbname, $port);
    if (!$conn) {
        $conn = @mysqli_connect('localhost', $user, $pass, $dbname);
    }

    if (!$conn) {
        die('<div style="font-family:sans-serif;padding:30px;background:#ffebee;color:#c62828;margin:20px;border-radius:8px;">
            <h3>Failed to Connect to MySQL Database!</h3>
            <p>Please ensure MySQL is running in XAMPP.</p>
            <p><small>Error: ' . htmlspecialchars(mysqli_connect_error()) . '</small></p>
        </div>');
    }

    mysqli_set_charset($conn, 'utf8mb4');
    return $conn;
}

/**
 * Execute a query procedurally
 */
function db_query($sql) {
    $conn = db_connect();
    return mysqli_query($conn, $sql);
}

/**
 * Procedural escape string for SQL safety
 */
function db_escape($value) {
    $conn = db_connect();
    return mysqli_real_escape_string($conn, (string)$value);
}

/**
 * Format number to Indonesian Rupiah currency format
 */
function format_rupiah($amount) {
    return 'Rp ' . number_format((float)$amount, 0, ',', '.');
}

/**
 * Estimated calories per dish
 */
function get_food_calories($food_id, $food_name = '') {
    $calories_map = [
        1 => 420, // Grilled Chicken
        2 => 550, // Beef Steak
        3 => 380, // Quinoa Bowl
        4 => 400, // Chicken Rice Bowl
        5 => 200, // Vegetable Salad
        6 => 320, // Chicken Salad
        7 => 480, // Beef Burger
        8 => 180, // Vegetable Soup
        9 => 360, // Grilled Fish
        10 => 250 // Oatmeal Fruit Bowl
    ];

    $id = (int)$food_id;
    if (isset($calories_map[$id])) {
        return $calories_map[$id];
    }

    return 350;
}

/**
 * Get current logged in user ID
 */
function get_logged_in_user_id() {
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

/**
 * Get logged in user record from database
 */
function get_logged_in_user() {
    $user_id = get_logged_in_user_id();
    if (!$user_id) {
        return null;
    }

    $conn = db_connect();
    $sql = "SELECT id, nama, email FROM users WHERE id = $user_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

/**
 * Require user login, redirect to login.php if not authenticated
 */
function require_login() {
    if (!get_logged_in_user_id()) {
        $_SESSION['flash_message'] = [
            'type' => 'warning',
            'text' => 'Please sign in to proceed.'
        ];
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? 'home.php';
        header('Location: login.php');
        exit;
    }
}

/**
 * Set flash message
 */
function set_flash($type, $text) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'text' => $text
    ];
}

/**
 * Retrieve and clear flash message
 */
function get_flash() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Get or create cart ID for given user ID in `cart` table
 */
function get_or_create_user_cart_id($user_id) {
    $conn = db_connect();
    $user_id = (int)$user_id;

    $sql = "SELECT id FROM cart WHERE user_id = $user_id LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return (int)$row['id'];
    }

    $insert = "INSERT INTO cart (user_id) VALUES ($user_id)";
    if (mysqli_query($conn, $insert)) {
        return (int)mysqli_insert_id($conn);
    }

    return null;
}

/**
 * Get total quantity count in user cart
 */
function get_cart_count() {
    $user_id = get_logged_in_user_id();
    if (!$user_id) {
        $session_cart = $_SESSION['guest_cart'] ?? [];
        $total = 0;
        foreach ($session_cart as $qty) {
            $total += (int)$qty;
        }
        return $total;
    }

    $conn = db_connect();
    $cart_id = get_or_create_user_cart_id($user_id);
    if (!$cart_id) {
        return 0;
    }

    $sql = "SELECT SUM(qty) as total_qty FROM cart_item WHERE cart_id = $cart_id";
    $res = mysqli_query($conn, $sql);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        return (int)($row['total_qty'] ?? 0);
    }
    return 0;
}

/**
 * Add food item to cart
 */
function add_to_cart($food_id, $qty = 1) {
    $food_id = (int)$food_id;
    $qty = max(1, (int)$qty);
    $user_id = get_logged_in_user_id();

    if (!$user_id) {
        if (!isset($_SESSION['guest_cart'])) {
            $_SESSION['guest_cart'] = [];
        }
        $_SESSION['guest_cart'][$food_id] = ($_SESSION['guest_cart'][$food_id] ?? 0) + $qty;
        return true;
    }

    $conn = db_connect();
    $cart_id = get_or_create_user_cart_id($user_id);
    if (!$cart_id) {
        return false;
    }

    $check_sql = "SELECT id, qty FROM cart_item WHERE cart_id = $cart_id AND food_id = $food_id LIMIT 1";
    $res = mysqli_query($conn, $check_sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $new_qty = (int)$row['qty'] + $qty;
        $update_sql = "UPDATE cart_item SET qty = $new_qty WHERE id = " . (int)$row['id'];
        return mysqli_query($conn, $update_sql);
    } else {
        $insert_sql = "INSERT INTO cart_item (cart_id, food_id, qty) VALUES ($cart_id, $food_id, $qty)";
        return mysqli_query($conn, $insert_sql);
    }
}

/**
 * Retrieve all items in the current user cart with food details
 */
function get_cart_items() {
    $user_id = get_logged_in_user_id();
    $conn = db_connect();
    $items = [];

    if ($user_id) {
        $cart_id = get_or_create_user_cart_id($user_id);
        if ($cart_id) {
            $sql = "SELECT ci.id as cart_item_id, ci.qty, f.id as food_id, f.food_name, f.price, f.img, f.description, c.category_name 
                    FROM cart_item ci
                    JOIN food f ON ci.food_id = f.id
                    LEFT JOIN category c ON f.category_id = c.id
                    WHERE ci.cart_id = $cart_id
                    ORDER BY ci.id DESC";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $row['calories'] = get_food_calories($row['food_id'], $row['food_name']);
                    $row['subtotal'] = (float)$row['price'] * (int)$row['qty'];
                    $items[] = $row;
                }
            }
        }
    } else {
        $guest_cart = $_SESSION['guest_cart'] ?? [];
        foreach ($guest_cart as $food_id => $qty) {
            $food_id = (int)$food_id;
            $sql = "SELECT f.id as food_id, f.food_name, f.price, f.img, f.description, c.category_name 
                    FROM food f
                    LEFT JOIN category c ON f.category_id = c.id
                    WHERE f.id = $food_id LIMIT 1";
            $res = mysqli_query($conn, $sql);
            if ($res && $row = mysqli_fetch_assoc($res)) {
                $row['cart_item_id'] = 'guest_' . $food_id;
                $row['qty'] = (int)$qty;
                $row['calories'] = get_food_calories($row['food_id'], $row['food_name']);
                $row['subtotal'] = (float)$row['price'] * (int)$row['qty'];
                $items[] = $row;
            }
        }
    }

    return $items;
}

/**
 * Fallback helper for food image URL
 */
function get_food_image_url($img_url, $food_name = '') {
    if (!empty($img_url) && filter_var($img_url, FILTER_VALIDATE_URL)) {
        return $img_url;
    }
    return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';
}

/**
 * Return dedicated icon for each allergen
 */
function get_allergen_icon($allergen_name) {
    $name = strtolower(trim($allergen_name));
    switch ($name) {
        case 'fish':
            return 'bi-water';
        case 'shellfish':
            return 'bi-shield-fill-check';
        case 'shrimp':
            return 'bi-tsunami';
        case 'crab':
            return 'bi-record-circle';
        case 'milk':
            return 'bi-cup-straw';
        case 'egg':
            return 'bi-egg-fried';
        case 'soy':
            return 'bi-flower2';
        case 'squid':
            return 'bi-droplet-half';
        case 'peanuts':
            return 'bi-nut';
        case 'octopus':
            return 'bi-life-preserver';
        default:
            return 'bi-shield-exclamation';
    }
}

/**
 * Return dedicated emoji for each allergen
 */
function get_allergen_emoji($allergen_name) {
    $name = strtolower(trim($allergen_name));
    switch ($name) {
        case 'fish':
            return '🐟';
        case 'shellfish':
            return '🦪';
        case 'shrimp':
            return '🦐';
        case 'crab':
            return '🦀';
        case 'milk':
            return '🥛';
        case 'egg':
            return '🍳';
        case 'soy':
            return '🌱';
        case 'squid':
            return '🦑';
        case 'peanuts':
            return '🥜';
        case 'octopus':
            return '🐙';
        default:
            return '⚠️';
    }
}

