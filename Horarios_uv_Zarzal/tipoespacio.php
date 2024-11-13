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

// Handle form submission for adding or updating a tipoespacio
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idTipoEsp = $_POST['idTipoEsp'];
    $nomenTipoEsp = $_POST['nomenTipoEsp'];
    $nombreTipoEsp = $_POST['nombreTipoEsp'];
    $descriTipoEsp = $_POST['descriTipoEsp'];

    if ($idTipoEsp) {
        // Update existing tipoespacio
        $sql = "UPDATE tipoespacio SET nomenTipoEsp = ?, nombreTipoEsp = ?, descriTipoEsp = ? WHERE idTipoEsp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nomenTipoEsp, $nombreTipoEsp, $descriTipoEsp, $idTipoEsp);
        if ($stmt->execute()) {
            echo "<script>alert('Tipo de espacio actualizado exitosamente.'); window.location.href = 'tipoespacio.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el tipo de espacio: " . $stmt->error . "'); window.location.href = 'tipoespacio.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new tipoespacio
        $sql = "INSERT INTO tipoespacio (nomenTipoEsp, nombreTipoEsp, descriTipoEsp) VALUES ('$nomenTipoEsp', '$nombreTipoEsp', '$descriTipoEsp')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: tipoespacio.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of a tipoespacio
if (isset($_GET['delete'])) {
    $idTipoEsp = $_GET['delete'];
    $sql = "DELETE FROM tipoespacio WHERE idTipoEsp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idTipoEsp);
    if ($stmt->execute()) {
        echo "<script>alert('Tipo de espacio eliminado exitosamente.'); window.location.href = 'tipoespacio.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el tipo de espacio: " . $stmt->error . "'); window.location.href = 'tipoespacio.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nomenTipoEsp LIKE '%$search%' OR nombreTipoEsp LIKE '%$search%' OR descriTipoEsp LIKE '%$search%'" : '';

// Pagination settings
$recordsPerPage = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $recordsPerPage;

// Retrieve total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM tipoespacio $searchQuery";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Retrieve list of tipoespacio with pagination
$sql = "SELECT * FROM tipoespacio $searchQuery LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipo de Espacio</title>
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
        <h1>Tipo de Espacio</h1>
        <div class="form-container">
            <form action="tipoespacio.php" method="post">
                <input type="hidden" name="idTipoEsp" id="idTipoEsp">
                <input type="text" name="nomenTipoEsp" id="nomenTipoEsp" placeholder="Nomenclatura del Tipo de Espacio" required>
                <input type="text" name="nombreTipoEsp" id="nombreTipoEsp" placeholder="Nombre del Tipo de Espacio" required>
                <input type="text" name="descriTipoEsp" id="descriTipoEsp" placeholder="Descripción del Tipo de Espacio" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="tipoespacio.php" method="get">
                <input type="text" name="search" placeholder="Buscar tipo de espacio" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nomenclatura</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idTipoEsp']; ?></td>
                    <td><?php echo $row['nomenTipoEsp']; ?></td>
                    <td><?php echo $row['nombreTipoEsp']; ?></td>
                    <td><?php echo $row['descriTipoEsp']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editTipoEspacio(<?php echo $row['idTipoEsp']; ?>, '<?php echo $row['nomenTipoEsp']; ?>', '<?php echo $row['nombreTipoEsp']; ?>', '<?php echo $row['descriTipoEsp']; ?>')">Editar</a>
                        <a href="tipoespacio.php?delete=<?php echo $row['idTipoEsp']; ?>" onclick="return confirm('¿Estás seguro de eliminar este tipo de espacio?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="tipoespacio.php?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="tipoespacio.php?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="tipoespacio.php?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function editTipoEspacio(idTipoEsp, nomenTipoEsp, nombreTipoEsp, descriTipoEsp) {
            document.getElementById('idTipoEsp').value = idTipoEsp;
            document.getElementById('nomenTipoEsp').value = nomenTipoEsp;
            document.getElementById('nombreTipoEsp').value = nombreTipoEsp;
            document.getElementById('descriTipoEsp').value = descriTipoEsp;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
