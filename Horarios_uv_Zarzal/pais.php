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


// Handle form submission for adding or updating a country
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idPais = isset($_POST['idPais']) ? trim($_POST['idPais']) : '';
    $nombrePais = isset($_POST['nombrePais']) ? trim($_POST['nombrePais']) : '';
    $descriPais = isset($_POST['descriPais']) ? trim($_POST['descriPais']) : '';
    $nomenPais = isset($_POST['nomenPais']) ? trim($_POST['nomenPais']) : '';

    // Validación simple
    if (empty($nombrePais) || empty($descriPais) || empty($nomenPais)) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.location.href = 'pais.php';</script>";
        exit();
    }

    if (empty($idPais)) {
        // Insertar un nuevo país
        $sql = "INSERT INTO pais (nombrePais, descriPais, nomenPais) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombrePais, $descriPais, $nomenPais);
    } else {
        // Actualizar país existente
        $sql = "UPDATE pais SET nombrePais = ?, descriPais = ?, nomenPais = ? WHERE idPais = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombrePais, $descriPais, $nomenPais, $idPais);
    }
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Operación realizada exitosamente.'); window.location.href = 'pais.php';</script>";
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "'); window.location.href = 'pais.php';</script>";
    }
    $stmt->close();
}

// Handle deletion of a country
if (isset($_GET['delete'])) {
    $idPais = $_GET['delete'];
    $sql = "DELETE FROM pais WHERE idPais = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idPais);
    if ($stmt->execute()) {
        echo "<script>alert('País eliminado exitosamente.'); window.location.href = 'pais.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el país: " . $stmt->error . "'); window.location.href = 'pais.php';</script>";
    }
    $stmt->close();
}

// Handle search
$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE nombrePais LIKE '%$search%' OR descriPais LIKE '%$search%' OR nomenPais LIKE '%$search%'" : '';

// Retrieve list of countries
$sql = "SELECT * FROM pais $searchQuery";
$result = $conn->query($sql);


// lista paises select

$paises = [
    "Afganistán", "Albania", "Alemania", "Andorra", "Angola", "Antigua y Barbuda", "Arabia Saudita", 
    "Argelia", "Argentina", "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas", "Bangladesh",
    "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia", 
    "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi",
    "Bután", "Cabo Verde", "Camboya", "Camerún", "Canadá", "Catar", "Chad", "Chile", "China", 
    "Chipre", "Colombia", "Comoras", "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica",
    "Croacia", "Cuba", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador", "Emiratos Árabes Unidos",
    "Eritrea", "Eslovaquia", "Eslovenia", "España", "Estados Unidos", "Estonia", "Esuatini", "Etiopía",
    "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabón", "Gambia", "Georgia", "Ghana", "Granada",
    "Grecia", "Guatemala", "Guinea", "Guinea-Bisáu", "Guinea Ecuatorial", "Guyana", "Haití", "Honduras",
    "Hungría", "India", "Indonesia", "Irak", "Irán", "Irlanda", "Islandia", "Islas Marshall", "Islas Salomón",
    "Israel", "Italia", "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia", "Kirguistán", "Kiribati",
    "Kuwait", "Laos", "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein", "Lituania",
    "Luxemburgo", "Macedonia del Norte", "Madagascar", "Malasia", "Malaui", "Maldivas", "Malí", "Malta",
    "Marruecos", "Mauricio", "Mauritania", "México", "Micronesia", "Moldavia", "Mónaco", "Mongolia",
    "Montenegro", "Mozambique", "Namibia", "Nauru", "Nepal", "Nicaragua", "Níger", "Nigeria", "Noruega",
    "Nueva Zelanda", "Omán", "Países Bajos", "Pakistán", "Palaos", "Panamá", "Papúa Nueva Guinea",
    "Paraguay", "Perú", "Polonia", "Portugal", "Reino Unido", "República Centroafricana", "República Checa",
    "República del Congo", "República Democrática del Congo", "República Dominicana", "Ruanda", "Rumania",
    "Rusia", "Samoa", "San Cristóbal y Nieves", "San Marino", "San Vicente y las Granadinas", "Santa Lucía",
    "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona", "Singapur", "Siria",
    "Somalia", "Sri Lanka", "Sudáfrica", "Sudán", "Sudán del Sur", "Suecia", "Suiza", "Surinam", 
    "Tailandia", "Tanzania", "Tayikistán", "Timor Oriental", "Togo", "Tonga", "Trinidad y Tobago",
    "Túnez", "Turkmenistán", "Turquía", "Tuvalu", "Ucrania", "Uganda", "Uruguay", "Uzbekistán", 
    "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Yibuti", "Zambia", "Zimbabue"
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>País</title>
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
        .form-container input, .form-container select {
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
        <h1>País</h1>
        <div class="form-container">
            <form action="pais.php" method="post">
                <input type="hidden" name="idPais" id="idPais">
                <label for="nombrePais">Selecciona el País:</label>
                <select name="nombrePais" id="nombrePais" required>
                    <option value="">-- Selecciona un país --</option>
                    <?php foreach ($paises as $pais): ?>
                        <option value="<?php echo htmlspecialchars($pais); ?>"><?php echo htmlspecialchars($pais); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="descriPais" id="descriPais" placeholder="Descripción del País" required>
                <input type="text" name="nomenPais" id="nomenPais" placeholder="Nomenclatura del País" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
        <div class="form-container">
            <form action="pais.php" method="get">
                <input type="text" name="search" placeholder="Buscar país" value="<?php echo htmlspecialchars($search); ?>">
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
                    <td><?php echo $row['idPais']; ?></td>
                    <td><?php echo $row['nombrePais']; ?></td>
                    <td><?php echo $row['descriPais']; ?></td>
                    <td><?php echo $row['nomenPais']; ?></td>
                    <td class="actions">
                        <a href="javascript:void(0);" onclick="editCountry(<?php echo $row['idPais']; ?>, '<?php echo $row['nombrePais']; ?>', '<?php echo $row['descriPais']; ?>', '<?php echo $row['nomenPais']; ?>')">Editar</a>
                        <a href="pais.php?delete=<?php echo $row['idPais']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este país?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function editCountry(idPais, nombrePais, descriPais, nomenPais) {
            document.getElementById('idPais').value = idPais;
            document.getElementById('nombrePais').value = nombrePais;
            document.getElementById('descriPais').value = descriPais;
            document.getElementById('nomenPais').value = nomenPais;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
