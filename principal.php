<?php
// Datos simples del usuario
$nombre = "Javier Mena";
$rol = "Coordinador";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú - CEDES Don Bosco</title>
    <link rel="stylesheet" href="menu.css">
</head>
<body>

    <aside class="menu">
        <!-- Logo -->
        <div class="logo">
            <img src="img/logo-cedes.png" alt="CEDES Don Bosco">
        </div>

        <!-- Opciones de navegación -->
        <nav class="opciones">
            <a href="#" class="activo">
                <img src="img/home.png" alt="" class="icono">
                <span>Home</span>
            </a>
            <a href="#">
                <img src="img/docentes.png" alt="" class="icono">
                <span>Docentes</span>
            </a>
            <a href="#">
                <img src="img/rubricas.png" alt="" class="icono">
                <span>Rúbricas</span>
            </a>
            <a href="#">
                <img src="img/evaluar.png" alt="" class="icono">
                <span>Evaluar</span>
            </a>
            <a href="#">
                <img src="img/estadistica.png" alt="" class="icono">
                <span>Estadística</span>
            </a>
        </nav>

        <!-- Perfil y Cerrar Sesión -->
        <div class="pie-menu">
            <div class="usuario">
                <img src="img/avatar.jpg" alt="Foto" class="avatar">
                <div>
                    <strong><?php echo $nombre; ?></strong>
                    <p><?php echo $rol; ?></p>
                </div>
            </div>

            <a href="#" class="salir">
                <img src="img/salir.png" alt="" class="icono">
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>

</body>
</html>