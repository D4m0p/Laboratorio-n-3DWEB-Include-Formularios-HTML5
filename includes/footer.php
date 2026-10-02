<?php
// includes/footer.php - Pie de página común
?>
    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <!-- Eslogan e identificación institucional -->
            <p class="mb-1 fw-semibold">Portal de Gestión de Aspirantes — Universidad Tecnológica de Panamá</p>
            <p class="small text-white-50 mb-2">Facultad de Ingeniería de Sistemas Computacionales · Desarrollo Web</p>

            <!-- Enlaces rápidos y de contacto -->
            <div class="mb-2">
                <a href="index.php" class="text-white text-decoration-none mx-2 small"><i class="bi bi-house-door"></i> Inicio</a> |
                <a href="https://github.com/D4m0p" target="_blank" rel="noopener" class="text-white text-decoration-none mx-2 small"><i class="bi bi-github"></i> GitHub</a> |
                <a href="https://utp.ac.pa" target="_blank" rel="noopener" class="text-white text-decoration-none mx-2 small"><i class="bi bi-globe"></i> UTP</a>
            </div>

            <!-- Derechos de autor; date('Y') mantiene el año al día -->
            <p class="text-white-50 small mb-0">
                &copy; <?php echo date('Y'); ?> Universidad Tecnológica de Panamá. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <!-- El JavaScript de Bootstrap hace funcionar el botón del menú en pantallas pequeñas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Cierre de las etiquetas abiertas en header.php -->
</body>
</html>
