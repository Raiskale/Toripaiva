<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Käyttäjänimi on jo käytössä.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            $success = "Rekisteröinti onnistui! Voit nyt kirjautua sisään.";
        } else {
            $error = "Rekisteröinti epäonnistui. Yritä uudelleen.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Rekisteröidy</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<?php include "src/header.php"; ?>

<div class="auth-wrapper">
    <div class="auth-box">
        <h2>Luo tili</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Käyttäjänimi" required>
            <input type="password" name="password" placeholder="Salasana" required>
            <button type="submit">Rekisteröidy</button>
        </form>

        <a class="switch-link" href="login.php">Onko sinulla jo tili? Kirjaudu</a>
    </div>
</div>

</body>
</html>
