<?php
// ============================================================================
// EatWise - Categories Page (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

$conn = db_connect();
$user_id = get_logged_in_user_id();

// Fetch current user's registered allergens
$user_allergens = [];
$user_allergen_names = [];
if ($user_id) {
    $u_res = mysqli_query($conn, "SELECT ua.allergen_id, a.allergen_name 
                                  FROM user_allergen ua 
                                  JOIN allergen a ON ua.allergen_id = a.id 
                                  WHERE ua.user_id = $user_id");
    if ($u_res) {
        while ($ur = mysqli_fetch_assoc($u_res)) {
            $user_allergens[] = (int)$ur['allergen_id'];
            $user_allergen_names[(int)$ur['allergen_id']] = $ur['allergen_name'];
        }
    }
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $food_id = (int)($_POST['food_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    if ($food_id > 0) {
        add_to_cart($food_id, $qty);
        set_flash('success', 'Meal added to your shopping cart!');
    }
    header("Location: cart.php");
    exit;
}

// Fetch categories list
$categories = [];
$cat_res = mysqli_query($conn, "SELECT id, category_name FROM category ORDER BY id ASC");
if ($cat_res) {
    while ($row = mysqli_fetch_assoc($cat_res)) {
        $c_id = (int)$row['id'];
        $count_res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM food WHERE category_id = $c_id");
        $row['food_count'] = ($count_res && $c_row = mysqli_fetch_assoc($count_res)) ? (int)$c_row['cnt'] : 0;
        $categories[] = $row;
    }
}

// Active category
$selected_cat_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$where_sql = ($selected_cat_id > 0) ? "WHERE f.category_id = $selected_cat_id" : "";

$foods_sql = "SELECT f.*, c.category_name,
              GROUP_CONCAT(DISTINCT a.allergen_name SEPARATOR ', ') as allergens,
              GROUP_CONCAT(DISTINCT fa.allergen_id) as allergen_ids
              FROM food f
              LEFT JOIN category c ON f.category_id = c.id
              LEFT JOIN food_allergen fa ON f.id = fa.food_id
              LEFT JOIN allergen a ON fa.allergen_id = a.id
              $where_sql
              GROUP BY f.id
              ORDER BY f.id ASC";
$foods_res = mysqli_query($conn, $foods_sql);
$foods = [];
if ($foods_res) {
    while ($row = mysqli_fetch_assoc($foods_res)) {
        $row['calories'] = get_food_calories($row['id'], $row['food_name']);

        // Check allergen conflicts
        $food_allergen_ids = !empty($row['allergen_ids']) ? explode(',', $row['allergen_ids']) : [];
        $matched_conflicts = [];
        if (!empty($user_allergens)) {
            foreach ($food_allergen_ids as $fa_id) {
                $fa_int = (int)$fa_id;
                if (in_array($fa_int, $user_allergens, true)) {
                    $matched_conflicts[] = $user_allergen_names[$fa_int] ?? 'Allergen #' . $fa_int;
                }
            }
        }
        $row['has_allergy_warning'] = !empty($matched_conflicts);
        $row['conflicting_allergens'] = implode(', ', $matched_conflicts);
        $foods[] = $row;
    }
}

$page_title = 'Meal Categories';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <!-- PAGE HEADER -->
    <div class="text-center mb-5">
        <span class="badge bg-maroon-subtle text-maroon border border-maroon px-3 py-2 rounded-pill fw-semibold mb-2">
            <i class="bi bi-grid-fill me-1"></i> Browse Categories
        </span>
        <h1 class="fw-bold text-maroon font-playfair display-6">EatWise Culinary Categories</h1>
        <p class="text-muted small">Select your preferred nutritional dish style designed for wholesome health.</p>
    </div>

    <!-- CATEGORY CARDS / TABS -->
    <div class="row g-3 mb-4 justify-content-center">
        <div class="col-6 col-md-3">
            <a href="catagory.php" class="card text-center p-3 text-decoration-none border shadow-sm rounded-4 transition-all <?= ($selected_cat_id === 0) ? 'bg-maroon text-white border-maroon' : 'bg-white text-dark' ?>">
                <div class="fs-3 mb-2"><i class="bi bi-collection-fill"></i></div>
                <h6 class="fw-bold mb-1 font-playfair">All Meals</h6>
                <small class="<?= ($selected_cat_id === 0) ? 'text-white-50' : 'text-muted' ?>">Total 10 Meals</small>
            </a>
        </div>
        <?php foreach ($categories as $cat): 
            $is_active = ($selected_cat_id === (int)$cat['id']);
        ?>
            <div class="col-6 col-md-3">
                <a href="catagory.php?category=<?= (int)$cat['id'] ?>" class="card text-center p-3 text-decoration-none border shadow-sm rounded-4 transition-all <?= $is_active ? 'bg-maroon text-white border-maroon' : 'bg-white text-dark' ?>">
                    <div class="fs-3 mb-2">
                        <?php if (stripos($cat['category_name'], 'salad') !== false): ?>
                            <i class="bi bi-egg-fried"></i>
                        <?php elseif (stripos($cat['category_name'], 'bowl') !== false): ?>
                            <i class="bi bi-cup-hot"></i>
                        <?php else: ?>
                            <i class="bi bi-check2-circle"></i>
                        <?php endif; ?>
                    </div>
                    <h6 class="fw-bold mb-1 font-playfair"><?= htmlspecialchars($cat['category_name']) ?></h6>
                    <small class="<?= $is_active ? 'text-white-50' : 'text-muted' ?>"><?= $cat['food_count'] ?> Choices</small>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- FOOD LIST GRID -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark font-playfair mb-0">
            <?= ($selected_cat_id === 0) ? 'Showing All Dishes' : 'Selected Category' ?>
            <span class="text-muted fs-6 fw-normal font-sans">(<?= count($foods) ?> dishes)</span>
        </h5>
        <?php if ($selected_cat_id > 0): ?>
            <a href="catagory.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="bi bi-x-circle me-1"></i> Clear Filter
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($foods)): ?>
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 bg-white">
            <h5 class="fw-bold text-maroon font-playfair">No meals found in this category.</h5>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            <?php foreach ($foods as $food): ?>
                <div class="col">
                    <div class="card h-100 ew-card">
                        <div class="food-card-img-wrap">
                            <img src="<?= htmlspecialchars(get_food_image_url($food['img'], $food['food_name'])) ?>" 
                                 alt="<?= htmlspecialchars($food['food_name']) ?>" loading="lazy">
                            <span class="food-card-badge"><?= htmlspecialchars($food['category_name']) ?></span>
                            <span class="food-calories-badge"><i class="bi bi-fire text-warning"></i> <?= $food['calories'] ?> kcal</span>
                        </div>
                        
                        <div class="card-body p-3 d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark mb-1 fs-6">
                                <a href="detail.php?id=<?= $food['id'] ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($food['food_name']) ?>
                                </a>
                            </h5>

                            <p class="card-text text-muted small flex-grow-1 mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($food['description'] ?: 'Wholesome balanced nutrition recipe.') ?>
                            </p>

                            <!-- ALLERGEN BADGES -->
                            <div class="mb-3">
                                <?php if ($food['has_allergy_warning']): ?>
                                    <span class="badge badge-allergy-warning rounded-pill small py-1 px-2" title="Allergen Warning">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Warning: <?= htmlspecialchars($food['conflicting_allergens']) ?>
                                    </span>
                                <?php elseif (!empty($food['allergens'])): ?>
                                    <span class="badge bg-light text-secondary border rounded-pill small py-1 px-2">
                                        <i class="bi bi-info-circle me-1"></i> Contains: <?= htmlspecialchars($food['allergens']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-olive-subtle text-olive border border-success-subtle rounded-pill small py-1 px-2">
                                        <i class="bi bi-check-circle me-1"></i> Free from Common Allergens
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Unit Price</small>
                                    <span class="fw-bold fs-6 text-maroon"><?= format_rupiah($food['price']) ?></span>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="detail.php?id=<?= $food['id'] ?>" class="btn-ew-outline-detail" title="View Meal Details">
                                        Detail
                                    </a>
                                    
                                    <form method="POST" action="catagory.php<?= $selected_cat_id > 0 ? '?category=' . $selected_cat_id : '' ?>" id="catForm_<?= $food['id'] ?>" class="m-0">
                                        <input type="hidden" name="action" value="add_to_cart">
                                        <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                                        <input type="hidden" name="qty" value="1">
                                        
                                        <?php if ($food['has_allergy_warning']): 
                                            $warn_info = get_allergen_warning_info($food['conflicting_allergens']);
                                        ?>
                                            <button type="button" class="btn btn-sm btn-ew-primary rounded-pill px-3" 
                                                    onclick="triggerCatWarning(<?= $food['id'] ?>, '<?= addslashes(htmlspecialchars($food['food_name'])) ?>', '<?= addslashes(htmlspecialchars($food['conflicting_allergens'])) ?>', '<?= addslashes($warn_info['category']) ?>', '<?= addslashes($warn_info['detected']) ?>', '<?= addslashes($warn_info['examples']) ?>')">
                                                <i class="bi bi-cart-plus me-1"></i> Order
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-ew-primary rounded-pill px-3">
                                                <i class="bi bi-cart-plus me-1"></i> Order
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- ALLERGEN VERIFICATION MODAL (MATCHING IMAGE 1 DESIGN) -->
<div class="modal fade" id="catAllergenModal" tabindex="-1" aria-labelledby="catAllergenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ew-warn-dialog">
        <div class="modal-content ew-warn-card">
            
            <!-- Red Warning Triangle Icon -->
            <div class="ew-warn-triangle-icon">
                <svg width="68" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L1 21H23L12 2Z" fill="#FF1E1E"/>
                    <path d="M12 8.5V13.5" stroke="#441715" stroke-width="2.4" stroke-linecap="round"/>
                    <circle cx="12" cy="17.2" r="1.3" fill="#441715"/>
                </svg>
            </div>

            <!-- Modal Heading in Playfair Display Red -->
            <h3 class="ew-warn-title" id="catAllergenModalLabel">Allergen Warning</h3>

            <!-- Centered Subtext (3 lines) -->
            <p class="ew-warn-subtext">
                This Food Contains <span id="catWarnCategoryText">Seafood</span><br>
                (According To Our Ingredient Data),<br>
                Which Matches Your Allergen Preference.
            </p>

            <!-- Detected Box with Allergen Icon -->
            <div class="ew-warn-detected-box">
                <div class="ew-warn-icon-wrap me-3">
                    <svg width="46" height="46" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M48 16 C 42 12, 34 12, 28 16 C 22 20, 18 28, 20 36 C 22 44, 28 50, 36 54 C 38 55, 42 56, 40 52 C 38 48, 34 46, 32 42 C 28 36, 30 28, 36 24 C 42 20, 48 22, 52 26 C 54 28, 56 26, 54 24 C 52 20, 50 18, 48 16 Z" fill="#E8613A"/>
                        <path d="M 38 20 C 34 22, 32 26, 32 30" stroke="#FF8A65" stroke-width="2" stroke-linecap="round"/>
                        <path d="M 34 26 C 30 30, 28 36, 30 40" stroke="#FF8A65" stroke-width="2" stroke-linecap="round"/>
                        <path d="M 44 14 C 48 10, 54 8, 58 8" stroke="#E8613A" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="44" cy="18" r="2" fill="#2E1C14"/>
                        <path d="M 38 52 C 40 56, 44 58, 48 56 C 44 54, 42 50, 40 48 Z" fill="#D34B22"/>
                    </svg>
                </div>
                <div>
                    <h5 class="ew-warn-detected-title mb-1" id="catWarnDetectedTitle">Seafood Detected</h5>
                    <small class="ew-warn-detected-sub" id="catWarnDetectedExamples">E.G. Shrimp, Fish, Oyster Sauce</small>
                </div>
            </div>

            <!-- Action Buttons matching Image 1 -->
            <div class="d-flex flex-column gap-2 w-100">
                <a href="#" id="catWarnViewIngredientsLink" class="btn btn-ew-warn-primary py-3 w-100">
                    View Ingredients
                </a>
                <button type="button" class="btn btn-ew-warn-secondary py-3 w-100" data-bs-dismiss="modal">
                    Choose Another Food
                </button>
            </div>

            <!-- Subtle Verification Link to Proceed Anyway -->
            <div class="text-center mt-3">
                <button type="button" id="catConfirmBtn" class="btn btn-link text-white-50 text-decoration-none small p-0 opacity-75">
                    <i class="bi bi-cart-check me-1"></i> I understand the risk, proceed to order anyway &rarr;
                </button>
            </div>

        </div>
    </div>
</div>

<script>
let catTargetFormId = null;

function triggerCatWarning(foodId, foodName, allergens, category, detectedTitle, detectedExamples) {
    catTargetFormId = 'catForm_' + foodId;
    document.getElementById('catWarnCategoryText').innerText = category || 'Allergen Ingredients';
    document.getElementById('catWarnDetectedTitle').innerText = detectedTitle || (allergens + ' Detected');
    document.getElementById('catWarnDetectedExamples').innerText = 'E.G. ' + (detectedExamples || allergens);
    document.getElementById('catWarnViewIngredientsLink').href = 'detail.php?id=' + foodId + '&from=warn';
    
    const warningModal = new bootstrap.Modal(document.getElementById('catAllergenModal'));
    warningModal.show();
}

document.getElementById('catConfirmBtn').addEventListener('click', function() {
    if (catTargetFormId) {
        const form = document.getElementById(catTargetFormId);
        if (form) form.submit();
    }
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>