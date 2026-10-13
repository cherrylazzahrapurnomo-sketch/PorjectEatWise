<?php
// ============================================================================
// EatWise - Home Page (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// Typography: Playfair Display & Inter | English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

$conn = db_connect();
$user_id = get_logged_in_user_id();

// Fetch current user's registered allergens (if logged in)
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

// Handle Add to Cart via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $food_id = (int)($_POST['food_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));

    if ($food_id > 0) {
        add_to_cart($food_id, $qty);
        set_flash('success', 'Meal successfully added to your cart!');
    }
    // Redirect directly to cart.php so users see their cart item immediately
    header('Location: cart.php');
    exit;
}

// Fetch categories from database
$categories = [];
$cat_res = mysqli_query($conn, "SELECT id, category_name FROM category ORDER BY id ASC");
if ($cat_res) {
    while ($row = mysqli_fetch_assoc($cat_res)) {
        $categories[] = $row;
    }
}

// Filter & Search
$search = trim($_GET['search'] ?? '');
$category_filter = (int)($_GET['category'] ?? 0);

$where_clauses = ["1=1"];
if (!empty($search)) {
    $safe_search = db_escape($search);
    $where_clauses[] = "(f.food_name LIKE '%$safe_search%' OR f.description LIKE '%$safe_search%')";
}
if ($category_filter > 0) {
    $where_clauses[] = "f.category_id = $category_filter";
}
$where_sql = implode(' AND ', $where_clauses);

// Query foods with category and allergen associations
$foods_sql = "SELECT f.*, c.category_name, 
              GROUP_CONCAT(DISTINCT a.allergen_name SEPARATOR ', ') as allergens,
              GROUP_CONCAT(DISTINCT fa.allergen_id) as allergen_ids
              FROM food f
              LEFT JOIN category c ON f.category_id = c.id
              LEFT JOIN food_allergen fa ON f.id = fa.food_id
              LEFT JOIN allergen a ON fa.allergen_id = a.id
              WHERE $where_sql
              GROUP BY f.id
              ORDER BY f.id ASC";
$foods_res = mysqli_query($conn, $foods_sql);
$foods = [];
if ($foods_res) {
    while ($row = mysqli_fetch_assoc($foods_res)) {
        $row['calories'] = get_food_calories($row['id'], $row['food_name']);
        
        // Detect conflict with user's allergens
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

$page_title = 'Healthy & Nutritious Meals';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <!-- HERO BANNER -->
    <section class="ew-hero">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-olive text-white px-3 py-2 rounded-pill fw-semibold mb-3">
                    <i class="bi bi-shield-check me-1"></i> 100% Organic &amp; Preservative Free
                </span>
                <h1 class="display-5 fw-bold text-maroon font-playfair mb-3">Fresh, Nourishing Meals Tailored to Your Body</h1>
                <p class="lead text-muted mb-4 fs-6">
                    Delight in chef-crafted nutritious meals customized to your dietary preferences, calorie goals, and allergen safety.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#menu-section" class="btn btn-ew-primary">
                        <i class="bi bi-compass me-1"></i> Explore Menu
                    </a>
                    <a href="dietery.php" class="btn btn-ew-outline">
                        <i class="bi bi-sliders me-1"></i> Set Dietary Preferences
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=700&q=80" 
                     alt="Healthy Bowl EatWise" class="img-fluid rounded-4 shadow-lg" style="max-height: 290px; object-fit: cover;">
            </div>
        </div>
    </section>

    <!-- MENU EXPLORATION SECTION -->
    <section id="menu-section" class="pt-3">

        <!-- SEARCH & HEADER -->
        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-6">
                <h2 class="fw-bold fs-3 text-maroon font-playfair mb-1">Curated Daily Menu</h2>
                <p class="text-muted small mb-0">Select delicious, balanced meals crafted for clean wellness.</p>
            </div>
            <div class="col-md-6">
                <form method="GET" action="home.php" class="d-flex justify-content-md-end">
                    <?php if ($category_filter > 0): ?>
                        <input type="hidden" name="category" value="<?= $category_filter ?>">
                    <?php endif; ?>
                    <div class="ew-search-box w-100">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="search" class="form-control" placeholder="Search menu, salad, chicken bowl..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </form>
            </div>
        </div>

        <!-- CATEGORY PILLS -->
        <div class="category-pills">
            <a href="home.php<?= !empty($search) ? '?search=' . urlencode($search) : '' ?>" 
               class="category-pill <?= ($category_filter === 0) ? 'active' : '' ?>">
                <i class="bi bi-collection me-1"></i> All Meals
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="home.php?category=<?= (int)$cat['id'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" 
                   class="category-pill <?= ($category_filter === (int)$cat['id']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['category_name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- FOOD CARDS GRID -->
        <?php if (empty($foods)): ?>
            <div class="card border-0 shadow-sm rounded-4 text-center py-5 px-3 bg-white">
                <div class="mb-3 text-muted fs-1"><i class="bi bi-search"></i></div>
                <h5 class="fw-bold text-maroon font-playfair">No Dishes Found</h5>
                <p class="text-muted small">Try adjusting your search query or selecting a different category filter.</p>
                <div class="mt-2">
                    <a href="home.php" class="btn btn-ew-outline btn-sm">Reset Search</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                <?php foreach ($foods as $food): ?>
                    <div class="col">
                        <div class="card h-100 ew-card">
                            <div class="food-card-img-wrap">
                                <img src="<?= htmlspecialchars(get_food_image_url($food['img'], $food['food_name'])) ?>" 
                                     alt="<?= htmlspecialchars($food['food_name']) ?>" loading="lazy">
                                <span class="food-card-badge"><?= htmlspecialchars($food['category_name'] ?? 'Healthy') ?></span>
                                <span class="food-calories-badge"><i class="bi bi-fire text-warning"></i> <?= $food['calories'] ?> kcal</span>
                            </div>
                            
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="card-title fw-bold text-dark mb-0 fs-6">
                                        <a href="detail.php?id=<?= $food['id'] ?>" class="text-dark text-decoration-none">
                                            <?= htmlspecialchars($food['food_name']) ?>
                                        </a>
                                    </h5>
                                </div>

                                <p class="card-text text-muted small flex-grow-1 mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($food['description'] ?: 'Clean, nutrient-dense meal prepared with fresh ingredients.') ?>
                                </p>

                                <!-- ALLERGEN BADGES (ERROR PREVENTION: MAROON) -->
                                <div class="mb-3">
                                    <?php if ($food['has_allergy_warning']): ?>
                                        <span class="badge badge-allergy-warning rounded-pill small py-1 px-2" title="Allergen Warning: This food contains ingredients you avoid!">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Allergen Warning: <?= htmlspecialchars($food['conflicting_allergens']) ?>
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
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Price</small>
                                        <span class="fw-bold fs-6 text-maroon"><?= format_rupiah($food['price']) ?></span>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="detail.php?id=<?= $food['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="View Meal Details">
                                            Details
                                        </a>
                                        
                                        <!-- Add to Cart Form -->
                                        <form method="POST" action="home.php" id="cartForm_<?= $food['id'] ?>" class="m-0">
                                            <input type="hidden" name="action" value="add_to_cart">
                                            <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                                            <input type="hidden" name="qty" value="1">
                                            
                                            <?php if ($food['has_allergy_warning']): ?>
                                                <!-- Intercept with Warning Verification Modal -->
                                                <button type="button" class="btn btn-sm btn-ew-primary rounded-pill px-3" 
                                                        onclick="triggerAllergenWarning(<?= $food['id'] ?>, '<?= addslashes(htmlspecialchars($food['food_name'])) ?>', '<?= addslashes(htmlspecialchars($food['conflicting_allergens'])) ?>')" 
                                                        title="Add to Cart">
                                                    <i class="bi bi-cart-plus me-1"></i> Order
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-ew-primary rounded-pill px-3" title="Add to Cart">
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

    </section>

</div>

<!-- ALLERGEN WARNING VERIFICATION MODAL (ERROR PREVENTION: MAROON) -->
<div class="modal fade" id="allergenWarningModal" tabindex="-1" aria-labelledby="allergenWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-maroon-subtle pb-0">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle bg-maroon text-white p-2 d-inline-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold text-maroon font-playfair mb-0" id="allergenWarningModalLabel">Allergen Safety Warning</h5>
                        <small class="text-maroon">Health &amp; Dietary Precaution</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3 text-dark">
                    The dish <strong id="warnFoodTitle" class="text-dark">...</strong> contains 
                    <strong id="warnAllergenList" class="text-maroon fw-bold">...</strong>, which is currently registered in your allergen restriction list.
                </p>
                <div class="alert alert-danger bg-maroon-subtle border-maroon text-maroon p-3 rounded-3 mb-3 small">
                    <i class="bi bi-info-circle-fill me-1"></i> <strong>Medical Notice:</strong> Consuming ingredients you are allergic to can cause adverse health reactions. Please verify if you still wish to proceed.
                </div>
                <p class="small text-muted mb-0">Do you still want to purchase this meal?</p>
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel / Choose Other
                </button>
                <button type="button" class="btn btn-ew-primary rounded-pill px-4" id="btnConfirmAllergenPurchase">
                    <i class="bi bi-cart-check-fill me-1"></i> Yes, Proceed Anyway
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentTargetFormId = null;

function triggerAllergenWarning(foodId, foodName, allergens) {
    currentTargetFormId = 'cartForm_' + foodId;
    document.getElementById('warnFoodTitle').innerText = foodName;
    document.getElementById('warnAllergenList').innerText = allergens;
    
    const warningModal = new bootstrap.Modal(document.getElementById('allergenWarningModal'));
    warningModal.show();
}

document.getElementById('btnConfirmAllergenPurchase').addEventListener('click', function() {
    if (currentTargetFormId) {
        const targetForm = document.getElementById(currentTargetFormId);
        if (targetForm) {
            targetForm.submit();
        }
    }
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
