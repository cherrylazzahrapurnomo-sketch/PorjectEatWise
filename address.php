<?php
// ============================================================================
// EatWise - Saved Addresses Management (Pure Procedural PHP Native)
// Primary: #F8F4E9, Secondary Maroon: #752E2A, Success: #4E5134
// English Localization
// ============================================================================
require_once __DIR__ . '/db.php';

require_login();
$conn = db_connect();
$user_id = get_logged_in_user_id();
$user = get_logged_in_user();

// Handle Add / Edit / Delete Address (Both POST and GET supported)
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$addr_id = (int)($_POST['address_id'] ?? $_GET['address_id'] ?? 0);

if (!empty($action)) {
    if ($action === 'add') {
        $recipient_name = db_escape(trim($_POST['recipient_name'] ?? ''));
        $phone = db_escape(trim($_POST['phone'] ?? ''));
        $full_address = db_escape(trim($_POST['full_address'] ?? ''));
        $city = db_escape(trim($_POST['city'] ?? ''));
        $notes = db_escape(trim($_POST['notes'] ?? ''));

        if (!empty($recipient_name) && !empty($phone) && !empty($full_address)) {
            $sql = "INSERT INTO address (user_id, recipient_name, phone, full_address, city, notes) 
                    VALUES ($user_id, '$recipient_name', '$phone', '$full_address', '$city', '$notes')";
            if (mysqli_query($conn, $sql)) {
                set_flash('success', 'New delivery address successfully added!');
            } else {
                set_flash('danger', 'Failed to save address: ' . mysqli_error($conn));
            }
        } else {
            set_flash('danger', 'Please provide recipient name, phone number, and complete address.');
        }
    } elseif ($action === 'edit') {
        $recipient_name = db_escape(trim($_POST['recipient_name'] ?? ''));
        $phone = db_escape(trim($_POST['phone'] ?? ''));
        $full_address = db_escape(trim($_POST['full_address'] ?? ''));
        $city = db_escape(trim($_POST['city'] ?? ''));
        $notes = db_escape(trim($_POST['notes'] ?? ''));

        if ($addr_id > 0 && !empty($recipient_name) && !empty($full_address)) {
            $sql = "UPDATE address SET 
                    recipient_name = '$recipient_name',
                    phone = '$phone',
                    full_address = '$full_address',
                    city = '$city',
                    notes = '$notes'
                    WHERE id = $addr_id AND user_id = $user_id";
            if (mysqli_query($conn, $sql)) {
                set_flash('success', 'Address details successfully updated!');
            } else {
                set_flash('danger', 'Failed to update address: ' . mysqli_error($conn));
            }
        }
    } elseif ($action === 'delete') {
        if ($addr_id > 0) {
            $del = mysqli_query($conn, "DELETE FROM address WHERE id = $addr_id AND user_id = $user_id");
            if ($del) {
                set_flash('info', 'Address successfully removed.');
            } else {
                set_flash('danger', 'Failed to delete address: ' . mysqli_error($conn));
            }
        }
    }

    header('Location: address.php');
    exit;
}

// Retrieve user's saved addresses
$addresses = [];
$res = mysqli_query($conn, "SELECT * FROM address WHERE user_id = $user_id ORDER BY id DESC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $addresses[] = $row;
    }
}

$page_title = 'Saved Addresses';
require_once __DIR__ . '/navbar.php';
?>

<div class="container py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold fs-3 text-maroon font-playfair mb-1">Saved Delivery Addresses</h1>
            <p class="text-muted small mb-0">Manage your home, office, and preferred delivery destinations.</p>
        </div>
        <button type="button" class="btn btn-ew-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            <i class="bi bi-plus-lg me-1"></i> Add New Address
        </button>
    </div>

    <?php if (empty($addresses)): ?>
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 px-3 bg-white">
            <div class="mb-3 text-maroon fs-1"><i class="bi bi-geo-alt"></i></div>
            <h5 class="fw-bold text-maroon font-playfair">No Addresses Saved Yet</h5>
            <p class="text-muted small">Add your home or workplace address for seamless checkout experience.</p>
            <div class="mt-2">
                <button type="button" class="btn btn-ew-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Your First Address
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($addresses as $idx => $addr): ?>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 position-relative ew-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-maroon-subtle text-maroon border border-maroon mb-2">
                                    <i class="bi bi-geo-alt-fill me-1"></i> <?= htmlspecialchars($addr['city']) ?>
                                </span>
                                <h5 class="fw-bold text-dark mb-0 fs-6"><?= htmlspecialchars($addr['recipient_name']) ?></h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <button class="dropdown-item small" type="button" onclick='openEditModal(<?= json_encode($addr) ?>)'>
                                            <i class="bi bi-pencil me-2"></i> Edit Address
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item small text-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteAddressModal_<?= $addr['id'] ?>">
                                            <i class="bi bi-trash me-2"></i> Delete Address
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-muted small mb-2 flex-grow-1">
                            <?= htmlspecialchars($addr['full_address']) ?>
                        </p>

                        <div class="d-flex align-items-center text-muted small mb-2">
                            <i class="bi bi-telephone-fill me-2 text-secondary"></i>
                            <span><?= htmlspecialchars($addr['phone']) ?></span>
                        </div>

                        <?php if (!empty($addr['notes'])): ?>
                            <div class="p-2 bg-light rounded-2 text-muted small fst-italic">
                                <i class="bi bi-chat-left-text me-1"></i> Note: <?= htmlspecialchars($addr['notes']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- DELETE CONFIRMATION MODAL -->
                    <div class="modal fade" id="deleteAddressModal_<?= $addr['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-maroon font-playfair">
                                        <i class="bi bi-trash3 text-maroon me-2"></i>Delete Address?
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body py-3">
                                    <p class="text-muted mb-0">Are you sure you want to delete the address for <strong><?= htmlspecialchars($addr['recipient_name']) ?></strong> (<?= htmlspecialchars($addr['city']) ?>)?</p>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                    <form method="POST" action="address.php" class="m-0">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                        <button type="submit" class="btn btn-danger bg-maroon border-maroon rounded-pill px-3">
                                            <i class="bi bi-trash me-1"></i> Yes, Delete
                                        </button>
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

<!-- ADD ADDRESS MODAL -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-maroon font-playfair" id="addAddressModalLabel">
                    <i class="bi bi-geo-alt-fill text-maroon me-2"></i>Add New Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="address.php">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Recipient Name</label>
                        <input type="text" name="recipient_name" class="form-control" placeholder="e.g. Jane Doe" value="<?= htmlspecialchars($user['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" placeholder="e.g. 08123456789" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">City / District</label>
                        <input type="text" name="city" class="form-control" placeholder="e.g. South Jakarta" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Full Address</label>
                        <textarea name="full_address" class="form-control" rows="3" placeholder="Street name, building, house number, landmarks..." required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-muted">Delivery Notes (Optional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g. Gate passcode, leave with security...">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ew-primary px-4">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT ADDRESS MODAL -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-maroon font-playfair" id="editAddressModalLabel">
                    <i class="bi bi-pencil-square text-maroon me-2"></i>Edit Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="address.php">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="address_id" id="edit_address_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Recipient Name</label>
                        <input type="text" name="recipient_name" id="edit_recipient_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Phone Number</label>
                        <input type="tel" name="phone" id="edit_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">City / District</label>
                        <input type="text" name="city" id="edit_city" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Full Address</label>
                        <textarea name="full_address" id="edit_full_address" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-muted">Delivery Notes (Optional)</label>
                        <input type="text" name="notes" id="edit_notes" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ew-primary px-4">Update Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(addr) {
    document.getElementById('edit_address_id').value = addr.id;
    document.getElementById('edit_recipient_name').value = addr.recipient_name;
    document.getElementById('edit_phone').value = addr.phone;
    document.getElementById('edit_city').value = addr.city;
    document.getElementById('edit_full_address').value = addr.full_address;
    document.getElementById('edit_notes').value = addr.notes || '';

    const editModal = new bootstrap.Modal(document.getElementById('editAddressModal'));
    editModal.show();
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
