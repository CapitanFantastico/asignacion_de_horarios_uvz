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

// Handle form submission for adding or updating a municipality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idMuni = $_POST['idMuni'];
    $nombreMuni = $_POST['nombreMuni'];
    $descriMuni = $_POST['descriMuni'];
    $nomenMuni = $_POST['nomenMuni'];
    $idDepto = $_POST['idDepto'];

    if ($idMuni) {
        // Update existing municipality
        $sql = "UPDATE municipio SET nombreMuni = ?, descriMuni = ?, nomenMuni = ?, idDepto = ? WHERE idMuni = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $nombreMuni, $descriMuni, $nomenMuni, $idDepto, $idMuni);
        if ($stmt->execute()) {
            echo "<script>alert('Municipio actualizado exitosamente.'); window.location.href = 'municipio.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el municipio: " . $stmt->error . "'); window.location.href = 'municipio.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new municipality
        $sql = "INSERT INTO municipio (nombreMuni, descriMuni, nomenMuni, idDepto) VALUES ('$nombreMuni', '$descriMuni', '$nomenMuni', '$idDepto')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: municipio.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of a municipality
if (isset($_GET['delete'])) {
    $idMuni = $_GET['delete'];
    $sql = "DELETE FROM municipio WHERE idMuni = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idMuni);
    if ($stmt->execute()) {
        echo "<script>alert('Municipio eliminado exitosamente.'); window.location.href = 'municipio.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el municipio: " . $stmt->error . "'); window.location.href = 'municipio.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nombreMuni LIKE '%$search%' OR descriMuni LIKE '%$search%' OR nomenMuni LIKE '%$search%' OR idDepto LIKE '%$search%'" : '';

// Retrieve list of municipalities
$sql = "SELECT * FROM municipio $searchQuery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .navbar {
            background-color: #DC143C;
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
            background-color: #FF5A73;
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
            background-color: #DC143C;
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
            background-color: #DC143C;
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
        <h1>Municipio</h1>
        <div class="form-container">
            <form action="municipio.php" method="post">
                <input type="hidden" name="idMuni" id="idMuni">
                <input type="text" name="nombreMuni" id="nombreMuni" placeholder="Nombre del Municipio" required>
                <input type="text" name="descriMuni" id="descriMuni" placeholder="Descripción del Municipio" required>
                <input type="text" name="nomenMuni" id="nomenMuni" placeholder="Nomenclatura del Municipio" required>
                <input type="number" name="idDepto" id="idDepto" placeholder="ID del Departamento" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="municipio.php" method="get">
                <input type="text" name="search" placeholder="Buscar municipio" value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>ID Departamento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idMuni']; ?></td>
                    <td><?php echo $row['nombreMuni']; ?></td>
                    <td><?php echo $row['descriMuni']; ?></td>
                    <td><?php echo $row['nomenMuni']; ?></td>
                    <td><?php echo $row['idDepto']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editMunicipio(<?php echo $row['idMuni']; ?>, '<?php echo $row['nombreMuni']; ?>', '<?php echo $row['descriMuni']; ?>', '<?php echo $row['nomenMuni']; ?>', '<?php echo $row['idDepto']; ?>')">Editar</a>
                        <a href="municipio.php?delete=<?php echo $row['idMuni']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este municipio?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function editMunicipio(idMuni, nombreMuni, descriMuni, nomenMuni, idDepto) {
            document.getElementById('idMuni').value = idMuni;
            document.getElementById('nombreMuni').value = nombreMuni;
            document.getElementById('descriMuni').value = descriMuni;
            document.getElementById('nomenMuni').value = nomenMuni;
            document.getElementById('idDepto').value = idDepto;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
