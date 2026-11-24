<?php
session_start();

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
$titulo_miga = "Gestión de Docentes";
$anio_escolar = "2026";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=57">
    <link rel="stylesheet" href="docentes.css?v=17">
    <style>
        .tabla-docentes th:nth-child(4),
        .tabla-docentes td:nth-child(4) {
            text-align: center !important;
        }
    </style>
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
            <a href="perfil.php" class="usuario" style="text-decoration: none; color: inherit; cursor: pointer;" title="Ver perfil de usuario">
                <div class="avatar-iniciales" id="menu-avatar-iniciales">JM</div>
                <div class="info-usuario">
                    <strong id="menu-nombre-usuario"><?php echo $nombre; ?></strong>
                    <p><?php echo $rol; ?></p>
                </div>
            </a>

            <button type="button" class="salir" onclick="abrirModalSalir()" title="Cerrar Sesión">
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
    <main class="contenido contenido-docentes">

        <?php include 'breadcrumbs.php'; ?>

        <header class="banner-seccion">
            <div class="banner-izq">
                <h1 class="titulo-docentes-bold">Gestión de Docentes a Cargo</h1>
                <p class="subtitulo-banner">Administra la lista de docentes asignados, revisa sus planeamientos y controla su estado activo.</p>
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

        <div class="barra-herramientas">
            <div class="caja-busqueda">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="input-buscar" placeholder="Buscar por cédula o nombre..." oninput="filtrarTabla()">
            </div>

            <div class="acciones-derecha">
                <select id="select-materia" class="select-filtro" onchange="filtrarTabla()">
                    <option value="">Filtrar por materia</option>
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

                <button type="button" class="btn-agregar-docente" onclick="abrirModalAgregar()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Agregar docente</span>
                </button>
            </div>
        </div>

        <div class="contenedor-tabla">
            <table class="tabla-docentes">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Docente</th>
                        <th>Materia</th>
                        <th>Planeamiento</th>
                        <th class="col-acciones-header">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla-docentes"></tbody>
            </table>
        </div>

    </main>
</div>

<!-- MODAL: AGREGAR / EDITAR DOCENTE -->
<div class="fondo-modal" id="modal-agregar" onclick="cerrarSiFuera(event, 'modal-agregar')">
    <div class="caja-modal caja-modal-form">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-agregar')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <h2 id="titulo-modal-docente">Agregar Nuevo Docente</h2>
        <p class="subtexto-modal" id="subtexto-modal-docente">Completa los datos para asignarlo a tu lista de docentes a cargo.</p>

        <form onsubmit="guardarDocente(event)" class="form-modal-docente">
            <div class="campo-form">
                <label for="nuevo-cedula">Cédula</label>
                <input type="text" id="nuevo-cedula" placeholder="Ej. 100564782" required>
            </div>

            <div class="campo-form">
                <label for="nuevo-nombre">Nombre completo</label>
                <input type="text" id="nuevo-nombre" placeholder="Ej. Lucía Rodríguez" required>
            </div>

            <div class="campo-form">
                <label for="nuevo-materia">Materia</label>
                <select id="nuevo-materia" required>
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

            <div class="campo-form">
                <label>Subir planeamiento (PDF)</label>
                <input type="file" id="nuevo-planeamiento-archivo" accept=".pdf,application/pdf" style="display: none;" onchange="mostrarArchivoSeleccionado(this)">
                
                <div class="caja-subir-archivo" onclick="document.getElementById('nuevo-planeamiento-archivo').click()">
                    <div class="btn-subir-visual">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <span>Subir archivo</span>
                    </div>
                    <span class="nombre-archivo-elegido" id="texto-archivo-elegido">Ningún archivo seleccionado</span>
                </div>
            </div>

            <div class="botones-modal">
                <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-agregar')">Cancelar</button>
                <button type="submit" class="btn-modal-guardar-verde" id="btn-submit-docente">Guardar Docente</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: CONFIRMAR ELIMINAR DOCENTE -->
<div class="fondo-modal" id="modal-eliminar-docente" onclick="cerrarSiFuera(event, 'modal-eliminar-docente')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-eliminar-docente')" aria-label="Cerrar">
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

        <h2>¿Deseas eliminar este docente?</h2>
        <p id="texto-eliminar-docente">Esta acción eliminará al docente de tu lista de docentes a cargo.</p>

        <div class="botones-modal">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-eliminar-docente')">Cancelar</button>
            <button type="button" class="btn-modal-confirmar-btn" onclick="confirmarEliminarDocente()">Sí, eliminar</button>
        </div>
    </div>
</div>

<!-- MODAL: VISTA PREVIA DE PLANEAMIENTO PDF -->
<div class="fondo-modal" id="modal-pdf" onclick="cerrarSiFuera(event, 'modal-pdf')">
    <div class="caja-modal caja-modal-pdf">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-pdf')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-pdf-modal">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM8.5 14.5h1c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5H9v1.5H8v-4.5h.5zm3.5 0h1.5c1.1 0 2 .9 2 2v.5c0 1-.8 2-2 2H12v-4.5zm4.5 0H19v1h-1.5v.8h1.2v1h-1.2V19h-1v-4.5z"/>
            </svg>
        </div>

        <h2 id="titulo-pdf-docente">Planeamiento Docente</h2>
        <p id="nombre-archivo-pdf" class="archivo-pdf-badge">Planeamiento.pdf</p>
        <p class="detalle-pdf-texto">Documento entregado por el docente para revisión del periodo evaluativo 2026.</p>

        <div class="botones-modal">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-pdf')">Cerrar vista previa</button>
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
            <a href="docentes.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const docentesIniciales = [
        { cedula: "100564782", nombre: "Lucía Rodríguez", materia: "Español", pdf: "Planeamiento_Espanol_Lucia.pdf", pdfData: "", activo: false },
        { cedula: "203648647", nombre: "Mario López", materia: "Química", pdf: "Planeamiento_Quimica_Mario.pdf", pdfData: "", activo: true },
        { cedula: "187263944", nombre: "Randall Ocón", materia: "Matemáticas", pdf: "Planeamiento_Mate_Randall.pdf", pdfData: "", activo: false },
        { cedula: "166478230", nombre: "Sharon Mendes", materia: "Música", pdf: "Planeamiento_Musica_Sharon.pdf", pdfData: "", activo: true }
    ];

    let listaDocentes = [];
    let indiceEditando = null;
    let indiceEliminando = null;
    const archivosTemporales = {};

    function sincronizarPerfilMenu() {
        const perfilGuardado = JSON.parse(localStorage.getItem('perfil_coordinador_cedes') || 'null');
        if (!perfilGuardado) return;

        const elNombreMenu = document.getElementById('menu-nombre-usuario');
        const elAvatarMenu = document.getElementById('menu-avatar-iniciales');

        if (perfilGuardado.nombre) {
            const partes = perfilGuardado.nombre.trim().split(/\s+/);
            if (elNombreMenu) elNombreMenu.textContent = perfilGuardado.nombre;

            if (elAvatarMenu) {
                if (perfilGuardado.foto && perfilGuardado.foto.startsWith('data:image')) {
                    elAvatarMenu.innerHTML = `<img src="${perfilGuardado.foto}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;">`;
                } else {
                    elAvatarMenu.textContent = partes.length > 1
                        ? (partes[0][0] + partes[partes.length - 1][0]).toUpperCase()
                        : partes[0].substring(0, 2).toUpperCase();
                }
            }
        }
    }

    function obtenerClaseMateria(materia) {
        const mapa = {
            "Español":           "pill-morado",
            "Química":           "pill-celeste",
            "Matemáticas":       "pill-naranja",
            "Música":            "pill-rosa",
            "Ciencias":          "pill-verde",
            "Informática":       "pill-azul",
            "Estudios Sociales": "pill-rojo",
            "Inglés":            "pill-turquesa",
            "Cívica":            "pill-indigo",
            "Educación Física":  "pill-lima"
        };
        return mapa[materia] || "pill-gris";
    }

    function renderizarTabla() {
        const tbody = document.getElementById('cuerpo-tabla-docentes');
        const textoBusqueda = document.getElementById('input-buscar').value.toLowerCase().trim();
        const filtroMateria = document.getElementById('select-materia').value;

        tbody.innerHTML = "";

        const filtrados = listaDocentes.filter(doc => {
            const coincideTexto = doc.cedula.toLowerCase().includes(textoBusqueda) || doc.nombre.toLowerCase().includes(textoBusqueda);
            const coincideMateria = filtroMateria === "" || doc.materia === filtroMateria;
            return coincideTexto && coincideMateria;
        });

        if (filtrados.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="sin-resultados">No se encontraron docentes con ese criterio.</td></tr>`;
            return;
        }

        filtrados.forEach((doc) => {
            const indiceReal = listaDocentes.indexOf(doc);
            const clasePill = obtenerClaseMateria(doc.materia);

            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td class="col-cedula">${doc.cedula}</td>
                <td class="col-nombre">${doc.nombre}</td>
                <td>
                    <span class="pill-materia ${clasePill}">
                        <span class="icono-pill">◎</span>
                        ${doc.materia}
                    </span>
                </td>
                <td>
                    <button type="button" class="btn-pdf-icono" onclick="verPlaneamiento('${doc.cedula}', '${doc.nombre}', '${doc.pdf}')" title="Ver ${doc.pdf}">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM7.5 14h1.3c.8 0 1.4.6 1.4 1.4s-.6 1.4-1.4 1.4H8.5V18h-1v-4zm3.5 0h1.4c1 0 1.8.8 1.8 1.8v.4c0 1-.8 1.8-1.8 1.8H11v-4zm4.2 0h2.3v.9h-1.3v.7h1.1v.9h-1.1V18h-1v-4z"/>
                        </svg>
                    </button>
                </td>
                <td class="col-acciones">
                    <div class="grupo-acciones-docente">
                        <label class="switch-estado" title="${doc.activo ? 'Docente activo' : 'Docente inactivo'}">
                            <input type="checkbox" ${doc.activo ? 'checked' : ''} onchange="cambiarSwitch(${indiceReal}, this.checked)">
                            <span class="slider-switch"></span>
                        </label>

                        <button type="button" class="btn-icono-tabla editar" onclick="abrirModalEditar(${indiceReal})" title="Editar docente">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                            </svg>
                        </button>

                        <button type="button" class="btn-icono-tabla eliminar" onclick="pedirEliminarDocente(${indiceReal})" title="Eliminar docente">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(fila);
        });
    }

    function filtrarTabla() {
        renderizarTabla();
    }

    function cambiarSwitch(indice, estado) {
        listaDocentes[indice].activo = estado;
        localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
    }

    function mostrarArchivoSeleccionado(input) {
        const texto = document.getElementById('texto-archivo-elegido');
        if (input.files && input.files.length > 0) {
            texto.textContent = input.files[0].name;
            texto.classList.add('archivo-cargado');
        } else {
            texto.textContent = "Ningún archivo seleccionado";
            texto.classList.remove('archivo-cargado');
        }
    }

    function abrirModalAgregar() {
        indiceEditando = null;
        document.getElementById('titulo-modal-docente').textContent = "Agregar Nuevo Docente";
        document.getElementById('subtexto-modal-docente').textContent = "Completa los datos para asignarlo a tu lista de docentes a cargo.";
        document.getElementById('btn-submit-docente').textContent = "Guardar Docente";

        document.getElementById('nuevo-cedula').value = "";
        document.getElementById('nuevo-nombre').value = "";
        document.getElementById('nuevo-materia').value = "Español";
        document.getElementById('nuevo-planeamiento-archivo').value = "";
        const texto = document.getElementById('texto-archivo-elegido');
        texto.textContent = "Ningún archivo seleccionado";
        texto.classList.remove('archivo-cargado');

        document.getElementById('modal-agregar').classList.add('visible');
    }

    function abrirModalEditar(indice) {
        indiceEditando = indice;
        const doc = listaDocentes[indice];
        if (!doc) return;

        document.getElementById('titulo-modal-docente').textContent = "Editar Docente";
        document.getElementById('subtexto-modal-docente').textContent = "Modifica la información del docente o actualiza su planeamiento PDF.";
        document.getElementById('btn-submit-docente').textContent = "Guardar Cambios";

        document.getElementById('nuevo-cedula').value = doc.cedula;
        document.getElementById('nuevo-nombre').value = doc.nombre;
        document.getElementById('nuevo-materia').value = doc.materia;
        document.getElementById('nuevo-planeamiento-archivo').value = "";

        const texto = document.getElementById('texto-archivo-elegido');
        texto.textContent = doc.pdf || "Ningún archivo seleccionado";
        if (doc.pdf) texto.classList.add('archivo-cargado');

        document.getElementById('modal-agregar').classList.add('visible');
    }

    function guardarDocente(e) {
        e.preventDefault();
        const cedula = document.getElementById('nuevo-cedula').value.trim();
        const nombre = document.getElementById('nuevo-nombre').value.trim();
        const materia = document.getElementById('nuevo-materia').value;
        const inputArchivo = document.getElementById('nuevo-planeamiento-archivo');

        const finalizarGuardado = (nombrePdf, archivoBase64) => {
            if (indiceEditando !== null) {
                const anterior = listaDocentes[indiceEditando];
                listaDocentes[indiceEditando] = {
                    ...anterior,
                    cedula: cedula,
                    nombre: nombre,
                    materia: materia,
                    pdf: nombrePdf || anterior.pdf,
                    pdfData: archivoBase64 !== null ? archivoBase64 : anterior.pdfData
                };
            } else {
                listaDocentes.push({
                    cedula: cedula,
                    nombre: nombre,
                    materia: materia,
                    pdf: nombrePdf || "Planeamiento_2026.pdf",
                    pdfData: archivoBase64 || "",
                    activo: true
                });
            }

            try {
                localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
            } catch (err) {
                const idx = indiceEditando !== null ? indiceEditando : listaDocentes.length - 1;
                listaDocentes[idx].pdfData = "";
                localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
            }

            renderizarTabla();
            cerrarModal('modal-agregar');
            indiceEditando = null;
        };

        if (inputArchivo.files && inputArchivo.files.length > 0) {
            const archivo = inputArchivo.files[0];
            archivosTemporales[cedula] = URL.createObjectURL(archivo);
            const lector = new FileReader();
            lector.onload = function(evento) {
                finalizarGuardado(archivo.name, evento.target.result);
            };
            lector.readAsDataURL(archivo);
        } else {
            finalizarGuardado(indiceEditando !== null ? "" : "Planeamiento_2026.pdf", null);
        }
    }

    function pedirEliminarDocente(indice) {
        indiceEliminando = indice;
        const doc = listaDocentes[indice];
        document.getElementById('texto-eliminar-docente').innerHTML =
            `¿Estás seguro de eliminar a <strong>${doc.nombre}</strong> (Cédula: ${doc.cedula}) de tu lista?`;
        document.getElementById('modal-eliminar-docente').classList.add('visible');
    }

    function confirmarEliminarDocente() {
        if (indiceEliminando !== null) {
            listaDocentes.splice(indiceEliminando, 1);
            localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
            renderizarTabla();
            indiceEliminando = null;
        }
        cerrarModal('modal-eliminar-docente');
    }

    function verPlaneamiento(cedula, nombreDocente, archivoPdf) {
        const doc = listaDocentes.find(d => d.cedula === cedula);
        if (archivosTemporales[cedula]) {
            window.open(archivosTemporales[cedula], '_blank');
            return;
        }
        if (doc && doc.pdfData && doc.pdfData.startsWith('data:')) {
            const win = window.open();
            win.document.write(`<iframe src="${doc.pdfData}" frameborder="0" style="border:0; width:100%; height:100%;" allowfullscreen></iframe>`);
            return;
        }

        document.getElementById('titulo-pdf-docente').textContent = "Planeamiento de " + nombreDocente;
        document.getElementById('nombre-archivo-pdf').textContent = archivoPdf;
        document.getElementById('modal-pdf').classList.add('visible');
    }

    function abrirModalSalir() {
        document.getElementById('modal-salir').classList.add('visible');
    }

    function cerrarModal(idModal) {
        document.getElementById(idModal).classList.remove('visible');
    }

    function cerrarSiFuera(evento, idModal) {
        if (evento.target.id === idModal) {
            cerrarModal(idModal);
        }
    }

    function alternarMenu() {
        const menu = document.getElementById('sidebar-menu');
        menu.classList.add('animar');
        menu.classList.toggle('colapsado');
        localStorage.setItem('menu_colapsado', menu.classList.contains('colapsado') ? '1' : '0');
    }

    window.onload = function() {
        sincronizarPerfilMenu();

        const guardados = localStorage.getItem('lista_docentes_cedes');
        if (guardados) {
            listaDocentes = JSON.parse(guardados);
        } else {
            listaDocentes = docentesIniciales;
            localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
        }

        renderizarTabla();
    };

    window.addEventListener('focus', sincronizarPerfilMenu);
    window.addEventListener('storage', sincronizarPerfilMenu);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-agregar');
            cerrarModal('modal-eliminar-docente');
            cerrarModal('modal-pdf');
        }
    });
</script>

</body>
</html>