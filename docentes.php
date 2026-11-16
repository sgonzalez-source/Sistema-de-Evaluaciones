<?php
session_start();

// Si confirmó "Cerrar Sesión", destruye la sesión y vuelve a inicio_sesion.php
if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=12">
    <link rel="stylesheet" href="docentes.css?v=3">
</head>
<body>

<div class="contenedor-general">

    <!-- MENÚ LATERAL AZUL-->
    <aside class="menu" id="sidebar-menu">
        <script>if(localStorage.getItem('menu_colapsado')==='1')document.getElementById('sidebar-menu').classList.add('colapsado');</script>
        <div class="logo">
            <img src="logo_white.png" alt="CEDES Don Bosco" class="logo-blanco">
        </div>

        <button type="button" class="btn-hamburguesa-menu" onclick="alternarMenu()" title="Expandir / Contraer menú" aria-label="Alternar menú lateral">
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

            <a href="#" title="Rúbricas">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span class="texto-menu">Rúbricas</span>
            </a>

            <a href="#" title="Evaluar">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span class="texto-menu">Evaluar</span>
            </a>

            <a href="#" title="Estadística">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span class="texto-menu">Estadística</span>
            </a>
        </nav>

        <div class="pie-menu">
            <div class="usuario" title="<?php echo $nombre . ' - ' . $rol; ?>">
                <div class="avatar-iniciales">JM</div>
                <div class="info-usuario">
                    <strong><?php echo $nombre; ?></strong>
                    <p><?php echo $rol; ?></p>
                </div>
            </div>

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

    <!-- ÁREA DERECHA: GESTIÓN DE DOCENTES A CARGO -->
    <main class="contenido contenido-docentes">

        <!-- RUTA DE NAVEGACIÓN DINÁMICA -->
        <?php include 'breadcrumbs.php'; ?>

        <!-- TÍTULO PRINCIPAL -->
        <h1 class="titulo-pagina">Gestión de Docentes a Cargo</h1>

        <!-- BARRA DE BÚSQUEDA, FILTRO Y BOTÓN AGREGAR -->
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
                    <span>Agregar docente</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- TABLA DE DOCENTES -->
        <div class="contenedor-tabla">
            <table class="tabla-docentes">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Docente</th>
                        <th>Materia</th>
                        <th>Planeamiento</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla-docentes">
                    <!-- Se llena dinámicamente desde JavaScript / localStorage -->
                </tbody>
            </table>
        </div>

    </main>
</div>

<!-- MODAL: AGREGAR NUEVO DOCENTE CON SELECTOR REAL DE ARCHIVO -->
<div class="fondo-modal" id="modal-agregar" onclick="cerrarSiFuera(event, 'modal-agregar')">
    <div class="caja-modal caja-modal-form">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-agregar')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <h2>Agregar Nuevo Docente</h2>
        <p class="subtexto-modal">Completa los datos para asignarlo a tu lista de docentes a cargo.</p>

        <form onsubmit="guardarNuevoDocente(event)" class="form-modal-docente">
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

            <!-- CAMPO DE SUBIR PLANEAMIENTO QUE ABRE EL EXPLORADOR DE ARCHIVOS -->
            <div class="campo-form">
                <label>Subir planeamiento</label>
                <input type="file" id="nuevo-planeamiento-archivo" accept=".pdf,.doc,.docx,application/pdf" style="display: none;" onchange="mostrarArchivoSeleccionado(this)">
                
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
                <button type="submit" class="btn-modal-guardar-azul">Guardar Docente</button>
            </div>
        </form>
    </div>
</div>

<!--VISTA PREVIA DE PLANEAMIENTO PDF -->
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
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM8.5 14.5h1c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5H9v1.5H8v-4.5h.5zm3.5 0h1.5c1.1 0 2 .9 2 2v.5c0 1.1-.9 2-2 2H12v-4.5zm4.5 0H19v1h-1.5v.8h1.2v1h-1.2V19h-1v-4.5z"/>
            </svg>
        </div>

        <h2 id="titulo-pdf-docente">Planeamiento Docente</h2>
        <p id="nombre-archivo-pdf" class="archivo-pdf-badge">Planeamiento.pdf</p>
        <p class="detalle-pdf-texto">Documento entregado por el docente para revisión del periodo evaluativo 2026.</p>

        <div class="botones-modal" id="acciones-modal-pdf">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-pdf')">Cerrar vista previa</button>
        </div>
    </div>
</div>

<!-- CONFIRMACIÓN PARA CERRAR SESIÓN -->
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
    // Datos iniciales de docentes
    const docentesIniciales = [
        { cedula: "100564782", nombre: "Lucía Rodríguez", materia: "Español", pdf: "Planeamiento_Espanol_Lucia.pdf", activo: false },
        { cedula: "203648647", nombre: "Mario López", materia: "Química", pdf: "Planeamiento_Quimica_Mario.pdf", activo: true },
        { cedula: "187263944", nombre: "Randall Ocón", materia: "Matemáticas", pdf: "Planeamiento_Mate_Randall.pdf", activo: false },
        { cedula: "166478230", nombre: "Sharon Mendes", materia: "Música", pdf: "Planeamiento_Musica_Sharon.pdf", activo: true }
    ];

    let listaDocentes = [];
    const archivosTemporales = {};

    // Cada materia tiene su propio color
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
                <td>
                    <label class="switch-estado" title="${doc.activo ? 'Evaluación activa' : 'Pendiente / Inactivo'}">
                        <input type="checkbox" ${doc.activo ? 'checked' : ''} onchange="cambiarSwitch(${indiceReal}, this.checked)">
                        <span class="slider-switch"></span>
                    </label>
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

    function guardarNuevoDocente(e) {
        e.preventDefault();
        const cedula = document.getElementById('nuevo-cedula').value.trim();
        const nombre = document.getElementById('nuevo-nombre').value.trim();
        const materia = document.getElementById('nuevo-materia').value;
        const inputArchivo = document.getElementById('nuevo-planeamiento-archivo');

        let nombrePdf = "Planeamiento_2026.pdf";
        if (inputArchivo.files && inputArchivo.files.length > 0) {
            const archivo = inputArchivo.files[0];
            nombrePdf = archivo.name;
            archivosTemporales[cedula] = URL.createObjectURL(archivo);
        }

        listaDocentes.push({
            cedula: cedula,
            nombre: nombre,
            materia: materia,
            pdf: nombrePdf,
            activo: true
        });

        localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
        renderizarTabla();
        cerrarModal('modal-agregar');
        e.target.reset();
        document.getElementById('texto-archivo-elegido').textContent = "Ningún archivo seleccionado";
        document.getElementById('texto-archivo-elegido').classList.remove('archivo-cargado');
    }

    function verPlaneamiento(cedula, nombreDocente, archivoPdf) {
        if (archivosTemporales[cedula]) {
            window.open(archivosTemporales[cedula], '_blank');
            return;
        }

        document.getElementById('titulo-pdf-docente').textContent = "Planeamiento de " + nombreDocente;
        document.getElementById('nombre-archivo-pdf').textContent = archivoPdf;
        document.getElementById('modal-pdf').classList.add('visible');
    }

    function abrirModalAgregar() {
        document.getElementById('modal-agregar').classList.add('visible');
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
        menu.classList.toggle('colapsado');
        localStorage.setItem('menu_colapsado', menu.classList.contains('colapsado') ? '1' : '0');
    }

    window.onload = function() {
        if (localStorage.getItem('menu_colapsado') === '1') {
            document.getElementById('sidebar-menu').classList.add('colapsado');
        }

        const guardados = localStorage.getItem('lista_docentes_cedes');
        if (guardados) {
            listaDocentes = JSON.parse(guardados);
        } else {
            listaDocentes = docentesIniciales;
            localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
        }

        renderizarTabla();
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-agregar');
            cerrarModal('modal-pdf');
        }
    });
</script>

</body>
</html>