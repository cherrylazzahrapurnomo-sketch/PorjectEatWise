
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EatWise - Order History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
                <span>EatWise</span>
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">CATEGORIES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">CARTS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">ACCOUNT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logout" href="#">LOGOUT</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ORDER HISTORY -->
    <main class="container history-container">

        <h4 class="page-title">Order History</h4>

        <!-- FILTER STATUS -->
        <div class="status-filters">
            <button class="filter-btn" onclick="filterOrders('All', this)">
                All
            </button>

            <button class="filter-btn" onclick="filterOrders('Completed', this)">
                Completed
            </button>

            <button class="filter-btn" onclick="filterOrders('Cancelled', this)">
                Cancelled
            </button>

            <button class="filter-btn active" onclick="filterOrders('Shipped', this)">
                Shipped
            </button>
        </div>

        <!-- ORDER 1 -->
        <div class="order-row" data-status="Shipped"
             onclick="showOrder('Chicken Grill Bowl', '60.000', '400 kcal')">

            <img
                src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=160&h=160&fit=crop"
                alt="Chicken Grill Bowl"
                class="food-image"
            >

            <div class="order-info">
                <h6>#EW123456</h6>
                <p class="food-name">Chicken Grill Bowl</p>
                <small>22 Apr 2026, 13:06</small>
            </div>

            <div class="order-status">
                <span class="badge-shipped">Shipped</span>
            </div>

            <div class="order-price">
                <strong>Rp 60.000</strong>
                <small>400 kcal</small>
            </div>

            <div class="order-arrow">
                <i class="bi bi-chevron-right"></i>
            </div>
        </div>

        <!-- ORDER 2 -->
        <div class="order-row" data-status="Shipped"
             onclick="showOrder('Caesar Salad', '45.000', '200 kcal')">

            <img
                src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=160&h=160&fit=crop"
                alt="Caesar Salad"
                class="food-image"
            >

            <div class="order-info">
                <h6>#EW123456</h6>
                <p class="food-name">Caesar Salad</p>
                <small>22 Apr 2026, 13:06</small>
            </div>

            <div class="order-status">
                <span class="badge-shipped">Shipped</span>
            </div>

            <div class="order-price">
                <strong>Rp 45.000</strong>
                <small>200 kcal</small>
            </div>

            <div class="order-arrow">
                <i class="bi bi-chevron-right"></i>
            </div>
        </div>

        <!-- EMPTY MESSAGE -->
        <div id="emptyMessage" class="empty-message" style="display:none;">
            No orders found for this status.
        </div>

    </main>

    <!-- ORDER DETAIL MODAL -->
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Order ID:</strong> #EW123456</p>
                    <p><strong>Menu:</strong> <span id="detailName"></span></p>
                    <p><strong>Price:</strong> Rp <span id="detailPrice"></span></p>
                    <p><strong>Calories:</strong> <span id="detailCalories"></span></p>
                    <p><strong>Status:</strong> <span class="badge-shipped">Shipped</span></p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function filterOrders(status, button) {
            const buttons = document.querySelectorAll(".filter-btn");
            const orders = document.querySelectorAll(".order-row");
            let visibleCount = 0;

            buttons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            orders.forEach(order => {
                const orderStatus = order.dataset.status;
                const show = status === "All" || orderStatus === status;

                order.style.display = show ? "grid" : "none";

                if (show) visibleCount++;
            });

            document.getElementById("emptyMessage").style.display =
                visibleCount === 0 ? "block" : "none";
        }

        function showOrder(name, price, calories) {
            document.getElementById("detailName").textContent = name;
            document.getElementById("detailPrice").textContent = price;
            document.getElementById("detailCalories").textContent = calories;

            const modal = new bootstrap.Modal(
                document.getElementById("orderModal")
            );

            modal.show();
        }
    </script>

</body>
</html>
