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
$titulo_miga = "Estadística";

$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
$cedula_sesion = $_SESSION["cedula"] ?? "1-0945-0612";

// Consultar usuario en la base de datos
try {
    $stmtUser = $pdo->prepare("
        SELECT u.nombre, u.apellido1, r.nombre AS nombre_rol
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        WHERE u.cedula = :cedula
        LIMIT 1
    ");
    $stmtUser->execute([':cedula' => $cedula_sesion]);
    $filaUser = $stmtUser->fetch();
    if ($filaUser) {
        $nombre = trim($filaUser['nombre'] . " " . $filaUser['apellido1']);
        if (!empty($filaUser['nombre_rol'])) {
            $rol = $filaUser['nombre_rol'];
        }
    }
} catch (PDOException $e) {
    // Conserva valores por defecto si no hay registro
}

// Consultar docentes, materias y planeamientos desde la base de datos
$docentes_bd = [];
$planeamientos_bd = [];
$criterios_bd = [];

try {
    $stmtDoc = $pdo->query("
        SELECT u.cedula, u.nombre, u.apellido1,
               COALESCE(m.nombre, 'General') AS materia,
               1 AS activo
        FROM usuarios u
        INNER JOIN roles r ON u.id_rol = r.id_rol
        LEFT JOIN detalle_usuario d ON u.cedula = d.cedula
        LEFT JOIN materias m ON d.id_materia = m.id_materia
        WHERE LOWER(r.nombre) LIKE '%docente%'
        ORDER BY u.nombre ASC
    ");
    $filasDoc = $stmtDoc->fetchAll();
    foreach ($filasDoc as $d) {
        $docentes_bd[] = [
            'cedula' => $d['cedula'],
            'nombre' => trim($d['nombre'] . " " . $d['apellido1']),
            'materia' => $d['materia'],
            'activo' => ((int)$d['activo'] === 1)
        ];
    }

    $stmtPlan = $pdo->query("
        SELECT id_planeamiento, cedula_docente, id_periodo, archivo, fecha_subido
        FROM planeamientos
        ORDER BY id_planeamiento DESC
    ");
    $planeamientos_bd = $stmtPlan->fetchAll();

    $stmtCrit = $pdo->query("
        SELECT descripcion
        FROM criterios
        ORDER BY id_criterio ASC
        LIMIT 5
    ");
    $filasCrit = $stmtCrit->fetchAll();
    foreach ($filasCrit as $fc) {
        if (!empty($fc['descripcion'])) {
            $criterios_bd[] = $fc['descripcion'];
        }
    }
} catch (PDOException $e) {
    $docentes_bd = [];
    $planeamientos_bd = [];
    $criterios_bd = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Evaluación - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="estadisticas.css?v=12">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>

<div class="contenedor-general">

    <!-- MENÚ LATERAL AZUL -->
    <aside class="menu" id="sidebar-menu">
        <script>if(localStorage.getItem('menu_colapsado')==='1')document.getElementById('sidebar-menu').classList.add('colapsado');</script>

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
                    <strong id="menu-nombre-usuario"><?php echo htmlspecialchars($nombre); ?></strong>
                    <p><?php echo htmlspecialchars($rol); ?></p>
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
    <main class="contenido contenido-estadisticas">

        <?php include 'breadcrumbs.php'; ?>

        <header class="banner-seccion">
            <div class="banner-izq">
                <h1 class="titulo-seccion-bold">Estadísticas de Evaluación a Docentes</h1>
                <p class="subtitulo-banner">Análisis en tiempo real del rendimiento por criterio, recomendaciones más repetidas y metas de cobertura docente.</p>
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

        <section class="grid-kpis-estadistica">
            <article class="card-kpi-est borde-top-azul">
                <div class="icono-kpi-est azul">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="info-kpi-est">
                    <span>Total de evaluados</span>
                    <strong id="est-total-evaluados">0 Docentes</strong>
                    <small id="est-cobertura-sub">0% del personal evaluado</small>
                </div>
            </article>

            <article class="card-kpi-est borde-top-rojo">
                <div class="icono-kpi-est rojo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="info-kpi-est">
                    <span>Entregas Pendientes</span>
                    <strong id="est-entregas-pendientes">0 Pendientes</strong>
                    <small id="est-pendientes-sub">Docentes por revisar</small>
                </div>
            </article>

            <article class="card-kpi-est borde-top-verde">
                <div class="icono-kpi-est verde">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="9 15 11 17 15 13"></polyline>
                    </svg>
                </div>
                <div class="info-kpi-est">
                    <span>Nota promedio</span>
                    <strong id="est-nota-promedio">0.00</strong>
                    <small id="est-nivel-global">Desempeño general</small>
                </div>
            </article>
        </section>

        <section class="grid-graficos-estadistica">
            <article class="card-grafico-est">
                <div class="grafico-header">
                    <div>
                        <h3>Rendimiento por criterio de rúbrica</h3>
                        <p>Promedio obtenido en cada dimensión evaluada (escala 0 a 100%)</p>
                    </div>
                    <span class="badge-grafico-azul" id="badge-criterio-critico">Analizando criterios</span>
                </div>
                <div class="canvas-wrapper">
                    <canvas id="chart-criterios"></canvas>
                </div>
            </article>

            <article class="card-grafico-est">
                <div class="grafico-header">
                    <div>
                        <h3>Distribución por categorías</h3>
                        <p>Proporción de calificaciones asignadas según nivel de logro</p>
                    </div>
                    <span class="badge-grafico-verde" id="badge-nivel-predominante">Semáforo de logro</span>
                </div>
                <div class="canvas-wrapper">
                    <canvas id="chart-categorias"></canvas>
                </div>
            </article>
        </section>

        <section class="grid-graficos-estadistica">
            <article class="card-grafico-est">
                <div class="grafico-header">
                    <div>
                        <h3>Mejoras y recomendaciones más repetidas</h3>
                        <p>Temas recurrentes detectados en las observaciones y criterios bajos</p>
                    </div>
                    <span class="badge-grafico-naranja" id="badge-top-mejora">Prioridad de capacitación</span>
                </div>
                <div class="canvas-wrapper">
                    <canvas id="chart-mejoras"></canvas>
                </div>
                <div class="resumen-recomendaciones-box" id="resumen-recomendaciones-box"></div>
            </article>

            <article class="card-grafico-est">
                <div class="grafico-header">
                    <div>
                        <h3>Metas de evaluación y promedio por materia</h3>
                        <p>Porcentaje de docentes evaluados frente al rendimiento promedio por especialidad</p>
                    </div>
                    <span class="badge-grafico-azul">Meta: 100%</span>
                </div>
                <div class="canvas-wrapper">
                    <canvas id="chart-metas"></canvas>
                </div>
            </article>
        </section>

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
            <a href="estadisticas.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const docentesBD = <?php echo json_encode($docentes_bd, JSON_UNESCAPED_UNICODE); ?>;
    const planeamientosBD = <?php echo json_encode($planeamientos_bd, JSON_UNESCAPED_UNICODE); ?>;
    const criteriosBD = <?php echo json_encode($criterios_bd, JSON_UNESCAPED_UNICODE); ?>;

    const docentesRespaldo = [
        { cedula: "100564782", nombre: "Lucía Rodríguez", materia: "Español", activo: true },
        { cedula: "203648647", nombre: "Mario López", materia: "Química", activo: true },
        { cedula: "187263944", nombre: "Randall Ocón", materia: "Matemáticas", activo: true },
        { cedula: "166478230", nombre: "Sharon Mendes", materia: "Música", activo: true }
    ];

    const nombresCriterios = (Array.isArray(criteriosBD) && criteriosBD.length > 0)
        ? criteriosBD
        : [
            "Observación de clase",
            "Dominio de contenidos",
            "Planeación",
            "Mediación pedagógica",
            "Metacognición"
        ];

    let chartsInstancias = {};

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

    function analizarMejorasRepetidas(listaEval) {
        const categoriasMejora = [
            {
                etiqueta: "Planeación y alineación",
                keywords: ["plan", "planeamiento", "objetivo", "organiza", "estructura", "cronograma"],
                criterioIdx: 2,
                conteo: 0
            },
            {
                etiqueta: "Participación activa",
                keywords: ["participa", "dinámic", "dinamic", "pasiv", "involucrar", "motiv", "estudiante"],
                criterioIdx: 0,
                conteo: 0
            },
            {
                etiqueta: "Mediación y recursos",
                keywords: ["estrategia", "recurso", "didáctic", "didactic", "mediación", "mediacion", "material"],
                criterioIdx: 3,
                conteo: 0
            },
            {
                etiqueta: "Retroalimentación oportuna",
                keywords: ["retroalimentación", "retroalimentacion", "metacognición", "evalua", "seguimiento", "oportuna"],
                criterioIdx: 4,
                conteo: 0
            },
            {
                etiqueta: "Dominio y actualización",
                keywords: ["dominio", "contenido", "tema", "profundiz", "actualiz", "ejemplo"],
                criterioIdx: 1,
                conteo: 0
            }
        ];

        listaEval.forEach(ev => {
            const textoRec = (ev.recomendaciones || ev.obs || "").toLowerCase();
            const puntajes = Array.isArray(ev.puntajes) ? ev.puntajes : [];

            categoriasMejora.forEach(cat => {
                if (puntajes[cat.criterioIdx] !== undefined && puntajes[cat.criterioIdx] > 0 && puntajes[cat.criterioIdx] <= 2) {
                    cat.conteo += 1;
                }
                if (textoRec && cat.keywords.some(kw => textoRec.includes(kw))) {
                    cat.conteo += 1;
                }
            });
        });

        return categoriasMejora.sort((a, b) => b.conteo - a.conteo);
    }

    function construirEstadisticas() {
        sincronizarPerfilMenu();

        const docentes = (Array.isArray(docentesBD) && docentesBD.length > 0)
            ? docentesBD
            : (JSON.parse(localStorage.getItem('lista_docentes_cedes') || 'null') || docentesRespaldo);

        const evalObj = JSON.parse(localStorage.getItem('evaluaciones_docentes_cedes') || '{}');

        const cedulasValidas = new Set(docentes.map(d => d.cedula));
        const evaluacionesValidas = Object.entries(evalObj)
            .filter(([ced]) => docentes.length === 0 || cedulasValidas.has(ced))
            .map(([ced, data]) => ({ cedula: ced, ...data }));

        const cedulasConEntregaBD = new Set(
            (Array.isArray(planeamientosBD) ? planeamientosBD : []).map(p => p.cedula_docente)
        );

        const totalDocentes = docentes.length;
        const totalEvaluados = evaluacionesValidas.length;
        const docentesSinEntrega = docentes.filter(d => !cedulasConEntregaBD.has(d.cedula)).length;
        const pendientes = totalDocentes > 0 ? docentesSinEntrega : Math.max(0, totalDocentes - totalEvaluados);
        const pctCoberturaGlobal = totalDocentes > 0 ? Math.round((totalEvaluados / totalDocentes) * 100) : 0;

        let sumaNotas = 0;
        evaluacionesValidas.forEach(ev => {
            const nota = ev.porcentaje !== undefined ? Number(ev.porcentaje) : (ev.nota !== undefined ? Number(ev.nota) : 0);
            sumaNotas += nota;
        });
        const promedioNum = totalEvaluados > 0 ? (sumaNotas / totalEvaluados) : 0;

        document.getElementById('est-total-evaluados').textContent = `${totalEvaluados} ${totalEvaluados === 1 ? 'Docente' : 'Docentes'}`;
        document.getElementById('est-cobertura-sub').textContent = `${pctCoberturaGlobal}% de ${totalDocentes} docentes registrados`;

        document.getElementById('est-entregas-pendientes').textContent = `${pendientes} ${pendientes === 1 ? 'Pendiente' : 'Pendientes'}`;
        document.getElementById('est-pendientes-sub').textContent = pendientes === 0 ? "¡Todos los docentes entregaron!" : "Docentes sin planeamiento registrado";

        document.getElementById('est-nota-promedio').textContent = promedioNum.toFixed(2);
        document.getElementById('est-nivel-global').textContent = totalEvaluados === 0
            ? "Sin calificaciones aún"
            : (promedioNum >= 85 ? "Nivel Sobresaliente" : (promedioNum >= 70 ? "Nivel Satisfactorio" : "Requiere acompañamiento"));

        const cantCrit = nombresCriterios.length;
        const sumaCriterios = new Array(cantCrit).fill(0);
        const conteoCriterios = new Array(cantCrit).fill(0);
        const distNiveles = { excelente: 0, muyBueno: 0, bueno: 0, insuficiente: 0 };

        evaluacionesValidas.forEach(ev => {
            if (Array.isArray(ev.puntajes)) {
                ev.puntajes.forEach((pts, idx) => {
                    const p = Number(pts) || 0;
                    if (p > 0 && idx < cantCrit) {
                        sumaCriterios[idx] += (p / 4) * 100;
                        conteoCriterios[idx] += 1;

                        if (p === 4) distNiveles.excelente++;
                        else if (p === 3) distNiveles.muyBueno++;
                        else if (p === 2) distNiveles.bueno++;
                        else if (p === 1) distNiveles.insuficiente++;
                    }
                });
            }
        });

        const promediosCriterios = sumaCriterios.map((suma, i) =>
            conteoCriterios[i] > 0 ? Math.round(suma / conteoCriterios[i]) : 0
        );

        if (totalEvaluados > 0) {
            let minVal = 101;
            let minIdx = 0;
            promediosCriterios.forEach((v, i) => {
                if (v < minVal) { minVal = v; minIdx = i; }
            });
            document.getElementById('badge-criterio-critico').textContent = `Área a reforzar: ${nombresCriterios[minIdx]} (${minVal}%)`;
        } else {
            document.getElementById('badge-criterio-critico').textContent = "Sin evaluaciones aún";
        }

        const mejorasOrdenadas = analizarMejorasRepetidas(evaluacionesValidas);
        const topMejora = mejorasOrdenadas[0];
        const resumenBox = document.getElementById('resumen-recomendaciones-box');

        if (topMejora && topMejora.conteo > 0) {
            document.getElementById('badge-top-mejora').textContent = `Más repetida: ${topMejora.etiqueta}`;
            const segunda = mejorasOrdenadas[1] && mejorasOrdenadas[1].conteo > 0
                ? ` seguida por <strong>${mejorasOrdenadas[1].etiqueta}</strong> (${mejorasOrdenadas[1].conteo})`
                : "";
            resumenBox.innerHTML = `<strong>Prioridad de capacitación:</strong> La mejora más repetida entre los maestros es <strong>${topMejora.etiqueta}</strong> (${topMejora.conteo} ${topMejora.conteo === 1 ? 'mención' : 'menciones'})${segunda}.`;
        } else {
            document.getElementById('badge-top-mejora').textContent = "Sin observaciones registradas";
            resumenBox.innerHTML = `Escribe recomendaciones al evaluar docentes en el módulo <strong>Evaluar</strong> para detectar automáticamente los patrones de mejora más repetidos.`;
        }

        const materiasUnicas = [...new Set(docentes.map(d => d.materia))];
        const coberturaPorMateria = [];
        const promedioPorMateria = [];

        materiasUnicas.forEach(mat => {
            const docsMat = docentes.filter(d => d.materia === mat);
            const evalsMat = evaluacionesValidas.filter(ev => {
                const docEncontrado = docentes.find(d => d.cedula === ev.cedula);
                return (ev.materia === mat) || (docEncontrado && docEncontrado.materia === mat);
            });

            const pctCobertura = docsMat.length > 0 ? Math.round((evalsMat.length / docsMat.length) * 100) : 0;
            const promMat = evalsMat.length > 0
                ? Math.round(evalsMat.reduce((acc, e) => acc + (Number(e.porcentaje ?? e.nota ?? 0)), 0) / evalsMat.length)
                : 0;

            coberturaPorMateria.push(pctCobertura);
            promedioPorMateria.push(promMat);
        });

        renderizarGraficos({
            promediosCriterios,
            distNiveles,
            mejorasOrdenadas,
            materiasUnicas,
            coberturaPorMateria,
            promedioPorMateria
        });
    }

    function renderizarGraficos(datos) {
        Object.values(chartsInstancias).forEach(ch => ch && ch.destroy());

        const coloresBarra = ['#071342', '#1d4ed8', '#15803d', '#0ea5e9', '#6366f1'];

        chartsInstancias.criterios = new Chart(document.getElementById('chart-criterios'), {
            type: 'bar',
            data: {
                labels: nombresCriterios,
                datasets: [{
                    label: 'Promedio (%)',
                    data: datos.promediosCriterios,
                    backgroundColor: nombresCriterios.map((_, i) => coloresBarra[i % coloresBarra.length]),
                    borderRadius: 6,
                    barPercentage: 0.55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });

        const totalCat = datos.distNiveles.excelente + datos.distNiveles.muyBueno + datos.distNiveles.bueno + datos.distNiveles.insuficiente;
        chartsInstancias.categorias = new Chart(document.getElementById('chart-categorias'), {
            type: 'doughnut',
            data: {
                labels: ['Excelente (4 pts)', 'Muy Bueno (3 pts)', 'Bueno (2 pts)', 'Insuficiente (1 pt)'],
                datasets: [{
                    data: totalCat > 0
                        ? [datos.distNiveles.excelente, datos.distNiveles.muyBueno, datos.distNiveles.bueno, datos.distNiveles.insuficiente]
                        : [1, 0, 0, 0],
                    backgroundColor: totalCat > 0
                        ? ['#15803d', '#4ade80', '#facc15', '#ef4444']
                        : ['#e2e8f0', '#e2e8f0', '#e2e8f0', '#e2e8f0'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11.5 } }
                    }
                }
            }
        });

        chartsInstancias.mejoras = new Chart(document.getElementById('chart-mejoras'), {
            type: 'line',
            data: {
                labels: datos.mejorasOrdenadas.map(m => m.etiqueta),
                datasets: [{
                    label: 'Frecuencia de menciones',
                    data: datos.mejorasOrdenadas.map(m => m.conteo),
                    borderColor: '#ea580c',
                    backgroundColor: 'rgba(234, 88, 12, 0.14)',
                    pointBackgroundColor: '#071342',
                    pointRadius: 5,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false }, ticks: { font: { size: 10.5 } } }
                }
            }
        });

        chartsInstancias.metas = new Chart(document.getElementById('chart-metas'), {
            type: 'bar',
            data: {
                labels: datos.materiasUnicas,
                datasets: [
                    {
                        label: 'Cobertura evaluada (%)',
                        data: datos.coberturaPorMateria,
                        backgroundColor: '#15803d',
                        borderRadius: 5
                    },
                    {
                        label: 'Nota promedio (%)',
                        data: datos.promedioPorMateria,
                        backgroundColor: '#6366f1',
                        borderRadius: 5
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11.5 } }
                    }
                },
                scales: {
                    x: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                    y: { grid: { display: false } }
                }
            }
        });
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

    window.onload = construirEstadisticas;
    window.addEventListener('focus', construirEstadisticas);
    window.addEventListener('storage', construirEstadisticas);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cerrarModal('modal-salir');
    });
</script>

</body>
</html>