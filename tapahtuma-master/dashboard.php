<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "db.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["user"];


$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}


$user['participating'] = (int)($user['participating'] ?? 0);
$user['role'] = $user['role'] ?? 'user';
$user['profile_pic'] = $user['profile_pic'] ?? '';

$success = "";
$error = "";


if (isset($_POST['upload'])) {
    if (!empty($_FILES['profile_pic']['name'])) {

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Vain JPG, PNG ja WEBP kuvat sallitaan.";
        } else {
            $newName = uniqid("profile_") . "." . $ext;
            $destination = __DIR__ . "/uploads/" . $newName;


            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)) {

                if (!empty($user['profile_pic']) && file_exists("uploads/" . $user['profile_pic'])) {
                    unlink("uploads/" . $user['profile_pic']);
                }

                $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE username = ?");
                $stmt->bind_param("ss", $newName, $username);
                $stmt->execute();

                $user['profile_pic'] = $newName;
                $success = "Profiilikuva päivitetty!";
            } else {
                $error = "Kuvan lataus epäonnistui.";
            }
        }
    }
}


if (isset($_POST['update_name'])) {
    $new_username = trim($_POST['new_username']);

    if ($new_username !== "") {
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_username, $username);

        if ($stmt->execute()) {
            $_SESSION['user'] = $new_username;
            $username = $new_username;

            
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            $user['participating'] = (int)($user['participating'] ?? 0);
            $user['role'] = $user['role'] ?? 'user';
            $user['profile_pic'] = $user['profile_pic'] ?? '';

            $success = "Käyttäjänimi päivitetty!";
        } else {
            $error = "Käyttäjänimen päivitys epäonnistui.";
        }
    }
}


if (isset($_POST['update_password'])) {
    if (password_verify($_POST['current_password'], $user['password'])) {

        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $hashed = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed, $username);
            $stmt->execute();

            $success = "Salasana vaihdettu!";
        } else {
            $error = "Salasanat eivät täsmää.";
        }

    } else {
        $error = "Nykyinen salasana väärin.";
    }
}


if (isset($_POST['update_participation'])) {
    $participating = isset($_POST['participating']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET participating = ? WHERE username = ?");
    $stmt->bind_param("is", $participating, $username);
    $stmt->execute();

    $user['participating'] = $participating;
    $success = "Osallistuminen päivitetty!";
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<?php include "header.php"; ?>

<div class="dashboard-wrapper">
<div class="dashboard-container">

    <h1 class="dashboard-title">Tervetuloa, <?= htmlspecialchars($username) ?> 👋</h1>

    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

    <div class="dashboard-grid">

     <!-- ensimmäinen kortti dashboardissa -->
        <div class="dash-card">
            <h3>Profiili</h3>

            <img src="<?= $user['profile_pic'] ? 'uploads/'.$user['profile_pic'] : 'public/bubel.png' ?>" class="profile-pic">

            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_pic" accept="image/*" required>
                <button name="upload">Vaihda kuva</button>
            </form>

            <form method="POST">
                <input type="text" name="new_username" placeholder="Uusi käyttäjänimi" required>
                <button name="update_name">Päivitä nimi</button>
            </form>
        </div>

     <!-- Dashboard turvallisuus asetuksien muokkaus -->
        <div class="dash-card">
            <h3>Turvallisuus</h3>
            <form method="POST">
                <input type="password" name="current_password" placeholder="Nykyinen salasana" required>
                <input type="password" name="new_password" placeholder="Uusi salasana" required>
                <input type="password" name="confirm_password" placeholder="Vahvista salasana" required>
                <button name="update_password">Vaihda salasana</button>
            </form>
        </div>

    <!-- tapahtumaan osallistumis kortti -->
        <div class="dash-card">
            <h3>Tapahtuma</h3>
            <form method="POST">
                <label class="checkbox">
                    <input type="checkbox" name="participating" <?= $user['participating'] === 1 ? 'checked' : '' ?>>
                    Osallistun tapahtumaan
                </label>
                <button name="update_participation">Päivitä</button>
            </form>
        </div>

       <!-- Jos käyttäjällä admin rooloi niin näkyy admin dashboardiin linkki -->
        <?php if ($user['role'] === 'admin'): ?>
        <div class="dash-card admin-card">
            <h3>Admin</h3>
            <a href="admin.php" class="admin-btn">Avaa hallintapaneeli</a>
        </div>
        <?php endif; ?>

    </div>

    <div class="dashboard-actions">
        <form method="POST" action="logout.php">
            <button class="logout-btn">Kirjaudu ulos</button>
        </form>
        <a href="index.php" class="home-btn">Etusivulle</a>
    </div>

</div>
</div>

</body>
</html>
