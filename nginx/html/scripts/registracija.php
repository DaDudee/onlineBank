<?php

$dsn = "mysql:host=localhost; dbname=banka";
$dbusername = "root";
$dbpassword = "";

try {
    //"pdo" metodo uporabljamo za dostop do podatkovnih baz
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    //nastavimo exception ce je error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "neki je narobe: " . $e->getMessage();
    exit; // V primeru napake zapustimo
}

// preverimo ali je uporabnik uporanil "post method" ? cene ga vrnemo nazaj na prijavo
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //preverimo, ce je uporabnik podal vse zahtevane informacije:
    
    if (empty($_POST["username"]) || empty($_POST["pwd"]) || empty($_POST["priimek"]) || empty($_POST["starost"]) || empty($_POST["spol"]) || empty($_POST["telefonska"]) || empty($_POST["davcna"])) {
        header("Location: ../html/registracija.html");
        exit();
    }
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];
    $priimek = $_POST["priimek"];
    $starost = $_POST["starost"];
    $spol = $_POST["spol"];
    $telefonska = $_POST["telefonska"];
    $davcna = $_POST["davcna"];

    try {
    
        $query = "INSERT INTO uporabnik(Ime, Geslo, Priimek, Starost, Spol, Tel_st, Davcna_st) VALUES(?, ?, ?, ?, ?, ?, ?);";
        $stmt = $pdo->prepare($query); // Prepare the statement --> SQL INJECTION !!

        $stmt->execute([$username, $pwd, $priimek, $starost, $spol, $telefonska, $davcna]); 

        $pdo = null;
        $stmt = null;

        header("Location: ../index.html");
        die();
    } catch (PDOException $e) {
        die("neki je narobe..: " . $e->getMessage());
    }
} else {
    // Redirect if the request method is not POST
    header("Location: ../index.html");
}
?>
