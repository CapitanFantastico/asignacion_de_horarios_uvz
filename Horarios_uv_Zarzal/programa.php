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

// Handle form submission for adding or updating a programa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idDocApoyo = $_POST['idDocApoyo'];
    $codProg = $_POST['codProg'];
    $nombreProg = $_POST['nombreProg'];
    $descriProg = $_POST['descriProg'];
    $SNIES = $_POST['SNIES'];
    $jornada = $_POST['jornada'];

    if ($codProg) {
        // Update existing programa
        $sql = "UPDATE programa SET idDocApoyo = ?, nombreProg = ?, descriProg = ?, SNIES = ?, jornada = ? WHERE codProg = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssi", $idDocApoyo, $nombreProg, $descriProg, $SNIES, $jornada, $codProg);
        if ($stmt->execute()) {
            echo "<script>alert('Programa académico actualizado exitosamente.'); window.location.href = 'programa.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el programa académico: " . $stmt->error . "'); window.location.href = 'programa.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new programa
        $sql = "INSERT INTO programa (idDocApoyo, nombreProg, descriProg, SNIES, jornada) VALUES ('$idDocApoyo', '$nombreProg', '$descriProg', '$SNIES', '$jornada')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: programa.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of a programa
if (isset($_GET['delete'])) {
    $codProg = $_GET['delete'];
    $sql = "DELETE FROM programa WHERE codProg = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codProg);
    if ($stmt->execute()) {
        echo "<script>alert('Programa académico eliminado exitosamente.'); window.location.href = 'programa.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el programa académico: " . $stmt->error . "'); window.location.href = 'programa.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nombreProg LIKE '%$search%' OR descriProg LIKE '%$search%' OR SNIES LIKE '%$search%' OR jornada LIKE '%$search%'" : '';

// Pagination settings
$recordsPerPage = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $recordsPerPage;

// Retrieve total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM programa $searchQuery";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Retrieve list of programas with pagination
$sql = "SELECT * FROM programa $searchQuery LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Académico</title>
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
        <h1>Programa Académico</h1>
        <div class="form-container">
            <form action="programa.php" method="post">
                <input type="hidden" name="codProg" id="codProg">
                <input type="number" name="idDocApoyo" id="idDocApoyo" placeholder="ID del Docente de Apoyo" required>
                <input type="text" name="nombreProg" id="nombreProg" placeholder="Nombre del Programa Académico" required>
                <input type="text" name="descriProg" id="descriProg" placeholder="Descripción del Programa Académico" required>
                <input type="text" name="SNIES" id="SNIES" placeholder="SNIES" required>
                <input type="text" name="jornada" id="jornada" placeholder="Tipo de Jornada" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="programa.php" method="get">
                <input type="text" name="search" placeholder="Buscar programa académico" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>ID Docente de Apoyo</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>SNIES</th>
                    <th>Jornada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['codProg']; ?></td>
                    <td><?php echo $row['idDocApoyo']; ?></td>
                    <td><?php echo $row['nombreProg']; ?></td>
                    <td><?php echo $row['descriProg']; ?></td>
                    <td><?php echo $row['SNIES']; ?></td>
                    <td><?php echo $row['jornada']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editPrograma(<?php echo $row['codProg']; ?>, '<?php echo $row['idDocApoyo']; ?>', '<?php echo $row['nombreProg']; ?>', '<?php echo $row['descriProg']; ?>', '<?php echo $row['SNIES']; ?>', '<?php echo $row['jornada']; ?>')">Editar</a>
                        <a href="programa.php?delete=<?php echo $row['codProg']; ?>" onclick="return confirm('¿Estás seguro de eliminar este programa académico?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="programa.php?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="programa.php?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="programa.php?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function editPrograma(codProg, idDocApoyo, nombreProg, descriProg, SNIES, jornada) {
            document.getElementById('codProg').value = codProg;
            document.getElementById('idDocApoyo').value = idDocApoyo;
            document.getElementById('nombreProg').value = nombreProg;
            document.getElementById('descriProg').value = descriProg;
            document.getElementById('SNIES').value = SNIES;
            document.getElementById('jornada').value = jornada;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
