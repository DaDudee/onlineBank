<?php
session_start();

$dsn = "mysql:host=localhost; dbname=banka";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Something went wrong: " . $e->getMessage();
    exit;
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: ../index.html");
    exit();
}

try { 
    $query = "SELECT * FROM uporabnik";
    $stmt = $pdo->query($query);

    if ($stmt->rowCount() > 0) {
        $userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        header("Location: ../index.html");
        exit();
    }
} catch (PDOException $e) {
    die("Something went wrong: " . $e->getMessage());
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>Meni</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/x-icon" href="../slike/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <header>
        <h3>Users</h3>
        <hr class="hrSeznam">
    </header>

    <main>
        <div class="uporabniki">
            <?php foreach ($userList as $user): ?>
                <p><i class="fas fa-user"></i> &nbsp <?php echo htmlspecialchars($user['Ime']); ?></p>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p><i>&copy; 2025 Online bank</i></p>
    </footer>

</body>

</html>