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

// Handle form submission for adding or updating an asignatura
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idAsig = $_POST['idAsig'];
    $codAsig = $_POST['codAsig'];
    $nombreAsig = $_POST['nombreAsig'];
    $codProg = $_POST['codProg'];
    $periodoAcade = $_POST['periodoAcade'];
    $codInclu = $_POST['codInclu'];

    if ($idAsig) {
        // Update existing asignatura
        $sql = "UPDATE asignatura SET codAsig = ?, nombreAsig = ?, codProg = ?, periodoAcade = ?, codInclu = ? WHERE idAsig = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $codAsig, $nombreAsig, $codProg, $periodoAcade, $codInclu, $idAsig);
        if ($stmt->execute()) {
            echo "<script>alert('Asignatura actualizada exitosamente.'); window.location.href = 'asignatura.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar la asignatura: " . $stmt->error . "'); window.location.href = 'asignatura.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new asignatura
        $sql = "INSERT INTO asignatura (codAsig, nombreAsig, codProg, periodoAcade, codInclu) VALUES ('$codAsig', '$nombreAsig', '$codProg', '$periodoAcade', '$codInclu')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: asignatura.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of an asignatura
if (isset($_GET['delete'])) {
    $idAsig = $_GET['delete'];
    $sql = "DELETE FROM asignatura WHERE idAsig = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idAsig);
    if ($stmt->execute()) {
        echo "<script>alert('Asignatura eliminada exitosamente.'); window.location.href = 'asignatura.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar la asignatura: " . $stmt->error . "'); window.location.href = 'asignatura.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE codAsig LIKE '%$search%' OR nombreAsig LIKE '%$search%' OR codProg LIKE '%$search%' OR periodoAcade LIKE '%$search%' OR codInclu LIKE '%$search%'" : '';

// Pagination settings
$recordsPerPage = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $recordsPerPage;

// Retrieve total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM asignatura $searchQuery";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Retrieve list of asignaturas with pagination
$sql = "SELECT * FROM asignatura $searchQuery LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaturas</title>
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
        <h1>Asignaturas</h1>
        <div class="form-container">
            <form action="asignatura.php" method="post">
                <input type="hidden" name="idAsig" id="idAsig">
                <input type="text" name="codAsig" id="codAsig" placeholder="Código de la Asignatura" required>
                <input type="text" name="nombreAsig" id="nombreAsig" placeholder="Nombre de la Asignatura" required>
                <input type="number" name="codProg" id="codProg" placeholder="Código del Programa" required>
                <input type="text" name="periodoAcade" id="periodoAcade" placeholder="Periodo Académico" required>
                <input type="number" name="codInclu" id="codInclu" placeholder="Código de Inclusión" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="asignatura.php" method="get">
                <input type="text" name="search" placeholder="Buscar asignatura" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Código del Programa</th>
                    <th>Periodo Académico</th>
                    <th>Código de Inclusión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idAsig']; ?></td>
                    <td><?php echo $row['codAsig']; ?></td>
                    <td><?php echo $row['nombreAsig']; ?></td>
                    <td><?php echo $row['codProg']; ?></td>
                    <td><?php echo $row['periodoAcade']; ?></td>
                    <td><?php echo $row['codInclu']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editAsignatura(<?php echo $row['idAsig']; ?>, '<?php echo $row['codAsig']; ?>', '<?php echo $row['nombreAsig']; ?>', '<?php echo $row['codProg']; ?>', '<?php echo $row['periodoAcade']; ?>', '<?php echo $row['codInclu']; ?>')">Editar</a>
                        <a href="asignatura.php?delete=<?php echo $row['idAsig']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta asignatura?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="asignatura.php?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="asignatura.php?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="asignatura.php?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function editAsignatura(idAsig, codAsig, nombreAsig, codProg, periodoAcade, codInclu) {
            document.getElementById('idAsig').value = idAsig || '';
            document.getElementById('codAsig').value = codAsig || '';
            document.getElementById('nombreAsig').value = nombreAsig || '';
            document.getElementById('codProg').value = codProg || '';
            document.getElementById('periodoAcade').value = periodoAcade || '';
            document.getElementById('codInclu').value = codInclu || '';
        }   
    </script>
</body>
</html>
