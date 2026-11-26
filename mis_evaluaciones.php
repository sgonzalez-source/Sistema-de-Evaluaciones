<?php
session_start();
require_once "conexion.php";

if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

date_default_timezone_set('America/Costa_Rica');
$anio_escolar = (int)date('Y');
$mes_actual = (int)date('n');
$periodo_actual = ($mes_actual >= 2 && $mes_actual <= 7) ? "I Periodo" : "II Periodo";
$titulo_miga = "Mis evaluaciones";

$nombre = $_SESSION["usuario"] ?? "Randall Ocón";
$rol = $_SESSION["rol"] ?? "Docente";
$cedula_sesion = $_SESSION["cedula"] ?? "187263944";
$correo_docente_bd = "";

// Consultar datos del docente desde usuarios, roles y detalle_usuario
try {
    $stmtDoc = $pdo->prepare("
        SELECT u.nombre, u.apellido1, u.apellido2,
               r.nombre AS nombre_rol,
               d.correo
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        LEFT JOIN detalle_usuario d ON u.cedula = d.cedula
        WHERE u.cedula = :cedula
        LIMIT 1
    ");
    $stmtDoc->execute([':cedula' => $cedula_sesion]);
    $filaDoc = $stmtDoc->fetch();

    if ($filaDoc) {
        $nombre = trim($filaDoc['nombre'] . " " . $filaDoc['apellido1']);
        if (!empty($filaDoc['nombre_rol'])) {
            $rol = $filaDoc['nombre_rol'];
        }
        if (!empty($filaDoc['correo'])) {
            $correo_docente_bd = $filaDoc['correo'];
        }
    }
} catch (PDOException $e) {
    // Conserva valores por defecto si no hay registro
}

// Consultar criterios y niveles desde la base de datos para armar la rúbrica
$criterios_bd = [];
$anio_rubrica_bd = (string)$anio_escolar;

try {
    $stmtRub = $pdo->query("
        SELECT r.id_rubrica, r.activo, p.anio_escolar
        FROM rubricas r
        LEFT JOIN periodos p ON r.id_periodo = p.id_periodo
        ORDER BY r.activo DESC, r.id_rubrica DESC
        LIMIT 1
    ");
    $filaRub = $stmtRub->fetch();

    if ($filaRub) {
        if (!empty($filaRub['anio_escolar'])) {
            $anio_rubrica_bd = (string)$filaRub['anio_escolar'];
        }

        $stmtCrit = $pdo->prepare("
            SELECT id_criterio, descripcion
            FROM criterios
            WHERE id_rubrica = :id_rubrica
            ORDER BY id_criterio ASC
        ");
        $stmtCrit->execute([':id_rubrica' => (int)$filaRub['id_rubrica']]);
        $filasCrit = $stmtCrit->fetchAll();

        foreach ($filasCrit as $idx => $crit) {
            $stmtNiv = $pdo->prepare("
                SELECT n.descripcion, p.puntos
                FROM niveles_evaluacion n
                LEFT JOIN puntos p ON n.id_puntos = p.id_puntos
                WHERE n.id_criterio = :id_criterio
                ORDER BY p.puntos DESC, n.id_niveles_evaluacion ASC
            ");
            $stmtNiv->execute([':id_criterio' => (int)$crit['id_criterio']]);
            $filasNiv = $stmtNiv->fetchAll();

            $mapaNiveles = [4 => "", 3 => "", 2 => "", 1 => ""];
            foreach ($filasNiv as $nIdx => $niv) {
                $ptsVal = isset($niv['puntos']) ? (int)$niv['puntos'] : (4 - $nIdx);
                if (isset($mapaNiveles[$ptsVal])) {
                    $mapaNiveles[$ptsVal] = $niv['descripcion'];
                }
            }

            // Puntaje de prueba por defecto si aún no hay evaluación guardada en localStorage
            $puntajeDefecto = ($idx % 2 === 0) ? 4 : 3;

            $criterios_bd[] = [
                'criterio' => $crit['descripcion'],
                'puntajeAsignado' => $puntajeDefecto,
                'niveles' => $mapaNiveles
            ];
        }
    }
} catch (PDOException $e) {
    $criterios_bd = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Evaluaciones - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="inicio_docente.css?v=5">
    <link rel="stylesheet" href="mis_evaluaciones.css?v=3">
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

            <a href="mis_evaluaciones.php" class="activo" title="Mis evaluaciones">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span class="texto-menu">Mis evaluaciones</span>
            </a>
        </nav>

        <div class="pie-menu">
            <a href="perfil_docente.php" class="usuario enlace-perfil-menu" title="Ver perfil del docente">
                <div class="avatar-iniciales" id="menu-avatar-iniciales">RO</div>
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
    <main class="contenido contenido-evaluaciones-docente">
        <div class="evaluaciones-wrapper">

            <?php if (file_exists('breadcrumbs.php')) include 'breadcrumbs.php'; ?>

            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold">Mis evaluaciones</h1>
                    <p class="subtitulo-banner">Respaldo enviado al correo y guardado en el sistema con el puntaje otorgado por Coordinación.</p>
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

            <!-- AVISO DE DOBLE RESPALDO (CORREO + SISTEMA) -->
            <section class="banner-respaldo-correo">
                <div class="respaldo-correo-izq">
                    <span class="icono-respaldo-correo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    <div>
                        <strong>Doble respaldo activo: Correo electrónico y registro en sistema</strong>
                        <p id="texto-correo-respaldo">Al finalizar la evaluación, el desglose se envía al correo institucional y queda respaldado aquí.</p>
                    </div>
                </div>

                <div class="respaldo-puntaje-pill">
                    <span>Puntaje total</span>
                    <strong id="puntaje-total-banner">0 / 0 pts</strong>
                </div>
            </section>

            <!-- RÚBRICA COMPLETA CON PUNTAJE ASIGNADO POR EL COORDINADOR -->
            <section class="hoja-rubrica-completa-card">
                <div class="tabla-rubrica-scroll">
                    <table class="tabla-rubrica-docente-full">
                        <tbody>
                            <tr class="fila-encabezado-datos">
                                <td colspan="2" class="celda-dato-inst">
                                    <span>Docente:</span> <strong id="dato-docente"><?php echo htmlspecialchars($nombre); ?></strong>
                                </td>
                                <td colspan="2" class="celda-dato-inst">
                                    <span>Sección:</span> <strong id="dato-seccion">12-2</strong>
                                </td>
                                <td rowspan="2" colspan="3" class="celda-anio-logo">
                                    <div class="bloque-anio-texto">
                                        <span>Año:</span> <strong id="dato-anio"><?php echo htmlspecialchars($anio_rubrica_bd); ?></strong>
                                    </div>
                                    <img src="logo_black.png" alt="CEDES Don Bosco" class="logo-rubrica-inst">
                                </td>
                            </tr>
                            <tr class="fila-encabezado-datos">
                                <td colspan="2" class="celda-dato-inst">
                                    <span>Curso lectivo:</span> <strong id="dato-curso"><?php echo htmlspecialchars($anio_rubrica_bd); ?></strong>
                                </td>
                                <td colspan="2" class="celda-dato-inst">
                                    <span>Periodo:</span> <strong id="dato-periodo"><?php echo htmlspecialchars($periodo_actual); ?></strong>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="7" class="fila-banner-amarillo">
                                    RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO
                                </td>
                            </tr>

                            <tr class="fila-cabecera-columnas">
                                <th class="col-th-criterio">Criterio</th>
                                <th class="col-th-nivel">Excelente (4<br>puntos)</th>
                                <th class="col-th-nivel">Muy Bueno (3<br>puntos)</th>
                                <th class="col-th-nivel">Bueno (2<br>puntos)</th>
                                <th class="col-th-nivel">Insuficiente (1<br>punto)</th>
                                <th class="col-th-seleccion">Selección</th>
                                <th class="col-th-puntaje">Puntaje obtenido</th>
                            </tr>
                        </tbody>

                        <tbody id="cuerpo-rubrica-completa"></tbody>

                        <tfoot>
                            <tr class="fila-total-rubrica-full">
                                <td colspan="5" class="texto-total-label">Puntaje total obtenido en la rúbrica</td>
                                <td class="col-centro"><span class="badge-respaldo-mini">Correo + Sistema</span></td>
                                <td class="col-centro"><strong id="puntaje-total-pie">0 / 0 pts</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="bloque-observaciones-full">
                    <div class="cabecera-obs-full">
                        <strong>Observaciones del Coordinador</strong>
                        <span id="fecha-evaluacion-full">Fecha de evaluación: 24/11/2026</span>
                    </div>
                    <p id="texto-observaciones-full">
                        Excelente disposición y dominio en la mediación pedagógica. Se sugiere fortalecer la evidencia de retroalimentación individualizada al cierre de cada sesión.
                    </p>
                </div>
            </section>

            <!-- BOTÓN DESCARGAR DESGLOSE EN PDF -->
            <div class="accion-descargar-pdf">
                <button type="button" class="btn-descargar-desglose-pdf" onclick="descargarDesglosePDF()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span>Descargar desglose en PDF</span>
                </button>
            </div>

        </div>
    </main>
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
            <a href="mis_evaluaciones.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const cedulaActual = "<?php echo addslashes($cedula_sesion); ?>";
    const nombreSesion = "<?php echo addslashes($nombre); ?>";
    const correoBD = "<?php echo addslashes($correo_docente_bd); ?>";
    const anioEscolar = "<?php echo htmlspecialchars($anio_rubrica_bd); ?>";
    const periodoActual = "<?php echo addslashes($periodo_actual); ?>";
    const criteriosBD = <?php echo json_encode($criterios_bd, JSON_UNESCAPED_UNICODE); ?>;

    const criteriosRespaldo = [
        {
            criterio: "Observación de clase",
            puntajeAsignado: 4,
            niveles: {
                4: "Las clases son dinámicas, participativas y promueven el aprendizaje activo.",
                3: "Las clases son generalmente participativas y promueven el aprendizaje.",
                2: "Las clases pueden ser un poco pasivas y no siempre promueven el aprendizaje.",
                1: "Las clases son generalmente pasivas y no promueven el aprendizaje."
            }
        },
        {
            criterio: "Planeación",
            puntajeAsignado: 3,
            niveles: {
                4: "Los planes de clase son claros, concisos, están bien organizados y se alinean con los objetivos.",
                3: "Los planes de clase son generalmente claros, concisos, están organizados y se alinean con los objetivos.",
                2: "Los planes de clase pueden ser un poco confusos y desorganizados. No siempre se alinean.",
                1: "Los planes de clase son confusos y desorganizados. No se alinean con los objetivos."
            }
        }
    ];

    const rubricaCompletaPorDefecto = {
        seccion: "12-2",
        cursoLectivo: anioEscolar,
        periodo: periodoActual,
        fechaEvaluacion: "24/11/2026",
        observaciones: "Excelente disposición y dominio en la mediación pedagógica. Se sugiere fortalecer la evidencia de retroalimentación individualizada al cierre de cada sesión.",
        criterios: (Array.isArray(criteriosBD) && criteriosBD.length > 0) ? criteriosBD : criteriosRespaldo
    };

    function etiquetaNivel(pts) {
        const n = Number(pts);
        if (n === 4) return "Excelente";
        if (n === 3) return "Muy Bueno";
        if (n === 2) return "Bueno";
        return "Insuficiente";
    }

    function claseCeldaMarcada(pts) {
        const n = Number(pts);
        if (n === 4) return "celda-nivel-full marcada excelente";
        if (n === 3) return "celda-nivel-full marcada muy-bueno";
        if (n === 2) return "celda-nivel-full marcada bueno";
        return "celda-nivel-full marcada insuficiente";
    }

    function claseBadgePuntaje(pts) {
        const n = Number(pts);
        if (n === 4) return "badge-pts-full p4";
        if (n === 3) return "badge-pts-full p3";
        if (n === 2) return "badge-pts-full p2";
        return "badge-pts-full p1";
    }

    function obtenerDatosEvaluacionFull() {
        const todasEval = JSON.parse(localStorage.getItem('evaluaciones_docentes_cedes') || '{}');
        const guardada = todasEval[cedulaActual];

        if (guardada && Array.isArray(guardada.criterios) && guardada.criterios.length > 0 && guardada.criterios[0].niveles) {
            return {
                seccion: guardada.seccion || rubricaCompletaPorDefecto.seccion,
                cursoLectivo: guardada.cursoLectivo || rubricaCompletaPorDefecto.cursoLectivo,
                periodo: guardada.periodo || rubricaCompletaPorDefecto.periodo,
                fechaEvaluacion: guardada.fecha || rubricaCompletaPorDefecto.fechaEvaluacion,
                observaciones: guardada.recomendaciones || guardada.observaciones || rubricaCompletaPorDefecto.observaciones,
                criterios: guardada.criterios
            };
        }

        if (guardada && Array.isArray(guardada.criterios) && guardada.criterios.length > 0) {
            const criteriosCombinados = rubricaCompletaPorDefecto.criterios.map((defCrit, idx) => {
                const itemGuardado = guardada.criterios[idx];
                const pts = itemGuardado ? Number(itemGuardado.obtenido ?? itemGuardado.puntaje ?? defCrit.puntajeAsignado) : defCrit.puntajeAsignado;
                return {
                    ...defCrit,
                    criterio: itemGuardado?.nombre || itemGuardado?.criterio || defCrit.criterio,
                    puntajeAsignado: Math.max(1, Math.min(4, pts))
                };
            });

            return {
                seccion: guardada.seccion || rubricaCompletaPorDefecto.seccion,
                cursoLectivo: guardada.cursoLectivo || rubricaCompletaPorDefecto.cursoLectivo,
                periodo: guardada.periodo || rubricaCompletaPorDefecto.periodo,
                fechaEvaluacion: guardada.fecha || rubricaCompletaPorDefecto.fechaEvaluacion,
                observaciones: guardada.recomendaciones || guardada.observaciones || rubricaCompletaPorDefecto.observaciones,
                criterios: criteriosCombinados
            };
        }

        if (guardada && guardada.recomendaciones) {
            return {
                ...rubricaCompletaPorDefecto,
                observaciones: guardada.recomendaciones,
                fechaEvaluacion: guardada.fecha || rubricaCompletaPorDefecto.fechaEvaluacion
            };
        }

        return rubricaCompletaPorDefecto;
    }

    function renderizarRubricaCompleta() {
        const perfil = JSON.parse(localStorage.getItem('perfil_docente_cedes') || 'null');
        const nombreDocente = (perfil && perfil.nombre) ? perfil.nombre : nombreSesion;
        const correoDocente = correoBD || ((perfil && perfil.correo) ? perfil.correo : "correo institucional del docente");

        document.getElementById('menu-nombre-usuario').textContent = nombreDocente;
        const elAvatar = document.getElementById('menu-avatar-iniciales');
        if (perfil && perfil.foto && perfil.foto.startsWith('data:image')) {
            elAvatar.innerHTML = `<img src="${perfil.foto}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">`;
        } else {
            const partes = nombreDocente.trim().split(/\s+/);
            elAvatar.textContent = partes.length > 1
                ? (partes[0][0] + partes[partes.length - 1][0]).toUpperCase()
                : partes[0].substring(0, 2).toUpperCase();
        }

        const datos = obtenerDatosEvaluacionFull();
        document.getElementById('dato-docente').textContent = nombreDocente;
        document.getElementById('dato-seccion').textContent = datos.seccion;
        document.getElementById('dato-curso').textContent = datos.cursoLectivo;
        document.getElementById('dato-periodo').textContent = datos.periodo;
        document.getElementById('dato-anio').textContent = datos.cursoLectivo;
        document.getElementById('texto-observaciones-full').textContent = datos.observaciones;
        document.getElementById('fecha-evaluacion-full').textContent = `Fecha de evaluación: ${datos.fechaEvaluacion}`;
        document.getElementById('texto-correo-respaldo').textContent =
            `Desglose enviado a ${correoDocente} y respaldado en el sistema.`;

        let totalObtenido = 0;
        const totalMaximo = datos.criterios.length * 4;

        const tbody = document.getElementById('cuerpo-rubrica-completa');
        tbody.innerHTML = datos.criterios.map((item) => {
            const pts = Number(item.puntajeAsignado ?? 4);
            totalObtenido += pts;
            const nombreNivel = etiquetaNivel(pts);

            const ordenNiveles = [4, 3, 2, 1];
            const celdasNiveles = ordenNiveles.map((nivelPts) => {
                const esElegido = (pts === nivelPts);
                const clase = esElegido ? claseCeldaMarcada(nivelPts) : "celda-nivel-full";
                return `<td class="${clase}">${item.niveles[nivelPts] || ""}</td>`;
            }).join('');

            return `
                <tr>
                    <td class="celda-criterio-full">${item.criterio}</td>
                    ${celdasNiveles}
                    <td class="celda-seleccion-full">
                        <span class="pill-nivel-elegido">${nombreNivel}</span>
                    </td>
                    <td class="celda-puntaje-full">
                        <span class="${claseBadgePuntaje(pts)}">${pts} ${pts === 1 ? 'pt' : 'pts'}</span>
                    </td>
                </tr>
            `;
        }).join('');

        const textoTotal = `${totalObtenido} / ${totalMaximo} pts`;
        document.getElementById('puntaje-total-banner').textContent = textoTotal;
        document.getElementById('puntaje-total-pie').textContent = textoTotal;
    }

    function descargarDesglosePDF() {
        const perfil = JSON.parse(localStorage.getItem('perfil_docente_cedes') || 'null');
        const nombreDocente = (perfil && perfil.nombre) ? perfil.nombre : nombreSesion;
        const datos = obtenerDatosEvaluacionFull();

        let totalObtenido = 0;
        const totalMaximo = datos.criterios.length * 4;

        const filasHtml = datos.criterios.map((item) => {
            const pts = Number(item.puntajeAsignado ?? 4);
            totalObtenido += pts;
            const nombreNivel = etiquetaNivel(pts);

            const estiloNivel = (nPts) => {
                const base = "padding:8px 7px;border:1px solid #94a3b8;font-size:11px;line-height:1.32;vertical-align:top;width:17%;";
                if (pts !== nPts) return base + "background:#ffffff;color:#1e293b;";
                if (nPts === 4) return base + "background:#dcfce7;border:2px solid #15803d;font-weight:700;color:#0f172a;";
                if (nPts === 3) return base + "background:#dbeafe;border:2px solid #1d4ed8;font-weight:700;color:#0f172a;";
                if (nPts === 2) return base + "background:#fef9c3;border:2px solid #ca8a04;font-weight:700;color:#0f172a;";
                return base + "background:#fee2e2;border:2px solid #dc2626;font-weight:700;color:#0f172a;";
            };

            return `
                <tr>
                    <td style="background:#e2e8f0;border:1px solid #64748b;padding:8px;font-weight:700;font-style:italic;font-size:11.5px;width:15%;">${item.criterio}</td>
                    <td style="${estiloNivel(4)}">${item.niveles[4] || ""}</td>
                    <td style="${estiloNivel(3)}">${item.niveles[3] || ""}</td>
                    <td style="${estiloNivel(2)}">${item.niveles[2] || ""}</td>
                    <td style="${estiloNivel(1)}">${item.niveles[1] || ""}</td>
                    <td style="border:1px solid #64748b;padding:8px 6px;text-align:center;font-weight:700;font-size:11.5px;width:9%;">${nombreNivel}</td>
                    <td style="border:1px solid #64748b;padding:8px 6px;text-align:center;font-weight:800;font-size:12px;color:#071342;width:8%;">${pts} ${pts === 1 ? 'pt' : 'pts'}</td>
                </tr>
            `;
        }).join('');

        const ventana = window.open('', '_blank', 'width=1120,height=780');
        if (!ventana) {
            window.print();
            return;
        }

        ventana.document.write(`
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Rúbrica de Observación Docente - ${nombreDocente}</title>
                <style>
                    body { font-family: Arial, sans-serif; color: #0f172a; padding: 20px; background: #fff; }
                    table { width: 100%; border-collapse: collapse; }
                    .meta td { border: 1px solid #64748b; padding: 7px 10px; font-size: 12.5px; }
                    .titulo-amarillo { background: #fde68a; border: 1px solid #64748b; padding: 8px 10px; text-align: center; font-weight: 800; font-size: 12.5px; }
                    th { background: #93c5fd; border: 1px solid #64748b; padding: 8px 6px; font-size: 11.5px; color: #0f172a; }
                    .total-fila td { background: #f1f5f9; border: 1px solid #64748b; padding: 8px 10px; font-weight: 800; font-size: 12.5px; }
                    .obs { margin-top: 14px; border: 1px solid #94a3b8; border-left: 4px solid #071342; border-radius: 6px; padding: 10px 14px; background: #f8fafc; }
                    .obs strong { display: block; margin-bottom: 4px; color: #071342; font-size: 12.5px; }
                </style>
            </head>
            <body>
                <table>
                    <tr class="meta">
                        <td colspan="2">Docente: <strong>${nombreDocente}</strong></td>
                        <td colspan="2">Sección: <strong>${datos.seccion}</strong></td>
                        <td colspan="3">Año: <strong>${datos.cursoLectivo}</strong> · CEDES Don Bosco</td>
                    </tr>
                    <tr class="meta">
                        <td colspan="2">Curso lectivo: <strong>${datos.cursoLectivo}</strong></td>
                        <td colspan="2">Periodo: <strong>${datos.periodo}</strong></td>
                        <td colspan="3">Fecha: <strong>${datos.fechaEvaluacion}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="titulo-amarillo">RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO</td>
                    </tr>
                    <tr>
                        <th>Criterio</th>
                        <th>Excelente (4 puntos)</th>
                        <th>Muy Bueno (3 puntos)</th>
                        <th>Bueno (2 puntos)</th>
                        <th>Insuficiente (1 punto)</th>
                        <th>Selección</th>
                        <th>Puntaje obtenido</th>
                    </tr>
                    <tbody>${filasHtml}</tbody>
                    <tfoot>
                        <tr class="total-fila">
                            <td colspan="5">Puntaje total obtenido en la rúbrica</td>
                            <td style="text-align:center;">Correo + Sistema</td>
                            <td style="text-align:center;">${totalObtenido} / ${totalMaximo} pts</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="obs">
                    <strong>Observaciones del Coordinador</strong>
                    <p style="margin:0;font-size:12.5px;line-height:1.45;">${datos.observaciones}</p>
                </div>
            </body>
            </html>
        `);
        ventana.document.close();
        ventana.focus();
        setTimeout(() => ventana.print(), 250);
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
        renderizarRubricaCompleta();
    };
</script>

</body>
</html>