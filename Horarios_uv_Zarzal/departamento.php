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
    $idDepto = isset($_POST['idDepto']) ? trim($_POST['idDepto']) : '';
    $nombreDepto = isset($_POST['nombreDepto']) ? trim($_POST['nombreDepto']) : '';
    $descriDepto = isset($_POST['descriDepto']) ? trim($_POST['descriDepto']) : '';
    $nomenDepto = isset($_POST['nomenDepto']) ? trim($_POST['nomenDepto']) : '';

    // Validación simple
    if (empty($nombreDepto) || empty($descriDepto) || empty($nomenDepto)) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.location.href = 'departamento.php';</script>";
        exit();
    }

    if (empty($idDepto)) {
        // Insertar un nuevo departamento
        $sql = "INSERT INTO departamento (nombreDepto, descriDepto, nomenDepto) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombreDepto, $descriDepto, $nomenDepto);
    } else {
        // Actualizar departamento existente
        $sql = "UPDATE departamento SET nombreDepto = ?, descriDepto = ?, nomenDepto = ? WHERE idDepto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombreDepto, $descriDepto, $nomenDepto, $idDepto);
    }
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Operación realizada exitosamente.'); window.location.href = 'departamento.php';</script>";
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "'); window.location.href = 'departamento.php';</script>";
    }
    $stmt->close();
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
        <a href="logout.php" class="right">cerrar sesion</a>
    </div>
    <div class="content">
        <h1>Departamento</h1>
        <div class="form-container">
            <form action="departamento.php" method="post">
                <input type="hidden" name="idDepto" id="idDepto">
                <input type="text" name="nombreDepto" id="nombreDepto" placeholder="Nombre del Departamento" required>
                <input type="text" name="descriDepto" id="descriDepto" placeholder="Descripción del Departamento" required>
                <input type="text" name="nomenDepto" id="nomenDepto" placeholder="Nomenclatura del Departamento" required>
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
