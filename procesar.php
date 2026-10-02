<?php
// procesar.php - Valida los datos del aspirante, les da formato y guarda la foto

date_default_timezone_set('America/Panama');

const CARPETA_FOTOS = __DIR__ . '/uploaded_files/';
const EDAD_MINIMA = 18;
const EDAD_MAXIMA = 70;
const PESO_MAXIMO = 2 * 1024 * 1024; // 2 MB

// Extensiones permitidas y el tipo de contenido que debe tener cada una
const TIPOS_PERMITIDOS = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
];

// Quita las etiquetas HTML y los espacios sobrantes de un campo de texto
function limpiarTexto($campo)
{
    $valor = $_POST[$campo] ?? '';
    return is_string($valor) ? trim(strip_tags($valor)) : '';
}

// Formato tipo título: "sofía DE león" -> "Sofía De León".
// mb_convert_case() respeta las tildes; si la extensión mbstring no está instalada
// se usa ucwords(strtolower()), que solo convierte bien las letras sin tilde
function formatoTitulo($texto)
{
    return function_exists('mb_convert_case')
        ? mb_convert_case($texto, MB_CASE_TITLE, 'UTF-8')
        : ucwords(strtolower($texto));
}

$listaErrores = [];
$aspirante = [];
$fotoEnPagina = '';

// Esta página solo procesa datos enviados desde index.php
$vieneDelFormulario = $_SERVER['REQUEST_METHOD'] === 'POST';

if (!$vieneDelFormulario) {
    $listaErrores[] = 'Esta página debe abrirse desde el formulario de registro.';
} elseif (empty($_POST) && empty($_FILES)) {
    // Si el envío supera post_max_size, PHP entrega $_POST y $_FILES vacíos
    $listaErrores[] = 'El envío es demasiado grande. La fotografía no puede pesar más de 2 MB.';
} else {
    // --- Nombre y apellido ---
    $nombre = limpiarTexto('nombre');
    $apellido = limpiarTexto('apellido');
    foreach (['nombre' => $nombre, 'apellido' => $apellido] as $campo => $valor) {
        if ($valor === '') {
            $listaErrores[] = "Debe escribir su $campo, el campo no puede quedar vacío.";
        } elseif (!preg_match('/^[\p{L}\s\'-]+$/u', $valor)) {
            // \p{L} acepta cualquier letra (también con tilde o ñ); la bandera u lee el texto como UTF-8
            $listaErrores[] = "El $campo solo puede tener letras y espacios, sin números ni símbolos.";
        }
    }
    $nombre = formatoTitulo($nombre);
    $apellido = formatoTitulo($apellido);

    // --- Identificación ---
    // strtoupper() la deja en mayúsculas cerradas: "pe-12-345" -> "PE-12-345"
    $identificacion = strtoupper(limpiarTexto('identificacion'));
    if ($identificacion === '') {
        $listaErrores[] = 'Debe escribir su identificación, el campo no puede quedar vacío.';
    } elseif (!preg_match('/^[A-Z0-9-]{4,20}$/', $identificacion)) {
        $listaErrores[] = 'La identificación solo puede tener letras, números y guiones (de 4 a 20 caracteres).';
    }

    // --- Fecha de nacimiento y edad ---
    $edad = null;
    $datoFecha = limpiarTexto('fecha_nacimiento');
    $fechaNacimiento = DateTime::createFromFormat('!Y-m-d', $datoFecha);
    // Se compara con el texto original para rechazar fechas imposibles como 2000-02-31
    if (!$fechaNacimiento || $fechaNacimiento->format('Y-m-d') !== $datoFecha) {
        $listaErrores[] = 'Debe indicar una fecha de nacimiento válida.';
    } elseif ($fechaNacimiento > new DateTime('today')) {
        $listaErrores[] = 'La fecha de nacimiento no puede ser una fecha futura.';
    } else {
        // diff() cuenta los años cumplidos hasta hoy
        $edad = $fechaNacimiento->diff(new DateTime('today'))->y;
        if ($edad < EDAD_MINIMA || $edad > EDAD_MAXIMA) {
            $listaErrores[] = 'El aspirante debe tener entre ' . EDAD_MINIMA . ' y ' . EDAD_MAXIMA
                . " años. Con esa fecha de nacimiento tiene $edad.";
        }
    }

    // --- Sexo ---
    $sexo = limpiarTexto('sexo');
    if (!in_array($sexo, ['Hombre', 'Mujer'], true)) {
        $listaErrores[] = 'Debe seleccionar el sexo.';
    }

    // --- Fotografía ---
    $foto = $_FILES['foto'] ?? null;
    $extension = '';
    if (!$foto || !is_int($foto['error']) || $foto['error'] === UPLOAD_ERR_NO_FILE) {
        $listaErrores[] = 'Debe adjuntar la fotografía del aspirante.';
    } elseif ($foto['error'] === UPLOAD_ERR_INI_SIZE || $foto['error'] === UPLOAD_ERR_FORM_SIZE || $foto['size'] > PESO_MAXIMO) {
        $listaErrores[] = 'La fotografía no puede pesar más de 2 MB.';
    } elseif ($foto['error'] !== UPLOAD_ERR_OK) {
        $listaErrores[] = 'Ocurrió un problema al subir la fotografía. Inténtelo de nuevo.';
    } else {
        // pathinfo() saca la extensión del nombre original
        $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        if (!isset(TIPOS_PERMITIDOS[$extension])) {
            $listaErrores[] = 'La fotografía debe ser un archivo jpg, jpeg, png, gif o webp.';
        } else {
            // La extensión se puede falsificar, por eso también se revisa el contenido real del archivo
            $tipoReal = (new finfo(FILEINFO_MIME_TYPE))->file($foto['tmp_name']);
            if ($tipoReal !== TIPOS_PERMITIDOS[$extension]) {
                $listaErrores[] = 'El archivo adjunto no es una imagen válida.';
            }
        }
    }

    // La foto se guarda solo si todos los datos pasaron las validaciones
    if (empty($listaErrores)) {
        // No se usa el nombre original del archivo: se arma uno propio con la identificación
        // y un código aleatorio, así no se pisan fotos ni se aceptan rutas extrañas
        $nombreFoto = 'aspirante_' . $identificacion . '_' . bin2hex(random_bytes(6)) . '.' . $extension;

        // move_uploaded_file() solo mueve archivos que llegaron por un formulario
        if (move_uploaded_file($foto['tmp_name'], CARPETA_FOTOS . $nombreFoto)) {
            // La carpeta está bloqueada para el navegador, así que la imagen se incrusta
            // en la página leyéndola desde PHP
            $fotoEnPagina = 'data:' . TIPOS_PERMITIDOS[$extension] . ';base64,'
                . base64_encode(file_get_contents(CARPETA_FOTOS . $nombreFoto));

            $aspirante = [
                'Nombre' => $nombre,
                'Apellido' => $apellido,
                'Identificación' => $identificacion,
                'Fecha de nacimiento' => $fechaNacimiento->format('d/m/Y'),
                'Edad' => $edad . ' años',
                'Sexo' => $sexo,
                'Fotografía guardada como' => $nombreFoto,
            ];
        } else {
            $listaErrores[] = 'No se pudo guardar la fotografía en el servidor.';
        }
    }
}

$tituloPagina = 'Resultado del Registro - Sistema de Admisión de la UTP';
include 'includes/header.php';
?>

    <main class="container py-4">
        <section class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h1 class="h3 mb-3">Resultado del Registro</h1>

                <?php if (!empty($listaErrores)): ?>
                    <!-- Si falló alguna validación se muestran todos los errores juntos -->
                    <div class="alert alert-danger" role="alert">
                        <strong>Revise los siguientes datos:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($listaErrores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <a href="index.php" class="btn btn-secondary">Volver al formulario</a>
                <?php else: ?>
                    <div class="alert alert-success" role="alert">El aspirante fue registrado correctamente.</div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <img src="<?php echo $fotoEnPagina; ?>" alt="Fotografía del aspirante"
                                     class="rounded border" style="max-width: 180px; max-height: 220px;">
                            </div>
                            <dl class="row mb-0">
                                <!-- htmlspecialchars() en toda la salida para no imprimir HTML del usuario -->
                                <?php foreach ($aspirante as $etiqueta => $valor): ?>
                                    <dt class="col-sm-5"><?php echo htmlspecialchars($etiqueta); ?>:</dt>
                                    <dd class="col-sm-7 text-break"><?php echo htmlspecialchars($valor); ?></dd>
                                <?php endforeach; ?>
                            </dl>
                        </div>
                    </div>
                    <a href="index.php" class="btn btn-primary mt-3">Registrar otro aspirante</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>
