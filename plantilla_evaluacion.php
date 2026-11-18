<?php
session_start();

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
$titulo_miga = "Plantillas de evaluación";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantillas de evaluación - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=20">
    <link rel="stylesheet" href="plantilla_evaluacion.css?v=4">
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
    <main class="contenido contenido-plantillas">

        <?php include 'breadcrumbs.php'; ?>

        <!-- VISTA 1: LISTADO DE PLANTILLAS -->
        <section id="vista-lista-plantillas" class="seccion-vista">
            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1>Plantillas de evaluación</h1>
                    <p class="subtitulo-banner">Configura, edita y activa las rúbricas oficiales de observación docente para cada periodo escolar.</p>
                </div>

                <div class="banner-der">
                    <button type="button" class="btn-crear-plantilla" onclick="abrirEditorPlantilla(null)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Crear plantilla</span>
                    </button>
                </div>
            </header>

            <div class="lista-plantillas" id="contenedor-plantillas"></div>
        </section>

        <!-- VISTA 2: EDITOR COMPLETO DE RÚBRICA -->
        <section id="vista-editor-plantilla" class="seccion-vista" style="display: none;">
            <header class="banner-seccion">
                <div class="banner-izq">
                    <span class="etiqueta-superior">Editor de Rúbrica</span>
                    <h1 id="titulo-editor-principal">Crear plantilla</h1>
                    <p class="subtitulo-banner">Edita libremente cualquier campo, criterio o descripción de la rúbrica institucional.</p>
                </div>

                <div class="botones-superiores-editor">
                    <button type="button" class="btn-volver-lista" onclick="volverALista()">← Volver a plantillas</button>
                    <button type="button" class="btn-agregar-fila" onclick="agregarFilaCriterio()">+ Agregar criterio</button>
                </div>
            </header>

            <div class="hoja-rubrica">
                <table class="tabla-rubrica-oficial">
                    <tbody>
                        <tr>
                            <td colspan="3" class="celda-meta">
                                <label>Docente: <input type="text" id="rubrica-docente" placeholder="Escribir nombre del docente..."></label>
                            </td>
                            <td colspan="2" class="celda-meta">
                                <label>Sección: <input type="text" id="rubrica-seccion" placeholder="Ej. 10-A"></label>
                            </td>
                            <td rowspan="2" class="celda-anio-box">
                                <div class="anio-inline">
                                    <span>Año:</span>
                                    <input type="text" id="rubrica-anio" value="2026">
                                </div>
                            </td>
                            <td rowspan="2" class="celda-logo-box">
                                <img src="logo_black.png" alt="CEDES Don Bosco" class="logo-rubrica-negro">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="celda-meta">
                                <label>Curso lectivo: <input type="text" id="rubrica-curso" placeholder="Ej. 2026"></label>
                            </td>
                            <td colspan="2" class="celda-meta">
                                <label>Periodo: <input type="text" id="rubrica-periodo" placeholder="Ej. I Periodo"></label>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7" class="titulo-institucional-rubrica">
                                <input type="text" id="rubrica-titulo-banner" value="RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO">
                            </td>
                        </tr>

                        <tr class="encabezados-criterios">
                            <th class="col-crit">Criterio</th>
                            <th class="col-nivel">Excelente (4 puntos)</th>
                            <th class="col-nivel">Muy Bueno (3 puntos)</th>
                            <th class="col-nivel">Bueno (2 puntos)</th>
                            <th class="col-nivel">Insuficiente (1 punto)</th>
                            <th class="col-sel">Selección</th>
                            <th class="col-pts">Puntaje obtenido</th>
                        </tr>
                    </tbody>

                    <tbody id="cuerpo-criterios"></tbody>

                    <tfoot>
                        <tr class="fila-total-salmon">
                            <td colspan="6" class="texto-total-label">Puntuación total obtenida</td>
                            <td class="valor-total-num" id="total-puntos"></td>
                        </tr>
                        <tr class="fila-interpretacion-amarilla">
                            <td colspan="6" class="texto-interp-label">Interpretación de la puntuación</td>
                            <td class="valor-interp-texto" id="texto-interpretacion"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="acciones-inferiores-rubrica">
                <button type="button" class="btn-guardar-borrador" onclick="guardarPlantilla(true)">Guardar borrador</button>
                <button type="button" class="btn-guardar-cambios" onclick="guardarPlantilla(false)">Guardar Cambios</button>
            </div>
        </section>

    </main>
</div>

<!-- MODAL: CONFIRMAR ELIMINAR PLANTILLA -->
<div class="fondo-modal" id="modal-eliminar" onclick="cerrarSiFuera(event, 'modal-eliminar')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-eliminar')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-modal-rojo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="12" y1="10" x2="12" y2="14"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>

        <h2>¿Deseas eliminar esta plantilla de evaluación?</h2>
        <p>Esta acción no se puede deshacer. Se eliminará de forma permanente y también, todos los criterios configurados en ella.</p>

        <div class="botones-modal">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-eliminar')">Cancelar</button>
            <button type="button" class="btn-modal-confirmar-btn" onclick="confirmarEliminarPlantilla()">Sí, eliminar</button>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMACIÓN PARA CERRAR SESIÓN -->
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
            <a href="plantilla_evaluacion.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const criteriosBase = [
        {
            criterio: "Observación de clase",
            exc: "Las clases son dinámicas, participativas y promueven el aprendizaje activo.",
            mb: "Las clases son generalmente participativas y promueven el aprendizaje.",
            b: "Las clases pueden ser un poco pasivas y no siempre promueven el aprendizaje.",
            m: "Las clases son generalmente pasivas y no promueven el aprendizaje.",
            seleccion: ""
        },
        {
            criterio: "Dominio de los contenidos",
            exc: "Demuestra un dominio excepcional del contenido y las estrategias de enseñanza.",
            mb: "Tiene un buen dominio del contenido y las estrategias de enseñanza.",
            b: "Tiene un dominio básico del contenido y las estrategias de enseñanza.",
            m: "Tiene un dominio limitado del contenido y las estrategias de enseñanza.",
            seleccion: ""
        },
        {
            criterio: "Planeación",
            exc: "Los planes de clase son claros, concisos, están bien organizados y se alinean con los estándares de aprendizaje y las necesidades de los estudiantes.",
            mb: "Los planes de clase son generalmente claros, concisos, están organizados y se alinean con los estándares de aprendizaje y las necesidades de la mayoría de los estudiantes.",
            b: "Los planes de clase pueden ser un poco confusos y desorganizados. No siempre se alinean con los estándares de aprendizaje o las necesidades de todos los estudiantes.",
            m: "Los planes de clase son confusos y desorganizados. No se alinean con los estándares de aprendizaje ni con las necesidades de los estudiantes.",
            seleccion: ""
        },
        {
            criterio: "Mediación pedagógica",
            exc: "Utiliza una variedad de estrategias de enseñanza efectivas para involucrar a todos los estudiantes.",
            mb: "Utiliza algunas estrategias de enseñanza efectivas para involucrar a la mayoría de los estudiantes.",
            b: "Utiliza pocas estrategias de enseñanza efectivas para involucrar a los estudiantes.",
            m: "No utiliza estrategias de enseñanza efectivas para involucrar a los estudiantes.",
            seleccion: ""
        },
        {
            criterio: "Metacognición del contenido (retroalimentación)",
            exc: "Brinda retroalimentación clara y oportuna a los estudiantes.",
            mb: "Brinda retroalimentación a los estudiantes, pero no siempre es clara u oportuna.",
            b: "La retroalimentación a los estudiantes es escasa o no es útil.",
            m: "No brinda retroalimentación a los estudiantes.",
            seleccion: ""
        },
        {
            criterio: "Orden y aseo del aula",
            exc: "El aula está organizada, limpia y segura. Los materiales didácticos totalmente guardados y accesibles.",
            mb: "El aula está organizada y limpia. Los materiales didácticos están guardados y accesibles.",
            b: "El aula está organizada, pero puede estar un poco sucia. Los materiales didácticos están guardados, pero pueden no ser fácilmente accesibles.",
            m: "El aula está desorganizada y sucia. Los materiales didácticos no están guardados y no son accesibles.",
            seleccion: ""
        },
        {
            criterio: "Relación con los estudiantes",
            exc: "Establece relaciones de comunicación respetuosa y asertiva con todos los estudiantes.",
            mb: "Establece algunas relaciones de comunicación respetuosa con algunos estudiantes.",
            b: "Establece muy pocas relaciones de comunicación respetuosa con los estudiantes.",
            m: "No establece relaciones positivas con los estudiantes.",
            seleccion: ""
        },
        {
            criterio: "Ambiente en el aula",
            exc: "Crea un ambiente de aula seguro y acogedor donde todos los estudiantes se sienten valorados y respetados.",
            mb: "Crea un ambiente de aula generalmente seguro y acogedor donde la mayoría de los estudiantes se sienten valorados y respetados.",
            b: "El ambiente de aula puede ser un poco tenso o no acogedor para algunos estudiantes.",
            m: "El ambiente de aula es tenso y no acogedor para la mayoría de los estudiantes.",
            seleccion: ""
        }
    ];

    const plantillasIniciales = [
        {
            id: 1,
            titulo: "Descripción de la rúbrica",
            descripcion: "Evaluación del desempeño del docente.",
            anio: "2026",
            activo: true,
            borrador: false,
            datos: {
                docente: "",
                seccion: "",
                curso: "2026",
                periodo: "I Periodo",
                anio: "2026",
                tituloBanner: "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO",
                filas: JSON.parse(JSON.stringify(criteriosBase))
            }
        },
        {
            id: 2,
            titulo: "Descripción de la rúbrica",
            descripcion: "Evaluación del desempeño del docente.",
            anio: "2023",
            activo: false,
            borrador: false,
            datos: {
                docente: "",
                seccion: "",
                curso: "2023",
                periodo: "II Periodo",
                anio: "2023",
                tituloBanner: "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO",
                filas: JSON.parse(JSON.stringify(criteriosBase))
            }
        }
    ];

    let listaPlantillas = [];
    let idPlantillaActual = null;
    let indiceAEliminar = null;
    let htmlMigasBase = "";

    function abrirEditorPlantilla(id = null) {
        idPlantillaActual = id;

        document.getElementById('vista-lista-plantillas').style.display = 'none';
        document.getElementById('vista-editor-plantilla').style.display = 'flex';

        const tituloEditor = id !== null ? "Editar plantilla" : "Crear plantilla";
        document.getElementById('titulo-editor-principal').textContent = tituloEditor;

        const navMigas = document.getElementById('migas-pan-dinamicas');
        if (!htmlMigasBase) {
            htmlMigasBase = navMigas.innerHTML;
        }
        const temp = document.createElement('div');
        temp.innerHTML = htmlMigasBase;
        const ultimoSpan = temp.querySelector('.miga-actual');
        if (ultimoSpan) {
            const enlaceVolver = document.createElement('a');
            enlaceVolver.href = "plantilla_evaluacion.php";
            enlaceVolver.textContent = ultimoSpan.textContent;
            enlaceVolver.setAttribute('onclick', 'volverALista(); return false;');
            ultimoSpan.replaceWith(enlaceVolver);
        }
        navMigas.innerHTML = temp.innerHTML + ` <span>/</span> <span class="miga-actual">${tituloEditor}</span>`;

        const tbody = document.getElementById('cuerpo-criterios');
        tbody.innerHTML = "";

        if (id !== null) {
            const encontrada = listaPlantillas.find(p => p.id === id);
            if (encontrada && encontrada.datos) {
                document.getElementById('rubrica-docente').value = encontrada.datos.docente || "";
                document.getElementById('rubrica-seccion').value = encontrada.datos.seccion || "";
                document.getElementById('rubrica-curso').value   = encontrada.datos.curso || "";
                document.getElementById('rubrica-periodo').value = encontrada.datos.periodo || "";
                document.getElementById('rubrica-anio').value    = encontrada.datos.anio || encontrada.anio || "2026";
                document.getElementById('rubrica-titulo-banner').value = encontrada.datos.tituloBanner || "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO";

                const filas = (encontrada.datos.filas && encontrada.datos.filas.length > 0) ? encontrada.datos.filas : criteriosBase;
                filas.forEach(f => agregarFilaCriterio({
                    criterio: f.criterio,
                    exc: f.exc,
                    mb: f.mb,
                    b: f.b,
                    m: f.m,
                    seleccion: ""
                }));
                recalcularTotales();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
        }

        document.getElementById('rubrica-docente').value = "";
        document.getElementById('rubrica-seccion').value = "";
        document.getElementById('rubrica-curso').value   = "";
        document.getElementById('rubrica-periodo').value = "";
        document.getElementById('rubrica-anio').value    = "2026";
        document.getElementById('rubrica-titulo-banner').value = "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO";

        criteriosBase.forEach(c => agregarFilaCriterio(c));
        recalcularTotales();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function volverALista() {
        idPlantillaActual = null;
        document.getElementById('vista-editor-plantilla').style.display = 'none';
        document.getElementById('vista-lista-plantillas').style.display = 'flex';
        if (htmlMigasBase) {
            document.getElementById('migas-pan-dinamicas').innerHTML = htmlMigasBase;
        }

        renderizarPlantillas();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function renderizarPlantillas() {
        const contenedor = document.getElementById('contenedor-plantillas');
        contenedor.innerHTML = "";

        if (listaPlantillas.length === 0) {
            contenedor.innerHTML = `<div class="sin-plantillas">Aún no hay plantillas creadas. Haz clic en <strong>+ Crear plantilla</strong> para empezar.</div>`;
            return;
        }

        listaPlantillas.forEach((item, index) => {
            const tarjeta = document.createElement('div');
            tarjeta.className = "tarjeta-plantilla";
            tarjeta.innerHTML = `
                <table class="tabla-plantilla-card">
                    <thead>
                        <tr>
                            <th class="col-nombre-th">Nombre</th>
                            <th class="col-anio-th">Año escolar</th>
                            <th class="col-estado-th">Estado</th>
                            <th class="col-acciones-th">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="celda-descripcion">
                                <strong>${item.titulo} ${item.borrador ? '<span class="badge-borrador">Borrador</span>' : ''}</strong>
                                <p>${item.descripcion}</p>
                            </td>
                            <td class="celda-anio">${item.anio}</td>
                            <td class="celda-estado">
                                <label class="switch-plantilla" title="${item.activo ? 'Plantilla activa' : 'Plantilla inactiva'}">
                                    <input type="checkbox" ${item.activo ? 'checked' : ''} onchange="cambiarEstadoPlantilla(${index}, this.checked)">
                                    <span class="slider-plantilla"></span>
                                </label>
                            </td>
                            <td class="celda-acciones">
                                <button type="button" class="btn-accion-icono btn-editar" onclick="abrirEditorPlantilla(${item.id})" title="Editar plantilla">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn-accion-icono btn-borrar" onclick="pedirEliminar(${index})" title="Eliminar plantilla">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            `;
            contenedor.appendChild(tarjeta);
        });
    }

    function cambiarEstadoPlantilla(index, estado) {
        listaPlantillas[index].activo = estado;
        localStorage.setItem('plantillas_cedes', JSON.stringify(listaPlantillas));
    }

    function pedirEliminar(index) {
        indiceAEliminar = index;
        abrirModal('modal-eliminar');
    }

    function confirmarEliminarPlantilla() {
        if (indiceAEliminar !== null) {
            listaPlantillas.splice(indiceAEliminar, 1);
            localStorage.setItem('plantillas_cedes', JSON.stringify(listaPlantillas));
            renderizarPlantillas();
            indiceAEliminar = null;
        }
        cerrarModal('modal-eliminar');
    }

    function agregarFilaCriterio(datos = { criterio: "Nuevo criterio", exc: "", mb: "", b: "", m: "", seleccion: "" }) {
        const tbody = document.getElementById('cuerpo-criterios');
        const tr = document.createElement('tr');
        const ptsInicial = datos.seleccion || "";

        tr.innerHTML = `
            <td class="celda-criterio-azul">
                <textarea class="input-criterio-nombre" placeholder="Nombre del criterio...">${datos.criterio}</textarea>
                <button type="button" class="btn-quitar-fila" onclick="eliminarFilaCriterio(this)" title="Eliminar criterio">×</button>
            </td>
            <td class="celda-nivel"><textarea class="celda-input" placeholder="Descripción Excelente...">${datos.exc}</textarea></td>
            <td class="celda-nivel"><textarea class="celda-input" placeholder="Descripción Muy Bueno...">${datos.mb}</textarea></td>
            <td class="celda-nivel"><textarea class="celda-input" placeholder="Descripción Bueno...">${datos.b}</textarea></td>
            <td class="celda-nivel"><textarea class="celda-input" placeholder="Descripción Insuficiente...">${datos.m}</textarea></td>
            <td class="celda-seleccion">
                <select class="select-nivel-rubrica" onchange="actualizarPuntajeFila(this)">
                    <option value="" ${ptsInicial === "" ? "selected" : ""}></option>
                    <option value="4" ${ptsInicial === "4" ? "selected" : ""}>Excelente</option>
                    <option value="3" ${ptsInicial === "3" ? "selected" : ""}>Muy Bueno</option>
                    <option value="2" ${ptsInicial === "2" ? "selected" : ""}>Bueno</option>
                    <option value="1" ${ptsInicial === "1" ? "selected" : ""}>Insuficiente</option>
                </select>
            </td>
            <td class="celda-puntaje-obtenido">
                <input type="number" class="input-puntaje-celda" min="0" max="4" value="${ptsInicial}" oninput="recalcularTotales()">
            </td>
        `;
        tbody.appendChild(tr);
        recalcularTotales();
    }

    function eliminarFilaCriterio(boton) {
        const filas = document.querySelectorAll('#cuerpo-criterios tr');
        if (filas.length <= 1) return;
        boton.closest('tr').remove();
        recalcularTotales();
    }

    function actualizarPuntajeFila(select) {
        const fila = select.closest('tr');
        const inputPts = fila.querySelector('.input-puntaje-celda');
        inputPts.value = select.value;
        recalcularTotales();
    }

    function recalcularTotales() {
        const inputs = document.querySelectorAll('.input-puntaje-celda');
        let suma = 0;
        let hayAlgunValor = false;
        let maximoPosible = inputs.length * 4;

        inputs.forEach(inp => {
            if (inp.value !== "") {
                const val = parseFloat(inp.value);
                if (!isNaN(val)) {
                    suma += val;
                    hayAlgunValor = true;
                }
            }
        });

        const totalEl = document.getElementById('total-puntos');
        const celdaInterp = document.getElementById('texto-interpretacion');

        if (!hayAlgunValor) {
            totalEl.textContent = "";
            celdaInterp.textContent = "";
            return;
        }

        totalEl.textContent = suma;

        const porcentaje = maximoPosible > 0 ? (suma / maximoPosible) * 100 : 0;
        if (porcentaje >= 85) {
            celdaInterp.textContent = "Desempeño Excelente";
        } else if (porcentaje >= 70) {
            celdaInterp.textContent = "Desempeño Muy Bueno";
        } else if (porcentaje >= 50) {
            celdaInterp.textContent = "Desempeño Bueno";
        } else {
            celdaInterp.textContent = "Desempeño Insuficiente";
        }
    }

    function guardarPlantilla(esBorrador) {
        const docente = document.getElementById('rubrica-docente').value.trim();
        const seccion = document.getElementById('rubrica-seccion').value.trim();
        const curso   = document.getElementById('rubrica-curso').value.trim();
        const periodo = document.getElementById('rubrica-periodo').value.trim();
        const anio    = document.getElementById('rubrica-anio').value.trim() || "2026";
        const tituloBanner = document.getElementById('rubrica-titulo-banner').value.trim();

        const filasDom = document.querySelectorAll('#cuerpo-criterios tr');
        const filasDatos = [];

        filasDom.forEach(tr => {
            const crit = tr.querySelector('.input-criterio-nombre').value;
            const textos = tr.querySelectorAll('.celda-input');

            filasDatos.push({
                criterio: crit,
                exc: textos[0].value,
                mb: textos[1].value,
                b: textos[2].value,
                m: textos[3].value,
                seleccion: "",
                pts: ""
            });
        });

        const tituloCard = docente ? `Rúbrica - ${docente}` : "Descripción de la rúbrica";
        const descCard   = periodo ? `Evaluación del desempeño del docente (${periodo}).` : "Evaluación del desempeño del docente.";

        const objetoPlantilla = {
            id: idPlantillaActual !== null ? idPlantillaActual : Date.now(),
            titulo: tituloCard,
            descripcion: descCard,
            anio: anio,
            activo: !esBorrador,
            borrador: esBorrador,
            datos: { docente, seccion, curso, periodo, anio, tituloBanner, filas: filasDatos }
        };

        if (idPlantillaActual !== null) {
            const idx = listaPlantillas.findIndex(p => p.id === idPlantillaActual);
            if (idx !== -1) {
                listaPlantillas[idx] = objetoPlantilla;
            } else {
                listaPlantillas.unshift(objetoPlantilla);
            }
        } else {
            listaPlantillas.unshift(objetoPlantilla);
        }

        localStorage.setItem('plantillas_cedes', JSON.stringify(listaPlantillas));
        volverALista();
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
        htmlMigasBase = document.getElementById('migas-pan-dinamicas').innerHTML;

        const guardadas = localStorage.getItem('plantillas_cedes');
        if (guardadas) {
            listaPlantillas = JSON.parse(guardadas);
        } else {
            listaPlantillas = plantillasIniciales;
            localStorage.setItem('plantillas_cedes', JSON.stringify(listaPlantillas));
        }
        renderizarPlantillas();
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-eliminar');
        }
    });
</script>

</body>
</html>