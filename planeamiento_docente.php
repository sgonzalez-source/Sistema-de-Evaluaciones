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
$titulo_miga = "Mis archivos";

date_default_timezone_set('America/Costa_Rica');
$anio_escolar = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Archivos - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="inicio_docente.css?v=4">
    <link rel="stylesheet" href="planeamiento_docente.css?v=1">
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

            <a href="planeamiento_docente.php" class="activo" title="Archivos">
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
            <a href="perfil_docente.php" class="usuario enlace-perfil-menu" title="Ver perfil del docente">
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

    <!-- ÁREA DERECHA -->
    <main class="contenido contenido-archivos-docente">
        <div class="archivos-wrapper">

            <?php if (file_exists('breadcrumbs.php')) include 'breadcrumbs.php'; ?>

            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold">Mis archivos</h1>
                    <p class="subtitulo-banner">Sube tus planeamientos en formato PDF y consulta tu historial de entregas.</p>
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

            <!-- WIDGET: SUBIR PLANEAMIENTO -->
            <section class="card-subir-archivos">
                <div class="cabecera-card-archivos">
                    <div class="cabecera-card-archivos-izq">
                        <div class="icono-widget-docente azul">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <div>
                            <h2>Subir planeamiento</h2>
                            <p>Arrastra tu documento o selecciónalo desde tu dispositivo</p>
                        </div>
                    </div>
                    <span class="tag-formato-pdf">Solo PDF</span>
                </div>

                <div class="zona-drop-archivos" id="zona-drop-archivos"
                     ondragover="event.preventDefault(); this.classList.add('arrastrando');"
                     ondragleave="this.classList.remove('arrastrando');"
                     ondrop="manejarDropPlaneamiento(event)">
                    <div class="circulo-nube-pdf">
                        <svg class="icono-nube-subida" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <p id="estado-subida-texto">Arrastra o suelta tu planeamiento aquí</p>
                    <small class="ayuda-formato-pdf">Formato admitido: PDF · Se registrará automáticamente en tu historial</small>

                    <input type="file" id="input-archivo-planeamiento" accept="application/pdf,.pdf" style="display: none;" onchange="manejarInputPlaneamiento(event)">
                    <button type="button" class="btn-seleccionar-archivo" onclick="document.getElementById('input-archivo-planeamiento').click()">
                        Seleccionar archivo
                    </button>
                </div>
            </section>

            <!-- WIDGET: HISTORIAL DE ENTREGAS -->
            <section class="card-historial-entregas">
                <div class="cabecera-card-archivos">
                    <div class="cabecera-card-archivos-izq">
                        <div class="icono-widget-docente verde">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        </div>
                        <div>
                            <h2>Historial de Entregas</h2>
                            <p>Registro cronológico de tus planeamientos enviados</p>
                        </div>
                    </div>
                    <span class="badge-total-entregas" id="badge-total-entregas">2 entregas</span>
                </div>

                <div class="lista-historial-entregas" id="lista-historial-entregas"></div>
            </section>

        </div>
    </main>
</div>

<!-- MODAL: CONFIRMACIÓN DE ENTREGA -->
<div class="fondo-modal" id="modal-entrega-exito" onclick="cerrarSiFuera(event, 'modal-entrega-exito')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-entrega-exito')" aria-label="Cerrar">
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

        <h2>Planeamiento registrado</h2>
        <p id="mensaje-entrega-exito">El archivo se agregó a tu historial de entregas.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarModal('modal-entrega-exito')">Aceptar</button>
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
            <a href="planeamiento_docente.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const cedulaActual = "<?php echo addslashes($cedula_sesion); ?>";
    const claveHistorial = `historial_planeamientos_${cedulaActual}`;

    const historialInicial = [
        {
            titulo: "Planeamiento 1",
            archivo: "Planeamiento_01_2026.pdf",
            fecha: "04/04/2026",
            estado: "Calificado"
        },
        {
            titulo: "Planeamiento 2",
            archivo: "Planeamiento_02_2026.pdf",
            fecha: "24/08/2026",
            estado: "Entregado"
        }
    ];

    function obtenerHistorial() {
        const guardado = localStorage.getItem(claveHistorial);
        if (guardado) {
            try {
                const parsed = JSON.parse(guardado);
                if (Array.isArray(parsed) && parsed.length > 0) return parsed;
            } catch (e) {}
        }
        localStorage.setItem(claveHistorial, JSON.stringify(historialInicial));
        return [...historialInicial];
    }

    function formatearFechaHoy() {
        const hoy = new Date();
        const dd = String(hoy.getDate()).padStart(2, '0');
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const yyyy = hoy.getFullYear();
        return `${dd}/${mm}/${yyyy}`;
    }

    function renderizarHistorial() {
        const lista = obtenerHistorial();
        const contenedor = document.getElementById('lista-historial-entregas');
        const badge = document.getElementById('badge-total-entregas');

        badge.textContent = `${lista.length} ${lista.length === 1 ? 'entrega' : 'entregas'}`;

        contenedor.innerHTML = lista.map((item) => `
            <div class="fila-historial-item">
                <div class="fila-historial-izq">
                    <span class="icono-pdf-fila">PDF</span>
                    <div class="detalle-historial-texto">
                        <strong>${item.titulo}</strong>
                        <span>${item.archivo || 'Documento PDF'}</span>
                    </div>
                </div>

                <div class="fila-historial-der">
                    <span class="fecha-entrega-pill">${item.fecha}</span>
                    <span class="estado-entrega-pill">${item.estado || 'Entregado'}</span>
                </div>
            </div>
        `).join('');
    }

    function procesarArchivoPDF(archivo) {
        if (!archivo) return;

        const lector = new FileReader();
        lector.onload = function(ev) {
            const lista = obtenerHistorial();
            const nuevoNumero = lista.length + 1;
            const fechaHoy = formatearFechaHoy();

            lista.push({
                titulo: `Planeamiento ${nuevoNumero}`,
                archivo: archivo.name,
                fecha: fechaHoy,
                estado: "Entregado"
            });

            localStorage.setItem(claveHistorial, JSON.stringify(lista));

            const docentes = JSON.parse(localStorage.getItem('lista_docentes_cedes') || '[]');
            const idxDoc = docentes.findIndex(d => d.cedula === cedulaActual);
            if (idxDoc !== -1) {
                docentes[idxDoc].pdf = archivo.name;
                docentes[idxDoc].pdfData = ev.target.result;
                localStorage.setItem('lista_docentes_cedes', JSON.stringify(docentes));
            }

            document.getElementById('estado-subida-texto').innerHTML = `Último archivo cargado: <strong>${archivo.name}</strong>`;
            document.getElementById('mensaje-entrega-exito').textContent = `Se registró "${archivo.name}" con fecha ${fechaHoy}.`;

            renderizarHistorial();
            abrirModal('modal-entrega-exito');
        };
        lector.readAsDataURL(archivo);
    }

    function manejarInputPlaneamiento(e) {
        const archivo = e.target.files[0];
        procesarArchivoPDF(archivo);
        e.target.value = "";
    }

    function manejarDropPlaneamiento(e) {
        e.preventDefault();
        document.getElementById('zona-drop-archivos').classList.remove('arrastrando');
        const archivo = e.dataTransfer.files[0];
        procesarArchivoPDF(archivo);
    }

    function sincronizarAvatarMenu() {
        const guardado = JSON.parse(localStorage.getItem('perfil_docente_cedes') || 'null');
        const nombreBase = (guardado && guardado.nombre) ? guardado.nombre : document.getElementById('menu-nombre-usuario').textContent;
        document.getElementById('menu-nombre-usuario').textContent = nombreBase;

        const elAvatar = document.getElementById('menu-avatar-iniciales');
        if (guardado && guardado.foto && guardado.foto.startsWith('data:image')) {
            elAvatar.innerHTML = `<img src="${guardado.foto}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">`;
        } else {
            const partes = nombreBase.trim().split(/\s+/);
            elAvatar.textContent = partes.length > 1
                ? (partes[0][0] + partes[partes.length - 1][0]).toUpperCase()
                : partes[0].substring(0, 2).toUpperCase();
        }
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
        sincronizarAvatarMenu();
        renderizarHistorial();
    };
</script>

</body>
</html>