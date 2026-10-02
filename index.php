<?php
// index.php - Formulario de registro de aspirantes
$tituloPagina = 'Registro de Aspirantes - Sistema de Admisión de la UTP';
include 'includes/header.php';
?>

    <main class="container py-4">
        <section class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h1 class="h3 mb-3">Formulario de Registro de Aspirantes</h1>

                <!-- enctype="multipart/form-data" es obligatorio para que la foto viaje al servidor -->
                <form action="procesar.php" method="post" enctype="multipart/form-data" class="card shadow-sm">
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">Nombre (Requerido):</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   placeholder="Ej.: María José" maxlength="50" required>
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label fw-semibold">Apellido (Requerido):</label>
                            <input type="text" class="form-control" id="apellido" name="apellido"
                                   placeholder="Ej.: González Pérez" maxlength="50" required>
                        </div>

                        <div class="mb-3">
                            <label for="identificacion" class="form-label fw-semibold">Identificación (Requerido):</label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion"
                                   placeholder="Cédula o pasaporte. Ej.: 8-123-4567" maxlength="20" required>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de Nacimiento (Requerido):</label>
                            <!-- max impide elegir una fecha futura -->
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                   max="<?php echo date('Y-m-d'); ?>" required>
                            <div class="form-text">El aspirante debe tener entre 18 y 70 años.</div>
                        </div>

                        <fieldset class="mb-4">
                            <legend class="form-label fw-semibold fs-6">Sexo (Requerido):</legend>
                            <!-- btn-check muestra los radios como botones -->
                            <div class="row g-2">
                                <div class="col">
                                    <input type="radio" class="btn-check" name="sexo" id="sexo_hombre" value="Hombre" required>
                                    <label class="btn btn-outline-secondary w-100" for="sexo_hombre">Hombre</label>
                                </div>
                                <div class="col">
                                    <input type="radio" class="btn-check" name="sexo" id="sexo_mujer" value="Mujer" required>
                                    <label class="btn btn-outline-secondary w-100" for="sexo_mujer">Mujer</label>
                                </div>
                            </div>
                        </fieldset>

                        <div class="mb-4">
                            <label for="foto" class="form-label fw-semibold">Fotografía del Aspirante (png, jpg, jpeg, gif, webp):</label>
                            <!-- accept filtra los archivos en el navegador; la validación real se hace en procesar.php -->
                            <input type="file" class="form-control" id="foto" name="foto"
                                   accept=".jpg,.jpeg,.png,.gif,.webp" required>
                            <div class="form-text">Tamaño máximo: 2 MB.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrar Aspirante</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>
