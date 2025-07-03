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

try {
    // Define the query to retrieve user information based on username
    $query = "SELECT * FROM uporabnik WHERE Ime = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);

    // Check if a record is found
    if ($stmt->rowCount() > 0) {
        // Fetch the data
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        $ime = $userData['Ime'];
        $priimek = $userData['Priimek'];
        $starost = $userData['Starost'];
        $spol = $userData['Spol'];
        $telefonska = $userData['Tel_st'];
        $davcna = $userData['Davcna_st'];
    } else {
        // Redirect the user back to the login page if user data is not found
        header("Location: ../index.html");
        exit();
    }
} catch (PDOException $e) {
    die("Something went wrong: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Account</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <head>
        <h3>Account</h3>
        <hr>
    </head>

    <main>

        <div class="podatki">
            <p>Name:
                <?php
                echo htmlspecialchars($userData['Ime']);
                ?>
            </p>
            <p>Surname:
                <?php
                echo htmlspecialchars($userData['Priimek']);
                ?>
            </p>
            <p>Age:
                <?php
                echo htmlspecialchars($userData['Starost']);
                ?>
            </p>
            <p>Gender:
                <?php
                echo htmlspecialchars($userData['Spol']);
                ?>
            </p>
            <p>Phone number:
                <?php
                echo htmlspecialchars($userData['Tel_st']);
                ?>
            </p>
            <p>Tax number:
                <?php
                echo htmlspecialchars($userData['Davcna_st']);
                ?>
            </p>
        </div>

    </main>

    <footer>
        <p><i>&copy; 2025 Online bank</i></p>
    </footer>

</body>

</html>