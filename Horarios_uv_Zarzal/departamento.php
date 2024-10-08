<?php
require_once "config/conexion.php";
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}


// Handle form submission for adding or updating a department
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idDepto = $_POST['idDepto'];
    $nombreDepto = $_POST['nombreDepto'];
    $descriDepto = $_POST['descriDepto'];
    $nomenDepto = $_POST['nomenDepto'];
    $idPais = $_POST['idPais'];

    if ($idDepto) {
        // Update existing department
        $sql = "UPDATE departamento SET nombreDepto = ?, descriDepto = ?, nomenDepto = ?, idPais = ? WHERE idDepto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombreDepto, $descriDepto, $nomenDepto, $idPais, $idDepto);
        if ($stmt->execute()) {
            echo "<script>alert('Departamento actualizado exitosamente.'); window.location.href = 'departamento.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el departamento: " . $stmt->error . "'); window.location.href = 'departamento.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new department
        $sql = "INSERT INTO departamento (nombreDepto, descriDepto, nomenDepto, idPais) VALUES ('$nombreDepto', '$descriDepto', '$nomenDepto', '$idPais')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: departamento.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of a department
if (isset($_GET['delete'])) {
    $idDepto = $_GET['delete'];
    $sql = "DELETE FROM departamento WHERE idDepto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idDepto);
    if ($stmt->execute()) {
        echo "<script>alert('Departamento eliminado exitosamente.'); window.location.href = 'departamento.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el departamento: " . $stmt->error . "'); window.location.href = 'departamento.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nombreDepto LIKE '%$search%' OR descriDepto LIKE '%$search%' OR nomenDepto LIKE '%$search%' OR idPais LIKE '%$search%'" : '';

// Retrieve list of departments
$sql = "SELECT * FROM departamento $searchQuery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departamento</title>
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
        <h1>Departamento</h1>
        <div class="form-container">
            <form action="departamento.php" method="post">
                <input type="hidden" name="idDepto" id="idDepto">
                <input type="text" name="nombreDepto" id="nombreDepto" placeholder="Nombre del Departamento" required>
                <input type="text" name="descriDepto" id="descriDepto" placeholder="Descripción del Departamento" required>
                <input type="text" name="nomenDepto" id="nomenDepto" placeholder="Nomenclatura del Departamento" required>
                <input type="text" name="idPais" id="idPais" placeholder="ID del País" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="departamento.php" method="get">
                <input type="text" name="search" placeholder="Buscar departamento" value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>ID País</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idDepto']; ?></td>
                    <td><?php echo $row['nombreDepto']; ?></td>
                    <td><?php echo $row['descriDepto']; ?></td>
                    <td><?php echo $row['nomenDepto']; ?></td>
                    <td><?php echo $row['idPais']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editDepartamento(<?php echo $row['idDepto']; ?>, '<?php echo $row['nombreDepto']; ?>', '<?php echo $row['descriDepto']; ?>', '<?php echo $row['nomenDepto']; ?>', '<?php echo $row['idPais']; ?>')">Editar</a>
                        <a href="departamento.php?delete=<?php echo $row['idDepto']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este departamento?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function editDepartamento(idDepto, nombreDepto, descriDepto, nomenDepto, idPais) {
            document.getElementById('idDepto').value = idDepto;
            document.getElementById('nombreDepto').value = nombreDepto;
            document.getElementById('descriDepto').value = descriDepto;
            document.getElementById('nomenDepto').value = nomenDepto;
            document.getElementById('idPais').value = idPais;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>