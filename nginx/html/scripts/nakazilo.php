<?php

session_start();

// Preverimo, ali je uporabnik prijavljen
if (!isset($_SESSION['username'])) {
    header("Location: ../html/user.php"); 
    exit;
}

$dsn = "mysql:host=localhost; dbname=banka";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "neki je narobe: " . $e->getMessage();
    exit; }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sender_username = $_SESSION['username'];
    
    $recipient_username = $_POST["recipient_username"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];

    try {
        // Get sender's ID
        $stmt = $pdo->prepare("SELECT ID_uporabnik FROM uporabnik WHERE Ime = ?");
        $stmt->execute([$sender_username]);
        $sender_row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sender_row) {
            echo "Pošiljatelj ne obstaja.";
            exit;
        }

        $sender_id = $sender_row['ID_uporabnik'];

        // Check if the recipient exists
        $stmt = $pdo->prepare("SELECT ID_uporabnik FROM uporabnik WHERE Ime = ?");
        $stmt->execute([$recipient_username]);
        $recipient_row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$recipient_row) {
            echo "Prejemnik ne obstaja.";
            exit;
        }

        $recipient_id = $recipient_row['ID_uporabnik'];

        // beginTransaction() je metoda v PHP-ju...
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE bancni_racun SET Stanje = Stanje - ? WHERE ID_uporabnik = ?");
        $stmt->execute([$amount, $sender_id]);

        $stmt = $pdo->prepare("UPDATE bancni_racun SET Stanje = Stanje + ? WHERE ID_uporabnik = ?");
        $stmt->execute([$amount, $recipient_id]);

        // Insert transfer record
        // funkcija NOW() vrne trenutni čas in datum
        $stmt = $pdo->prepare("INSERT INTO nakazilo (ID_posiljatelj, ID_prejemnik, Znesek, Opis, Datum) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$sender_id, $recipient_id, $amount, $description]);

        $pdo->commit();

        echo "Nakazilo uspešno izvedeno!";
        header("Location: ../html/user.php");
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "neki je narobe..: " . $e->getMessage();
    }
} else {
    echo "Neveljavna metoda zahteve.";
}

?>
