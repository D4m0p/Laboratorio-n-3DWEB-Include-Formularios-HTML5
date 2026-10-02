<?php
// router.php - Solo para el servidor integrado de PHP (php -S localhost:8000 router.php).
// Ese servidor no lee los archivos .htaccess, así que aquí se repite el bloqueo
// de la carpeta de fotos. En Apache (WAMP, XAMPP) este archivo no hace falta.

$ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (stripos(rawurldecode($ruta), '/uploaded_files') === 0) {
    http_response_code(403);
    echo 'Acceso denegado.';
    return true;
}

// false deja que el servidor atienda la petición de forma normal
return false;
