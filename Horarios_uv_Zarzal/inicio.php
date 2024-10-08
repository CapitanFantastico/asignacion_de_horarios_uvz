<?php
require_once "config/conexion.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .navbar {
            background-color: #007BFF;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .navbar .right {
            float: right;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .fab:hover {
            background-color: #0056b3;
        }
        .fab-menu {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .fab-menu a {
            display: block;
            padding: 10px 20px;
            color: #007BFF;
            text-decoration: none;
        }
        .fab-menu a:hover {
            background-color: #f0f0f0;
        }
    </style>
    <script>
        function toggleFabMenu() {
            var menu = document.getElementById('fabMenu');
            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        }
    </script>
    </head>
<body>
    <div class="navbar">
        <a href="#home">Home</a>
        <a href="docente.php">DOCENTE</a>
        <a href="asignatura.php">ASIGNATURA</a>
        <a href="pais.php">PAIS</a>
        <a href="ver_informacion.php">INFORMACION REGISTRADA</a>
        <a href="logout.php" class="right">cerrar sesión</a>
    </div>
    <div class="content">
        <h1>Bienvenido señ@r administrador, <?= htmlspecialchars($_SESSION['username']) ?> !</h1>
        <p>This is the home page.</p>
        <img src="img/icono_univalle.jpg" alt="Imagen de bienvenida" class="center-image">
    </div>
    <button class="fab" onclick="toggleFabMenu()">+</button>
    <div id="fabMenu" class="fab-menu">
        <a href="pais.php">PAIS</a>
        <a href="docente.php">DOCENTE</a>
        <a href="asignatura.php">ASIGNATURA</a>
        <a href="logout.php">Cerrar sesión</a>
    </div>
    <style>
        .center-image {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 25%; /* Ajusta el tamaño de la imagen según sea necesario */
        }
    </style>
</body>
</html>