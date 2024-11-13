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

// Handle form submission for adding or updating a docasighorario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idDocAsig = $_POST['idDocAsig'];
    $idDoc = $_POST['idDoc'];
    $idAsig = $_POST['idAsig'];
    $idEsp = $_POST['idEsp'];
    $diaAsigHorario = $_POST['diaAsigHorario'];
    $horaAsigHorario = $_POST['horaAsigHorario'];
    $fechaRegistro = $_POST['fechaRegistro'];

    if ($idDocAsig) {
        // Update existing docasighorario
        $sql = "UPDATE docasighorario SET idDoc = ?, idAsig = ?, idEsp = ?, diaAsigHorario = ?, horaAsigHorario = ?, fechaRegistro = ? WHERE idDocAsig = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisssi", $idDoc, $idAsig, $idEsp, $diaAsigHorario, $horaAsigHorario, $fechaRegistro, $idDocAsig);
        if ($stmt->execute()) {
            echo "<script>alert('Asignación de horario actualizada exitosamente.'); window.location.href = 'docasighorario.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar la asignación de horario: " . $stmt->error . "'); window.location.href = 'docasighorario.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new docasighorario
        $sql = "INSERT INTO docasighorario (idDoc, idAsig, idEsp, diaAsigHorario, horaAsigHorario, fechaRegistro) VALUES ('$idDoc', '$idAsig', '$idEsp', '$diaAsigHorario', '$horaAsigHorario', '$fechaRegistro')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: docasighorario.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of a docasighorario
if (isset($_GET['delete'])) {
    $idDocAsig = $_GET['delete'];
    $sql = "DELETE FROM docasighorario WHERE idDocAsig = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idDocAsig);
    if ($stmt->execute()) {
        echo "<script>alert('Asignación de horario eliminada exitosamente.'); window.location.href = 'docasighorario.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar la asignación de horario: " . $stmt->error . "'); window.location.href = 'docasighorario.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE idDoc LIKE '%$search%' OR idAsig LIKE '%$search%' OR idEsp LIKE '%$search%' OR diaAsigHorario LIKE '%$search%' OR horaAsigHorario LIKE '%$search%' OR fechaRegistro LIKE '%$search%'" : '';

// Pagination settings
$recordsPerPage = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $recordsPerPage;

// Retrieve total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM docasighorario $searchQuery";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Retrieve list of docasighorario with pagination
$sql = "SELECT * FROM docasighorario $searchQuery LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Horarios de Docentes</title>
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
        <h1>Asignación de Horarios de Docentes</h1>
        <div class="form-container">
            <form action="docasighorario.php" method="post">
                <input type="hidden" name="idDocAsig" id="idDocAsig">
                <input type="number" name="idDoc" id="idDoc" placeholder="ID del Docente" required>
                <input type="number" name="idAsig" id="idAsig" placeholder="ID de la Asignatura" required>
                <input type="number" name="idEsp" id="idEsp" placeholder="ID del Espacio" required>
                <input type="text" name="diaAsigHorario" id="diaAsigHorario" placeholder="Días de la Asignatura y Horario" required>
                <input type="text" name="horaAsigHorario" id="horaAsigHorario" placeholder="Hora de la Asignatura y Horario" required>
                <input type="date" name="fechaRegistro" id="fechaRegistro" placeholder="Fecha de Registro" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="docasighorario.php" method="get">
                <input type="text" name="search" placeholder="Buscar asignación de horario" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Docente</th>
                    <th>ID Asignatura</th>
                    <th>ID Espacio</th>
                    <th>Días de Asignatura y Horario</th>
                    <th>Hora de Asignatura y Horario</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['idDocAsig']; ?></td>
                    <td><?php echo $row['idDoc']; ?></td>
                    <td><?php echo $row['idAsig']; ?></td>
                    <td><?php echo $row['idEsp']; ?></td>
                    <td><?php echo $row['diaAsigHorario']; ?></td>
                    <td><?php echo $row['horaAsigHorario']; ?></td>
                    <td><?php echo $row['fechaRegistro']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editDocAsigHorario(<?php echo $row['idDocAsig']; ?>, '<?php echo $row['idDoc']; ?>', '<?php echo $row['idAsig']; ?>', '<?php echo $row['idEsp']; ?>', '<?php echo $row['diaAsigHorario']; ?>', '<?php echo $row['horaAsigHorario']; ?>', '<?php echo $row['fechaRegistro']; ?>')">Editar</a>
                        <a href="docasighorario.php?delete=<?php echo $row['idDocAsig']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta asignación de horario?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="docasighorario.php?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="docasighorario.php?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="docasighorario.php?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function editDocAsigHorario(idDocAsig, idDoc, idAsig, idEsp, diaAsigHorario, horaAsigHorario, fechaRegistro) {
            document.getElementById('idDocAsig').value = idDocAsig;
            document.getElementById('idDoc').value = idDoc;
            document.getElementById('idAsig').value = idAsig;
            document.getElementById('idEsp').value = idEsp;
            document.getElementById('diaAsigHorario').value = diaAsigHorario;
            document.getElementById('horaAsigHorario').value = horaAsigHorario;
            document.getElementById('fechaRegistro').value = fechaRegistro;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
