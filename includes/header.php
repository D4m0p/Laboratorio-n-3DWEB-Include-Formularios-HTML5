<?php
// includes/header.php - Cabecera común: metadatos, menú y migas de pan

// basename() deja solo el nombre del archivo que se está viendo (index.php o procesar.php)
$paginaActual = basename($_SERVER['PHP_SELF']);

// Cada página puede definir su propio título antes de hacer el include
$tituloPagina = $tituloPagina ?? 'Sistema de Admisión de la UTP';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina); ?></title>
    <meta name="description" content="Sistema de admisión de datos para aspirantes">
    <meta name="author" content="Universidad Tecnológica de Panamá / David Moreno">
    <!-- Es un sitio de práctica: se pide a los buscadores que no lo indexen -->
    <meta name="robots" content="noindex, nofollow">
    <!-- Color de la barra del navegador en el celular, igual al del menú -->
    <meta name="theme-color" content="#212529">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <header>
        <!-- Menú de navegación -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">PortalU</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                        aria-controls="menuPrincipal" aria-expanded="false" aria-label="Mostrar el menú">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <!-- La clase active marca en el menú la página en la que se está -->
                            <a class="nav-link <?php echo $paginaActual == 'index.php' ? 'active' : ''; ?>" href="index.php">Registro de Aspirante</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Migas de pan: cambian según la página -->
        <div class="bg-white border-bottom py-2">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Inicio</a></li>

                        <?php if ($paginaActual == 'procesar.php'): ?>
                            <!-- En el procesador se muestra el paso anterior con su enlace para regresar -->
                            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Registro</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Procesando Datos</li>
                        <?php else: ?>
                            <li class="breadcrumb-item active" aria-current="page">Registro de Aspirante</li>
                        <?php endif; ?>

                    </ol>
                </nav>
            </div>
        </div>
    </header>
