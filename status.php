<?php
session_start();

// Data transaksi dari halaman sebelumnya
$transactionNo = $_SESSION['transaction_no'] ?? '#TRX123456';
$totalPayment = $_SESSION['total_payment'] ?? 105000;

// Format harga
$formattedPayment = 'Rp ' . number_format(
    (float) $totalPayment,
    0,
    ',',
    '.'
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EatWise - Payment Status</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="status-page">

    <div class="status-label">Status</div>

    <main class="success-container">

        <!-- SUCCESS ICON -->
        <div class="success-illustration">

            <div class="confetti confetti-left">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="success-circle">
                <span class="checkmark"></span>
            </div>

            <div class="confetti confetti-right">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>

        <!-- SUCCESS MESSAGE -->
        <h1>Payment Successful!</h1>

        <p class="success-description">
            Thank You! Your Payment Has Been<br>
            Successfully Processed.
        </p>

        <!-- TRANSACTION INFORMATION -->
        <section class="transaction-card">

            <div class="transaction-info">
                <span class="info-label">Transaction No.</span>

                <strong>
                    <?= htmlspecialchars((string) $transactionNo) ?>
                </strong>
            </div>

            <div class="transaction-total">
                <span class="info-label">Total Payment</span>

                <strong>
                    <?= htmlspecialchars($formattedPayment) ?>
                </strong>
            </div>

        </section>

        <!-- VIEW ORDER DETAILS -->
        <a href="order-details.php" class="details-button">
            View Order Details
        </a>

    </main>

</div>

</body>
</html>