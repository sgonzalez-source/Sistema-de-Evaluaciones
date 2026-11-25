<?php
session_start();

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador Académico";
$cedula_sesion = $_SESSION["cedula"] ?? "1-0945-0612";
$titulo_miga = "Perfil de usuario";

date_default_timezone_set('America/Costa_Rica');
$anio_escolar = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Personal - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="perfil.css?v=5">
</head>
<body>

<div class="contenedor-general">

    <!-- SIDEBAR COORDINADOR -->
    <aside class="menu" id="sidebar-menu">
        <script>if(localStorage.getItem('menu_colapsado')==='1')document.getElementById('sidebar-menu').classList.add('colapsado');</script>
        <script>if(window.innerWidth<=768&&localStorage.getItem('menu_colapsado')===null)document.getElementById('sidebar-menu').classList.add('colapsado');</script>

        <div class="cabecera-menu">
            <div class="logo">
                <img src="logo_white.png" alt="CEDES Don Bosco" class="logo-blanco">
            </div>

            <button type="button" class="btn-hamburguesa-menu" onclick="alternarMenu()" title="Expandir / Contraer menú">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>

        <nav class="opciones">
            <a href="principal.php" title="Inicio">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span class="texto-menu">Inicio</span>
            </a>

            <a href="docentes.php" title="Gestión de Docentes">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="texto-menu">Gestión de Docentes</span>
            </a>

            <a href="plantilla_evaluacion.php" title="Plantillas de evaluación">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span class="texto-menu">Plantillas de evaluación</span>
            </a>

            <a href="evaluar.php" title="Evaluación">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <span class="texto-menu">Evaluación</span>
            </a>

            <a href="estadisticas.php" title="Estadística">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span class="texto-menu">Estadística</span>
            </a>
        </nav>

        <div class="pie-menu">
            <a href="perfil.php" class="usuario enlace-perfil-menu activo-perfil" title="Ver información personal">
                <div class="avatar-iniciales" id="menu-avatar-iniciales">JM</div>
                <div class="info-usuario">
                    <strong id="menu-nombre-usuario"><?php echo htmlspecialchars($nombre); ?></strong>
                    <p id="menu-rol-usuario"><?php echo htmlspecialchars($rol); ?></p>
                </div>
            </a>

            <button type="button" class="salir" onclick="abrirModal('modal-salir')" title="Cerrar Sesión">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span class="texto-menu">Cerrar Sesión</span>
            </button>
        </div>
    </aside>

    <!-- ÁREA DERECHA: INFORMACIÓN PERSONAL DEL COORDINADOR -->
    <main class="contenido contenido-perfil">
        <div class="perfil-wrapper">

            <?php if (file_exists('breadcrumbs.php')) include 'breadcrumbs.php'; ?>

            <header class="banner-perfil-superior">
                <div class="banner-perfil-izq">
                    <h1>Información Personal</h1>
                    <p>Consulta y actualiza tus datos personales, fotografía de perfil y credenciales de acceso.</p>
                </div>

                <div class="banner-perfil-der">
                    <div class="pill-anio-escolar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>Año escolar <strong><?php echo $anio_escolar; ?></strong></span>
                    </div>
                </div>
            </header>

            <section class="tarjeta-perfil-usuario">
                <div class="barra-titulo-datos">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Datos personales</span>
                </div>

                <div class="cuerpo-perfil-grid">
                    <form class="form-datos-usuario" onsubmit="event.preventDefault();">
                        <div class="campo-perfil">
                            <label for="coord-nombre">Nombre</label>
                            <input type="text" id="coord-nombre" value="<?php echo htmlspecialchars($nombre); ?>" readonly>
                        </div>

                        <div class="campo-perfil">
                            <label for="coord-cedula">Número de cédula</label>
                            <input type="text" id="coord-cedula" value="<?php echo htmlspecialchars($cedula_sesion); ?>" readonly>
                        </div>

                        <div class="campo-perfil">
                            <label for="coord-correo">Correo electrónico</label>
                            <input type="email" id="coord-correo" value="jmena@cedesdonbosco.ed.cr" readonly>
                        </div>

                        <div class="campo-perfil">
                            <label for="coord-departamento">Departamento</label>
                            <input type="text" id="coord-departamento" value="Coordinación Académica" readonly>
                        </div>

                        <div class="acciones-perfil">
                            <button type="button" class="btn-cambiar-clave" onclick="abrirModal('modal-cambiar-clave')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span>Cambiar contraseña</span>
                            </button>
                        </div>
                    </form>

                    <div class="columna-avatar-usuario">
                        <div class="circulo-avatar-grande" id="contenedor-avatar-grande">
                            <img id="img-avatar-coord" alt="Foto de perfil" style="display: none;">
                            <svg id="svg-avatar-defecto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>

                        <div class="pildora-rol-usuario" id="coord-rol-badge"><?php echo htmlspecialchars($rol); ?></div>

                        <input type="file" id="input-foto-coord" accept="image/*" style="display: none;" onchange="procesarNuevaFotoCoord(event)">

                        <div class="grupo-botones-foto">
                            <button type="button" class="btn-cambiar-foto" onclick="document.getElementById('input-foto-coord').click()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                                <span>Cambiar foto</span>
                            </button>

                            <button type="button" class="btn-eliminar-foto" id="btn-eliminar-foto" onclick="abrirModal('modal-confirmar-eliminar-foto')" style="display: none;" title="Quitar fotografía actual">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                <span>Eliminar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>
</div>

<!-- MODAL: CONFIRMAR ELIMINAR FOTO DE PERFIL -->
<div class="fondo-modal" id="modal-confirmar-eliminar-foto" onclick="cerrarSiFuera(event, 'modal-confirmar-eliminar-foto')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-confirmar-eliminar-foto')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-modal-rojo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>

        <h2>¿Deseas eliminar tu foto de perfil?</h2>
        <p>Se quitará tu fotografía actual y se restaurará el ícono predeterminado en tu perfil.</p>

        <div class="botones-modal">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-confirmar-eliminar-foto')">Cancelar</button>
            <button type="button" class="btn-modal-confirmar" style="border: none; cursor: pointer;" onclick="confirmarEliminarFotoCoord()">Sí, eliminar</button>
        </div>
    </div>
</div>

<!-- MODAL: CAMBIAR CONTRASEÑA -->
<div class="fondo-modal" id="modal-cambiar-clave" onclick="cerrarSiFuera(event, 'modal-cambiar-clave')">
    <div class="caja-modal caja-modal-clave">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-cambiar-clave')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="cabecera-modal-clave">
            <span class="icono-llave-clave">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </span>
            <div>
                <h2>Cambiar contraseña</h2>
                <p class="subtexto-clave">Ingresa tu contraseña actual y define una nueva clave segura para tu cuenta institucional.</p>
            </div>
        </div>

        <form onsubmit="guardarNuevaClave(event)" class="form-clave-modal">
            <div class="grupo-clave">
                <label for="clave-actual">Contraseña actual</label>
                <div class="campo-clave-wrapper">
                    <input type="password" id="clave-actual" placeholder="••••••••" required>
                    <button type="button" class="btn-ojito-clave" onclick="alternarVisibilidadClave('clave-actual', this)" title="Mostrar contraseña" aria-label="Mostrar contraseña">
                        <svg class="icono-ojo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grupo-clave">
                <label for="clave-nueva">Nueva contraseña</label>
                <div class="campo-clave-wrapper">
                    <input type="password" id="clave-nueva" minlength="6" placeholder="Mínimo 6 caracteres" required>
                    <button type="button" class="btn-ojito-clave" onclick="alternarVisibilidadClave('clave-nueva', this)" title="Mostrar contraseña" aria-label="Mostrar contraseña">
                        <svg class="icono-ojo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grupo-clave">
                <label for="clave-confirmar">Confirmar nueva contraseña</label>
                <div class="campo-clave-wrapper">
                    <input type="password" id="clave-confirmar" minlength="6" placeholder="Repite la nueva contraseña" required>
                    <button type="button" class="btn-ojito-clave" onclick="alternarVisibilidadClave('clave-confirmar', this)" title="Mostrar contraseña" aria-label="Mostrar contraseña">
                        <svg class="icono-ojo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <p class="mensaje-error-clave" id="error-clave-texto" style="display: none;"></p>

            <div class="botones-modal botones-clave-alineados">
                <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-cambiar-clave')">Cancelar</button>
                <button type="submit" class="btn-modal-guardar-verde">Actualizar contraseña</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ÉXITO -->
<div class="fondo-modal" id="modal-docente-exito" onclick="cerrarSiFuera(event, 'modal-docente-exito')">
    <div class="caja-modal" style="max-width: 400px; padding: 26px 24px; text-align: center;">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-docente-exito')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-modal-verde" style="width: 54px; height: 54px; border-radius: 50%; margin: 0 auto 14px auto; background-color: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 26px; height: 26px; display: block;">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h2 id="titulo-exito-docente" style="font-size: 20px; margin-bottom: 6px;">Foto actualizada</h2>
        <p id="mensaje-exito-docente" style="font-size: 13.5px; color: #64748b; margin-bottom: 18px;">La fotografía de perfil se actualizó correctamente.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarModal('modal-docente-exito')">Aceptar</button>
        </div>
    </div>
</div>

<!-- MODAL: CERRAR SESIÓN -->
<div class="fondo-modal" id="modal-salir" onclick="cerrarSiFuera(event, 'modal-salir')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-salir')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-modal-rojo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </div>

        <h2>¿Desea cerrar sesión?</h2>
        <p>Estás a punto de salir del sistema de evaluaciones. Tendrás que volver a ingresar tus credenciales para acceder.</p>

        <div class="botones-modal">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-salir')">Cancelar</button>
            <a href="perfil.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const perfilCoordDefecto = {
        nombre: "<?php echo addslashes($nombre); ?>",
        cedula: "<?php echo addslashes($cedula_sesion); ?>",
        correo: "jmena@cedesdonbosco.ed.cr",
        departamento: "Coordinación Académica",
        foto: ""
    };

    let datosCoordActual = { ...perfilCoordDefecto };

    function obtenerIniciales(nombreCompleto) {
        const partes = nombreCompleto.trim().split(/\s+/);
        if (!partes.length || !partes[0]) return "JM";
        if (partes.length === 1) return partes[0].substring(0, 2).toUpperCase();
        return (partes[0][0] + partes[partes.length - 1][0]).toUpperCase();
    }

    function pintarDatosCoord() {
        document.getElementById('coord-nombre').value = datosCoordActual.nombre;
        document.getElementById('coord-cedula').value = datosCoordActual.cedula;
        document.getElementById('coord-correo').value = datosCoordActual.correo;
        document.getElementById('coord-departamento').value = datosCoordActual.departamento;

        document.getElementById('menu-nombre-usuario').textContent = datosCoordActual.nombre;
        const elAvatarMenu = document.getElementById('menu-avatar-iniciales');
        if (datosCoordActual.foto && datosCoordActual.foto.startsWith('data:image')) {
            elAvatarMenu.innerHTML = `<img src="${datosCoordActual.foto}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;">`;
        } else {
            elAvatarMenu.textContent = obtenerIniciales(datosCoordActual.nombre);
        }

        const img = document.getElementById('img-avatar-coord');
        const svg = document.getElementById('svg-avatar-defecto');
        const btnEliminar = document.getElementById('btn-eliminar-foto');

        if (datosCoordActual.foto && datosCoordActual.foto.startsWith('data:image')) {
            img.src = datosCoordActual.foto;
            img.style.display = 'block';
            svg.style.display = 'none';
            btnEliminar.style.display = 'inline-flex';
        } else {
            img.src = '';
            img.style.display = 'none';
            svg.style.display = 'block';
            btnEliminar.style.display = 'none';
        }
    }

    function procesarNuevaFotoCoord(e) {
        const archivo = e.target.files[0];
        if (!archivo) return;

        const lector = new FileReader();
        lector.onload = function(ev) {
            datosCoordActual.foto = ev.target.result;
            localStorage.setItem('perfil_coordinador_cedes', JSON.stringify(datosCoordActual));
            pintarDatosCoord();

            document.getElementById('titulo-exito-docente').textContent = "Foto actualizada";
            document.getElementById('mensaje-exito-docente').textContent = "La fotografía de perfil se actualizó correctamente.";
            abrirModal('modal-docente-exito');
        };
        lector.readAsDataURL(archivo);
        e.target.value = "";
    }

    function confirmarEliminarFotoCoord() {
        cerrarModal('modal-confirmar-eliminar-foto');
        datosCoordActual.foto = "";
        localStorage.setItem('perfil_coordinador_cedes', JSON.stringify(datosCoordActual));
        pintarDatosCoord();

        document.getElementById('titulo-exito-docente').textContent = "Foto eliminada";
        document.getElementById('mensaje-exito-docente').textContent = "Se restauró el ícono predeterminado.";
        abrirModal('modal-docente-exito');
    }

    function alternarVisibilidadClave(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const mostrar = input.type === 'password';
        input.type = mostrar ? 'text' : 'password';
        btn.classList.toggle('activo', mostrar);
        btn.title = mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña';
        btn.setAttribute('aria-label', btn.title);

        btn.innerHTML = mostrar
            ? `<svg class="icono-ojo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
               </svg>`
            : `<svg class="icono-ojo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
               </svg>`;
    }

    function guardarNuevaClave(e) {
        e.preventDefault();
        const nueva = document.getElementById('clave-nueva').value;
        const confirmar = document.getElementById('clave-confirmar').value;
        const errorBox = document.getElementById('error-clave-texto');

        if (nueva !== confirmar) {
            errorBox.textContent = "Las contraseñas nuevas no coinciden. Verifica e intenta de nuevo.";
            errorBox.style.display = 'block';
            return;
        }

        errorBox.style.display = 'none';
        document.getElementById('clave-actual').value = "";
        document.getElementById('clave-nueva').value = "";
        document.getElementById('clave-confirmar').value = "";

        cerrarModal('modal-cambiar-clave');
        document.getElementById('titulo-exito-docente').textContent = "Contraseña actualizada";
        document.getElementById('mensaje-exito-docente').textContent = "Tu contraseña de acceso fue modificada con éxito.";
        abrirModal('modal-docente-exito');
    }

    function abrirModal(id) {
        document.getElementById(id).classList.add('visible');
    }

    function cerrarModal(id) {
        document.getElementById(id).classList.remove('visible');
    }

    function cerrarSiFuera(e, id) {
        if (e.target.id === id) cerrarModal(id);
    }

    function alternarMenu() {
        const menu = document.getElementById('sidebar-menu');
        menu.classList.add('animar');
        menu.classList.toggle('colapsado');
        localStorage.setItem('menu_colapsado', menu.classList.contains('colapsado') ? '1' : '0');
    }

    window.onload = function() {
        const guardado = localStorage.getItem('perfil_coordinador_cedes');
        if (guardado) {
            datosCoordActual = { ...perfilCoordDefecto, ...JSON.parse(guardado) };
        }
        pintarDatosCoord();
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-confirmar-eliminar-foto');
            cerrarModal('modal-cambiar-clave');
            cerrarModal('modal-docente-exito');
        }
    });
</script>

</body>
</html>