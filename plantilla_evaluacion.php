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

date_default_timezone_set('America/Costa_Rica');
$anio_escolar = date('Y');
$mes_actual = (int)date('n');
$periodo_mep_auto = ($mes_actual >= 2 && $mes_actual <= 7) ? "I Periodo" : "II Periodo";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantillas de Evaluación - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=60">
    <link rel="stylesheet" href="plantilla_evaluacion.css?v=30">
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

        <!-- ================= VISTA 1: LISTA DE BLOQUES DE PLANTILLAS ================= -->
        <div id="vista-lista-plantillas" class="vista-bloque">
            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold">Plantillas de evaluación</h1>
                    <p class="subtitulo-banner">Configura, edita y activa las rúbricas oficiales de observación docente para cada periodo escolar.</p>
                </div>

                <div class="banner-der">
                    <button type="button" class="btn-crear-plantilla-verde" onclick="abrirEditorNuevaPlantilla()">
                        <span>+</span>
                        <span>Crear plantilla</span>
                    </button>
                </div>
            </header>

            <div class="lista-bloques-plantillas" id="lista-bloques-plantillas"></div>
        </div>

        <!-- ================= VISTA 2: EDITOR DE RÚBRICA (TODO EDITABLE DE FORMA UNIFORME) ================= -->
        <div id="vista-editor-plantilla" class="vista-bloque" style="display: none;">
            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold" id="titulo-editor-plantilla">Editar plantilla</h1>
                    <p class="subtitulo-banner">Edita libremente cualquier campo, criterio, puntaje o descripción de la rúbrica institucional.</p>
                </div>

                <div class="banner-der">
                    <button type="button" class="btn-volver-plantillas" onclick="volverAListaPlantillas()">
                        <span>← Volver a plantillas</span>
                    </button>

                    <button type="button" class="btn-agregar-criterio" onclick="agregarFilaCriterio()">
                        <span>+ Agregar criterio</span>
                    </button>
                </div>
            </header>

            <!-- DATOS GENERALES DE LA TARJETA EN LA LISTA -->
            <div class="barra-datos-tarjeta-plantilla">
                <div class="campo-inline-tarjeta">
                    <label for="input-nombre-tarjeta">Nombre en lista:</label>
                    <input type="text" id="input-nombre-tarjeta" placeholder="Ej. Rúbrica - Randall Ocón">
                </div>
                <div class="campo-inline-tarjeta">
                    <label for="input-desc-tarjeta">Subtítulo en lista:</label>
                    <input type="text" id="input-desc-tarjeta" placeholder="Ej. Evaluación del desempeño del docente (I Periodo).">
                </div>
            </div>

            <!-- TABLA DE RÚBRICA CON ENCABEZADOS UNIFORMES Y EDITABLES AL TOCAR -->
            <div class="contenedor-tabla-rubrica-editor">
                <table class="tabla-rubrica-editable">
                    <thead>
                        <!-- Fila 1 Encabezado institucional editable -->
                        <tr class="fila-meta-editor">
                            <th colspan="2" class="celda-meta-ed">
                                <div class="inline-meta-ed">
                                    <span>Docente:</span>
                                    <input type="text" id="ed-meta-docente" value="Randall Ocón">
                                </div>
                            </th>
                            <th colspan="2" class="celda-meta-ed">
                                <div class="inline-meta-ed">
                                    <span>Sección:</span>
                                    <input type="text" id="ed-meta-seccion" value="12-2">
                                </div>
                            </th>
                            <th rowspan="2" colspan="2" class="celda-anio-ed">
                                <div class="inline-anio-ed">
                                    <span>Año:</span>
                                    <input type="text" id="ed-meta-anio" value="2027">
                                </div>
                            </th>
                            <th rowspan="2" class="celda-logo-ed">
                                <img src="logo_black.png" onerror="this.src='logo_white.png'; this.style.filter='invert(1)';" alt="CEDES Don Bosco" class="logo-negro-editor">
                            </th>
                        </tr>

                        <!-- Fila 2 Encabezado institucional editable -->
                        <tr class="fila-meta-editor">
                            <th colspan="2" class="celda-meta-ed">
                                <div class="inline-meta-ed">
                                    <span>Curso lectivo:</span>
                                    <input type="text" id="ed-meta-curso" value="2027">
                                </div>
                            </th>
                            <th colspan="2" class="celda-meta-ed">
                                <div class="inline-meta-ed">
                                    <span>Periodo:</span>
                                    <input type="text" id="ed-meta-periodo" value="I Periodo">
                                </div>
                            </th>
                        </tr>

                        <!-- Franja amarilla título institucional editable -->
                        <tr class="fila-amarilla-editor">
                            <th colspan="7">
                                <input type="text" id="ed-titulo-rubrica" value="RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO">
                            </th>
                        </tr>

                        <!-- FILA AZUL UNIFORME: TOCA DIRECTAMENTE SOBRE CUALQUIERA PARA EDITAR -->
                        <tr class="fila-columnas-editor">
                            <th class="th-criterio-ed">
                                <input type="text" id="ed-col-criterio" class="input-encabezado-nivel" value="Criterio" title="Haz clic para editar">
                            </th>
                            <th class="th-nivel-ed">
                                <input type="text" id="ed-nivel-0" class="input-encabezado-nivel" value="Excelente (4 puntos)" title="Haz clic para editar nombre y puntaje">
                            </th>
                            <th class="th-nivel-ed">
                                <input type="text" id="ed-nivel-1" class="input-encabezado-nivel" value="Muy Bueno (3 puntos)" title="Haz clic para editar nombre y puntaje">
                            </th>
                            <th class="th-nivel-ed">
                                <input type="text" id="ed-nivel-2" class="input-encabezado-nivel" value="Bueno (2 puntos)" title="Haz clic para editar nombre y puntaje">
                            </th>
                            <th class="th-nivel-ed">
                                <input type="text" id="ed-nivel-3" class="input-encabezado-nivel" value="Insuficiente (1 punto)" title="Haz clic para editar nombre y puntaje">
                            </th>
                            <th class="th-seleccion-ed">Selección</th>
                            <th class="th-puntaje-ed">Puntaje obtenido</th>
                        </tr>
                    </thead>

                    <tbody id="tbody-criterios-editor"></tbody>
                </table>
            </div>

            <!-- BOTONES INFERIORES DEL EDITOR -->
            <div class="barra-acciones-editor">
                <button type="button" class="btn-cancelar-editor" onclick="volverAListaPlantillas()">Cancelar</button>
                <button type="button" class="btn-guardar-plantilla-verde" onclick="guardarPlantillaCompleta()">Guardar plantilla</button>
            </div>
        </div>

    </main>
</div>

<!-- MODAL: CONFIRMACIÓN DE PLANTILLA GUARDADA -->
<div class="fondo-modal" id="modal-plantilla-guardada" onclick="cerrarSiFuera(event, 'modal-plantilla-guardada')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-plantilla-guardada')" aria-label="Cerrar">
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

        <h2>Plantilla guardada</h2>
        <p id="texto-plantilla-guardada">Los cambios de la rúbrica y sus puntajes se han guardado correctamente.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarYVolverPlantillas()">Aceptar</button>
        </div>
    </div>
</div>

<!-- MODAL: ELIMINAR PLANTILLA -->
<div class="fondo-modal" id="modal-eliminar-plantilla" onclick="cerrarSiFuera(event, 'modal-eliminar-plantilla')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-eliminar-plantilla')" aria-label="Cerrar">
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

        <h2>¿Deseas eliminar esta plantilla?</h2>
        <p id="texto-eliminar-plantilla">Esta acción eliminará la rúbrica seleccionada de tu lista.</p>

        <div class="botones-modal">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-eliminar-plantilla')">Cancelar</button>
            <button type="button" class="btn-modal-confirmar-btn" onclick="confirmarEliminarPlantilla()">Sí, eliminar</button>
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
            <a href="plantilla_evaluacion.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const criteriosBase = [
        {
            nombre: "Observación de clase",
            descripciones: [
                "Las clases son dinámicas, participativas y promueven el aprendizaje activo.",
                "Las clases son generalmente participativas y promueven el aprendizaje.",
                "Las clases pueden ser un poco pasivas y no siempre promueven el aprendizaje.",
                "Las clases son generalmente pasivas y no promueven el aprendizaje."
            ]
        },
        {
            nombre: "Dominio de los contenidos",
            descripciones: [
                "Demuestra un dominio excepcional del contenido y las estrategias de enseñanza.",
                "Tiene un buen dominio del contenido y las estrategias de enseñanza.",
                "Tiene un dominio básico del contenido y las estrategias de enseñanza.",
                "Tiene un dominio limitado del contenido y las estrategias de enseñanza."
            ]
        },
        {
            nombre: "Planeación",
            descripciones: [
                "Los planes de clase son claros, concisos, están bien organizados y se alinean con los",
                "Los planes de clase son generalmente claros, concisos, están organizados y se alinean con los",
                "Los planes de clase pueden ser un poco confusos y desorganizados. No siempre se alinean",
                "Los planes de clase son confusos y desorganizados. No se alinean con los estándares de"
            ]
        },
        {
            nombre: "Mediación pedagógica",
            descripciones: [
                "Utiliza una variedad de estrategias de enseñanza efectivas para involucrar a todos los estudiantes.",
                "Utiliza algunas estrategias de enseñanza efectivas para involucrar a la mayoría de los",
                "Utiliza pocas estrategias de enseñanza efectivas para involucrar a los estudiantes.",
                "No utiliza estrategias de enseñanza efectivas para involucrar a los estudiantes."
            ]
        },
        {
            nombre: "Metacognición del contenido (retroalimentación)",
            descripciones: [
                "Brinda retroalimentación clara y oportuna a los estudiantes.",
                "Brinda retroalimentación a los estudiantes, pero no siempre es clara u oportuna.",
                "La retroalimentación a los estudiantes es escasa o no es útil.",
                "No brinda retroalimentación a los estudiantes."
            ]
        }
    ];

    const plantillasIniciales = [
        {
            id: "pl_1",
            nombre: "Rúbrica - Randall Ocón",
            descripcion: "Evaluación del desempeño del docente (I Periodo).",
            anio: "2027",
            activa: true,
            metaDocente: "Randall Ocón",
            metaSeccion: "12-2",
            metaPeriodo: "I Periodo",
            tituloRubrica: "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO",
            colCriterio: "Criterio",
            niveles: [
                { nombre: "Excelente", puntos: 4 },
                { nombre: "Muy Bueno", puntos: 3 },
                { nombre: "Bueno", puntos: 2 },
                { nombre: "Insuficiente", puntos: 1 }
            ],
            criterios: JSON.parse(JSON.stringify(criteriosBase))
        },
        {
            id: "pl_2",
            nombre: "Descripción de la rúbrica",
            descripcion: "Evaluación del desempeño del docente.",
            anio: "2026",
            activa: true,
            metaDocente: "Randall Ocón",
            metaSeccion: "12-2",
            metaPeriodo: "II Periodo",
            tituloRubrica: "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO",
            colCriterio: "Criterio",
            niveles: [
                { nombre: "Excelente", puntos: 4 },
                { nombre: "Muy Bueno", puntos: 3 },
                { nombre: "Bueno", puntos: 2 },
                { nombre: "Insuficiente", puntos: 1 }
            ],
            criterios: JSON.parse(JSON.stringify(criteriosBase))
        },
        {
            id: "pl_3",
            nombre: "Descripción de la rúbrica",
            descripcion: "Evaluación del desempeño del docente.",
            anio: "2023",
            activa: false,
            metaDocente: "Randall Ocón",
            metaSeccion: "12-2",
            metaPeriodo: "I Periodo",
            tituloRubrica: "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO",
            colCriterio: "Criterio",
            niveles: [
                { nombre: "Excelente", puntos: 4 },
                { nombre: "Muy Bueno", puntos: 3 },
                { nombre: "Bueno", puntos: 2 },
                { nombre: "Insuficiente", puntos: 1 }
            ],
            criterios: JSON.parse(JSON.stringify(criteriosBase))
        }
    ];

    let listaPlantillas = [];
    let idEditando = null;
    let idEliminando = null;

    function guardarStorage() {
        localStorage.setItem('plantillas_rubrica_cedes_v2', JSON.stringify(listaPlantillas));
    }

    function renderizarListaBloques() {
        const contenedor = document.getElementById('lista-bloques-plantillas');

        contenedor.innerHTML = listaPlantillas.map((pl, idx) => `
            <div class="bloque-tabla-plantilla">
                <table class="tabla-plantilla-individual">
                    <thead>
                        <tr>
                            <th class="col-pl-nombre">Nombre</th>
                            <th class="col-pl-anio">Año escolar</th>
                            <th class="col-pl-estado">Estado</th>
                            <th class="col-pl-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="celda-pl-nombre">
                                <strong>${pl.nombre}</strong>
                                <p>${pl.descripcion}</p>
                            </td>
                            <td class="celda-pl-anio">
                                <strong>${pl.anio}</strong>
                            </td>
                            <td class="celda-pl-estado">
                                <label class="switch-azul-plantilla" title="${pl.activa ? 'Plantilla activa' : 'Plantilla inactiva'}">
                                    <input type="checkbox" ${pl.activa ? 'checked' : ''} onchange="cambiarEstadoSwitch(${idx}, this.checked)">
                                    <span class="slider-azul"></span>
                                </label>
                            </td>
                            <td class="celda-pl-acciones">
                                <div class="grupo-botones-pl">
                                    <button type="button" class="btn-cuadro-editar" onclick="abrirEditorPlantilla('${pl.id}')" title="Editar plantilla">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-cuadro-eliminar" onclick="pedirEliminarPlantilla('${pl.id}')" title="Eliminar plantilla">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `).join('');
    }

    function cambiarEstadoSwitch(indice, estado) {
        listaPlantillas[indice].activa = estado;
        guardarStorage();
    }

    function abrirEditorNuevaPlantilla() {
        idEditando = null;
        document.getElementById('titulo-editor-plantilla').textContent = "Crear plantilla";

        const nueva = {
            nombre: "Descripción de la rúbrica",
            descripcion: "Evaluación del desempeño del docente.",
            anio: "<?php echo $anio_escolar; ?>",
            metaDocente: "Randall Ocón",
            metaSeccion: "12-2",
            metaPeriodo: "<?php echo $periodo_mep_auto; ?>",
            tituloRubrica: "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO",
            colCriterio: "Criterio",
            niveles: [
                { nombre: "Excelente", puntos: 4 },
                { nombre: "Muy Bueno", puntos: 3 },
                { nombre: "Bueno", puntos: 2 },
                { nombre: "Insuficiente", puntos: 1 }
            ],
            criterios: JSON.parse(JSON.stringify(criteriosBase))
        };

        cargarDatosEnTablaEditor(nueva);
        document.getElementById('vista-lista-plantillas').style.display = 'none';
        document.getElementById('vista-editor-plantilla').style.display = 'flex';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function abrirEditorPlantilla(id) {
        const pl = listaPlantillas.find(p => p.id === id);
        if (!pl) return;

        idEditando = id;
        document.getElementById('titulo-editor-plantilla').textContent = "Editar plantilla";

        cargarDatosEnTablaEditor(pl);
        document.getElementById('vista-lista-plantillas').style.display = 'none';
        document.getElementById('vista-editor-plantilla').style.display = 'flex';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function cargarDatosEnTablaEditor(pl) {
        document.getElementById('input-nombre-tarjeta').value = pl.nombre || "";
        document.getElementById('input-desc-tarjeta').value = pl.descripcion || "";
        document.getElementById('ed-meta-docente').value = pl.metaDocente || "Randall Ocón";
        document.getElementById('ed-meta-seccion').value = pl.metaSeccion || "12-2";
        document.getElementById('ed-meta-anio').value = pl.anio || "2027";
        document.getElementById('ed-meta-curso').value = pl.anio || "2027";
        document.getElementById('ed-meta-periodo').value = pl.metaPeriodo || "I Periodo";
        document.getElementById('ed-titulo-rubrica').value = pl.tituloRubrica || "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO";
        document.getElementById('ed-col-criterio').value = pl.colCriterio || "Criterio";

        const niveles = pl.niveles || [
            { nombre: "Excelente", puntos: 4 },
            { nombre: "Muy Bueno", puntos: 3 },
            { nombre: "Bueno", puntos: 2 },
            { nombre: "Insuficiente", puntos: 1 }
        ];

        niveles.forEach((niv, i) => {
            const etiquetaPts = niv.puntos === 1 ? "punto" : "puntos";
            document.getElementById(`ed-nivel-${i}`).value = `${niv.nombre} (${niv.puntos} ${etiquetaPts})`;
        });

        const tbody = document.getElementById('tbody-criterios-editor');
        tbody.innerHTML = "";
        (pl.criterios || criteriosBase).forEach(crit => agregarFilaCriterio(crit));
    }

    function agregarFilaCriterio(datos = null) {
        const tbody = document.getElementById('tbody-criterios-editor');
        const tr = document.createElement('tr');

        const nombreCrit = datos ? datos.nombre : "Nuevo criterio";
        const d0 = datos ? datos.descripciones[0] : "Descripción para Excelente.";
        const d1 = datos ? datos.descripciones[1] : "Descripción para Muy Bueno.";
        const d2 = datos ? datos.descripciones[2] : "Descripción para Bueno.";
        const d3 = datos ? datos.descripciones[3] : "Descripción para Insuficiente.";

        tr.innerHTML = `
            <td class="td-criterio-ed">
                <textarea rows="3" class="txt-criterio-nombre">${nombreCrit}</textarea>
            </td>
            <td class="td-desc-ed">
                <textarea rows="3" class="txt-desc-0">${d0}</textarea>
            </td>
            <td class="td-desc-ed">
                <textarea rows="3" class="txt-desc-1">${d1}</textarea>
            </td>
            <td class="td-desc-ed">
                <textarea rows="3" class="txt-desc-2">${d2}</textarea>
            </td>
            <td class="td-desc-ed">
                <textarea rows="3" class="txt-desc-3">${d3}</textarea>
            </td>
            <td class="td-seleccion-ed">
                <span class="flecha-preview">⌄</span>
            </td>
            <td class="td-puntaje-ed">
                <button type="button" class="btn-eliminar-fila-ed" onclick="eliminarFilaCriterio(this)" title="Eliminar criterio">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function eliminarFilaCriterio(btn) {
        const tbody = document.getElementById('tbody-criterios-editor');
        if (tbody.children.length <= 1) return;
        btn.closest('tr').remove();
    }

    function guardarPlantillaCompleta() {
        const nombreTarjeta = document.getElementById('input-nombre-tarjeta').value.trim() || "Descripción de la rúbrica";
        const descTarjeta = document.getElementById('input-desc-tarjeta').value.trim() || "Evaluación del desempeño del docente.";
        const anioVal = document.getElementById('ed-meta-anio').value.trim() || "<?php echo $anio_escolar; ?>";

        const nivelesEditados = [0, 1, 2, 3].map(i => {
            const textoCompleto = document.getElementById(`ed-nivel-${i}`).value.trim();
            const match = textoCompleto.match(/^(.*?)\s*\(\s*(\d+)\s*puntos?\s*\)$/i);
            if (match) {
                return { nombre: match[1].trim(), puntos: Number(match[2]) };
            }
            const soloNumero = textoCompleto.match(/(\d+)/);
            return {
                nombre: textoCompleto.replace(/\(.*?\)/g, '').trim() || `Nivel ${i + 1}`,
                puntos: soloNumero ? Number(soloNumero[1]) : 0
            };
        });

        const filas = Array.from(document.querySelectorAll('#tbody-criterios-editor tr'));
        const criteriosEditados = filas.map(tr => ({
            nombre: tr.querySelector('.txt-criterio-nombre').value.trim() || "Criterio",
            descripciones: [
                tr.querySelector('.txt-desc-0').value.trim(),
                tr.querySelector('.txt-desc-1').value.trim(),
                tr.querySelector('.txt-desc-2').value.trim(),
                tr.querySelector('.txt-desc-3').value.trim()
            ]
        }));

        const objetoPlantilla = {
            id: idEditando || ('pl_' + Date.now()),
            nombre: nombreTarjeta,
            descripcion: descTarjeta,
            anio: anioVal,
            activa: true,
            metaDocente: document.getElementById('ed-meta-docente').value.trim(),
            metaSeccion: document.getElementById('ed-meta-seccion').value.trim(),
            metaPeriodo: document.getElementById('ed-meta-periodo').value.trim(),
            tituloRubrica: document.getElementById('ed-titulo-rubrica').value.trim(),
            colCriterio: document.getElementById('ed-col-criterio').value.trim(),
            niveles: nivelesEditados,
            criterios: criteriosEditados
        };

        if (idEditando) {
            const idx = listaPlantillas.findIndex(p => p.id === idEditando);
            if (idx !== -1) {
                objetoPlantilla.activa = listaPlantillas[idx].activa;
                listaPlantillas[idx] = objetoPlantilla;
            }
        } else {
            listaPlantillas.unshift(objetoPlantilla);
        }

        guardarStorage();
        abrirModal('modal-plantilla-guardada');
    }

    function volverAListaPlantillas() {
        document.getElementById('vista-editor-plantilla').style.display = 'none';
        document.getElementById('vista-lista-plantillas').style.display = 'flex';
        idEditando = null;
        renderizarListaBloques();
    }

    function cerrarYVolverPlantillas() {
        cerrarModal('modal-plantilla-guardada');
        volverAListaPlantillas();
    }

    function pedirEliminarPlantilla(id) {
        const pl = listaPlantillas.find(p => p.id === id);
        if (!pl) return;
        idEliminando = id;
        document.getElementById('texto-eliminar-plantilla').innerHTML =
            `¿Estás seguro de eliminar <strong>${pl.nombre}</strong> (${pl.anio})?`;
        abrirModal('modal-eliminar-plantilla');
    }

    function confirmarEliminarPlantilla() {
        if (idEliminando) {
            listaPlantillas = listaPlantillas.filter(p => p.id !== idEliminando);
            guardarStorage();
            renderizarListaBloques();
            idEliminando = null;
        }
        cerrarModal('modal-eliminar-plantilla');
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
        const guardadas = localStorage.getItem('plantillas_rubrica_cedes_v2');
        if (guardadas) {
            listaPlantillas = JSON.parse(guardadas);
        } else {
            listaPlantillas = JSON.parse(JSON.stringify(plantillasIniciales));
            guardarStorage();
        }
        renderizarListaBloques();
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-plantilla-guardada');
            cerrarModal('modal-eliminar-plantilla');
        }
    });
</script>

</body>
</html>