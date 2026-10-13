<?php
// ============================================================================
// EatWise - Allergen & Dietary Preferences (Pure Procedural PHP Native)
// Error Prevention (Allergen): #752E2A, Dietary: #4E5134, Primary: #F8F4E9
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

require_login();
$conn = db_connect();
$user_id = get_logged_in_user_id();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_preferences') {
    $selected_allergens = $_POST['allergens'] ?? [];
    $selected_dietary = $_POST['dietary'] ?? [];

    // Clear existing preferences
    mysqli_query($conn, "DELETE FROM user_allergen WHERE user_id = $user_id");
    mysqli_query($conn, "DELETE FROM user_dietary WHERE user_id = $user_id");

    // Insert newly selected allergens
    if (is_array($selected_allergens)) {
        foreach ($selected_allergens as $allg_id) {
            $allg_id = (int)$allg_id;
            if ($allg_id > 0) {
                mysqli_query($conn, "INSERT INTO user_allergen (user_id, allergen_id) VALUES ($user_id, $allg_id)");
            }
        }
    }

    // Insert newly selected dietary goals
    if (is_array($selected_dietary)) {
        foreach ($selected_dietary as $d_id) {
            $d_id = (int)$d_id;
            if ($d_id > 0) {
                mysqli_query($conn, "INSERT INTO user_dietary (user_id, dietary_id) VALUES ($user_id, $d_id)");
            }
        }
    }

    set_flash('success', 'Your allergen and dietary preferences have been successfully updated!');
    header('Location: dietery.php?saved=1');
    exit;
}

// Fetch all allergens from table
$all_allergens = [];
$a_res = mysqli_query($conn, "SELECT id, allergen_name FROM allergen ORDER BY id ASC");
if ($a_res) {
    while ($ar = mysqli_fetch_assoc($a_res)) {
        $all_allergens[] = $ar;
    }
}

// Fetch all dietary preferences from table
$all_dietary = [];
$d_res = mysqli_query($conn, "SELECT id, preferences_name FROM dietary_preferences ORDER BY id ASC");
if ($d_res) {
    while ($dr = mysqli_fetch_assoc($d_res)) {
        $all_dietary[] = $dr;
    }
}

// Fetch user's currently active allergens
$active_allergens = [];
$ua_res = mysqli_query($conn, "SELECT allergen_id FROM user_allergen WHERE user_id = $user_id");
if ($ua_res) {
    while ($row = mysqli_fetch_assoc($ua_res)) {
        $active_allergens[] = (int)$row['allergen_id'];
    }
}

// Fetch user's currently active dietary goals
$active_dietary = [];
$ud_res = mysqli_query($conn, "SELECT dietary_id FROM user_dietary WHERE user_id = $user_id");
if ($ud_res) {
    while ($row = mysqli_fetch_assoc($ud_res)) {
        $active_dietary[] = (int)$row['dietary_id'];
    }
}

$page_title = 'Dietary & Allergen Preferences';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">

                <div class="text-center mb-4">
                    <span class="brand-leaf-icon fs-3 mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <h2 class="fw-bold text-maroon font-playfair mt-2">Allergy &amp; Dietary Preferences</h2>
                    <p class="text-muted small mx-auto" style="max-width: 520px;">
                        Select food ingredients you must avoid and your nutrition lifestyle goals. EatWise displays automatic warning verifications whenever a meal contains an ingredient you avoid.
                    </p>
                </div>

                <form method="POST" action="dietery.php">
                    <input type="hidden" name="action" value="save_preferences">

                    <!-- SECTION 1: ALLERGENS (ERROR PREVENTION: MAROON) -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-shield-exclamation text-maroon me-2 fs-5"></i>
                            <h5 class="fw-bold text-maroon font-playfair mb-0">1. Allergen Avoidance Restrictions</h5>
                        </div>
                        <p class="text-muted small mb-3">Mark all ingredients that cause allergic reactions or sensitivity:</p>

                        <div class="row g-2">
                            <?php foreach ($all_allergens as $allg): 
                                $is_checked = in_array((int)$allg['id'], $active_allergens, true);
                                $icon = get_allergen_icon($allg['allergen_name']);
                                $emoji = get_allergen_emoji($allg['allergen_name']);
                            ?>
                                <div class="col-6 col-sm-4">
                                    <label class="pref-badge-check pref-badge-check-maroon w-100 m-0" title="<?= htmlspecialchars($allg['allergen_name']) ?>">
                                        <input type="checkbox" name="allergens[]" value="<?= $allg['id'] ?>" <?= $is_checked ? 'checked' : '' ?>>
                                        <div class="badge-content w-100 justify-content-center py-2 px-3">
                                            <span class="me-2 fs-6"><?= $emoji ?></span>
                                            <i class="bi <?= $icon ?> text-maroon me-1"></i>
                                            <span class="fw-semibold small"><?= htmlspecialchars($allg['allergen_name']) ?></span>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SECTION 2: DIETARY PREFERENCES (OLIVE GREEN: #4E5134) -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-heart-pulse-fill text-olive me-2 fs-5"></i>
                            <h5 class="fw-bold text-dark font-playfair mb-0">2. Nutrition &amp; Dietary Lifestyle Goals</h5>
                        </div>
                        <p class="text-muted small mb-3">Choose the nutrition goals you are currently pursuing:</p>

                        <div class="row g-2">
                            <?php foreach ($all_dietary as $diet): 
                                $is_checked = in_array((int)$diet['id'], $active_dietary, true);
                                $diet_icon = 'bi-check2-circle';
                                if (stripos($diet['preferences_name'], 'calorie') !== false) $diet_icon = 'bi-fire';
                                elseif (stripos($diet['preferences_name'], 'gluten') !== false) $diet_icon = 'bi-shield-check';
                                elseif (stripos($diet['preferences_name'], 'fat') !== false) $diet_icon = 'bi-heart-pulse';
                                elseif (stripos($diet['preferences_name'], 'protein') !== false) $diet_icon = 'bi-lightning-charge';
                                elseif (stripos($diet['preferences_name'], 'sugar') !== false) $diet_icon = 'bi-slash-circle';
                            ?>
                                <div class="col-6 col-sm-4">
                                    <label class="pref-badge-check pref-badge-check-olive w-100 m-0">
                                        <input type="checkbox" name="dietary[]" value="<?= $diet['id'] ?>" <?= $is_checked ? 'checked' : '' ?>>
                                        <div class="badge-content w-100 justify-content-center py-2 px-3">
                                            <i class="bi <?= $diet_icon ?> text-olive me-2"></i>
                                            <span class="fw-semibold small"><?= htmlspecialchars($diet['preferences_name']) ?></span>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-3 pt-3">
                        <button type="submit" class="btn btn-ew-primary py-3 px-4 flex-grow-1 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Health &amp; Allergy Preferences
                        </button>
                        <a href="home.php" class="btn btn-ew-outline py-3 px-4">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>