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
$stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    echo "Sinulla ei ole oikeuksia nähdä tätä sivua.";
    exit;
}

$adminId = $user['id'];


if (isset($_POST['update_user'])) {
    $id = (int)$_POST['id'];
    $new_username = trim($_POST['username']);
    $participating = isset($_POST['participating']) ? 1 : 0;

    
    if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] === 0) {
        $filename = time() . '_' . basename($_FILES['profile_pic']['name']);
        $destination = 'uploads/' . $filename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)) {
            $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
            $stmt->bind_param("si", $filename, $id);
            $stmt->execute();
        }
    }

    
    $stmt = $conn->prepare("UPDATE users SET username = ?, participating = ? WHERE id = ?");
    $stmt->bind_param("sii", $new_username, $participating, $id);
    $stmt->execute();

    
    if ($id === $adminId) {
        $_SESSION['user'] = $new_username;
    }

    header("Location: admin.php");
    exit;
}


if (isset($_POST['delete_user'])) {
    $id = (int)$_POST['id'];

    if ($id !== $adminId) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: admin.php");
    exit;
}


$result = $conn->query("SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<div class="dashboard-box">
    <h2>Admin Dashboard</h2>
    <a href="dashboard.php" class="home-btn">Takaisin Dashboardiin</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Käyttäjänimi</th>
                <th>Profiilikuva</th>
                <th>Osallistuminen</th>
                <th>Rekisteröitynyt</th>
                <th>Toiminnot</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($u = $result->fetch_assoc()): ?>
            <tr>
                <!-- admininille hallintaa -->
                <form method="POST" enctype="multipart/form-data">
                    <td>
                        <?= $u['id'] ?>
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    </td>

                    <td>
                        <input type="text" name="username" value="<?= htmlspecialchars($u['username']) ?>">
                    </td>

                    <td>
                        <img src="<?= $u['profile_pic'] ? 'uploads/' . htmlspecialchars($u['profile_pic']) : 'public/bubel.png' ?>" width="50">
                        <input type="file" name="profile_pic">
                    </td>

                    <td>
                        <input type="checkbox" name="participating" <?= $u['participating'] ? 'checked' : '' ?>>
                    </td>

                    <td><?= $u['created_at'] ?></td>

                    <td>
                        <button type="submit" name="update_user" class="updatebtn">Päivitä</button>

                        <?php if ($u['role'] !== 'admin'): ?>
                            <button type="submit" name="delete_user"
                                onclick="return confirm('Haluatko varmasti poistaa käyttäjän?');">
                                Poista
                            </button>
                        <?php endif; ?>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
