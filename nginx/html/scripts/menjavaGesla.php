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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["geslo"]) && isset($_POST["geslo2"])) {
        $newPassword = $_POST["geslo"];
        $confirmPassword = $_POST["geslo2"];
        $message = changePassword($pdo, $username, $newPassword, $confirmPassword);
        echo $message;
    }
}

function changePassword($pdo, $username, $newPassword, $confirmPassword){

    if ($newPassword === $confirmPassword) {

        try {
            $query = "UPDATE uporabnik SET Geslo = :password WHERE Ime = :username";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['password' => $newPassword, 'username' => $username]);

            header("Location: ../html/user.php");
        } catch (PDOException $e) {
            return "Nekaj je šlo narobe: " . $e->getMessage();
        }
    } else {
        return "Gesli se ne ujemata.";
    }
}
?>
