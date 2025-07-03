<?php
session_start(); // Start the session

$dsn = "mysql:host=localhost; dbname=banka";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // preverimo ali je uporabnik prijavljen
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.html");
        exit();
    }

    $username = $_SESSION['username'];

    $query = "DELETE FROM uporabnik WHERE ime = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        $_SESSION = array(); // Clear all session variables
        session_destroy(); // Destroy the session
        header("Location: ../index.html");
        exit();
    } else {
        header("Location: ../html/meni.html?error=1");
        exit();
    }
} catch (PDOException $e) {
    echo "Napaka: " . $e->getMessage();
    exit();
} finally {
    $pdo = null;
}
?>
