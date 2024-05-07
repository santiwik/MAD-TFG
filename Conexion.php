<?php
    $servername = "localhost";
    $dbname = "phpbd2";
    $username = "Daniel";
    $password = "";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
?>