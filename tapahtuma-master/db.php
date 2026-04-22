<?php
$servername = "localhost";
$dbusername = "rainii";
$dbpassword = "Salakirjain1!"; 
$dbname = "rainii";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
