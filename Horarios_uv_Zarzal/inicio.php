<?php
require_once "config/conexion.php";
session_start();

// Verificar si el usuario ha iniciado sesión
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
            background-color: #FF5A73;
        }

        .center-image {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 25%; /* Ajusta el tamaño de la imagen según sea necesario */
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
    
    <?php include 'menu.php'; ?>

    <div class="content">
        <h1>Bienvenido señ@r administrador, <?= htmlspecialchars($_SESSION['username']) ?> !</h1>
        <p>This is the home page.</p>
        <img src="img/icono_univalle.jpg" alt="Imagen de bienvenida" class="center-image">
    </div>
    
    <!-- Botón flotante -->


</body>
</html>
