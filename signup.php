<?php

// ===============================
// PROSES SIGN UP
// ===============================

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validasi
    if (empty($fullname) || empty($email) || empty($password)) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    } else {

        /*
         * Untuk sementara data hanya diproses oleh PHP.
         *
         * Nanti bagian ini bisa diganti dengan:
         * 1. koneksi database
         * 2. cek email
         * 3. password_hash()
         * 4. INSERT ke database
         */

        $message = "Registration successful!";
        $message_type = "success";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EatWise - Sign Up</title>


    <!-- Bootstrap CSS -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- Custom CSS -->

    <link rel="stylesheet" href="style.css">

</head>


<body>


<div class="signup-container">

    <div class="signup-card">


        <!-- ================= LOGO ================= -->

        <div class="logo-section">

            <div class="leaf-logo">
                <span></span>
            </div>

            <h1>EatWise</h1>

            <p>Good Food, Smart Choices</p>

        </div>



        <!-- ================= MESSAGE PHP ================= -->

        <?php if (!empty($message)): ?>

            <div
                class="alert
                <?php echo ($message_type == 'success')
                    ? 'alert-success'
                    : 'alert-danger'; ?>"
            >

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>



        <!-- ================= SIGN UP FORM ================= -->

        <form method="POST" action="signup.php">


            <!-- Full Name -->

            <div class="mb-3">

                <label for="fullname" class="form-label">
                    Full name
                </label>

                <input
                    type="text"
                    name="fullname"
                    class="form-control"
                    id="fullname"
                    placeholder="Enter Your Name"
                    value="<?php echo isset($_POST['fullname'])
                        ? htmlspecialchars($_POST['fullname'])
                        : ''; ?>"
                >

            </div>



            <!-- Email -->

            <div class="mb-3">

                <label for="email" class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    id="email"
                    placeholder="johndoe@gmail.com"
                    value="<?php echo isset($_POST['email'])
                        ? htmlspecialchars($_POST['email'])
                        : ''; ?>"
                >

            </div>



            <!-- Password -->

            <div class="mb-3">

                <label for="password" class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    id="password"
                    placeholder="Create Your Password"
                >

            </div>



            <!-- Sign Up Button -->

            <button
                type="submit"
                class="btn signup-btn"
            >
                Sign Up
            </button>


        </form>



        <!-- ================= DIVIDER ================= -->

        <div class="divider">

            <span></span>

            <p>or</p>

            <span></span>

        </div>



        <!-- ================= SOCIAL LOGIN ================= -->

        <div class="social-login">

            <button type="button" class="social-btn google">
                <i class="bi bi-google"></i>
            </button>

            <button type="button" class="social-btn apple">
                <i class="bi bi-apple"></i>
            </button>

            <button type="button" class="social-btn facebook">
                <i class="bi bi-facebook"></i>
            </button>

        </div>



        <!-- ================= LOGIN ================= -->

        <p class="login-text">

            Already have an account?

            <a href="login.php">
                Log in
            </a>

        </p>


    </div>

</div>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>