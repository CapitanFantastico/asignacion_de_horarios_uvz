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

// Handle form submission for adding or updating an espacio
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idEsp = $_POST['idEsp'];
    $idTipoEsp = $_POST['idTipoEsp'];
    $codUbi = $_POST['codUbi'];
    $capacidad = $_POST['capacidad'];
    $piso = $_POST['piso'];
    $cumpleInclusion = $_POST['cumpleInclusion'];

    if ($idEsp) {
        // Update existing espacio
        $sql = "UPDATE espacio SET idTipoEsp = ?, codUbi = ?, capacidad = ?, piso = ?, cumpleInclusion = ? WHERE idEsp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiiii", $idTipoEsp, $codUbi, $capacidad, $piso, $cumpleInclusion, $idEsp);
        if ($stmt->execute()) {
            echo "<script>alert('Espacio actualizado exitosamente.'); window.location.href = 'espacio.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el espacio: " . $stmt->error . "'); window.location.href = 'espacio.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new espacio
        $sql = "INSERT INTO espacio (idTipoEsp, codUbi, capacidad, piso, cumpleInclusion) VALUES ('$idTipoEsp', '$codUbi', '$capacidad', '$piso', '$cumpleInclusion')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: espacio.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of an espacio
if (isset($_GET['delete'])) {
    $idEsp = $_GET['delete'];
    $sql = "DELETE FROM espacio WHERE idEsp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idEsp);
    if ($stmt->execute()) {
        echo "<script>alert('Espacio eliminado exitosamente.'); window.location.href = 'espacio.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el espacio: " . $stmt->error . "'); window.location.href = 'espacio.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE idTipoEsp LIKE '%$search%' OR codUbi LIKE '%$search%' OR capacidad LIKE '%$search%' OR piso LIKE '%$search%' OR cumpleInclusion LIKE '%$search%'" : '';

// Pagination settings
$recordsPerPage = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $recordsPerPage;

// Retrieve total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM espacio $searchQuery";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Retrieve list of espacios with pagination
$sql = "SELECT * FROM espacio $searchQuery LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espacios</title>
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
        .pagination {
            margin: 20px 0;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            color: #007BFF;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #007BFF;
            color: #fff;
        }
        .pagination .active {
            background-color: #007BFF;
            color: #fff;
            border: 1px solid #007BFF;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="inicio.php">Home</a>
        <a href="asignatura.php">Asignaturas</a>
        <a href="programa.php">Programas</a>
        <a href="espacio.php">Espacios</a>
        <a href="tipoespacio.php">Tipos de Espacios</a>
        <a href="ubicacion.php">Ubicaciones</a>
        <a href="logout.php" class="right">cerrar sesion</a>
    </div>
    <div class="content">
        <h1>Espacios</h1>
        <div class="form-container">
            <form action="espacio.php" method="post">
                <input type="hidden" name="idEsp" id="idEsp">
                <input type="number" name="idTipoEsp" id="idTipoEsp" placeholder="ID del Tipo de Espacio" required>
                <input type="number" name="codUbi" id="codUbi" placeholder="Código de la Ubicación" required>
                <input type="number" name="capacidad" id="capacidad" placeholder="Capacidad" required>
                <input type="number" name="piso" id="piso" placeholder="Piso" required>
                <input type="number" name="cumpleInclusion" id="cumpleInclusion" placeholder="Cumple con Inclusión" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="espacio.php" method="get">
                <input type="text" name="search" placeholder="Buscar espacio" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Tipo Espacio</th>
                    <th>Código Ubicación</th>
                    <th>Capacidad</th>
                    <th>Piso</th>
                    <th>Cumple Inclusión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idEsp']; ?></td>
                    <td><?php echo $row['idTipoEsp']; ?></td>
                    <td><?php echo $row['codUbi']; ?></td>
                    <td><?php echo $row['capacidad']; ?></td>
                    <td><?php echo $row['piso']; ?></td>
                    <td><?php echo $row['cumpleInclusion']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editEspacio(<?php echo $row['idEsp']; ?>, '<?php echo $row['idTipoEsp']; ?>', '<?php echo $row['codUbi']; ?>', '<?php echo $row['capacidad']; ?>', '<?php echo $row['piso']; ?>', '<?php echo $row['cumpleInclusion']; ?>')">Editar</a>
                        <a href="espacio.php?delete=<?php echo $row['idEsp']; ?>" onclick="return confirm('¿Estás seguro de eliminar este espacio?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="espacio.php?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="espacio.php?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="espacio.php?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function editEspacio(idEsp, idTipoEsp, codUbi, capacidad, piso, cumpleInclusion) {
            document.getElementById('idEsp').value = idEsp;
            document.getElementById('idTipoEsp').value = idTipoEsp;
            document.getElementById('codUbi').value = codUbi;
            document.getElementById('capacidad').value = capacidad;
            document.getElementById('piso').value = piso;
            document.getElementById('cumpleInclusion').value = cumpleInclusion;
        }
    </script>
</body>
</html>
