<?php
session_start();

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
$titulo_miga = "Evaluación";

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
    <title>Evaluar Docente - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=57">
    <link rel="stylesheet" href="evaluar.css?v=35">
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
    <main class="contenido contenido-evaluar">

        <?php include 'breadcrumbs.php'; ?>

        <div id="vista-lista-docentes" class="vista-bloque">
            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold">Evaluar Docente</h1>
                    <p class="subtitulo-banner">Busca y selecciona un docente a cargo para revisar su planeamiento y aplicar la rúbrica vigente.</p>
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
                    <input type="text" id="input-buscar-eval" placeholder="Buscar docente por cédula o nombre..." oninput="renderizarDocentesEvaluar()">
                </div>

                <div class="acciones-derecha">
                    <select id="select-materia-eval" class="select-filtro" onchange="renderizarDocentesEvaluar()">
                        <option value="">Todas las materias</option>
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
            </div>

            <div class="grid-docentes-evaluar" id="grid-docentes-evaluar"></div>
        </div>

        <div id="vista-pantalla-dividida" class="vista-bloque" style="display: none;">
            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold">Evaluación y revisión</h1>
                    <p class="subtitulo-banner">Haz clic directamente sobre la casilla correspondiente en cada criterio para asignar el puntaje.</p>
                </div>

                <div class="banner-der">
                    <div class="grupo-seccion-rapida">
                        <label for="input-seccion-docente">Sección:</label>
                        <input type="text" id="input-seccion-docente" value="12-2" oninput="actualizarSeccionRubrica()">
                    </div>

                    <button type="button" class="btn-volver-docentes" onclick="volverAListaDocentes()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Volver a docentes</span>
                    </button>
                </div>
            </header>

            <section class="split-screen-evaluacion">

                <!-- MITAD IZQUIERDA: VISOR DE PLANEAMIENTO PDF REAL -->
                <div class="panel-mitad panel-planeamiento">
                    <div class="caja-interna-pdf">
                        <div class="barra-archivo-pdf">
                            <div class="nombre-pdf-izq">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                <span id="visor-nombre-pdf">Planeamiento.pdf</span>
                            </div>

                            <a id="btn-descargar-pdf" href="#" download="Planeamiento.pdf" class="btn-descargar-pdf" title="Descargar planeamiento PDF">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                            </a>
                        </div>

                        <div class="contenedor-visor-real">
                            <iframe id="iframe-pdf-real" title="Visor de Planeamiento PDF"></iframe>
                            <div class="mensaje-sin-pdf-real" id="mensaje-sin-pdf-real" style="display: none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <p><strong>Archivo registrado:</strong> <span id="texto-archivo-muestra"></span></p>
                                <span>Este docente aún no tiene un archivo PDF cargado desde el módulo de Docentes. Edítalo en "Docentes" y sube su PDF para verlo aquí.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MITAD DERECHA: RÚBRICA CLIQUEABLE -->
                <div class="panel-mitad panel-rubrica">
                    <div class="scroll-rubrica-horizontal">
                        <table class="tabla-rubrica-oficial" id="tabla-rubrica-click">
                            <thead>
                                <tr class="fila-meta-rubrica">
                                    <th colspan="2" class="celda-meta">
                                        <span>Docente:</span> <strong id="rubrica-docente-nombre"></strong>
                                    </th>
                                    <th colspan="2" class="celda-meta">
                                        <span>Sección:</span> <strong id="rubrica-docente-seccion">12-2</strong>
                                    </th>
                                    <th rowspan="2" colspan="2" class="celda-anio">
                                        Año: <strong><?php echo $anio_escolar; ?></strong>
                                    </th>
                                    <th rowspan="2" class="celda-logo-rubrica">
                                        <img src="logo_black.png" onerror="this.src='logo_white.png'; this.style.filter='invert(1)';" alt="CEDES Don Bosco" class="logo-negro-rubrica">
                                    </th>
                                </tr>
                                <tr class="fila-meta-rubrica">
                                    <th colspan="2" class="celda-meta">
                                        <span>Curso lectivo:</span> <strong><?php echo $anio_escolar; ?></strong>
                                    </th>
                                    <th colspan="2" class="celda-meta">
                                        <span>Periodo:</span> <strong><?php echo $periodo_mep_auto; ?></strong>
                                    </th>
                                </tr>
                                <tr class="fila-titulo-amarilla">
                                    <th colspan="7">RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO</th>
                                </tr>
                                <tr class="fila-columnas-rubrica">
                                    <th>Criterio</th>
                                    <th>Excelente (4 puntos)</th>
                                    <th>Muy Bueno (3 puntos)</th>
                                    <th>Bueno (2 puntos)</th>
                                    <th>Insuficiente (1 punto)</th>
                                    <th>Selección</th>
                                    <th>Puntaje obtenido</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-rubrica-cliqueable">
                                <tr data-criterio="0">
                                    <td class="col-criterio">Observación de clase</td>
                                    <td class="celda-opcion" data-nivel="Excelente" data-puntos="4" onclick="seleccionarCeldaRubrica(this)">Las clases son dinámicas, participativas y promueven el aprendizaje activo.</td>
                                    <td class="celda-opcion" data-nivel="Muy Bueno" data-puntos="3" onclick="seleccionarCeldaRubrica(this)">Las clases son generalmente participativas y promueven el aprendizaje.</td>
                                    <td class="celda-opcion" data-nivel="Bueno" data-puntos="2" onclick="seleccionarCeldaRubrica(this)">Las clases pueden ser un poco pasivas y no siempre promueven el aprendizaje.</td>
                                    <td class="celda-opcion" data-nivel="Insuficiente" data-puntos="1" onclick="seleccionarCeldaRubrica(this)">Las clases son generalmente pasivas y no promueven el aprendizaje.</td>
                                    <td class="col-seleccion-auto" id="sel-crit-0">—</td>
                                    <td class="col-puntaje-auto" id="pts-crit-0">0 pts</td>
                                </tr>

                                <tr data-criterio="1">
                                    <td class="col-criterio">Dominio de los contenidos</td>
                                    <td class="celda-opcion" data-nivel="Excelente" data-puntos="4" onclick="seleccionarCeldaRubrica(this)">Demuestra un dominio excepcional del contenido y las estrategias de enseñanza.</td>
                                    <td class="celda-opcion" data-nivel="Muy Bueno" data-puntos="3" onclick="seleccionarCeldaRubrica(this)">Tiene un buen dominio del contenido y las estrategias de enseñanza.</td>
                                    <td class="celda-opcion" data-nivel="Bueno" data-puntos="2" onclick="seleccionarCeldaRubrica(this)">Tiene un dominio básico del contenido y las estrategias de enseñanza.</td>
                                    <td class="celda-opcion" data-nivel="Insuficiente" data-puntos="1" onclick="seleccionarCeldaRubrica(this)">Tiene un dominio limitado del contenido y las estrategias de enseñanza.</td>
                                    <td class="col-seleccion-auto" id="sel-crit-1">—</td>
                                    <td class="col-puntaje-auto" id="pts-crit-1">0 pts</td>
                                </tr>

                                <tr data-criterio="2">
                                    <td class="col-criterio">Planeación</td>
                                    <td class="celda-opcion" data-nivel="Excelente" data-puntos="4" onclick="seleccionarCeldaRubrica(this)">Los planes de clase son claros, concisos, están bien organizados y se alinean con los objetivos.</td>
                                    <td class="celda-opcion" data-nivel="Muy Bueno" data-puntos="3" onclick="seleccionarCeldaRubrica(this)">Los planes de clase son generalmente claros, concisos, están organizados y se alinean con los objetivos.</td>
                                    <td class="celda-opcion" data-nivel="Bueno" data-puntos="2" onclick="seleccionarCeldaRubrica(this)">Los planes de clase pueden ser un poco confusos y desorganizados. No siempre se alinean.</td>
                                    <td class="celda-opcion" data-nivel="Insuficiente" data-puntos="1" onclick="seleccionarCeldaRubrica(this)">Los planes de clase son confusos y desorganizados. No se alinean con los estándares.</td>
                                    <td class="col-seleccion-auto" id="sel-crit-2">—</td>
                                    <td class="col-puntaje-auto" id="pts-crit-2">0 pts</td>
                                </tr>

                                <tr data-criterio="3">
                                    <td class="col-criterio">Mediación pedagógica</td>
                                    <td class="celda-opcion" data-nivel="Excelente" data-puntos="4" onclick="seleccionarCeldaRubrica(this)">Utiliza una variedad de estrategias de enseñanza efectivas para involucrar a todos los estudiantes.</td>
                                    <td class="celda-opcion" data-nivel="Muy Bueno" data-puntos="3" onclick="seleccionarCeldaRubrica(this)">Utiliza algunas estrategias de enseñanza efectivas para involucrar a la mayoría de los estudiantes.</td>
                                    <td class="celda-opcion" data-nivel="Bueno" data-puntos="2" onclick="seleccionarCeldaRubrica(this)">Utiliza pocas estrategias de enseñanza efectivas para involucrar a los estudiantes.</td>
                                    <td class="celda-opcion" data-nivel="Insuficiente" data-puntos="1" onclick="seleccionarCeldaRubrica(this)">No utiliza estrategias de enseñanza efectivas para involucrar a los estudiantes.</td>
                                    <td class="col-seleccion-auto" id="sel-crit-3">—</td>
                                    <td class="col-puntaje-auto" id="pts-crit-3">0 pts</td>
                                </tr>

                                <tr data-criterio="4">
                                    <td class="col-criterio">Metacognición del contenido (retroalimentación)</td>
                                    <td class="celda-opcion" data-nivel="Excelente" data-puntos="4" onclick="seleccionarCeldaRubrica(this)">Brinda retroalimentación clara y oportuna a los estudiantes.</td>
                                    <td class="celda-opcion" data-nivel="Muy Bueno" data-puntos="3" onclick="seleccionarCeldaRubrica(this)">Brinda retroalimentación a los estudiantes, pero no siempre es clara u oportuna.</td>
                                    <td class="celda-opcion" data-nivel="Bueno" data-puntos="2" onclick="seleccionarCeldaRubrica(this)">La retroalimentación a los estudiantes es escasa o no es útil.</td>
                                    <td class="celda-opcion" data-nivel="Insuficiente" data-puntos="1" onclick="seleccionarCeldaRubrica(this)">No brinda retroalimentación a los estudiantes.</td>
                                    <td class="col-seleccion-auto" id="sel-crit-4">—</td>
                                    <td class="col-puntaje-auto" id="pts-crit-4">0 pts</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="fila-total-rubrica">
                                    <td colspan="6" class="celda-label-total">Puntuación total obtenida</td>
                                    <td class="celda-valor-total" id="celda-puntuacion-total">0 / 20 pts</td>
                                </tr>
                                <tr class="fila-interpretacion-rubrica">
                                    <td colspan="6" class="celda-label-interpretacion">Interpretación de la puntuación</td>
                                    <td class="celda-valor-interpretacion" id="celda-interpretacion-texto">Sin evaluar</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </section>

            <section class="bloque-recomendaciones">
                <label for="textarea-recomendaciones">Recomendaciones</label>
                <textarea id="textarea-recomendaciones" rows="3" placeholder="Escribe observaciones o comentarios para el docente..."></textarea>

                <div class="barra-botones-finales">
                    <button type="button" class="btn-limpiar-rubrica" onclick="limpiarSeleccionRubrica()">Limpiar selección</button>
                    <button type="button" class="btn-finalizar-verde" onclick="guardarEvaluacionDocente()">Finalizar evaluación</button>
                </div>
            </section>
        </div>

    </main>
</div>

<!-- MODAL: EVALUACIÓN GUARDADA -->
<div class="fondo-modal" id="modal-eval-guardada" onclick="cerrarSiFuera(event, 'modal-eval-guardada')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-eval-guardada')" aria-label="Cerrar">
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

        <h2>Evaluación guardada</h2>
        <p id="texto-confirmacion-eval">La rúbrica del docente se ha guardado correctamente.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarYVolverLista()">Aceptar</button>
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
            <a href="evaluar.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const docentesBase = [
        { cedula: "100564782", nombre: "Lucía Rodríguez", materia: "Español", pdf: "Planeamiento_Espanol_Lucia.pdf", pdfData: "", activo: true },
        { cedula: "203648647", nombre: "Mario López", materia: "Química", pdf: "Planeamiento_Quimica_Mario.pdf", pdfData: "", activo: true },
        { cedula: "187263944", nombre: "Randall Ocón", materia: "Matemáticas", pdf: "Planeamiento_Mate_Randall.pdf", pdfData: "", activo: true },
        { cedula: "166478230", nombre: "Sharon Mendes", materia: "Música", pdf: "Planeamiento_Musica_Sharon.pdf", pdfData: "", activo: true }
    ];

    let listaDocentes = [];
    let evaluacionesGuardadas = {};
    let docenteEvaluando = null;
    let seleccionesActuales = [null, null, null, null, null];

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

    function renderizarDocentesEvaluar() {
        const contenedor = document.getElementById('grid-docentes-evaluar');
        const texto = document.getElementById('input-buscar-eval').value.toLowerCase().trim();
        const materia = document.getElementById('select-materia-eval').value;

        const filtrados = listaDocentes.filter(doc => {
            const coincideTexto = doc.cedula.toLowerCase().includes(texto) || doc.nombre.toLowerCase().includes(texto);
            const coincideMateria = materia === "" || doc.materia === materia;
            return coincideTexto && coincideMateria;
        });

        if (filtrados.length === 0) {
            contenedor.innerHTML = `<div class="vacio-evaluar">No se encontraron docentes con ese criterio de búsqueda.</div>`;
            return;
        }

        contenedor.innerHTML = filtrados.map(doc => {
            const evalInfo = evaluacionesGuardadas[doc.cedula];
            const porcentajeMostrar = evalInfo ? (evalInfo.porcentaje ?? evalInfo.nota ?? 0) : 0;
            const estadoHtml = evalInfo
                ? `<span class="badge-evaluado">Evaluado (${porcentajeMostrar}%)</span>`
                : `<span class="badge-pendiente-eval">Pendiente</span>`;

            return `
                <article class="card-docente-eval">
                    <div class="card-eval-top">
                        <div>
                            <span class="eval-cedula">Cédula: ${doc.cedula}</span>
                            <h3>${doc.nombre}</h3>
                            <span class="eval-materia">${doc.materia}</span>
                        </div>
                        ${estadoHtml}
                    </div>

                    <div class="card-eval-footer">
                        <span class="archivo-pdf-mini" title="${doc.pdf}">📄 ${doc.pdf}</span>
                        <button type="button" class="btn-aplicar-rubrica" onclick="abrirEvaluacionDividida('${doc.cedula}')">
                            ${evalInfo ? 'Editar evaluación' : 'Evaluar ahora'}
                        </button>
                    </div>
                </article>
            `;
        }).join('');
    }

    function abrirEvaluacionDividida(cedula) {
        const doc = listaDocentes.find(d => d.cedula === cedula);
        if (!doc) return;
        docenteEvaluando = doc;

        document.getElementById('vista-lista-docentes').style.display = 'none';
        document.getElementById('vista-pantalla-dividida').style.display = 'flex';
        window.scrollTo({ top: 0, behavior: 'smooth' });

        document.getElementById('rubrica-docente-nombre').textContent = doc.nombre;

        const nombreArchivo = doc.pdf || "Planeamiento.pdf";
        document.getElementById('visor-nombre-pdf').textContent = nombreArchivo;

        const iframePdf = document.getElementById('iframe-pdf-real');
        const msgSinPdf = document.getElementById('mensaje-sin-pdf-real');
        const btnDescargar = document.getElementById('btn-descargar-pdf');

        if (doc.pdfData && doc.pdfData.startsWith('data:')) {
            iframePdf.src = doc.pdfData;
            iframePdf.style.display = 'block';
            msgSinPdf.style.display = 'none';

            btnDescargar.href = doc.pdfData;
            btnDescargar.download = nombreArchivo;
            btnDescargar.style.pointerEvents = 'auto';
            btnDescargar.style.opacity = '1';
        } else {
            iframePdf.src = '';
            iframePdf.style.display = 'none';
            document.getElementById('texto-archivo-muestra').textContent = nombreArchivo;
            msgSinPdf.style.display = 'flex';

            btnDescargar.href = '#';
            btnDescargar.style.pointerEvents = 'none';
            btnDescargar.style.opacity = '0.45';
        }

        limpiarSeleccionRubrica();
        const previa = evaluacionesGuardadas[cedula];
        if (previa) {
            document.getElementById('input-seccion-docente').value = previa.seccion || "12-2";
            document.getElementById('textarea-recomendaciones').value = previa.recomendaciones || previa.obs || "";
            if (Array.isArray(previa.puntajes)) {
                previa.puntajes.forEach((pts, idx) => {
                    if (pts > 0) {
                        const fila = document.querySelector(`#cuerpo-rubrica-cliqueable tr[data-criterio="${idx}"]`);
                        const celda = fila ? fila.querySelector(`.celda-opcion[data-puntos="${pts}"]`) : null;
                        if (celda) seleccionarCeldaRubrica(celda);
                    }
                });
            }
        }
        actualizarSeccionRubrica();
    }

    function volverAListaDocentes() {
        document.getElementById('vista-pantalla-dividida').style.display = 'none';
        document.getElementById('vista-lista-docentes').style.display = 'flex';
        docenteEvaluando = null;
        renderizarDocentesEvaluar();
    }

    function actualizarSeccionRubrica() {
        const seccion = document.getElementById('input-seccion-docente').value.trim() || "12-2";
        document.getElementById('rubrica-docente-seccion').textContent = seccion;
    }

    function obtenerClaseColorPorPuntos(puntos) {
        if (puntos >= 3) return 'activa-verde';
        if (puntos === 2) return 'activa-amarillo';
        return 'activa-rojo';
    }

    function seleccionarCeldaRubrica(celda) {
        const fila = celda.closest('tr');
        const idxCriterio = Number(fila.dataset.criterio);
        const nivel = celda.dataset.nivel;
        const puntos = Number(celda.dataset.puntos);

        fila.querySelectorAll('.celda-opcion').forEach(c => {
            c.classList.remove('activa-verde', 'activa-amarillo', 'activa-rojo');
        });

        const claseColor = obtenerClaseColorPorPuntos(puntos);
        celda.classList.add(claseColor);
        seleccionesActuales[idxCriterio] = { nivel, puntos };

        document.getElementById(`sel-crit-${idxCriterio}`).textContent = nivel;
        document.getElementById(`pts-crit-${idxCriterio}`).textContent = `${puntos} ${puntos === 1 ? 'pt' : 'pts'}`;

        actualizarPuntuacionFinal();
    }

    function obtenerInterpretacion(total, haySeleccion) {
        if (!haySeleccion) return "Sin evaluar";
        if (total >= 18) return "Excelente (Desempeño sobresaliente)";
        if (total >= 14) return "Muy Bueno (Desempeño esperado)";
        if (total >= 10) return "Bueno (Requiere mejora)";
        return "Insuficiente (Requiere acompañamiento)";
    }

    function actualizarPuntuacionFinal() {
        const total = seleccionesActuales.reduce((suma, item) => suma + (item ? item.puntos : 0), 0);
        const haySeleccion = seleccionesActuales.some(item => item !== null);
        const porcentaje = Math.round((total / 20) * 100);

        document.getElementById('celda-puntuacion-total').textContent = `${total} / 20 pts (${porcentaje}%)`;
        document.getElementById('celda-interpretacion-texto').textContent = obtenerInterpretacion(total, haySeleccion);
    }

    function limpiarSeleccionRubrica() {
        seleccionesActuales = [null, null, null, null, null];
        document.querySelectorAll('#cuerpo-rubrica-cliqueable .celda-opcion').forEach(c => {
            c.classList.remove('activa-verde', 'activa-amarillo', 'activa-rojo');
        });
        for (let i = 0; i < 5; i++) {
            document.getElementById(`sel-crit-${i}`).textContent = "—";
            document.getElementById(`pts-crit-${i}`).textContent = "0 pts";
        }
        document.getElementById('textarea-recomendaciones').value = "";
        actualizarPuntuacionFinal();
    }

    function guardarEvaluacionDocente() {
        if (!docenteEvaluando) return;

        const puntajes = seleccionesActuales.map(item => item ? item.puntos : 0);
        const total = puntajes.reduce((acc, n) => acc + n, 0);
        const porcentaje = Math.round((total / 20) * 100);
        const seccion = document.getElementById('input-seccion-docente').value.trim() || "12-2";
        const recomendaciones = document.getElementById('textarea-recomendaciones').value.trim();

        evaluacionesGuardadas[docenteEvaluando.cedula] = {
            docente: docenteEvaluando.nombre,
            materia: docenteEvaluando.materia,
            seccion,
            puntajes,
            total,
            porcentaje,
            recomendaciones
        };

        localStorage.setItem('evaluaciones_docentes_cedes', JSON.stringify(evaluacionesGuardadas));

        document.getElementById('texto-confirmacion-eval').innerHTML =
            `La evaluación de <strong>${docenteEvaluando.nombre}</strong> se guardó con una puntuación total de <strong>${total} / 20 pts (${porcentaje}%)</strong>.`;
        abrirModal('modal-eval-guardada');
    }

    function cerrarYVolverLista() {
        cerrarModal('modal-eval-guardada');
        volverAListaDocentes();
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
        sincronizarPerfilMenu();

        const docentesStorage = localStorage.getItem('lista_docentes_cedes');
        listaDocentes = docentesStorage ? JSON.parse(docentesStorage) : docentesBase;

        const evalStorage = localStorage.getItem('evaluaciones_docentes_cedes');
        if (evalStorage) {
            evaluacionesGuardadas = JSON.parse(evalStorage);
        }

        renderizarDocentesEvaluar();
    };

    window.addEventListener('focus', sincronizarPerfilMenu);
    window.addEventListener('storage', sincronizarPerfilMenu);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-eval-guardada');
        }
    });
</script>

</body>
</html>