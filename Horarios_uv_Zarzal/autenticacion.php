<?php
require_once "config/conexion.php";
session_start();

// Retrieve username and password from POST request
$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare and execute SQL query
$sql = "SELECT * FROM usuario WHERE usuario = ? AND passwd = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

// Check if credentials are valid
if ($result->num_rows > 0) {
    $_SESSION['username'] = $user;
    header(header: "Location: inicio.php");
    exit();
} else {
    echo "<script>alert('Invalid username or password.'); window.location.href = 'index.html';</script>";
}

// Close connection
$stmt->close();
$conn->close();
