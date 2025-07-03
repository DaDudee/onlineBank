<?php
session_start();


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {

    header("Location: ../index.html");
    exit();
}

$dsn = "mysql:host=localhost; dbname=banka";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT Bitcoin FROM bancni_racun JOIN uporabnik ON bancni_racun.ID_uporabnik = uporabnik.ID_uporabnik WHERE uporabnik.Ime = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $stanje = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Something went wrong: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Bitcoin mining</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../slike/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        function novaStranMining() {
            var newPageUrl = "mining.html";
            window.location.href = newPageUrl;
        }
    </script>
    <!--black ninjaaaaa-->
</head>

<body>
    <header>
        <h3>Bitcoin mining</h3>
    </header>

    <main>

        <p class="ime"><i>Welcome,
                <?php
                echo htmlspecialchars($username);
                ?></i>
        </p>
        <div class="stanjeB">
            <h1>BALANCE</h1>
            <p class="kolicina">
                <?php
                echo htmlspecialchars($stanje);
                ?>
                BTC
            </p>
        </div>

        <div class="gumbi">
            <button class="gumbM" onclick="return novaStranMining()">START MINING</button>
        </div>
    </main>

    <footer>
        <hr>
        <div class="ikone">
            <div class="crteB" onclick="novaStranMeni()">&#x2630</div>
            <div class="accB" onclick="return novaStranProfil()"><i class="fas fa-user"></i></div>
        </div>
    </footer>

</body>

</html>