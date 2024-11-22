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

// Handle form submission for adding or updating an inclusion social
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codInclu = $_POST['codInclu'];
    $nombreInclu = $_POST['nombreInclu'];
    $descriInclu = $_POST['descriInclu'];
    $nomenInclu = $_POST['nomenInclu'];

    if ($codInclu) {
        // Update existing inclusion social
        $sql = "UPDATE inclusionsocial SET nombreInclu = ?, descriInclu = ?, nomenInclu = ? WHERE codInclu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombreInclu, $descriInclu, $nomenInclu, $codInclu);
        if ($stmt->execute()) {
            echo "<script>alert('Inclusión social actualizada exitosamente.'); window.location.href = 'inclusionsocial.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar la inclusión social: " . $stmt->error . "'); window.location.href = 'inclusionsocial.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new inclusion social
        $sql = "INSERT INTO inclusionsocial (nombreInclu, descriInclu, nomenInclu) VALUES ('$nombreInclu', '$descriInclu', '$nomenInclu')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: inclusionsocial.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of an inclusion social
if (isset($_GET['delete'])) {
    $codInclu = $_GET['delete'];
    $sql = "DELETE FROM inclusionsocial WHERE codInclu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codInclu);
    if ($stmt->execute()) {
        echo "<script>alert('Inclusión social eliminada exitosamente.'); window.location.href = 'inclusionsocial.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar la inclusión social: " . $stmt->error . "'); window.location.href = 'inclusionsocial.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nombreInclu LIKE '%$search%' OR descriInclu LIKE '%$search%' OR nomenInclu LIKE '%$search%'" : '';

// Pagination settings
$recordsPerPage = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $recordsPerPage;

// Retrieve total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM inclusionsocial $searchQuery";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Retrieve list of inclusions sociales with pagination
$sql = "SELECT * FROM inclusionsocial $searchQuery LIMIT $recordsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inclusión Social</title>
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
<?php include 'menu.php'; ?>
    </div>
    <div class="content">
        <h1>Inclusión Social</h1>
        <div class="form-container">
            <form action="inclusionsocial.php" method="post">
                <input type="hidden" name="codInclu" id="codInclu">
                <input type="text" name="nombreInclu" id="nombreInclu" placeholder="Nombre de Inclusión" required>
                <input type="text" name="descriInclu" id="descriInclu" placeholder="Descripción de Inclusión" required>
                <input type="text" name="nomenInclu" id="nomenInclu" placeholder="Nomenclatura de Inclusión" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="inclusionsocial.php" method="get">
                <input type="text" name="search" placeholder="Buscar inclusión social" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Nomenclatura</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['codInclu']; ?></td>
                    <td><?php echo $row['nombreInclu']; ?></td>
                    <td><?php echo $row['descriInclu']; ?></td>
                    <td><?php echo $row['nomenInclu']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editInclusionSocial(<?php echo $row['codInclu']; ?>, '<?php echo $row['nombreInclu']; ?>', '<?php echo $row['descriInclu']; ?>', '<?php echo $row['nomenInclu']; ?>')">Editar</a>
                        <a href="inclusionsocial.php?delete=<?php echo $row['codInclu']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta inclusión social?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="inclusionsocial.php?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="inclusionsocial.php?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="inclusionsocial.php?page=<?php echo $page + 1; ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function editInclusionSocial(codInclu, nombreInclu, descriInclu, nomenInclu) {
            document.getElementById('codInclu').value = codInclu;
            document.getElementById('nombreInclu').value = nombreInclu;
            document.getElementById('descriInclu').value = descriInclu;
            document.getElementById('nomenInclu').value = nomenInclu;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
