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
    $query = "SELECT ID_uporabnik FROM uporabnik WHERE Ime = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $ID_uporabnik = $user['ID_uporabnik'];
    } else {
        header("Location: ../index.html");
        exit();
    }
} else {
    header("Location: ../index.html");
    exit();
}

try {
    $query = "SELECT * FROM nakazilo WHERE ID_posiljatelj = :ID_uporabnik";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['ID_uporabnik' => $ID_uporabnik]);

    // Fetch all transactions associated with the logged-in user ID
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Something went wrong: " . $e->getMessage());
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>Transaction History</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" type="image/x-icon" href="../slike/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <header>
        <h3>Transaction History</h3>
    </header>

    <main class="mainZgodovina">
        <div class="vsebina">
            <?php foreach ($transactions as $transaction): ?>
                <div class="transaction">
                    <p><b>Recipient:</b> <?php echo htmlspecialchars($transaction['ID_prejemnik']); ?></p>
                    <p><b>Amount:</b> <?php echo htmlspecialchars($transaction['Znesek']); ?> €</p>
                    <p><b>Description:</b> <?php echo htmlspecialchars($transaction['Opis']); ?></p>
                    <p><b>Date:</b> <?php echo htmlspecialchars($transaction['Datum']); ?></p>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p><i>&copy; 2025 Online bank</i></p>
    </footer>
</body>

</html>