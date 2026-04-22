<?php
session_start();
?>
<!-- navbaria -->
<header class="header">
    <div class="logo">
        <a href="index.php">
            <img src="public/bubel.png" alt="">
            Toripäivä
        </a>
    </div>

    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">&#9776;</label>

    <nav class="navbar">

<a href="index.php#eka">Etusivu</a>
<a href="index.php#toka">Tapahtuma</a>
<a href="index.php#kolmas">Ohjelma</a>



        <?php
            $link = isset($_SESSION["user"]) ? "dashboard.php" : "login.php";
            $text = isset($_SESSION["user"]) ? "Oma profiili" : "Ilmoittaudu mukaan";
        ?>

        <a id="nappi" href="<?= $link ?>">
            <?= $text ?> <i class='bx bx-arrow-back bx-rotate-180'></i>
        </a>

    </nav>
</header>
