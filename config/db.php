<?php

$host = "localhost";
$dbname = "university_internship_portal";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    echo "Database Connected Successfully!";

} catch (PDOException $e) {

    die("Database Connection Failed: " . $e->getMessage());

}
?>