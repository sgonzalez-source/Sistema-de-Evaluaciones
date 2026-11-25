<?php
session_start();

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

$nombre = $_SESSION["usuario"] ?? "Lucía Rodríguez";
$rol = $_SESSION["rol"] ?? "Docente";
$cedula_sesion = $_SESSION["cedula"] ?? "100564782";
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
    <link rel="stylesheet" href="inicio_docente.css?v=5">
    <link rel="stylesheet" href="perfil_docente.css?v=3">
</head>
<body>

<div class="contenedor-general">

    <!-- SIDEBAR DOCENTE -->
    <aside class="menu menu-docente" id="sidebar-menu">
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
            <a href="inicio_docente.php" title="Home">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span class="texto-menu">Home</span>
            </a>

            <a href="planeamiento_docente.php" title="Archivos">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span class="texto-menu">Archivos</span>
            </a>

            <a href="mis_evaluaciones.php" title="Mis evaluaciones">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span class="texto-menu">Mis evaluaciones</span>
            </a>
        </nav>

        <div class="pie-menu">
            <a href="perfil_docente.php" class="usuario enlace-perfil-menu activo-perfil" title="Ver información personal">
                <div class="avatar-iniciales" id="menu-avatar-iniciales">LR</div>
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

    <!-- ÁREA DERECHA: INFORMACIÓN PERSONAL DE LA DOCENTE -->
    <main class="contenido contenido-perfil-docente">
        <div class="perfil-docente-wrapper">

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

            <section class="tarjeta-perfil-docente">
                <div class="barra-titulo-datos">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Datos personales</span>
                </div>

                <div class="cuerpo-perfil-grid">
                    <form class="form-datos-docente" onsubmit="guardarCambiosDocente(event)">
                        <div class="campo-perfil">
                            <label for="docente-nombre">Nombre</label>
                            <input type="text" id="docente-nombre" value="<?php echo htmlspecialchars($nombre); ?>" readonly required>
                        </div>

                        <div class="campo-perfil">
                            <label for="docente-cedula">Número de cédula</label>
                            <input type="text" id="docente-cedula" value="<?php echo htmlspecialchars($cedula_sesion); ?>" readonly required>
                        </div>

                        <div class="campo-perfil">
                            <label for="docente-correo">Correo electrónico</label>
                            <input type="email" id="docente-correo" value="lrodriguez@cedesdonbosco.ed.cr" readonly required>
                        </div>

                        <div class="campo-perfil">
                            <label for="docente-materia">Materia asignada</label>
                            <select id="docente-materia" disabled>
                                <option value="Español">Español</option>
                                <option value="Química">Química</option>
                                <option value="Matemáticas">Matemáticas</option>
                                <option value="Música">Música</option>
                                <option value="Ciencias">Ciencias</option>
                                <option value="Informática">Informática</option>
                                <option value="Estudios Sociales">Estudios Sociales</option>
                                <option value="Inglés">Inglés</option>
                                <option value="Cívica">Cívica</option>
                                <option value="Educación Física">Educación Física</option>
                            </select>
                        </div>

                        <div class="acciones-perfil" id="acciones-modo-lectura">
                            <button type="button" class="btn-editar-info" onclick="activarEdicionDocente()">Editar información</button>
                            <button type="button" class="btn-cambiar-clave" onclick="abrirModal('modal-cambiar-clave')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span>Cambiar contraseña</span>
                            </button>
                        </div>

                        <div class="acciones-perfil" id="acciones-modo-edicion" style="display: none;">
                            <button type="button" class="btn-cancelar-perfil" onclick="cancelarEdicionDocente()">Cancelar</button>
                            <button type="submit" class="btn-guardar-perfil-verde">Guardar cambios</button>
                        </div>
                    </form>

                    <div class="columna-avatar-docente">
                        <div class="circulo-avatar-grande" id="contenedor-avatar-grande">
                            <img id="img-avatar-docente" alt="Foto de perfil" style="display: none;">
                            <svg id="svg-avatar-defecto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>

                        <div class="pildora-rol-docente" id="docente-materia-badge">Docente · Español</div>

                        <input type="file" id="input-foto-docente" accept="image/*" style="display: none;" onchange="procesarNuevaFotoDocente(event)">

                        <div class="grupo-botones-foto">
                            <button type="button" class="btn-cambiar-foto" onclick="document.getElementById('input-foto-docente').click()">
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
            <button type="button" class="btn-modal-confirmar" style="border: none; cursor: pointer;" onclick="confirmarEliminarFotoDocente()">Sí, eliminar</button>
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

<!-- MODAL: ÉXITO -->
<div class="fondo-modal" id="modal-docente-exito" onclick="cerrarSiFuera(event, 'modal-docente-exito')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-docente-exito')" aria-label="Cerrar">
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

        <h2 id="titulo-exito-docente">Información actualizada</h2>
        <p id="mensaje-exito-docente">Los datos del docente se guardaron correctamente.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarModal('modal-docente-exito')">Aceptar</button>
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
            <a href="perfil_docente.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const perfilDocenteDefecto = {
        nombre: "<?php echo addslashes($nombre); ?>",
        cedula: "<?php echo addslashes($cedula_sesion); ?>",
        correo: "lrodriguez@cedesdonbosco.ed.cr",
        materia: "Español",
        foto: ""
    };

    let datosDocenteActual = { ...perfilDocenteDefecto };

    function obtenerIniciales(nombreCompleto) {
        const partes = nombreCompleto.trim().split(/\s+/);
        if (!partes.length || !partes[0]) return "LR";
        if (partes.length === 1) return partes[0].substring(0, 2).toUpperCase();
        return (partes[0][0] + partes[partes.length - 1][0]).toUpperCase();
    }

    function pintarDatosDocente() {
        document.getElementById('docente-nombre').value = datosDocenteActual.nombre;
        document.getElementById('docente-cedula').value = datosDocenteActual.cedula;
        document.getElementById('docente-correo').value = datosDocenteActual.correo;
        document.getElementById('docente-materia').value = datosDocenteActual.materia;
        document.getElementById('docente-materia-badge').textContent = `Docente · ${datosDocenteActual.materia}`;

        document.getElementById('menu-nombre-usuario').textContent = datosDocenteActual.nombre;
        const elAvatarMenu = document.getElementById('menu-avatar-iniciales');
        if (datosDocenteActual.foto && datosDocenteActual.foto.startsWith('data:image')) {
            elAvatarMenu.innerHTML = `<img src="${datosDocenteActual.foto}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;">`;
        } else {
            elAvatarMenu.textContent = obtenerIniciales(datosDocenteActual.nombre);
        }

        const img = document.getElementById('img-avatar-docente');
        const svg = document.getElementById('svg-avatar-defecto');
        const btnEliminar = document.getElementById('btn-eliminar-foto');

        if (datosDocenteActual.foto && datosDocenteActual.foto.startsWith('data:image')) {
            img.src = datosDocenteActual.foto;
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

    function activarEdicionDocente() {
        document.querySelectorAll('.form-datos-docente input').forEach(inp => {
            inp.removeAttribute('readonly');
            inp.classList.add('editable-activo');
        });
        const selMateria = document.getElementById('docente-materia');
        selMateria.removeAttribute('disabled');
        selMateria.classList.add('editable-activo');

        document.getElementById('docente-nombre').focus();
        document.getElementById('acciones-modo-lectura').style.display = 'none';
        document.getElementById('acciones-modo-edicion').style.display = 'flex';
    }

    function cancelarEdicionDocente() {
        document.querySelectorAll('.form-datos-docente input').forEach(inp => {
            inp.setAttribute('readonly', 'readonly');
            inp.classList.remove('editable-activo');
        });
        const selMateria = document.getElementById('docente-materia');
        selMateria.setAttribute('disabled', 'disabled');
        selMateria.classList.remove('editable-activo');

        pintarDatosDocente();
        document.getElementById('acciones-modo-edicion').style.display = 'none';
        document.getElementById('acciones-modo-lectura').style.display = 'flex';
    }

    function guardarCambiosDocente(e) {
        e.preventDefault();

        datosDocenteActual.nombre = document.getElementById('docente-nombre').value.trim();
        datosDocenteActual.cedula = document.getElementById('docente-cedula').value.trim();
        datosDocenteActual.correo = document.getElementById('docente-correo').value.trim();
        datosDocenteActual.materia = document.getElementById('docente-materia').value;

        localStorage.setItem('perfil_docente_cedes', JSON.stringify(datosDocenteActual));

        cancelarEdicionDocente();
        document.getElementById('titulo-exito-docente').textContent = "Información actualizada";
        document.getElementById('mensaje-exito-docente').textContent = "Los datos de la docente se guardaron correctamente.";
        abrirModal('modal-docente-exito');
    }

    function procesarNuevaFotoDocente(e) {
        const archivo = e.target.files[0];
        if (!archivo) return;

        const lector = new FileReader();
        lector.onload = function(ev) {
            datosDocenteActual.foto = ev.target.result;
            localStorage.setItem('perfil_docente_cedes', JSON.stringify(datosDocenteActual));
            pintarDatosDocente();

            document.getElementById('titulo-exito-docente').textContent = "Foto actualizada";
            document.getElementById('mensaje-exito-docente').textContent = "La fotografía de perfil se actualizó correctamente.";
            abrirModal('modal-docente-exito');
        };
        lector.readAsDataURL(archivo);
        e.target.value = "";
    }

    function confirmarEliminarFotoDocente() {
        cerrarModal('modal-confirmar-eliminar-foto');
        datosDocenteActual.foto = "";
        localStorage.setItem('perfil_docente_cedes', JSON.stringify(datosDocenteActual));
        pintarDatosDocente();

        document.getElementById('titulo-exito-docente').textContent = "Foto eliminada";
        document.getElementById('mensaje-exito-docente').textContent = "Se restauró el ícono predeterminado.";
        abrirModal('modal-docente-exito');
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
        const guardado = localStorage.getItem('perfil_docente_cedes');
        if (guardado) {
            datosDocenteActual = { ...perfilDocenteDefecto, ...JSON.parse(guardado) };
        }
        pintarDatosDocente();
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