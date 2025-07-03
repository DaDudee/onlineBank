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

if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    exit();
}

try {
    $query = "SELECT * FROM uporabnik";
    $stmt = $pdo->query($query);

    if ($stmt->rowCount() > 0) {
        $userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $userList = [];
    }
} catch (PDOException $e) {
    die("Something went wrong: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrator Page</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/x-icon" href="slike/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        function novaStranMeni() {
            var newPageUrl = "meni.html";
            window.location.href = newPageUrl;
            return false;
        }

        function novaStranGraf() {
            var newPageUrl = "grafi.html";
            window.location.href = newPageUrl;
            return false;
        }
    </script>
</head>
<body>
    <header>
        <h3>Management Board</h3>
    </header>

    <main class="mainAdmin">

        <div class="gumbi">
            <button class="gumbM" onclick="return novaStranNakazilo()">DODAJ UPORABNIKA</button>
            <br>
            <button class="gumbM">ODSTRANI UPORABNIKA</button>
            <br>
            <button class="gumbM" onclick="return novaStranZgodovina()">SPREMENI UPORABNIKA</button>
        </div>

        <h2>Users & Info</h2>
        <hr class="hrAdmin">
        <div class="adminUporabniki"> 
            <?php if (!empty($userList)): ?>
                <?php foreach ($userList as $user): ?>
                    <div class="adminUporabnik">
                        <p><i class="fas fa-user"></i> Ime: <?php echo htmlspecialchars($user['Ime']); ?></p>
                        <p>Priimek: <?php echo htmlspecialchars($user['Priimek']); ?></p>
                        <p>Starost: <?php echo htmlspecialchars($user['Starost']); ?></p>
                        <p>Spol: <?php echo htmlspecialchars($user['Spol']); ?></p>
                        <p>Telefonska številka: <?php echo htmlspecialchars($user['Tel_st']); ?></p>
                        <p>Davčna številka: <?php echo htmlspecialchars($user['Davcna_st']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>

    </main>

    <footer>
        <hr>
        <div class="ikoneAdmin">
            <div class="crte" onclick="novaStranMeni()">&#x2630</div>
            <div class="acc" onclick="return novaStranGraf()"><i class="fas fa-chart-bar"></i></div>
        </div>
    </footer>
</body>
</html>
