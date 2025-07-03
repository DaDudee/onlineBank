<?php

session_start(); // Start the session

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

// Check if the session variable is set
if (!isset($_SESSION['username'])) {
    header("Location: ../html/meni.html");
    exit();
}

// Retrieve the username from the session
$username = $_SESSION["username"];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $TRR_racun = $_POST['TRR_racun'];
    $Stevilka_racuna = $_POST['Stevilka_racuna'];
    $Varnostna_stevilka = $_POST['Varnostna_stevilka'];

    try {
        // Get the user ID based on the username
        $query = "SELECT ID_uporabnik FROM uporabnik WHERE Ime = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Insert data into the bancni_racun table
            $query = "INSERT INTO bancni_racun(ID_uporabnik, TRR_racun, Stevilka_racuna, Varnostna_stevilka) VALUES(?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user['ID_uporabnik'], $TRR_racun, $Stevilka_racuna, $Varnostna_stevilka]);

            // Redirect the user
            header("Location: ../html/user.php");
            exit();
        } else {
            echo "User not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if the request method is not POST
    header("Location: ../html/user.php");
    exit();
}

?>
