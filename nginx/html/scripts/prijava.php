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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    if (empty($username) || empty($pwd)) {
        header("Location: ../index.html");
        die();
    }

    try {
        $query = "SELECT * FROM uporabnik WHERE Ime = ? AND Geslo = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, $pwd]);

        if($stmt->rowCount() > 0) {
            $_SESSION['username'] = $username; // shranimo username v SESSION spremenljivki
            header("Location: ../html/user.php");
            die();
        } 
        else{
            header("Location: ../index.html");
            die();
        }
    } 
    catch(PDOException $e) {
        die("Something went wrong: " . $e->getMessage());
    }
} 
else {
    header("Location: ../html/login.html");
}
?>