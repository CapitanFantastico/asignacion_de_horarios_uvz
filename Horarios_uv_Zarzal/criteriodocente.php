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

// Handle form submission for adding or updating a criterion of teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idCritDoc = $_POST['idCritDoc'];
    $diasHorario = $_POST['diasHorario'];
    $idDoc = $_POST['idDoc'];
    $fechaRegistroCri = $_POST['fechaRegistroCri'];
    $codInclu = $_POST['codInclu'];

    if ($idCritDoc) {
        // Update existing criterion of teacher
        $sql = "UPDATE criteriodocente SET diasHorario = ?, idDoc = ?, fechaRegistroCri = ?, codInclu = ? WHERE idCritDoc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisii", $diasHorario, $idDoc, $fechaRegistroCri, $codInclu, $idCritDoc);
        if ($stmt->execute()) {
            echo "<script>alert('Criterio de docente actualizado exitosamente.'); window.location.href = 'criteriodocente.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el criterio de docente: " . $stmt->error . "'); window.location.href = 'criteriodocente.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new criterion of teacher
        $sql = "INSERT INTO criteriodocente (diasHorario, idDoc, fechaRegistroCri, codInclu) VALUES ('$diasHorario', '$idDoc', '$fechaRegistroCri', '$codInclu')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: criteriodocente.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of a criterion of teacher
if (isset($_GET['delete'])) {
    $idCritDoc = $_GET['delete'];
    $sql = "DELETE FROM criteriodocente WHERE idCritDoc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCritDoc);
    if ($stmt->execute()) {
        echo "<script>alert('Criterio de docente eliminado exitosamente.'); window.location.href = 'criteriodocente.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el criterio de docente: " . $stmt->error . "'); window.location.href = 'criteriodocente.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE diasHorario LIKE '%$search%' OR idDoc LIKE '%$search%' OR fechaRegistroCri LIKE '%$search%' OR codInclu LIKE '%$search%'" : '';

// Pagination settings
$recordsPerPage = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $recordsPerPage;

// Retrieve total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM criteriodocente $searchQuery";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Retrieve list of criteria of teachers with pagination
$sql = "SELECT * FROM criteriodocente $searchQuery LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criterio de Docente</title>
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
        <a href="docente.php">Docente</a>
        <a href="tipodocente.php">Tipo de Docente</a>
        <a href="criteriodocente.php">Criterio de Docente</a>
        <a href="inclusionsocial.php">Inclusión Social</a>
        <a href="docasighorario.php">Docente Asignatura</a>
        <a href="logout.php" class="right">cerrar sesion</a>
    </div>
    <div class="content">
        <h1>Criterio de Docente</h1>
        <div class="form-container">
            <form action="criteriodocente.php" method="post">
                <input type="hidden" name="idCritDoc" id="idCritDoc">
                <input type="text" name="diasHorario" id="diasHorario" placeholder="Días de Horario" required>
                <input type="number" name="idDoc" id="idDoc" placeholder="ID del Docente" required>
                <input type="date" name="fechaRegistroCri" id="fechaRegistroCri" placeholder="Fecha de Registro" required>
                <input type="number" name="codInclu" id="codInclu" placeholder="Código de Inclusión" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="criteriodocente.php" method="get">
                <input type="text" name="search" placeholder="Buscar criterio de docente" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Días de Horario</th>
                    <th>ID Docente</th>
                    <th>Fecha de Registro</th>
                    <th>Código de Inclusión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idCritDoc']; ?></td>
                    <td><?php echo $row['diasHorario']; ?></td>
                    <td><?php echo $row['idDoc']; ?></td>
                    <td><?php echo $row['fechaRegistroCri']; ?></td>
                    <td><?php echo $row['codInclu']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editCriterioDocente(<?php echo $row['idCritDoc']; ?>, '<?php echo $row['diasHorario']; ?>', '<?php echo $row['idDoc']; ?>', '<?php echo $row['fechaRegistroCri']; ?>', '<?php echo $row['codInclu']; ?>')">Editar</a>
                        <a href="criteriodocente.php?delete=<?php echo $row['idCritDoc']; ?>" onclick="return confirm('¿Estás seguro de eliminar este criterio de docente?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="criteriodocente.php?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="criteriodocente.php?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="criteriodocente.php?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function editCriterioDocente(idCritDoc, diasHorario, idDoc, fechaRegistroCri, codInclu) {
            document.getElementById('idCritDoc').value = idCritDoc;
            document.getElementById('diasHorario').value = diasHorario;
            document.getElementById('idDoc').value = idDoc;
            document.getElementById('fechaRegistroCri').value = fechaRegistroCri;
            document.getElementById('codInclu').value = codInclu;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
