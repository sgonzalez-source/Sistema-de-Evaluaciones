<?php
session_start();

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

require_once 'conexion.php';

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
$titulo_miga = "Evaluación";

date_default_timezone_set('America/Costa_Rica');
$anio_escolar = date('Y');
$mes_actual = (int)date('n');
$periodo_actual = ($mes_actual >= 2 && $mes_actual <= 7) ? "I Periodo" : "II Periodo";

$docentesBD = [];
try {
    $colsStmt = $pdo->query("SHOW COLUMNS FROM usuarios");
    $cols = array_column($colsStmt->fetchAll(PDO::FETCH_ASSOC), 'Field');

    $hasCedula = in_array('cedula', $cols, true);
    $hasNombre = in_array('nombre', $cols, true);
    $hasAp1 = in_array('apellido1', $cols, true);
    $hasAp2 = in_array('apellido2', $cols, true);
    $hasCorreo = in_array('correo', $cols, true);
    $hasIdRol = in_array('id_rol', $cols, true);

    $selCedula = $hasCedula ? "u.cedula" : "'' AS cedula";
    $selNombre = $hasNombre ? "u.nombre" : "'' AS nombre";
    $selAp1 = $hasAp1 ? "u.apellido1" : "'' AS apellido1";
    $selAp2 = $hasAp2 ? "u.apellido2" : "'' AS apellido2";
    $selCorreo = $hasCorreo ? "COALESCE(u.correo, '') AS correo" : "'' AS correo";

    $joinRol = "";
    $selRol = "'' AS rol_nombre";
    if ($hasIdRol) {
        $joinRol = " LEFT JOIN roles r ON u.id_rol = r.id_rol ";
        $selRol = " COALESCE(r.nombre, '') AS rol_nombre ";
    }

    $orderBy = $hasNombre ? " ORDER BY u.nombre ASC " : "";

    $sql = "SELECT $selCedula, $selNombre, $selAp1, $selAp2, $selCorreo, $selRol
            FROM usuarios u
            $joinRol
            $orderBy";

    $stmt = $pdo->query($sql);
    $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $colsPlan = [];
    try {
        $colsPlanStmt = $pdo->query("SHOW COLUMNS FROM planeamientos");
        $colsPlan = array_column($colsPlanStmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
    } catch (Throwable $eColsPlan) {
        $colsPlan = [];
    }

    foreach ($filas as $f) {
        $rolNombre = (string)($f['rol_nombre'] ?? '');
        if ($hasIdRol && $rolNombre !== '' && stripos($rolNombre, 'coord') !== false && stripos($rolNombre, 'docente') === false) {
            continue;
        }

        $nombreCompleto = trim(
            ($f['nombre'] ?? '') . ' ' . ($f['apellido1'] ?? '') . ' ' . ($f['apellido2'] ?? '')
        );
        $cedula = (string)($f['cedula'] ?? '');

        $materia = "Docente";
        $archivoPlaneamiento = "";

        if ($cedula !== '' && !empty($colsPlan)) {
            try {
                $colCedPlan = null;
                foreach (['cedula_docente', 'cedula', 'id_usuario', 'docente_cedula'] as $candidataCed) {
                    if (in_array($candidataCed, $colsPlan, true)) {
                        $colCedPlan = $candidataCed;
                        break;
                    }
                }

                if ($colCedPlan !== null) {
                    $tieneIdMateria = in_array('id_materia', $colsPlan, true);
                    $colOrden = in_array('id_planeamiento', $colsPlan, true)
                        ? 'p.id_planeamiento DESC'
                        : (in_array('id', $colsPlan, true) ? 'p.id DESC' : '');
                    $orderPlan = $colOrden !== '' ? "ORDER BY $colOrden" : "";

                    $sqlPlan = $tieneIdMateria
                        ? "SELECT p.*, m.nombre AS materia_nombre
                           FROM planeamientos p
                           LEFT JOIN materias m ON p.id_materia = m.id_materia
                           WHERE p.$colCedPlan = :cedula
                           $orderPlan
                           LIMIT 1"
                        : "SELECT p.*
                           FROM planeamientos p
                           WHERE p.$colCedPlan = :cedula
                           $orderPlan
                           LIMIT 1";

                    $stmtPlan = $pdo->prepare($sqlPlan);
                    $stmtPlan->execute([':cedula' => $cedula]);
                    $plan = $stmtPlan->fetch(PDO::FETCH_ASSOC);

                    if ($plan) {
                        if (!empty($plan['materia_nombre'])) {
                            $materia = $plan['materia_nombre'];
                        } elseif (!empty($plan['materia'])) {
                            $materia = $plan['materia'];
                        }

                        foreach (['archivo', 'archivo_pdf', 'ruta_archivo', 'ruta', 'documento', 'nombre_archivo', 'pdf'] as $colArchivo) {
                            if (!empty($plan[$colArchivo])) {
                                $archivoPlaneamiento = $plan[$colArchivo];
                                break;
                            }
                        }
                    }
                }
            } catch (Throwable $ePlan) {
            }
        }

        $docentesBD[] = [
            'cedula' => $cedula,
            'nombre' => $nombreCompleto !== '' ? $nombreCompleto : ($cedula !== '' ? $cedula : 'Docente'),
            'correo' => !empty($f['correo']) ? $f['correo'] : 'docente@cedesdonbosco.ed.cr',
            'materia' => $materia,
            'archivoPlaneamiento' => $archivoPlaneamiento
        ];
    }
} catch (Throwable $e) {
    $docentesBD = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación Docente - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="evaluar.css?v=6">
</head>
<body>

<div class="contenedor-general">

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

            <a href="evaluar.php" class="activo" title="Evaluación">
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

    <main class="contenido contenido-evaluar">
        <div class="evaluar-wrapper">

            <?php if (file_exists('breadcrumbs.php')) include 'breadcrumbs.php'; ?>

            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold">Evaluación docente</h1>
                    <p class="subtitulo-banner">Selecciona el docente a evaluar, revisa su planeamiento y califica cada criterio de la rúbrica.</p>
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

            <section id="vista-seleccion-docentes" class="vista-bloque">
                <div class="barra-herramientas">
                    <div class="caja-busqueda">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="buscar-docente-eval" placeholder="Buscar docente por nombre, cédula o materia..." oninput="renderizarTarjetasDocentes()">
                    </div>

                    <div class="acciones-derecha">
                        <select id="filtro-estado-eval" class="select-filtro" onchange="renderizarTarjetasDocentes()">
                            <option value="todos">Todos los docentes</option>
                            <option value="pendiente">Pendientes de evaluar</option>
                            <option value="evaluado">Evaluados</option>
                        </select>
                    </div>
                </div>

                <div id="grid-docentes-evaluar" class="grid-docentes-evaluar"></div>
            </section>

            <section id="vista-aplicar-evaluacion" class="vista-bloque" style="display: none;">

                <div class="barra-superior-evaluacion">
                    <div class="info-docente-activo">
                        <button type="button" class="btn-volver-docentes" onclick="volverATarjetasDocentes()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            <span>Cambiar docente</span>
                        </button>

                        <span class="badge-docente-activo" id="etiqueta-docente-activo">Docente: —</span>

                        <div class="grupo-seccion-rapida">
                            <label for="input-seccion-evaluar">Sección:</label>
                            <input type="text" id="input-seccion-evaluar" value="12-2">
                        </div>
                    </div>

                    <div class="resumen-puntaje-vivo">
                        <span>Puntaje acumulado</span>
                        <strong id="puntaje-vivo-texto">0 / 20 pts</strong>
                    </div>
                </div>

                <div class="split-screen-evaluacion">

                    <section class="panel-mitad panel-planeamiento">
                        <div class="caja-interna-pdf">
                            <div class="barra-archivo-pdf">
                                <div class="nombre-pdf-izq">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    <span id="nombre-archivo-planeamiento">Planeamiento_Docente.pdf</span>
                                </div>

                                <a id="enlace-descargar-planeamiento" href="#" download class="btn-descargar-pdf" title="Descargar planeamiento">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </a>
                            </div>

                            <div class="contenedor-visor-real">
                                <iframe id="iframe-pdf-real" src="" title="Visor de planeamiento docente"></iframe>

                                <div class="mensaje-sin-pdf-real" id="mensaje-sin-pdf-real" style="display: none;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="9" y1="13" x2="15" y2="13"></line>
                                    </svg>
                                    <p id="texto-sin-pdf-titulo">Sin planeamiento PDF asignado</p>
                                    <span id="texto-sin-pdf-detalle">Este docente aún no tiene un archivo de planeamiento disponible para previsualizar.</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="panel-mitad panel-rubrica card-matriz-evaluar">
                        <div class="scroll-rubrica-horizontal tabla-evaluar-scroll">
                            <table class="tabla-evaluar-matriz">
                                <thead>
                                    <tr>
                                        <th class="col-crit">Criterio</th>
                                        <th class="col-niv">Excelente (4 pts)</th>
                                        <th class="col-niv">Muy Bueno (3 pts)</th>
                                        <th class="col-niv">Bueno (2 pts)</th>
                                        <th class="col-niv">Insuficiente (1 pt)</th>
                                        <th class="col-pts">Puntaje</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo-evaluar-matriz"></tbody>
                            </table>
                        </div>

                        <div class="campo-observaciones-eval">
                            <label for="textarea-observaciones-eval">Observaciones del Coordinador</label>
                            <textarea id="textarea-observaciones-eval" rows="3" placeholder="Escribe tus observaciones o recomendaciones pedagógicas para el docente..."></textarea>
                        </div>

                        <div class="acciones-evaluar-pie">
                            <button type="button" class="btn-limpiar-rubrica" onclick="limpiarRubricaActual()">Limpiar selección</button>
                            <button type="button" class="btn-guardar-evaluacion" onclick="guardarYEnviarEvaluacion()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                <span>Guardar evaluación y respaldar</span>
                            </button>
                        </div>
                    </section>

                </div>
            </section>

        </div>
    </main>
</div>

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

        <h2>Evaluación registrada</h2>
        <p id="mensaje-eval-guardada">Se guardó el desglose en el sistema y se notificó por correo al docente.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarModal('modal-eval-guardada'); renderizarTarjetasDocentes();">Aceptar</button>
        </div>
    </div>
</div>

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
    const anioEscolar = "<?php echo $anio_escolar; ?>";
    const periodoActual = "<?php echo addslashes($periodo_actual); ?>";
    const docentesDesdeBD = <?php echo json_encode($docentesBD, JSON_UNESCAPED_UNICODE); ?>;

    const criteriosBase = [
        {
            criterio: "Observación de clase",
            niveles: {
                4: "Las clases son dinámicas, participativas y promueven el aprendizaje activo.",
                3: "Las clases son generalmente participativas y promueven el aprendizaje.",
                2: "Las clases pueden ser un poco pasivas y no siempre promueven el aprendizaje.",
                1: "Las clases son generalmente pasivas y no promueven el aprendizaje."
            }
        },
        {
            criterio: "Dominio de los contenidos",
            niveles: {
                4: "Demuestra un dominio excepcional del contenido y las estrategias de enseñanza.",
                3: "Tiene un buen dominio del contenido y las estrategias de enseñanza.",
                2: "Tiene un dominio básico del contenido y las estrategias de enseñanza.",
                1: "Tiene un dominio limitado del contenido y las estrategias de enseñanza."
            }
        },
        {
            criterio: "Planeación",
            niveles: {
                4: "Los planes de clase son claros, concisos, están bien organizados y se alinean con los objetivos.",
                3: "Los planes de clase son generalmente claros, concisos, están organizados y se alinean con los objetivos.",
                2: "Los planes de clase pueden ser un poco confusos y desorganizados. No siempre se alinean.",
                1: "Los planes de clase son confusos y desorganizados. No se alinean con los objetivos."
            }
        },
        {
            criterio: "Mediación pedagógica",
            niveles: {
                4: "Utiliza una variedad de estrategias de enseñanza efectivas y adaptadas al grupo.",
                3: "Utiliza algunas estrategias de enseñanza efectivas para guiar el aprendizaje.",
                2: "Utiliza pocas estrategias de enseñanza efectivas y requiere mayor dinamismo.",
                1: "No utiliza estrategias de enseñanza efectivas durante la sesión."
            }
        },
        {
            criterio: "Metacognición del contenido (retroalimentación)",
            niveles: {
                4: "Brinda retroalimentación clara, oportuna y constante que favorece la autoevaluación.",
                3: "Brinda retroalimentación a los estudiantes, pero puede profundizar en el cierre.",
                2: "La retroalimentación a los estudiantes es escasa o no es del todo útil.",
                1: "No brinda retroalimentación a los estudiantes sobre su desempeño."
            }
        }
    ];

    let puntajesSeleccionados = criteriosBase.map(() => null);
    let docenteActual = null;

    function obtenerListaDocentes() {
        if (Array.isArray(docentesDesdeBD) && docentesDesdeBD.length > 0) {
            return docentesDesdeBD;
        }
        return [];
    }

    function obtenerEvaluacionesGuardadas() {
        return JSON.parse(localStorage.getItem('evaluaciones_docentes_cedes') || '{}');
    }

    function renderizarTarjetasDocentes() {
        const contenedor = document.getElementById('grid-docentes-evaluar');
        const textoBusqueda = (document.getElementById('buscar-docente-eval').value || '').trim().toLowerCase();
        const filtroEstado = document.getElementById('filtro-estado-eval').value;

        const docentes = obtenerListaDocentes();
        const evalGlobal = obtenerEvaluacionesGuardadas();

        if (docentes.length === 0) {
            contenedor.innerHTML = `<div class="vacio-evaluar">No se encontraron docentes registrados en la base de datos <strong>cedes_evaluacion</strong>.</div>`;
            return;
        }

        const filtrados = docentes.filter(d => {
            const yaEvaluado = Boolean(evalGlobal[d.cedula]);
            if (filtroEstado === 'pendiente' && yaEvaluado) return false;
            if (filtroEstado === 'evaluado' && !yaEvaluado) return false;

            if (!textoBusqueda) return true;
            const combinado = `${d.nombre} ${d.cedula} ${d.materia}`.toLowerCase();
            return combinado.includes(textoBusqueda);
        });

        if (filtrados.length === 0) {
            contenedor.innerHTML = `<div class="vacio-evaluar">No hay docentes que coincidan con el filtro seleccionado.</div>`;
            return;
        }

        contenedor.innerHTML = filtrados.map(d => {
            const evalData = evalGlobal[d.cedula];
            const badge = evalData
                ? `<span class="badge-evaluado">Evaluado (${evalData.total}/${criteriosBase.length * 4} pts)</span>`
                : `<span class="badge-pendiente-eval">Pendiente</span>`;

            const nombreArch = d.archivoPlaneamiento
                ? String(d.archivoPlaneamiento).split('/').pop()
                : "Sin planeamiento asignado";

            return `
                <article class="card-docente-eval">
                    <div>
                        <div class="card-eval-top">
                            <div>
                                <span class="eval-cedula">Cédula: ${d.cedula}</span>
                                <h3>${d.nombre}</h3>
                                <span class="eval-materia">${d.materia}</span>
                            </div>
                            ${badge}
                        </div>
                    </div>

                    <div class="card-eval-footer">
                        <span class="archivo-pdf-mini" title="${nombreArch}">${nombreArch}</span>
                        <button type="button" class="btn-aplicar-rubrica" onclick="abrirEvaluacionDocente('${d.cedula}')">
                            Aplicar rúbrica
                        </button>
                    </div>
                </article>
            `;
        }).join('');
    }

    function actualizarVisorPlaneamiento(doc) {
        const iframe = document.getElementById('iframe-pdf-real');
        const sinPdfBox = document.getElementById('mensaje-sin-pdf-real');
        const nombreSpan = document.getElementById('nombre-archivo-planeamiento');
        const linkDescarga = document.getElementById('enlace-descargar-planeamiento');
        const tituloSinPdf = document.getElementById('texto-sin-pdf-titulo');
        const detalleSinPdf = document.getElementById('texto-sin-pdf-detalle');

        const rawArchivo = (doc && doc.archivoPlaneamiento) ? String(doc.archivoPlaneamiento).trim() : "";

        if (!rawArchivo) {
            nombreSpan.textContent = `Sin planeamiento asignado (${doc ? doc.nombre : "Docente"})`;
            linkDescarga.removeAttribute('href');
            linkDescarga.style.pointerEvents = 'none';
            linkDescarga.style.opacity = '0.45';
            iframe.style.display = 'none';
            iframe.src = '';
            sinPdfBox.style.display = 'flex';
            tituloSinPdf.textContent = "Sin planeamiento PDF disponible";
            detalleSinPdf.textContent = `Aún no se ha registrado un archivo de planeamiento para ${doc ? doc.nombre : "este docente"}.`;
            return;
        }

        const nombreVisible = rawArchivo.startsWith('data:')
            ? `Planeamiento_${(doc.nombre || 'Docente').replace(/\s+/g, '_')}.pdf`
            : rawArchivo.split('/').pop();

        nombreSpan.textContent = nombreVisible;
        linkDescarga.href = rawArchivo;
        linkDescarga.setAttribute('download', nombreVisible);
        linkDescarga.style.pointerEvents = 'auto';
        linkDescarga.style.opacity = '1';

        iframe.style.display = 'block';
        sinPdfBox.style.display = 'none';
        iframe.src = rawArchivo;
    }

    function notificarEvaluandoEnTiempoReal() {
        if (!docenteActual) return;
        localStorage.setItem('evaluando_docente_cedes', JSON.stringify({
            cedula: docenteActual.cedula,
            nombre: docenteActual.nombre,
            enProgreso: true,
            actualizadoEn: Date.now()
        }));
    }

    function limpiarEvaluandoEnTiempoReal() {
        localStorage.removeItem('evaluando_docente_cedes');
    }

    function limpiarRubricaActual() {
        puntajesSeleccionados = criteriosBase.map(() => null);
        document.getElementById('textarea-observaciones-eval').value = '';
        renderizarMatrizEvaluar();
    }

    function abrirEvaluacionDocente(cedula) {
        const docentes = obtenerListaDocentes();
        const doc = docentes.find(d => String(d.cedula) === String(cedula));
        if (!doc) return;

        docenteActual = doc;
        puntajesSeleccionados = criteriosBase.map(() => null);
        document.getElementById('textarea-observaciones-eval').value = '';
        document.getElementById('etiqueta-docente-activo').textContent = `${doc.nombre} (${doc.materia})`;

        document.getElementById('vista-seleccion-docentes').style.display = 'none';
        document.getElementById('vista-aplicar-evaluacion').style.display = 'flex';

        actualizarVisorPlaneamiento(doc);
        notificarEvaluandoEnTiempoReal();
        renderizarMatrizEvaluar();
    }

    function volverATarjetasDocentes() {
        limpiarEvaluandoEnTiempoReal();
        docenteActual = null;
        document.getElementById('vista-aplicar-evaluacion').style.display = 'none';
        document.getElementById('vista-seleccion-docentes').style.display = 'flex';
        renderizarTarjetasDocentes();
    }

    function seleccionarNivel(indiceCriterio, puntos) {
        const valor = Number(puntos);
        puntajesSeleccionados[indiceCriterio] = (puntajesSeleccionados[indiceCriterio] === valor) ? null : valor;
        notificarEvaluandoEnTiempoReal();
        renderizarMatrizEvaluar();
    }

    function renderizarMatrizEvaluar() {
        const tbody = document.getElementById('cuerpo-evaluar-matriz');
        let suma = 0;

        tbody.innerHTML = criteriosBase.map((item, idx) => {
            const actualPts = puntajesSeleccionados[idx];
            if (typeof actualPts === 'number') {
                suma += actualPts;
            }

            const nivelesOrden = [4, 3, 2, 1];
            const celdas = nivelesOrden.map((pts) => {
                const estaMarcada = actualPts === pts;
                let claseColor = "";
                if (estaMarcada) {
                    if (pts === 4 || pts === 3) claseColor = "seleccionada seleccionada-verde";
                    else if (pts === 2) claseColor = "seleccionada seleccionada-amarillo";
                    else if (pts === 1) claseColor = "seleccionada seleccionada-rojo";
                }

                return `
                    <td>
                        <button type="button" class="btn-celda-eval ${claseColor}" data-puntos="${pts}" onclick="seleccionarNivel(${idx}, ${pts})">
                            ${item.niveles[pts]}
                        </button>
                    </td>
                `;
            }).join('');

            const textoPts = typeof actualPts === 'number' ? `<strong>${actualPts} pts</strong>` : `<span>—</span>`;

            return `
                <tr>
                    <td class="celda-criterio-eval">${item.criterio}</td>
                    ${celdas}
                    <td class="celda-pts-eval">${textoPts}</td>
                </tr>
            `;
        }).join('');

        document.getElementById('puntaje-vivo-texto').textContent = `${suma} / ${criteriosBase.length * 4} pts`;
    }

    function guardarYEnviarEvaluacion() {
        if (!docenteActual) return;

        const sinCalificar = puntajesSeleccionados.some(p => typeof p !== 'number');
        if (sinCalificar) {
            alert("Por favor selecciona un nivel en cada criterio antes de guardar la evaluación.");
            return;
        }

        const hoy = new Date();
        const fechaHoy = `${String(hoy.getDate()).padStart(2, '0')}/${String(hoy.getMonth() + 1).padStart(2, '0')}/${hoy.getFullYear()}`;
        const obs = document.getElementById('textarea-observaciones-eval').value.trim() ||
            "Evaluación completada y respaldada por Coordinación Académica.";
        const seccion = document.getElementById('input-seccion-evaluar').value.trim() || "12-2";

        const criteriosDetalle = criteriosBase.map((item, idx) => ({
            criterio: item.criterio,
            puntajeAsignado: puntajesSeleccionados[idx],
            niveles: item.niveles
        }));

        const totalPuntos = criteriosDetalle.reduce((acc, c) => acc + c.puntajeAsignado, 0);
        const maxPuntos = criteriosDetalle.length * 4;
        const porcentaje = Math.round((totalPuntos / maxPuntos) * 100);
        const idEvaluacion = `eval_${Date.now()}_${Math.random().toString(36).slice(2, 7)}`;

        const registroEvaluacion = {
            idEvaluacion,
            cedula: docenteActual.cedula,
            nombre: docenteActual.nombre,
            correo: docenteActual.correo,
            seccion,
            cursoLectivo: anioEscolar,
            periodo: periodoActual,
            fecha: fechaHoy,
            total: totalPuntos,
            porcentaje,
            nota: porcentaje,
            recomendaciones: obs,
            correoEnviado: true,
            criterios: criteriosDetalle
        };

        const evalGlobal = obtenerEvaluacionesGuardadas();
        evalGlobal[docenteActual.cedula] = registroEvaluacion;
        localStorage.setItem('evaluaciones_docentes_cedes', JSON.stringify(evalGlobal));

        const claveHist = `historial_evaluaciones_${docenteActual.cedula}`;
        const histDocente = JSON.parse(localStorage.getItem(claveHist) || '[]');
        histDocente.push(registroEvaluacion);
        localStorage.setItem(claveHist, JSON.stringify(histDocente));

        limpiarEvaluandoEnTiempoReal();

        document.getElementById('mensaje-eval-guardada').textContent =
            `Evaluación de ${docenteActual.nombre} (${totalPuntos}/${maxPuntos} pts) guardada en el sistema y enviada a ${docenteActual.correo}.`;
        abrirModal('modal-eval-guardada');
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

    window.addEventListener('beforeunload', limpiarEvaluandoEnTiempoReal);

    window.onload = function() {
        renderizarTarjetasDocentes();
    };
</script>

</body>
</html>