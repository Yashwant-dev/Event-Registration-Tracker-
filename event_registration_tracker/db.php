<?php
$host = 'localhost';
$db_name = 'event_registration_tracker';
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
function readJSONFile($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    $jsonData = file_get_contents($filename);
    return json_decode($jsonData, true);
}

function writeJSONFile($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}
?>
