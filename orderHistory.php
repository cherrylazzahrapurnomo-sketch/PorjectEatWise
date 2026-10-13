
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');

:root {
    --maroon: #7d2927;
    --olive: #505338;
    --cream: #f8f4e9;
    --peach: #f9d9d0;
    --border: #ded9cd;
    --muted: #77756e;
    --red: #d32f26;
}

* {
    box-sizing: border-box;
}

html {
    min-height: 100%;
}

body {
    margin: 0;
    min-height: 100vh;
    background: var(--peach);
    color: #080808;
    font-family: 'DM Sans', Arial, sans-serif;
}

/* MAIN PAGE */

.app-container {
    width: calc(100% - 10px);
    max-width: 1200px;
    min-height: 100vh;
    margin: 0 auto;
    background: var(--cream);
}

/* NAVBAR */

.navbar {
    width: 100%;
    height: 37px;
    padding: 0 6.2%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--maroon);
}

.brand {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #fff;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 18px;
    text-decoration: none;
    white-space: nowrap;
}

.brand-leaf {
    width: 29px;
    height: 25px;
    flex-shrink: 0;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 30px;
}

.nav-links a {
    color: #fff7ef;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 8px;
    text-decoration: none;
    white-space: nowrap;
}

.nav-links a:hover {
    opacity: 0.75;
}

.nav-links .logout {
    color: #ff6969;
}

/* ORDER HISTORY CONTENT */

.history-main {
    padding: 14px 7.5% 35px;
}

.history-main h1 {
    margin: 0 0 12px -3px;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 16px;
    line-height: 1.4;
    font-weight: 700;
}

/* FILTER TABS */

.filter-tabs {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 24px;
    margin-bottom: 10px;
}

.filter-tab {
    min-width: 87px;
    height: 28px;
    padding: 0 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ded2c1;
    border-radius: 20px;
    background: #eee3d3;
    color: #111;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition:
        background 0.2s ease,
        color 0.2s ease;
}

.filter-tab:hover {
    background: #ded5c4;
}

.filter-tab.active {
    background: var(--olive);
    border-color: var(--olive);
    color: #fff;
}

/* ORDER LIST */

.order-list {
    width: 100%;
}

.order-item {
    width: 100%;
    min-height: 89px;
    padding: 9px 7px 8px 3px;
    display: grid;
    grid-template-columns: 64px minmax(150px, 1fr) 95px 100px 13px;
    align-items: center;
    column-gap: 12px;
    border-bottom: 1px solid #e0dcd1;
    color: inherit;
    text-decoration: none;
    transition: background 0.15s ease;
}

.order-item:hover {
    background: rgba(225, 219, 205, 0.18);
}

/* FOOD IMAGE */

.food-image {
    width: 64px;
    height: 64px;
    overflow: hidden;
    border-radius: 9px;
    background: #e8e3d8;
}

.food-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

/* ORDER DETAILS */

.order-info {
    min-width: 0;
    align-self: center;
}

.order-info h2 {
    margin: 0 0 4px;
    font-family: 'DM Sans', Arial, sans-serif;
    font-size: 16px;
    line-height: 1.25;
    font-weight: 700;
}

.food-name {
    margin: 0 0 7px;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 13px;
    line-height: 1.3;
}

.order-date {
    margin: 0;
    color: #77756e;
    font-size: 11px;
    font-weight: 300;
}

/* STATUS BADGES */

.order-status {
    display: flex;
    justify-content: center;
    align-items: center;
}

.status-badge {
    min-width: 76px;
    padding: 6px 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.status-badge.shipped {
    background: #fae3a6;
    color: #937019;
}

.status-badge.completed {
    background: #a9f29c;
    color: #27833c;
}

.status-badge.cancelled {
    background: #f5c8c4;
    color: #b52d27;
}

/* PRICE AND CALORIES */

.order-summary {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.price {
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.calories {
    color: #d62920;
    font-size: 11px;
    font-weight: 400;
    white-space: nowrap;
}

/* RIGHT ARROW */

.order-arrow {
    width: 12px;
    height: 12px;
    border-top: 2px solid #080808;
    border-right: 2px solid #080808;
    transform: rotate(45deg);
    justify-self: center;
}

/* EMPTY STATE */

.empty-state {
    min-height: 100px;
    padding: 24px 10px;
    color: var(--muted);
    font-size: 13px;
}

.empty-state a {
    color: var(--maroon);
    font-weight: 600;
    text-decoration: none;
}

/* RESPONSIVE: TABLET */

@media (max-width: 700px) {
    .navbar {
        height: auto;
        min-height: 48px;
        padding: 10px 4%;
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }

    .nav-links {
        width: 100%;
        justify-content: space-between;
        gap: 8px;
    }

    .nav-links a {
        font-size: 8px;
    }

    .history-main {
        padding: 15px 5% 30px;
    }

    .filter-tabs {
        gap: 9px;
    }

    .filter-tab {
        min-width: 72px;
        height: 28px;
        padding: 0 12px;
        font-size: 11px;
    }

    .order-item {
        grid-template-columns: 55px minmax(90px, 1fr) auto 12px;
        column-gap: 9px;
        min-height: 80px;
        padding-right: 5px;
    }

    .food-image {
        width: 55px;
        height: 58px;
    }

    .order-info h2 {
        font-size: 13px;
    }

    .food-name {
        font-size: 12px;
    }

    .order-date {
        font-size: 10px;
    }

    .order-status {
        grid-column: 3;
        grid-row: 1;
    }

    .order-summary {
        grid-column: 3;
        grid-row: 1;
        align-self: end;
        padding-bottom: 1px;
        gap: 5px;
    }

    .status-badge {
        min-width: 0;
        padding: 4px 6px;
        font-size: 10px;
        transform: translateY(-14px);
    }

    .price {
        font-size: 10px;
    }

    .calories {
        font-size: 10px;
    }

    .order-arrow {
        grid-column: 4;
        grid-row: 1;
    }
}

/* RESPONSIVE: SMALL PHONE */

@media (max-width: 420px) {
    .app-container {
        width: 100%;
    }

    .brand {
        font-size: 17px;
    }

    .history-main {
        padding-right: 12px;
        padding-left: 12px;
    }

    .order-item {
        grid-template-columns: 48px minmax(75px, 1fr) 12px;
        column-gap: 8px;
    }

    .food-image {
        width: 48px;
        height: 50px;
    }

    .order-info h2 {
        font-size: 12px;
    }

    .food-name {
        font-size: 11px;
    }

    .order-status {
        display: none;
    }

    .order-summary {
        grid-column: 2;
        grid-row: 2;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        gap: 8px;
        margin-top: -3px;
    }

    .order-arrow {
        grid-column: 3;
        grid-row: 1 / span 2;
    }
}
