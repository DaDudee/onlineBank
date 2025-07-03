<?php
session_start();


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} 
else{
    header("Location: ../index.html");
    exit();
}

$dsn = "mysql:host=localhost; dbname=banka";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the Stanje value from the bancni_racun table
    $query = "SELECT Stanje FROM bancni_racun JOIN uporabnik ON bancni_racun.ID_uporabnik = uporabnik.ID_uporabnik WHERE uporabnik.Ime = ?";
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
    <title>Online Bank</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../slike/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        function novaStranMeni() {
            var newPageUrl = "meni.html";
            window.location.href = newPageUrl;
        }

        function novaStranProfil() {
            var newPageUrl = "profil.php";
            window.location.href = newPageUrl;
            return false;
        }

        function novaStranNakazilo() {
            var newPageUrl = "nakazilo.html";
            window.location.href = newPageUrl;
            return false;
        }

        function novaStranZgodovina() {
            var newPageUrl = "zgodovina.php";
            window.location.href = newPageUrl;
            return false;
        }

        function novaStranInfo() {
            var newPageUrl = "info.html";
            window.location.href = newPageUrl;
            return false;
        }
    </script>
    <!--black ninjaaaaa-->
</head>

<body>

    <header>
        <h3>Online Bank</h3>
    </header>

    <main>
        <p class="ime"><i>Welcome,
                <?php
                echo htmlspecialchars($username);
                ?></i>
        </p>
        <div class="stanje">
            <h1>BALANCE</h1>
            <p class="kolicina">
                <?php
                echo htmlspecialchars($stanje);
                ?>
                €
            </p>
        </div>

        <div class="gumbi">
            <button class="gumbM" onclick="return novaStranNakazilo()">SEND</button>
            <br>
            <button class="gumbM">RECEIVE</button>
            <br>
            <button class="gumbM" onclick="return novaStranZgodovina()">HISTORY</button>
        </div>
    </main>

    <footer>
        <hr>
        <div class="ikone">
            <div class="crte" onclick="novaStranMeni()">&#x2630</div>
            <div class="vpr" onclick="return novaStranInfo()"><i class="fas fa-question-circle"></i></div>
            <div class="acc" onclick="return novaStranProfil()"><i class="fas fa-user"></i></div>
        </div>
    </footer>

</body>

</html>