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

// Handle form submission for adding or updating a type of teacher
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idTipoDoc = $_POST['idTipoDoc'];
    $nombreTipoDoc = $_POST['nombreTipoDoc'];
    $descTipoDoc = $_POST['descTipoDoc'];
    $nomenTipoDoc = $_POST['nomenTipoDoc'];

    if ($idTipoDoc) {
        // Update existing type of teacher
        $sql = "UPDATE tipodocente SET nombreTipoDoc = ?, descTipoDoc = ?, nomenTipoDoc = ? WHERE idTipoDoc = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombreTipoDoc, $descTipoDoc, $nomenTipoDoc, $idTipoDoc);
        if ($stmt->execute()) {
            echo "<script>alert('Tipo de docente actualizado exitosamente.'); window.location.href = 'tipodocente.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el tipo de docente: " . $stmt->error . "'); window.location.href = 'tipodocente.php';</script>";
        }
        $stmt->close();
    } else {
        // Insert new type of teacher
        $sql = "INSERT INTO tipodocente (nombreTipoDoc, descTipoDoc, nomenTipoDoc) VALUES ('$nombreTipoDoc', '$descTipoDoc', '$nomenTipoDoc')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado === TRUE) {
            header("location: tipodocente.php");
            exit();
        } else {
            echo "Datos no ingresados";
        }
    }
}

// Handle deletion of a type of teacher
if (isset($_GET['delete'])) {
    $idTipoDoc = $_GET['delete'];
    $sql = "DELETE FROM tipodocente WHERE idTipoDoc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idTipoDoc);
    if ($stmt->execute()) {
        echo "<script>alert('Tipo de docente eliminado exitosamente.'); window.location.href = 'tipodocente.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el tipo de docente: " . $stmt->error . "'); window.location.href = 'tipodocente.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nombreTipoDoc LIKE '%$search%' OR descTipoDoc LIKE '%$search%' OR nomenTipoDoc LIKE '%$search%'" : '';

// Retrieve list of types of teachers
$sql = "SELECT * FROM tipodocente $searchQuery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipo de Docente</title>
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
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="content">
        <h1>Tipo de Docente</h1>
        <div class="form-container">
            <form action="tipodocente.php" method="post">
                <input type="hidden" name="idTipoDoc" id="idTipoDoc">
                <input type="text" name="nombreTipoDoc" id="nombreTipoDoc" placeholder="Nombre del Tipo de Docente" required>
                <input type="text" name="descTipoDoc" id="descTipoDoc" placeholder="Descripción del Tipo de Docente" required>
                <input type="text" name="nomenTipoDoc" id="nomenTipoDoc" placeholder="Nomenclatura del Tipo de Docente" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="tipodocente.php" method="get">
                <input type="text" name="search" placeholder="Buscar tipo de docente" value="<?php echo htmlspecialchars($search); ?>">
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
                    <td><?php echo $row['idTipoDoc']; ?></td>
                    <td><?php echo $row['nombreTipoDoc']; ?></td>
                    <td><?php echo $row['descTipoDoc']; ?></td>
                    <td><?php echo $row['nomenTipoDoc']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editTipoDocente(<?php echo $row['idTipoDoc']; ?>, '<?php echo $row['nombreTipoDoc']; ?>', '<?php echo $row['descTipoDoc']; ?>', '<?php echo $row['nomenTipoDoc']; ?>')">Editar</a>
                        <a href="tipodocente.php?delete=<?php echo $row['idTipoDoc']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este tipo de docente?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function editTipoDocente(idTipoDoc, nombreTipoDoc, descTipoDoc, nomenTipoDoc) {
            document.getElementById('idTipoDoc').value = idTipoDoc;
            document.getElementById('nombreTipoDoc').value = nombreTipoDoc;
            document.getElementById('descTipoDoc').value = descTipoDoc;
            document.getElementById('nomenTipoDoc').value = nomenTipoDoc;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
