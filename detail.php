<?php
// ============================================================================
// EatWise - Food Detail Page (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

$conn = db_connect();
$food_id = (int)($_GET['id'] ?? 1);

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    add_to_cart($food_id, $qty);
    set_flash('success', "Meal added to your cart ($qty portion(s))!");
    header("Location: cart.php");
    exit;
}

// Fetch food details from database
$sql = "SELECT f.*, c.category_name 
        FROM food f 
        LEFT JOIN category c ON f.category_id = c.id 
        WHERE f.id = $food_id LIMIT 1";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    set_flash('danger', 'Requested meal not found.');
    header('Location: home.php');
    exit;
}

$food = mysqli_fetch_assoc($res);
$food['calories'] = get_food_calories($food['id'], $food['food_name']);

// Fetch food allergens
$allergens_sql = "SELECT a.id, a.allergen_name 
                  FROM food_allergen fa 
                  JOIN allergen a ON fa.allergen_id = a.id 
                  WHERE fa.food_id = $food_id";
$a_res = mysqli_query($conn, $allergens_sql);
$food_allergens = [];
$food_allergen_ids = [];
if ($a_res) {
    while ($ar = mysqli_fetch_assoc($a_res)) {
        $food_allergens[] = $ar['allergen_name'];
        $food_allergen_ids[] = (int)$ar['id'];
    }
}

// Check if user is allergic to this dish
$user_id = get_logged_in_user_id();
$user_has_allergy = false;
$conflicting_allergens = [];
if ($user_id) {
    $ua_res = mysqli_query($conn, "SELECT a.allergen_name, a.id 
                                   FROM user_allergen ua 
                                   JOIN allergen a ON ua.allergen_id = a.id 
                                   WHERE ua.user_id = $user_id");
    if ($ua_res) {
        while ($uar = mysqli_fetch_assoc($ua_res)) {
            if (in_array((int)$uar['id'], $food_allergen_ids, true)) {
                $user_has_allergy = true;
                $conflicting_allergens[] = $uar['allergen_name'];
            }
        }
    }
}

// Estimate macronutrients
$protein = round($food['calories'] * 0.08);
$carbs = round($food['calories'] * 0.09);
$fat = round($food['calories'] * 0.03);

$page_title = htmlspecialchars($food['food_name']);
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="catagory.php?category=<?= $food['category_id'] ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($food['category_name']) ?></a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page"><?= htmlspecialchars($food['food_name']) ?></li>
        </ol>
    </nav>

    <!-- ALLERGY WARNING BANNER (ERROR PREVENTION: MAROON) -->
    <?php if ($user_has_allergy): ?>
        <div class="alert alert-danger bg-maroon-subtle border border-maroon rounded-4 p-4 shadow-sm mb-4 text-maroon" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-2 me-3 text-maroon"></i>
                <div>
                    <h5 class="alert-heading fw-bold font-playfair mb-1 text-maroon">Allergen Safety Warning</h5>
                    <p class="mb-0 small text-maroon">
                        This meal contains <strong><?= htmlspecialchars(implode(', ', $conflicting_allergens)) ?></strong>, which is currently flagged in your dietary allergy profile. Please exercise caution before purchasing.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4 g-lg-5">

        <!-- LEFT: FOOD IMAGE -->
        <div class="col-lg-6">
            <div class="detail-img-box position-relative rounded-4 overflow-hidden shadow">
                <img src="<?= htmlspecialchars(get_food_image_url($food['img'], $food['food_name'])) ?>" 
                     alt="<?= htmlspecialchars($food['food_name']) ?>" class="w-100 h-100 object-fit-cover">
                <span class="position-absolute top-0 start-0 m-3 badge bg-white text-maroon px-3 py-2 rounded-pill shadow-sm fw-bold border">
                    <i class="bi bi-tag-fill me-1"></i> <?= htmlspecialchars($food['category_name']) ?>
                </span>
            </div>
        </div>

        <!-- RIGHT: FOOD INFO & ORDER -->
        <div class="col-lg-6">
            <div class="ps-lg-3">

                <h1 class="display-6 fw-bold text-dark font-playfair mb-2"><?= htmlspecialchars($food['food_name']) ?></h1>
                
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="fs-3 fw-bold text-maroon font-playfair"><?= format_rupiah($food['price']) ?></span>
                    <span class="badge bg-olive-subtle text-olive px-3 py-2 rounded-pill fw-semibold border border-success-subtle">
                        <i class="bi bi-fire text-danger me-1"></i> <?= $food['calories'] ?> kcal
                    </span>
                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                        <i class="bi bi-star-fill text-warning me-1"></i> 4.9 (140+ reviews)
                    </span>
                </div>

                <p class="text-muted mb-4 lead fs-6">
                    <?= htmlspecialchars($food['description'] ?: 'Delightful chef-curated wholesome recipe prepared with organic garden vegetables and high-quality ingredients.') ?>
                </p>

                <!-- NUTRITION INFO TILES -->
                <div class="row g-2 mb-4">
                    <div class="col-3">
                        <div class="nutrition-pill">
                            <span>Calories</span>
                            <strong class="text-maroon"><?= $food['calories'] ?></strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="nutrition-pill">
                            <span>Protein</span>
                            <strong class="text-maroon"><?= $protein ?>g</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="nutrition-pill">
                            <span>Carbs</span>
                            <strong class="text-maroon"><?= $carbs ?>g</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="nutrition-pill">
                            <span>Fats</span>
                            <strong class="text-maroon"><?= $fat ?>g</strong>
                        </div>
                    </div>
                </div>

                <!-- INGREDIENTS SECTION (MATCHING IMAGE 1 BACKGROUND) -->
                <?php $ingredients = get_food_ingredients($food['id']); ?>
                <div class="card bg-white border rounded-4 p-3 mb-4" id="ingredientsSection">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-card-checklist text-maroon me-2 fs-5"></i>
                        <span class="fw-bold small text-dark">Fresh Ingredients:</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($ingredients as $ing): ?>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                                <i class="bi bi-check2 text-olive me-1"></i> <?= htmlspecialchars($ing) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ALLERGENS SECTION -->
                <div class="card bg-white border rounded-4 p-3 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-shield-exclamation text-maroon me-2 fs-5"></i>
                        <span class="fw-bold small text-dark">Allergen Information for this Dish:</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($food_allergens)): ?>
                            <?php foreach ($food_allergens as $allg): 
                                $is_user_allergic = in_array($allg, $conflicting_allergens, true);
                            ?>
                                <span class="badge <?= $is_user_allergic ? 'bg-maroon text-white' : 'bg-maroon-subtle text-maroon' ?> border border-maroon px-3 py-2 rounded-pill shadow-sm small">
                                    <span class="me-1"><?= get_allergen_emoji($allg) ?></span>
                                    <i class="bi <?= get_allergen_icon($allg) ?> me-1"></i> <?= htmlspecialchars($allg) ?>
                                    <?= $is_user_allergic ? ' (Your Restriction)' : '' ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge bg-olive-subtle text-olive border border-success-subtle px-3 py-2 rounded-pill shadow-sm small">
                                <i class="bi bi-check-circle-fill text-olive me-1"></i> Free from common allergens
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ORDER FORM -->
                <form method="POST" action="detail.php?id=<?= $food['id'] ?>" id="orderForm">
                    <input type="hidden" name="action" value="add_to_cart">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <label class="fw-semibold text-muted small">Portions:</label>
                        <div class="qty-control">
                            <button type="button" onclick="decrementQty()">-</button>
                            <input type="number" id="qtyInput" name="qty" value="1" min="1" max="50" readonly>
                            <button type="button" onclick="incrementQty()">+</button>
                        </div>
                        <span class="text-muted small ms-2">Total: <strong id="totalPrice" class="text-maroon font-playfair"><?= format_rupiah($food['price']) ?></strong></span>
                    </div>

                    <div class="d-flex gap-3">
                        <?php if ($user_has_allergy): ?>
                            <!-- Trigger Verification Warning Modal Matching Image 1 -->
                            <button type="button" class="btn btn-ew-primary py-3 px-4 flex-grow-1 shadow" onclick="showDetailWarningModal()">
                                <i class="bi bi-bag-plus-fill me-2 fs-5"></i> Add to Cart
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-ew-primary py-3 px-4 flex-grow-1 shadow">
                                <i class="bi bi-bag-plus-fill me-2 fs-5"></i> Add to Cart
                            </button>
                        <?php endif; ?>
                        <a href="home.php" class="btn btn-ew-outline py-3 px-4" title="Back to Menu">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>

</div>

<!-- ALLERGEN VERIFICATION MODAL (EXACTLY MATCHING IMAGE 1 DESIGN) -->
<?php if ($user_has_allergy): 
    $warn_info = get_allergen_warning_info(implode(', ', $conflicting_allergens));
?>
<div class="modal fade" id="detailAllergenModal" tabindex="-1" aria-labelledby="detailAllergenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ew-warn-dialog">
        <div class="modal-content ew-warn-card">
            
            <!-- Red Warning Triangle Icon with dark exclamation mark -->
            <div class="ew-warn-triangle-icon">
                <svg width="68" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L1 21H23L12 2Z" fill="#FF1E1E"/>
                    <path d="M12 8.5V13.5" stroke="#441715" stroke-width="2.4" stroke-linecap="round"/>
                    <circle cx="12" cy="17.2" r="1.3" fill="#441715"/>
                </svg>
            </div>

            <!-- Modal Heading in Playfair Display Red -->
            <h3 class="ew-warn-title" id="detailAllergenModalLabel">Allergen Warning</h3>

            <!-- Centered Subtext (3 lines) -->
            <p class="ew-warn-subtext">
                This Food Contains <?= htmlspecialchars($warn_info['category']) ?><br>
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
                    <h5 class="ew-warn-detected-title mb-1"><?= htmlspecialchars($warn_info['detected']) ?></h5>
                    <small class="ew-warn-detected-sub">E.G. <?= htmlspecialchars($warn_info['examples']) ?></small>
                </div>
            </div>

            <!-- Action Buttons matching Image 1 -->
            <div class="d-flex flex-column gap-2 w-100">
                <button type="button" class="btn btn-ew-warn-primary py-3 w-100" onclick="focusIngredients()">
                    View Ingredients
                </button>
                <a href="home.php" class="btn btn-ew-warn-secondary py-3 w-100 text-decoration-none">
                    Choose Another Food
                </a>
            </div>

            <!-- Subtle Verification Link to Proceed Anyway -->
            <div class="text-center mt-3">
                <button type="button" class="btn btn-link text-white-50 text-decoration-none small p-0 opacity-75" onclick="document.getElementById('orderForm').submit();">
                    <i class="bi bi-cart-check me-1"></i> I understand the risk, proceed to order anyway &rarr;
                </button>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>

<script>
const unitPrice = <?= (float)$food['price'] ?>;
function updatePriceDisplay(qty) {
    const total = unitPrice * qty;
    document.getElementById('totalPrice').innerText = 'Rp ' + total.toLocaleString('id-ID');
}
function incrementQty() {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) || 1;
    val = Math.min(50, val + 1);
    input.value = val;
    updatePriceDisplay(val);
}
function decrementQty() {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) || 1;
    val = Math.max(1, val - 1);
    input.value = val;
    updatePriceDisplay(val);
}

let detailWarnModalInstance = null;
function showDetailWarningModal() {
    const modalEl = document.getElementById('detailAllergenModal');
    if (modalEl) {
        detailWarnModalInstance = new bootstrap.Modal(modalEl);
        detailWarnModalInstance.show();
    }
}

function focusIngredients() {
    if (detailWarnModalInstance) {
        detailWarnModalInstance.hide();
    }
    const ingEl = document.getElementById('ingredientsSection');
    if (ingEl) {
        ingEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        ingEl.classList.add('border-danger', 'shadow');
        setTimeout(() => {
            ingEl.classList.remove('border-danger', 'shadow');
        }, 2500);
    }
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
