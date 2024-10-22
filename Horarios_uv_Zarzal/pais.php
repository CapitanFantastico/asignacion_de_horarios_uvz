<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require_once "config/conexion.php";

// Handle form submission for adding or updating a country
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idPais = isset($_POST['idPais']) ? trim($_POST['idPais']) : '';
    $nombrePais = $_POST['nombrePais'];
    $descriPais = $_POST['descriPais'];
    $nomenPais = $_POST['nomenPais'];


/*<?php endif; ?>*/

//mira un ejemplo que tengo

/*<?php if ($variable_para_ingresar_o_actualizar === 'ingresar') : ?>;
            <div class="form-container">
            <form action="pais.php" method="post">

                <input type="text" name="nombrePais" id="nombrePais" placeholder="Nombre del País" required>
                <input type="text" name="descriPais" id="descriPais" placeholder="Descripción del País" required>
                <input type="text" name="nomenPais" id="nomenPais" placeholder="Nomenclatura del País" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
                //elimas el campo id porque la validacion indica que actualizaras

<?php elseif ($variable_para_ingresar_o_actualizar === 'actualizar') : ?>;
                <div class="form-container">
            <form action="pais.php" method="post">
                <input type="number" name="idPais" id="idPais" placeholder="ID del País">
                <input type="text" name="nombrePais" id="nombrePais" placeholder="Nombre del País" required>
                <input type="text" name="descriPais" id="descriPais" placeholder="Descripción del País" required>
                <input type="text" name="nomenPais" id="nomenPais" placeholder="Nomenclatura del País" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
            //como ahi se ingresa si se coloca el id
        <?php endif; ?>

    */
    //esa seria la estructura del html que tendrias que poner para validar por medio de dos opciones al inicio
    
//y ya al final para cerrar esas validaciones colocas



    var_dump($idPais);
    var_dump(empty($idPais));
    
    if (empty($idPais)) {
        // Insertar un nuevo país -
        $sql = "INSERT INTO pais (nombrePais, descriPais, nomenPais) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombrePais, $descriPais, $nomenPais);
    } else {
        // Actualizar país existente
        $sql = "UPDATE pais SET nombrePais = ?, descriPais = ?, nomenPais = ? WHERE idPais = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombrePais, $descriPais, $nomenPais, $idPais);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('Operación realizada exitosamente.'); window.location.href = 'pais.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'pais.php';</script>";
    }
    $stmt->close();
    
}

// Handle deletion of a country
if (isset($_GET['delete'])) {
    $idPais = $_GET['delete'];
    $sql = "DELETE FROM pais WHERE idPais = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idPais);
    if ($stmt->execute()) {
        echo "<script>alert('País eliminado exitosamente.'); window.location.href = 'pais.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el país: " . $stmt->error . "'); window.location.href = 'pais.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nombrePais LIKE '%$search%' OR descriPais LIKE '%$search%' OR nomenPais LIKE '%$search%'" : '';

// Retrieve list of countries
$sql = "SELECT * FROM pais $searchQuery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>País</title>
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
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 20px auto;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: #fff;
        }
        .actions {
            text-align: center;
        }
        .actions a {
            margin: 0 5px;
            color: #007BFF;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="inicio.php">Home</a>
        <a href="pais.php">País</a>
        <a href="municipio.php">municipio</a>
        <a href="departamento.php">Departamento</a>
        <a href="logout.php" class="right">Logout</a>
    </div>
    <div class="content">
        <h1>País</h1>
        <div class="form-container">
            <form action="pais.php" method="post">
                <input type="text" name="nombrePais" id="nombrePais" placeholder="Nombre del País" required>
                <input type="text" name="descriPais" id="descriPais" placeholder="Descripción del País" required>
                <input type="text" name="nomenPais" id="nomenPais" placeholder="Nomenclatura del País" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="pais.php" method="get">
                <input type="text" name="search" placeholder="Buscar país" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Nomenclatura</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
               <tr>
                    <td><?php echo $row['idPais']; ?></td>
                    <td><?php echo $row['nombrePais']; ?></td>
                    <td><?php echo $row['descriPais']; ?></td>
                    <td><?php echo $row['nomenPais']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editCountry(<?php echo $row['idPais']; ?>, '<?php echo $row['nombrePais']; ?>', '<?php echo $row['descriPais']; ?>', '<?php echo $row['nomenPais']; ?>')">Editar</a>
                        <a href="pais.php?delete=<?php echo $row['idPais']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este país?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function editCountry(idPais, nombrePais, descriPais, nomenPais) {
            document.getElementById('idPais').value = idPais;
            document.getElementById('nombrePais').value = nombrePais;
            document.getElementById('descriPais').value = descriPais;
            document.getElementById('nomenPais').value = nomenPais;
        }
    </script>
</body>
</html>

<?php
$conn->close();
