<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toripäivä | Kevätmarkkina 2026</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="public/bubel.png" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

</head>
<body>

<?php include 'header.php'; ?>
<?php include 'front.php'; ?>
<?php include 'info.php'; ?>
<?php include 'kolmas.php'; ?>

<footer class="site-footer">
    <div class="footer-content">
        <p>&copy; 2026 Toripäivä | Oulu</p>
        <p>Seuraa meitä: 
            <a href="#" target="_blank">Facebook</a> | 
            <a href="#" target="_blank">Instagram</a>
        </p>
        <p>Yhteystiedot: <a href="mailto:info@toripaiva.fi">info@toripaiva.fi</a></p>
    </div>
</footer>
<!-- Animaatioille AOS -->
   <script>
    AOS.init();
  </script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".card h3").forEach(header => {
        header.addEventListener("click", () => {
            header.parentElement.classList.toggle("active");
        });
    });
});
</script>






</body>
</html>
