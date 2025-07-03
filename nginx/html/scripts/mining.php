<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../html/user.php");
    exit;
}

// Database connection parameters
$dsn = "mysql:host=localhost;dbname=banka";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Something went wrong: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["balance"])) {
        $username = $_SESSION['username'];
        $balance = floatval($_POST["balance"]);

        try {
            // Get the user's ID
            $stmt = $pdo->prepare("SELECT ID_uporabnik FROM uporabnik WHERE Ime = ?");
            $stmt->execute([$username]);
            $user_row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user_row) {
                echo "User does not exist.";
                exit;
            }

            $user_id = $user_row['ID_uporabnik'];

            // Update the user's Bitcoin balance
            $stmt = $pdo->prepare("UPDATE bancni_racun SET Bitcoin = Bitcoin + ? WHERE ID_uporabnik = ?");
            $stmt->execute([$balance, $user_id]);

            echo "Bitcoin balance successfully updated!";
            header("Location: ../html/bitcoin.php");
        } catch (PDOException $e) {
            echo "Something went wrong: " . $e->getMessage();
        }
    } else {
        echo "Balance not set.";
    }
} else {
    echo "Invalid request method.";
}
?>
