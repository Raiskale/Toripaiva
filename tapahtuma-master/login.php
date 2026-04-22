<?php

    session_start();

require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = $user["username"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Väärä salasana.";
        }
    } else {
        $error = "Käyttäjää ei löydy.";
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Kirjaudu</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<?php include "header.php"; ?>

<div class="auth-wrapper">
    <div class="auth-box">
        <h2>Kirjaudu sisään</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Käyttäjänimi" required>
            <input type="password" name="password" placeholder="Salasana" required>
            <button type="submit">Kirjaudu</button>
        </form>

        <a class="switch-link" href="register.php">Ei vielä tiliä? Rekisteröidy</a>
    </div>
</div>

</body>
</html>