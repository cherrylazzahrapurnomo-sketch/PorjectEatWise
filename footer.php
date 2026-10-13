</div><!-- /.main-wrapper -->

<!-- FOOTER (No social media, no location, no phone number as requested) -->
<footer class="ew-footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-5 col-md-6">
                <a class="d-inline-flex align-items-center gap-2 text-decoration-none text-maroon fw-bold fs-3 mb-3" href="home.php">
                    <?= get_brand_leaf_svg(36, 36) ?>
                    <span class="font-playfair text-maroon">EatWise</span>
                </a>
                <p class="text-muted small mt-1 pe-lg-4">
                    EatWise provides nutritious, wholesome, and delicious meals customized to your personal dietary needs and allergen preferences. Good food, smart choices.
                </p>
            </div>

            <div class="col-lg-3 col-md-3 col-6">
                <h6 class="fw-bold text-dark mb-3">Quick Navigation</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="home.php" class="text-muted">Home</a></li>
                    <li><a href="catagory.php" class="text-muted">Categories</a></li>
                    <li><a href="cart.php" class="text-muted">Shopping Cart</a></li>
                    <li><a href="profile.php" class="text-muted">My Account</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-3 col-6">
                <h6 class="fw-bold text-dark mb-3">Menu &amp; Health</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="catagory.php?category=1" class="text-muted">Main Course</a></li>
                    <li><a href="catagory.php?category=2" class="text-muted">Fresh Salads</a></li>
                    <li><a href="catagory.php?category=3" class="text-muted">Healthy Bowls</a></li>
                    <li><a href="dietery.php" class="text-muted">Dietary &amp; Allergen Preferences</a></li>
                </ul>
            </div>
        </div>

        <div class="border-top pt-3 d-flex flex-column flex-sm-row justify-content-between align-items-center text-muted small">
            <p class="mb-0">&copy; <?= date('Y') ?> EatWise. All rights reserved.</p>
            <p class="mb-0 text-maroon fw-semibold font-playfair fs-6">Eat Healthy, Live Wise.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
