<?php
// ==========================================
// PROSES DATA DIETARY & ALLERGEN
// ==========================================

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dietary preferences
    $dietary = isset($_POST['dietary']) ? $_POST['dietary'] : [];

    // Ambil data allergens
    $allergens = isset($_POST['allergens']) ? $_POST['allergens'] : [];

    // Jika tombol Save ditekan
    if (isset($_POST['save'])) {

        // Untuk sementara tampilkan hasil
        $message = "Preferences saved successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EatWise - Allergens & Dietary Preferences</title>


    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600&display=swap"
        rel="stylesheet"
    >


    <!-- CSS -->
    <link rel="stylesheet" href="style.css">

</head>


<body>

<div class="page">


    <!-- ==========================================
         HEADER
    =========================================== -->

    <header class="header">

        <div class="leaf-logo">
            <span></span>
        </div>

        <h1>EatWise</h1>

        <h2>
            Allergen &amp; Dietary<br>
            Preferences
        </h2>

        <p>
            Select the ingredients you need to avoid<br>
            and your dietary preferences.
        </p>

    </header>



    <!-- ==========================================
         MESSAGE PHP
    =========================================== -->

    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php endif; ?>



    <!-- ==========================================
         FORM
    =========================================== -->

    <form method="POST" action="dietary.php">

        <main class="preferences-container">


            <!-- ==================================
                 DIETARY PREFERENCES
            =================================== -->

            <section class="preference-section">

                <h3>
                    Dietary Preferences
                </h3>


                <div class="preference-card">


                    <!-- Gluten Free -->

                    <div class="preference-item">

                        <div class="item-left">

                            <span class="item-icon">
                                ◉
                            </span>

                            <span>
                                Gluten Free
                            </span>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                name="dietary[]"
                                value="Gluten Free"
                            >

                            <span class="slider"></span>

                        </label>

                    </div>



                    <!-- Sugar Free -->

                    <div class="preference-item">

                        <div class="item-left">

                            <span class="item-icon">
                                ◉
                            </span>

                            <span>
                                Sugar Free
                            </span>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                name="dietary[]"
                                value="Sugar Free"
                            >

                            <span class="slider"></span>

                        </label>

                    </div>



                    <!-- Low Calorie -->

                    <div class="preference-item">

                        <div class="item-left">

                            <span class="item-icon">
                                ◉
                            </span>

                            <span>
                                Low Calorie
                            </span>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                name="dietary[]"
                                value="Low Calorie"
                            >

                            <span class="slider"></span>

                        </label>

                    </div>



                    <!-- Vegetarian -->

                    <div class="preference-item">

                        <div class="item-left">

                            <span class="item-icon">
                                ♡
                            </span>

                            <span>
                                Vegetarian
                            </span>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                name="dietary[]"
                                value="Vegetarian"
                            >

                            <span class="slider"></span>

                        </label>

                    </div>



                    <!-- Tree Nuts -->

                    <div class="preference-item">

                        <div class="item-left">

                            <span class="item-icon">
                                ◒
                            </span>

                            <span>
                                Tree Nuts
                            </span>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                name="dietary[]"
                                value="Tree Nuts"
                            >

                            <span class="slider"></span>

                        </label>

                    </div>



                    <!-- Other Allergen -->

                    <button
                        type="button"
                        class="other-allergen"
                    >

                        <span class="plus">
                            ＋
                        </span>

                        <span>
                            Other Allergen
                        </span>

                    </button>


                </div>

            </section>



            <!-- ==================================
                 ALLERGENS
            =================================== -->

            <section class="preference-section">

                <h3>
                    Allergens
                </h3>


                <div class="preference-card">


                    <!-- Seafood -->

                    <div class="allergen-item active">

                        <div class="item-left">

                            <span class="allergen-icon seafood">
                                ≋
                            </span>

                            <span>
                                Seafood
                            </span>

                        </div>


                        <label class="allergen-check">

                            <input
                                type="checkbox"
                                name="allergens[]"
                                value="Seafood"
                            >

                        </label>

                    </div>



                    <!-- Shellfish -->

                    <div class="allergen-item">

                        <div class="item-left">

                            <span class="allergen-icon">
                                ♧
                            </span>

                            <span>
                                Shellfish
                            </span>

                        </div>


                        <label class="allergen-check">

                            <input
                                type="checkbox"
                                name="allergens[]"
                                value="Shellfish"
                            >

                            

                        </label>

                    </div>



                    <!-- Fish -->

                    <div class="allergen-item">

                        <div class="item-left">

                            <span class="allergen-icon">
                                ◈
                            </span>

                            <span>
                                Fish
                            </span>

                        </div>


                        <label class="allergen-check">

                            <input
                                type="checkbox"
                                name="allergens[]"
                                value="Fish"
                            >

                            

                        </label>

                    </div>



                    <!-- Peanuts -->

                    <div class="allergen-item">

                        <div class="item-left">

                            <span class="allergen-icon peanut">
                                ●
                            </span>

                            <span>
                                Peanuts
                            </span>

                        </div>


                        <label class="allergen-check">

                            <input
                                type="checkbox"
                                name="allergens[]"
                                value="Peanuts"
                            >

                            

                        </label>

                    </div>



                    <!-- Tree Nuts -->

                    <div class="allergen-item">

                        <div class="item-left">

                            <span class="allergen-icon">
                                ◒
                            </span>

                            <span>
                                Tree Nuts
                            </span>

                        </div>


                        <label class="allergen-check">

                            <input
                                type="checkbox"
                                name="allergens[]"
                                value="Tree Nuts"
                            >

                            

                        </label>

                    </div>



                    <!-- Other Allergen -->

                    <button
                        type="button"
                        class="other-allergen"
                    >

                        <span class="plus">
                            ＋
                        </span>

                        <span>
                            Other Allergen
                        </span>

                    </button>


                </div>

            </section>

        </main>



        <!-- ==========================================
             SAVE BUTTON
        =========================================== -->

        <div class="save-container">

            <button
                type="submit"
                name="save"
                class="save-button"
            >
                Save
            </button>

        </div>

    </form>


</div>

</body>

</html>