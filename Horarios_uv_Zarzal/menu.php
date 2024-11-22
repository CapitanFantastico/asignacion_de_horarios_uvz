<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú con Bootstrap</title>
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#home">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    <!-- Menú desplegable para "Docente" -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="docenteDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Docente
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="docenteDropdown">
                            <li><a class="dropdown-item" href="docente.php">Docente</a></li>
                            <li><a class="dropdown-item" href="tipodocente.php">Tipo de Docente</a></li>
                            <li><a class="dropdown-item" href="criteriodocente.php">Criterio de Docente</a></li>
                            <li><a class="dropdown-item" href="inclusionsocial.php">Inclusión Social</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="asignatura.php">ASIGNATURA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pais.php">PAIS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ver_informacion.php">INFORMACION REGISTRADA</a>
                    </li>
                </ul>
                <!-- Botón de cerrar sesión -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Enlace a Bootstrap JS y Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
