# Laboratorio 3 - Include y Formularios HTML5

Taller "Registro de Aspirantes": formulario con Bootstrap, validación en PHP y subida
segura de la fotografía, sin base de datos.
Materia: Desarrollo Web - Grupo 1S3122 - Instructora: Irina Fong
Módulos II y III - II Semestre 2026

## Contenido

- `Laboratorio #3.pptx` - Enunciado del laboratorio
- `index.php` - Formulario de registro dentro de `<main><section>`: nombre, apellido,
  identificación, fecha de nacimiento (`type="date"`), sexo y fotografía. Todos los campos
  son requeridos y usan `placeholder`; el formulario lleva `enctype="multipart/form-data"`
- `procesar.php` - Backend que valida los datos, les da formato, guarda la foto y
  muestra el resultado o la lista de errores
- `includes/header.php` - Metadatos del `<head>`, menú de navegación y migas de pan;
  con `basename($_SERVER['PHP_SELF'])` detecta la página actual y cambia las migas
- `includes/footer.php` - Pie de página con eslogan institucional, enlaces rápidos y
  el año generado con `date('Y')`
- `uploaded_files/` - Carpeta donde se guardan las fotos. El `.htaccess` impide abrirlas
  desde el navegador y el `.gitkeep` mantiene la carpeta en el repositorio
- `router.php` - Repite ese bloqueo cuando se usa el servidor integrado de PHP, que no
  lee los `.htaccess`

## Requerimientos de la rúbrica

| Requerimiento | Dónde se cumple |
| --- | --- |
| Bootstrap 5.3.8 y etiquetas semánticas | `<header>` y `<footer>` en `includes/`, `<main>` y `<section>` en cada página |
| El formulario va dentro de `<main><section>` | `index.php` |
| Menú y migas de pan modularizados con `include` | `includes/header.php` |
| Edad entre 18 y 70 años | `procesar.php`, calculada desde la fecha de nacimiento |
| Nombre y apellido en formato tipo título | `procesar.php`, función `formatoTitulo()` |
| Foto guardada de forma segura, sin base de datos | `procesar.php` y `uploaded_files/` |
| Carpeta de fotos no accesible desde el navegador | `uploaded_files/.htaccess` y `router.php` |

## Validaciones y seguridad

- `trim()` y `strip_tags()` limpian los espacios y las etiquetas HTML de cada campo.
- `htmlspecialchars()` en toda la salida, para evitar ataques XSS.
- Nombre y apellido: solo letras y espacios; se convierten a formato tipo título
  ("sofía" a "Sofía"). Se usa `mb_convert_case()` porque respeta las tildes; si la
  extensión mbstring no está instalada se usa `ucwords(strtolower())`.
- Identificación: se pasa a mayúsculas con `strtoupper()`.
- Fecha de nacimiento: debe ser una fecha real y no futura; la edad se calcula con
  `DateTime::diff()`.
- Fotografía: extensiones jpg, jpeg, png, gif y webp, máximo 2 MB. Además de la extensión
  se revisa el contenido real del archivo con `finfo`, y se guarda con un nombre
  generado por el sistema usando `move_uploaded_file()`.
- La foto solo se guarda si todos los datos son válidos. Como la carpeta está bloqueada,
  `procesar.php` la muestra leyéndola desde PHP.

## Requisitos

- PHP 8 con la extensión `fileinfo`.
- Conexión a internet, porque Bootstrap y Bootstrap Icons se cargan desde un CDN.
- Permiso de escritura sobre la carpeta `uploaded_files/`.

## Cómo ejecutarlo

Con el servidor integrado de PHP:

1. Abrir una terminal dentro de la carpeta del proyecto.
2. Levantar el servidor: `php -S localhost:8000 router.php`
3. Abrir en el navegador `http://localhost:8000/`

Con WAMP o XAMPP: copiar la carpeta dentro de `www` o `htdocs` y abrir
`http://localhost/` seguido del nombre de la carpeta. En Apache el bloqueo de las fotos
lo hace el `.htaccess`, que necesita `AllowOverride All` en la configuración.

## Autor

David Moreno - Universidad Tecnológica de Panamá
