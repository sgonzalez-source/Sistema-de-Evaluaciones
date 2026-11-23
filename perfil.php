<?php
session_start();

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
$titulo_miga = "Perfil de usuario";

date_default_timezone_set('America/Costa_Rica');
$anio_escolar = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=57">
    <link rel="stylesheet" href="perfil.css?v=2">
</head>
<body>

<div class="contenedor-general">

    <!-- MENÚ LATERAL AZUL -->
    <aside class="menu" id="sidebar-menu">
        <script>if(localStorage.getItem('menu_colapsado')==='1')document.getElementById('sidebar-menu').classList.add('colapsado');</script>

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

        <nav class="opciones">
            <a href="principal.php" title="Home">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span class="texto-menu">Home</span>
            </a>

            <a href="docentes.php" title="Docentes">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="texto-menu">Docentes</span>
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

            <a href="evaluar.php" title="Evaluar">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span class="texto-menu">Evaluar</span>
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
            <a href="perfil.php" class="usuario enlace-perfil-menu" title="Ver perfil de usuario">
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

    <!-- ÁREA DERECHA: INFORMACIÓN PERSONAL -->
    <main class="contenido contenido-perfil">

        <?php include 'breadcrumbs.php'; ?>

        <header class="banner-seccion">
            <div class="banner-izq">
                <h1 class="titulo-seccion-bold">Información Personal</h1>
                <p class="subtitulo-banner">Consulta y actualiza tus datos personales, fotografía de perfil y credenciales de acceso.</p>
            </div>

            <div class="banner-der">
                <div class="pildora-info">
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
                <form class="form-datos-personales" onsubmit="guardarCambiosPerfil(event)">
                    <div class="campo-perfil">
                        <label for="perfil-nombre">Nombre</label>
                        <input type="text" id="perfil-nombre" value="<?php echo htmlspecialchars($nombre); ?>" readonly required>
                    </div>

                    <div class="campo-perfil">
                        <label for="perfil-cedula">Número de cédula</label>
                        <input type="text" id="perfil-cedula" value="1-0945-0612" readonly required>
                    </div>

                    <div class="campo-perfil">
                        <label for="perfil-correo">Correo electrónico</label>
                        <input type="email" id="perfil-correo" value="jmena@cedesdonbosco.ed.cr" readonly required>
                    </div>

                    <div class="campo-perfil">
                        <label for="perfil-departamento">Departamento</label>
                        <input type="text" id="perfil-departamento" value="Coordinación Académica" readonly required>
                    </div>

                    <div class="acciones-perfil" id="acciones-modo-lectura">
                        <button type="button" class="btn-editar-info" onclick="activarEdicionPerfil()">Editar información</button>
                        <button type="button" class="btn-cambiar-clave" onclick="abrirModal('modal-cambiar-clave')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>Cambiar contraseña</span>
                        </button>
                    </div>

                    <div class="acciones-perfil" id="acciones-modo-edicion" style="display: none;">
                        <button type="button" class="btn-cancelar-perfil" onclick="cancelarEdicionPerfil()">Cancelar</button>
                        <button type="submit" class="btn-guardar-perfil-verde">Guardar cambios</button>
                    </div>
                </form>

                <div class="columna-avatar-perfil">
                    <div class="circulo-avatar-grande" id="contenedor-avatar-grande">
                        <img id="img-avatar-perfil" alt="Foto de perfil" style="display: none;">
                        <svg id="svg-avatar-defecto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>

                    <div class="pildora-rol-perfil" id="perfil-rol-badge">Coordinador Académico</div>

                    <input type="file" id="input-foto-perfil" accept="image/*" style="display: none;" onchange="procesarNuevaFoto(event)">

                    <div class="grupo-botones-foto">
                        <button type="button" class="btn-cambiar-foto" onclick="document.getElementById('input-foto-perfil').click()">
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

    </main>
</div>

<!-- MODAL: CONFIRMAR ELIMINAR FOTO DE PERFIL -->
<div class="fondo-modal" id="modal-confirmar-eliminar-foto" onclick="cerrarSiFuera(event, 'modal-confirmar-eliminar-foto')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-confirmar-eliminar-foto')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <button type="button" class="btn-modal-confirmar" style="border: none; cursor: pointer;" onclick="confirmarEliminarFotoPerfil()">Sí, eliminar</button>
        </div>
    </div>
</div>

<!-- MODAL: CAMBIAR CONTRASEÑA -->
<div class="fondo-modal" id="modal-cambiar-clave" onclick="cerrarSiFuera(event, 'modal-cambiar-clave')">
    <div class="caja-modal caja-modal-clave">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-cambiar-clave')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <h2>Cambiar contraseña</h2>
        <p class="subtexto-clave">Ingresa tu contraseña actual y define una nueva clave segura para tu cuenta institucional.</p>

        <form onsubmit="guardarNuevaClave(event)" class="form-clave-modal">
            <div class="grupo-clave">
                <label for="clave-actual">Contraseña actual</label>
                <input type="password" id="clave-actual" placeholder="••••••••" required>
            </div>

            <div class="grupo-clave">
                <label for="clave-nueva">Nueva contraseña</label>
                <input type="password" id="clave-nueva" minlength="6" placeholder="Mínimo 6 caracteres" required>
            </div>

            <div class="grupo-clave">
                <label for="clave-confirmar">Confirmar nueva contraseña</label>
                <input type="password" id="clave-confirmar" minlength="6" placeholder="Repite la nueva contraseña" required>
            </div>

            <p class="mensaje-error-clave" id="error-clave-texto" style="display: none;"></p>

            <div class="botones-modal">
                <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-cambiar-clave')">Cancelar</button>
                <button type="submit" class="btn-modal-guardar-verde">Actualizar contraseña</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: NOTIFICACIÓN DE GUARDADO EXITOSO -->
<div class="fondo-modal" id="modal-perfil-exito" onclick="cerrarSiFuera(event, 'modal-perfil-exito')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-perfil-exito')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-modal-verde">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h2 id="titulo-exito-perfil">Información actualizada</h2>
        <p id="mensaje-exito-perfil">Tus datos personales se guardaron correctamente.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarModal('modal-perfil-exito')">Aceptar</button>
        </div>
    </div>
</div>

<!-- MODAL: CERRAR SESIÓN -->
<div class="fondo-modal" id="modal-salir" onclick="cerrarSiFuera(event, 'modal-salir')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-salir')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    const perfilPorDefecto = {
        nombre: "<?php echo addslashes($nombre); ?>",
        cedula: "1-0945-0612",
        correo: "jmena@cedesdonbosco.ed.cr",
        departamento: "Coordinación Académica",
        foto: ""
    };

    let datosPerfilActual = { ...perfilPorDefecto };

    function obtenerIniciales(nombreCompleto) {
        const partes = nombreCompleto.trim().split(/\s+/);
        if (partes.length === 0 || !partes[0]) return "JM";
        if (partes.length === 1) return partes[0].substring(0, 2).toUpperCase();
        return (partes[0][0] + partes[partes.length - 1][0]).toUpperCase();
    }

    function pintarDatosPerfil() {
        document.getElementById('perfil-nombre').value = datosPerfilActual.nombre;
        document.getElementById('perfil-cedula').value = datosPerfilActual.cedula;
        document.getElementById('perfil-correo').value = datosPerfilActual.correo;
        document.getElementById('perfil-departamento').value = datosPerfilActual.departamento;

        // Sincronizar menú lateral izquierdo (nombre + foto o iniciales)
        document.getElementById('menu-nombre-usuario').textContent = datosPerfilActual.nombre;
        const elAvatarMenu = document.getElementById('menu-avatar-iniciales');
        if (datosPerfilActual.foto && datosPerfilActual.foto.startsWith('data:image')) {
            elAvatarMenu.innerHTML = `<img src="${datosPerfilActual.foto}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;">`;
        } else {
            elAvatarMenu.textContent = obtenerIniciales(datosPerfilActual.nombre);
        }

        // Foto de perfil y visibilidad del botón Eliminar
        const img = document.getElementById('img-avatar-perfil');
        const svg = document.getElementById('svg-avatar-defecto');
        const btnEliminar = document.getElementById('btn-eliminar-foto');

        if (datosPerfilActual.foto && datosPerfilActual.foto.startsWith('data:image')) {
            img.src = datosPerfilActual.foto;
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

    function activarEdicionPerfil() {
        const inputs = document.querySelectorAll('.form-datos-personales input');
        inputs.forEach(inp => {
            inp.removeAttribute('readonly');
            inp.classList.add('editable-activo');
        });
        document.getElementById('perfil-nombre').focus();
        document.getElementById('acciones-modo-lectura').style.display = 'none';
        document.getElementById('acciones-modo-edicion').style.display = 'flex';
    }

    function cancelarEdicionPerfil() {
        const inputs = document.querySelectorAll('.form-datos-personales input');
        inputs.forEach(inp => {
            inp.setAttribute('readonly', 'readonly');
            inp.classList.remove('editable-activo');
        });
        pintarDatosPerfil();
        document.getElementById('acciones-modo-edicion').style.display = 'none';
        document.getElementById('acciones-modo-lectura').style.display = 'flex';
    }

    function guardarCambiosPerfil(e) {
        e.preventDefault();

        datosPerfilActual.nombre = document.getElementById('perfil-nombre').value.trim();
        datosPerfilActual.cedula = document.getElementById('perfil-cedula').value.trim();
        datosPerfilActual.correo = document.getElementById('perfil-correo').value.trim();
        datosPerfilActual.departamento = document.getElementById('perfil-departamento').value.trim();

        localStorage.setItem('perfil_coordinador_cedes', JSON.stringify(datosPerfilActual));

        cancelarEdicionPerfil();
        document.getElementById('titulo-exito-perfil').textContent = "Información actualizada";
        document.getElementById('mensaje-exito-perfil').textContent = "Tus datos personales se guardaron correctamente.";
        abrirModal('modal-perfil-exito');
    }

    function procesarNuevaFoto(e) {
        const archivo = e.target.files[0];
        if (!archivo) return;

        const lector = new FileReader();
        lector.onload = function(ev) {
            datosPerfilActual.foto = ev.target.result;
            localStorage.setItem('perfil_coordinador_cedes', JSON.stringify(datosPerfilActual));
            pintarDatosPerfil();

            document.getElementById('titulo-exito-perfil').textContent = "Foto actualizada";
            document.getElementById('mensaje-exito-perfil').textContent = "Tu fotografía de perfil se actualizó correctamente.";
            abrirModal('modal-perfil-exito');
        };
        lector.readAsDataURL(archivo);
        e.target.value = "";
    }

    function confirmarEliminarFotoPerfil() {
        cerrarModal('modal-confirmar-eliminar-foto');
        datosPerfilActual.foto = "";
        localStorage.setItem('perfil_coordinador_cedes', JSON.stringify(datosPerfilActual));
        pintarDatosPerfil();

        document.getElementById('titulo-exito-perfil').textContent = "Foto eliminada";
        document.getElementById('mensaje-exito-perfil').textContent = "Se restauró el ícono predeterminado de tu perfil.";
        abrirModal('modal-perfil-exito');
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
        document.getElementById('titulo-exito-perfil').textContent = "Contraseña actualizada";
        document.getElementById('mensaje-exito-perfil').textContent = "Tu contraseña de acceso fue modificada con éxito.";
        abrirModal('modal-perfil-exito');
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
            datosPerfilActual = { ...perfilPorDefecto, ...JSON.parse(guardado) };
        }
        pintarDatosPerfil();
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-confirmar-eliminar-foto');
            cerrarModal('modal-cambiar-clave');
            cerrarModal('modal-perfil-exito');
        }
    });
</script>

</body>
</html>