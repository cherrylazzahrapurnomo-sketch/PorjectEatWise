
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EatWise - Address Saved</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-eatwise">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img
                src="https://cdn-icons-png.flaticon.com/512/2909/2909761.png"
                alt="EatWise Logo"
                class="logo"
            >
            EatWise
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="#">CATEGORIES</a></li>
                <li class="nav-item"><a class="nav-link" href="#">CARTS</a></li>
                <li class="nav-item"><a class="nav-link" href="#">ACCOUNT</a></li>
                <li class="nav-item"><a class="nav-link logout" href="#">LOGOUT</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main class="container address-container">

    <h4 class="page-title">Address Saved</h4>

    <div class="row g-3">

        <!-- KOLOM KIRI: DAFTAR ALAMAT -->
        <div class="col-lg-6">

            <div id="addressList">

                <!-- HOME -->
                <div class="saved-address selected" data-address="Home">
                    <input type="radio" name="selectedAddress"
                           class="address-radio" checked
                           onchange="selectAddress(this)">

                    <i class="bi bi-house-fill address-icon home-icon"></i>

                    <div class="address-info">
                        <span class="primary-badge">Primary address</span>
                        <strong>Home</strong>
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            Jl. Sukamajto No.25, Cilodong, Cilodong, Depok,
                            Jawa Barat 20903
                        </p>
                        <small><i class="bi bi-person"></i> Jane Jhon</small>
                        <small><i class="bi bi-telephone"></i> +62 81234567891</small>
                    </div>

                    <div class="address-actions">
                        <button class="mini-btn edit-btn"
                                onclick="editAddress(this)">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </button>
                        <button class="mini-btn remove-btn"
                                onclick="removeAddress(this)">
                            <i class="bi bi-trash-fill"></i> Remove
                        </button>
                    </div>
                </div>

                <!-- OFFICE -->
                <div class="saved-address" data-address="Office">
                    <input type="radio" name="selectedAddress"
                           class="address-radio"
                           onchange="selectAddress(this)">

                    <i class="bi bi-building address-icon"></i>

                    <div class="address-info">
                        <strong>Office</strong>
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            Universitas, Kebayoran Lama, Jakarta Selatan,
                            Daerah Khusus Ibukota Jakarta 12220
                        </p>
                        <small><i class="bi bi-person"></i> Jane Jhon</small>
                        <small><i class="bi bi-telephone"></i> +62 81234567891</small>
                    </div>

                    <div class="address-actions">
                        <button class="mini-btn edit-btn"
                                onclick="editAddress(this)">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </button>
                        <button class="mini-btn remove-btn"
                                onclick="removeAddress(this)">
                            <i class="bi bi-trash-fill"></i> Remove
                        </button>
                    </div>
                </div>

                <!-- DORM -->
                <div class="saved-address" data-address="Dorm">
                    <input type="radio" name="selectedAddress"
                           class="address-radio"
                           onchange="selectAddress(this)">

                    <i class="bi bi-building address-icon"></i>

                    <div class="address-info">
                        <strong>Dorm</strong>
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            Jl. Rawas, Kebayoran Lama, Jakarta Selatan,
                            Daerah Khusus Ibukota Jakarta 12220
                        </p>
                        <small><i class="bi bi-person"></i> Jane Jhon</small>
                        <small><i class="bi bi-telephone"></i> +62 81234567891</small>
                    </div>

                    <div class="address-actions">
                        <button class="mini-btn edit-btn"
                                onclick="editAddress(this)">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </button>
                        <button class="mini-btn remove-btn"
                                onclick="removeAddress(this)">
                            <i class="bi bi-trash-fill"></i> Remove
                        </button>
                    </div>
                </div>

            </div>

            <!-- ADD NEW ADDRESS BUTTON -->
            <button class="add-address-btn" onclick="newAddress()">
                <i class="bi bi-plus-circle-fill"></i>
                <span>
                    <strong>Add a new address</strong>
                    <small>Save other addresses to order food easily.</small>
                </span>
            </button>

            <!-- LEFT BUTTONS -->
            <div class="left-buttons">
                <button class="btn btn-save" onclick="saveAddress()">
                    <i class="bi bi-floppy"></i> Save Address
                </button>

                <button class="btn btn-cancel" onclick="cancelAddress()">
                    Cancel
                </button>
            </div>
        </div>

        <!-- KOLOM KANAN -->
        <div class="col-lg-6">

            <!-- SELECTED ADDRESS -->
            <section class="selected-card">
                <h5>
                    <i class="bi bi-geo-alt"></i>
                    Selected address
                </h5>

                <div class="selected-address-preview">
                    <i class="bi bi-house-fill preview-home"></i>

                    <div class="preview-info">
                        <div>
                            <strong id="previewName">Home</strong>
                            <span class="primary-badge">Primary address</span>
                        </div>
                        <p id="previewAddress">
                            Jl. Sukamajto No.25, Cilodong, Cilodong, Depok,
                            Jawa Barat 20903
                        </p>
                        <small id="previewPhone">Jane Jhon | +62 81234567891</small>
                    </div>

                    <span class="selected-check">
                        <i class="bi bi-check-circle"></i> Selected
                    </span>
                </div>
            </section>

            <!-- ADD ADDRESS FORM -->
            <section class="form-card" id="addressForm">
                <h5>
                    <i class="bi bi-plus-circle-fill"></i>
                    Add a new address
                </h5>

                <form id="newAddressForm">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="recipient">Recipient's Name<span>*</span></label>
                            <input type="text" id="recipient"
                                   class="form-control"
                                   placeholder="Enter full name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone">Phone Number<span>*</span></label>
                            <input type="tel" id="phone"
                                   class="form-control"
                                   placeholder="Enter phone number" required>
                        </div>

                        <div class="col-md-6">
                            <label for="province">Province<span>*</span></label>
                            <select id="province" class="form-select" required>
                                <option value="">Select Province</option>
                                <option>DKI Jakarta</option>
                                <option>Jawa Barat</option>
                                <option>Jawa Tengah</option>
                                <option>Jawa Timur</option>
                                <option>Banten</option>
                                <option>DI Yogyakarta</option>
                                <option>Bali</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="city">City/County<span>*</span></label>
                            <select id="city" class="form-select" required>
                                <option value="">Select City/County</option>
                                <option>Jakarta Selatan</option>
                                <option>Jakarta Timur</option>
                                <option>Depok</option>
                                <option>Bekasi</option>
                                <option>Bogor</option>
                                <option>Tangerang</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="district">Subdistrict<span>*</span></label>
                            <select id="district" class="form-select" required>
                                <option value="">Select Subdistrict</option>
                                <option>Cilodong</option>
                                <option>Kebayoran Lama</option>
                                <option>Pancoran</option>
                                <option>Beji</option>
                                <option>Sukmajaya</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="zipcode">ZIP code<span>*</span></label>
                            <input type="text" id="zipcode"
                                   class="form-control"
                                   placeholder="Example: 16415" required>
                        </div>

                        <div class="col-12">
                            <label for="fullAddress">Full address<span>*</span></label>
                            <textarea id="fullAddress" class="form-control"
                                      rows="3"
                                      placeholder="Street name, building, house number, etc."
                                      required></textarea>
                        </div>

                        <div class="col-12">
                            <label for="details">Address details<span>*</span></label>
                            <input type="text" id="details"
                                   class="form-control"
                                   placeholder="Example: House number, floor, or other details"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="primary-checkbox">
                                <input type="checkbox" id="makePrimary">
                                Set as primary address
                            </label>
                        </div>

                    </div>

                </form>
            </section>

        </div>
    </div>
</main>

<!-- NOTIFICATION -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast" id="notification" role="alert">
        <div class="toast-body" id="notificationMessage"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let selectedAddress = "Home";
    let editingCard = null;

    function notify(message) {
        document.getElementById("notificationMessage").textContent = message;
        bootstrap.Toast.getOrCreateInstance(
            document.getElementById("notification")
        ).show();
    }

    function selectAddress(radio) {
        document.querySelectorAll(".saved-address").forEach(card => {
            card.classList.remove("selected");
        });

        const card = radio.closest(".saved-address");
        card.classList.add("selected");

        selectedAddress = card.dataset.address;

        const info = card.querySelector(".address-info");
        document.getElementById("previewName").textContent =
            card.dataset.address;

        document.getElementById("previewAddress").textContent =
            info.querySelector("p").innerText.trim();

        document.getElementById("previewPhone").textContent =
            info.querySelectorAll("small")[0].innerText + " | " +
            info.querySelectorAll("small")[1].innerText;
    }

    function newAddress() {
        editingCard = null;
        document.getElementById("newAddressForm").reset();
        document.getElementById("addressForm").scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
        document.getElementById("recipient").focus();
    }

    function editAddress(button) {
        editingCard = button.closest(".saved-address");
        const info = editingCard.querySelector(".address-info");

        document.getElementById("recipient").value =
            info.querySelector("strong").textContent;

        document.getElementById("fullAddress").value =
            info.querySelector("p").innerText.trim();

        const phoneText = info.querySelectorAll("small")[1].innerText;
        document.getElementById("phone").value =
            phoneText.replace(/.*?(\+62.*)/, "$1");

        document.getElementById("addressForm").scrollIntoView({
            behavior: "smooth",
            block: "center"
        });

        document.getElementById("recipient").focus();
    }

    function removeAddress(button) {
        const card = button.closest(".saved-address");

        if (!confirm("Are you sure you want to remove this address?")) {
            return;
        }

        const wasSelected = card.classList.contains("selected");
        card.remove();

        if (wasSelected) {
            const firstCard = document.querySelector(".saved-address");

            if (firstCard) {
                firstCard.querySelector("input[type=radio]").checked = true;
                selectAddress(firstCard.querySelector("input[type=radio]"));
            } else {
                selectedAddress = "";
                document.getElementById("previewName").textContent = "No address selected";
                document.getElementById("previewAddress").textContent = "";
                document.getElementById("previewPhone").textContent = "";
            }
        }

        notify("Address removed.");
    }

    document.getElementById("newAddressForm").addEventListener("submit", function(event) {
        event.preventDefault();
    });

    function saveAddress() {
        const form = document.getElementById("newAddressForm");

        if (!form.reportValidity()) {
            notify("Please complete the required address fields.");
            return;
        }

        const name = document.getElementById("recipient").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const address = document.getElementById("fullAddress").value.trim();
        const province = document.getElementById("province").value;
        const city = document.getElementById("city").value;
        const district = document.getElementById("district").value;
        const zipcode = document.getElementById("zipcode").value.trim();
        const details = document.getElementById("details").value.trim();
        const primary = document.getElementById("makePrimary").checked;

        if (!name || !phone || !address || !province ||
            !city || !district || !zipcode || !details) {
            notify("Please complete all required fields.");
            return;
        }

        const fullAddress =
            address + ", " + district + ", " + city + ", " +
            province + " " + zipcode + ". " + details;

        let card = editingCard;

        if (!card) {
            card = document.createElement("div");
            card.className = "saved-address";
            card.dataset.address = name;

            card.innerHTML = `
                <input type="radio" name="selectedAddress"
                       class="address-radio"
                       onchange="selectAddress(this)">

                <i class="bi bi-geo-alt-fill address-icon"></i>

                <div class="address-info">
                    <strong></strong>
                    <p><i class="bi bi-geo-alt"></i></p>
                    <small><i class="bi bi-person"></i></small>
                    <small><i class="bi bi-telephone"></i></small>
                </div>

                <div class="address-actions">
                    <button type="button" class="mini-btn edit-btn"
                            onclick="editAddress(this)">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </button>
                    <button type="button" class="mini-btn remove-btn"
                            onclick="removeAddress(this)">
                        <i class="bi bi-trash-fill"></i> Remove
                    </button>
                </div>
            `;

            document.getElementById("addressList").appendChild(card);
        }

        card.dataset.address = name;
        card.querySelector(".address-info strong").textContent = name;
        card.querySelector(".address-info p").textContent = fullAddress;
        card.querySelectorAll(".address-info small")[0].textContent = name;
        card.querySelectorAll(".address-info small")[1].textContent = phone;

        if (primary) {
            document.querySelectorAll(".saved-address").forEach(item => {
                item.classList.remove("selected");
                item.querySelector(".primary-badge")?.remove();
            });

            const badge = document.createElement("span");
            badge.className = "primary-badge";
            badge.textContent = "Primary address";
            card.querySelector(".address-info").prepend(badge);
        }

        card.querySelector("input[type=radio]").checked = true;
        selectAddress(card.querySelector("input[type=radio]"));

        editingCard = null;
        form.reset();
        notify("Address saved successfully.");
    }

    function cancelAddress() {
        document.getElementById("newAddressForm").reset();
        editingCard = null;
        notify("Changes in the form have been cancelled.");
    }
</script>

</body>
</html>
