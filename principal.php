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
$semestre_mep = ($mes_actual >= 2 && $mes_actual <= 7) ? "I Periodo" : "II Periodo";
$periodo_vigente_auto = $semestre_mep . " " . $anio_escolar;

// Valores por defecto
$nombre_completo = $_SESSION["usuario"] ?? "Javier Mena";
$rol = $_SESSION["rol"] ?? "Coordinador";
$cedula_sesion = $_SESSION["cedula"] ?? "1-0945-0612";

// Consultar datos reales del usuario en la base de datos 
try {
    $stmtUsuario = $pdo->prepare("
        SELECT u.cedula, u.nombre, u.apellido1, u.apellido2, r.nombre AS nombre_rol
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        WHERE u.cedula = :cedula
        LIMIT 1
    ");
    $stmtUsuario->execute([':cedula' => $cedula_sesion]);
    $filaUsuario = $stmtUsuario->fetch();

    if ($filaUsuario) {
        $nombre_completo = trim($filaUsuario['nombre'] . " " . $filaUsuario['apellido1']);
        if (!empty($filaUsuario['nombre_rol'])) {
            $rol = $filaUsuario['nombre_rol'];
        }
    }
} catch (PDOException $e) {
    // Si falla la consulta, conserva los valores de sesión o por defecto
}

$primer_nombre = explode(" ", trim($nombre_completo))[0];
$titulo_miga = "Inicio";

$hora = (int)date('G');
$saludo = ($hora < 12) ? "BUENOS DÍAS" : (($hora < 18) ? "BUENAS TARDES" : "BUENAS NOCHES");

$dias_es = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
$meses_es = ["", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
$fecha_hoy_texto = $dias_es[(int)date('w')] . ", " . date('j') . " de " . $meses_es[(int)date('n')] . " de " . date('Y');

// Consultar fecha límite de entrega desde la tabla periodos
$fecha_limite_defecto = ($mes_actual >= 2 && $mes_actual <= 7) ? "{$anio_escolar}-07-17" : "{$anio_escolar}-11-27";
$fecha_limite_bd = $fecha_limite_defecto;

try {
    $stmtPeriodo = $pdo->prepare("
        SELECT cierre_entrega
        FROM periodos
        WHERE anio_escolar = :anio
        ORDER BY id_periodo DESC
        LIMIT 1
    ");
    $stmtPeriodo->execute([':anio' => $anio_escolar]);
    $filaPeriodo = $stmtPeriodo->fetch();

    if ($filaPeriodo && !empty($filaPeriodo['cierre_entrega'])) {
        $fecha_limite_bd = date('Y-m-d', strtotime($filaPeriodo['cierre_entrega']));
    }
} catch (PDOException $e) {
    // Usa la fecha por defecto si no hay registro aún
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
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
                    <strong id="menu-nombre-usuario"><?php echo htmlspecialchars($nombre_completo); ?></strong>
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
    <main class="contenido contenido-inicio">
        <div class="dashboard-wrapper">

            <header class="card-bienvenida-moderna">
                <div class="bienvenida-izq">
                    <span class="saludo-tag"><?php echo $saludo; ?></span>
                    <h1>¡Hola, <span class="nombre-destacado" id="saludo-primer-nombre"><?php echo htmlspecialchars($primer_nombre); ?></span>!</h1>
                    <p>Te damos la bienvenida al sistema de evaluaciones docentes</p>
                </div>

                <div class="bienvenida-der">
                    <div class="pill-anio-escolar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>Año escolar <strong><?php echo $anio_escolar; ?></strong></span>
                    </div>
                    <span class="fecha-actual-sub"><?php echo $fecha_hoy_texto; ?></span>
                </div>
            </header>

            <section class="card-control-periodo-moderna">
                <div class="top-control-periodo">
                    <div class="titulo-con-icono">
                        <div class="icono-calendario-institucional">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="titulo-subtitulo-periodo">
                            <h2>Control del Periodo</h2>
                            <p>Gestión de vigencia y cierre de recepción de planeamientos</p>
                        </div>
                    </div>

                    <button type="button" class="btn-modificar-periodo" id="btn-activar-edicion-fecha" onclick="activarEdicionFecha()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Modificar fecha</span>
                    </button>
                </div>

                <div class="franja-datos-periodo">
                    <div class="dato-periodo-col">
                        <span class="sublabel-periodo">Periodo vigente (MEP)</span>
                        <strong class="valor-periodo-destacado" id="nombre-periodo-vigente"><?php echo htmlspecialchars($periodo_vigente_auto); ?></strong>
                    </div>

                    <div class="separador-subcaja"></div>

                    <div class="dato-periodo-col">
                        <span class="sublabel-periodo">Estado actual</span>
                        <span class="pill-estado-activo abierto" id="pill-estado-periodo">
                            <span class="punto-verde"></span>
                            <span id="badge-periodo">Activo</span>
                        </span>
                    </div>

                    <div class="separador-subcaja"></div>

                    <div class="dato-periodo-col col-fecha-entrega">
                        <span class="sublabel-periodo">Fecha límite de entrega</span>

                        <div id="vista-fecha-lectura">
                            <strong class="valor-fecha-grande" id="fecha-limite-periodo">27 Noviembre <?php echo $anio_escolar; ?></strong>
                        </div>

                        <div id="vista-fecha-edicion" class="editor-fecha-inline" style="display: none;">
                            <div class="caja-input-fecha-inline">
                                <input type="date" id="input-fecha-limite-inline" value="<?php echo htmlspecialchars($fecha_limite_bd); ?>">
                            </div>
                            <div class="acciones-fecha-inline">
                                <button type="button" class="btn-cancelar-fecha-inline" onclick="cancelarEdicionFecha()">Cancelar</button>
                                <button type="button" class="btn-guardar-fecha-inline" onclick="guardarFechaInline()">Guardar fecha</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid-tres-metricas">
                <div class="card-kpi borde-azul">
                    <div class="kpi-top">
                        <span>Evaluaciones completadas</span>
                        <div class="kpi-icono azul">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="kpi-valor">
                        <strong id="metrica-docentes-num">0</strong>
                        <span id="metrica-docentes-sufijo">Docentes</span>
                    </div>
                </div>

                <div class="card-kpi borde-verde">
                    <div class="kpi-top">
                        <span>Progreso general</span>
                        <div class="kpi-icono verde">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="kpi-valor-barra">
                        <strong id="metrica-porcentaje-texto">0%</strong>
                        <div class="barra-kpi-track">
                            <div class="barra-kpi-fill" id="metrica-barra-relleno" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>

                <div class="card-kpi borde-naranja">
                    <div class="kpi-top">
                        <span>Próximo Cierre</span>
                        <div class="kpi-icono naranja">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="kpi-valor">
                        <strong id="metrica-dias-num">9</strong>
                        <span id="metrica-dias-sufijo">días</span>
                    </div>
                </div>
            </section>

            <section class="grid-dos-tareas">
                <div class="card-tareas-moderna">
                    <div class="tareas-card-header">
                        <h3>Tareas Pendientes</h3>
                        <span class="tag-estado-tareas por-hacer">Por hacer</span>
                    </div>

                    <form class="form-nueva-tarea" onsubmit="agregarNuevaTarea(event)">
                        <input type="text" id="input-nueva-tarea" placeholder="+ Agregar nueva tarea..." maxlength="80">
                        <button type="submit">Añadir</button>
                    </form>

                    <ul class="lista-tareas-interactiva" id="lista-tareas-pendientes"></ul>
                </div>

                <div class="card-tareas-moderna">
                    <div class="tareas-card-header">
                        <h3>Tareas realizadas</h3>
                        <span class="tag-estado-tareas completadas">Completadas</span>
                    </div>

                    <ul class="lista-tareas-interactiva" id="lista-tareas-realizadas"></ul>
                </div>
            </section>

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
            <a href="principal.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const tareasIniciales = {
        pendientes: [
            { id: 1, texto: "Evaluar 3 docentes" },
            { id: 2, texto: "Revisar 5 planeamientos" },
            { id: 3, texto: "Enviar 2 evaluaciones" },
            { id: 4, texto: "Evaluar 1 docente" }
        ],
        realizadas: [
            { id: 101, texto: "Evaluación para Manuel finalizada" },
            { id: 102, texto: "Evaluación para Esther finalizada" },
            { id: 103, texto: "Revisión de planeamientos finalizada" }
        ]
    };

    let estadoTareas = JSON.parse(JSON.stringify(tareasIniciales));
    let fechaGuardadaActual = "";

    function sincronizarPerfilMenu() {
        const perfilGuardado = JSON.parse(localStorage.getItem('perfil_coordinador_cedes') || 'null');
        if (!perfilGuardado) return;

        const elNombreMenu = document.getElementById('menu-nombre-usuario');
        const elAvatarMenu = document.getElementById('menu-avatar-iniciales');
        const elSaludoNombre = document.getElementById('saludo-primer-nombre');

        if (perfilGuardado.nombre) {
            const partes = perfilGuardado.nombre.trim().split(/\s+/);
            if (elNombreMenu) elNombreMenu.textContent = perfilGuardado.nombre;
            if (elSaludoNombre && partes[0]) elSaludoNombre.textContent = partes[0];

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

    function obtenerPeriodoMepActual() {
        const hoy = new Date();
        const anio = hoy.getFullYear();
        const mes = hoy.getMonth() + 1;
        const semestre = (mes >= 2 && mes <= 7) ? "I Periodo" : "II Periodo";
        return `${semestre} ${anio}`;
    }

    function formatearFechaEsp(fechaIso) {
        const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        const [anio, mes, dia] = fechaIso.split("-").map(Number);
        if (!anio || !mes || !dia) return fechaIso;
        return `${dia} ${meses[mes - 1]} ${anio}`;
    }

    function activarEdicionFecha() {
        document.getElementById('vista-fecha-lectura').style.display = 'none';
        document.getElementById('vista-fecha-edicion').style.display = 'flex';
        document.getElementById('btn-activar-edicion-fecha').style.visibility = 'hidden';
        const input = document.getElementById('input-fecha-limite-inline');
        input.value = fechaGuardadaActual;
        input.focus();
    }

    function cancelarEdicionFecha() {
        document.getElementById('input-fecha-limite-inline').value = fechaGuardadaActual;
        document.getElementById('vista-fecha-edicion').style.display = 'none';
        document.getElementById('vista-fecha-lectura').style.display = 'block';
        document.getElementById('btn-activar-edicion-fecha').style.visibility = 'visible';
    }

    function guardarFechaInline() {
        const nuevaFecha = document.getElementById('input-fecha-limite-inline').value;
        if (!nuevaFecha) return;

        fechaGuardadaActual = nuevaFecha;
        localStorage.setItem('fecha_limite_mep_cedes', nuevaFecha);
        aplicarFechaYCalendarioMep(nuevaFecha);
        actualizarMetricasDinamicas();

        document.getElementById('vista-fecha-edicion').style.display = 'none';
        document.getElementById('vista-fecha-lectura').style.display = 'block';
        document.getElementById('btn-activar-edicion-fecha').style.visibility = 'visible';
    }

    function aplicarFechaYCalendarioMep(fechaIso) {
        fechaGuardadaActual = fechaIso;
        const periodoMep = obtenerPeriodoMepActual();
        document.getElementById('nombre-periodo-vigente').textContent = periodoMep;

        document.getElementById('fecha-limite-periodo').textContent = formatearFechaEsp(fechaIso);
        document.getElementById('input-fecha-limite-inline').value = fechaIso;

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const [anio, mes, dia] = fechaIso.split("-").map(Number);
        const fechaLimite = new Date(anio, mes - 1, dia);

        const diffMs = fechaLimite - hoy;
        const diasRestantes = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
        const estaActivo = diasRestantes >= 0;

        const pill = document.getElementById('pill-estado-periodo');
        const badge = document.getElementById('badge-periodo');
        badge.textContent = estaActivo ? "Activo" : "Cerrado";
        pill.className = 'pill-estado-activo ' + (estaActivo ? 'abierto' : 'cerrado');

        const diasMostrar = Math.max(0, diasRestantes);
        document.getElementById('metrica-dias-num').textContent = diasMostrar;
        document.getElementById('metrica-dias-sufijo').textContent = (diasMostrar === 1) ? "día" : "días";
    }

    function actualizarMetricasDinamicas() {
        const docentes = JSON.parse(localStorage.getItem('lista_docentes_cedes') || '[]');
        const evaluaciones = JSON.parse(localStorage.getItem('evaluaciones_docentes_cedes') || '{}');
        const plantillas = JSON.parse(localStorage.getItem('plantillas_rubrica_cedes_v2') || '[]');

        const cedulasValidas = new Set(docentes.map(d => d.cedula));
        const docentesRevisados = Object.keys(evaluaciones).filter(ced =>
            docentes.length === 0 || cedulasValidas.has(ced)
        ).length;

        const elNumDoc = document.getElementById('metrica-docentes-num');
        const elSufDoc = document.getElementById('metrica-docentes-sufijo');
        if (elNumDoc) elNumDoc.textContent = docentesRevisados;
        if (elSufDoc) elSufDoc.textContent = (docentesRevisados === 1) ? "Docente" : "Docentes";

        const totalDocentes = docentes.length > 0 ? docentes.length : 4;
        const avanceEvaluaciones = Math.min(docentesRevisados / totalDocentes, 1);

        const cantPendientes = estadoTareas.pendientes.length;
        const cantRealizadas = estadoTareas.realizadas.length;
        const totalTareas = cantPendientes + cantRealizadas;
        const avanceTareas = totalTareas > 0 ? (cantRealizadas / totalTareas) : 0;

        const tienePlantillaActiva = plantillas.some(p => p.activa) ? 1 : 0.5;
        const tieneFechaConfigurada = localStorage.getItem('fecha_limite_mep_cedes') ? 1 : 0.5;
        const avanceGestion = (tienePlantillaActiva + tieneFechaConfigurada) / 2;

        const porcentajeFinal = Math.round(
            (avanceEvaluaciones * 50) +
            (avanceTareas * 35) +
            (avanceGestion * 15)
        );

        const elPorcentaje = document.getElementById('metrica-porcentaje-texto');
        const elBarra = document.getElementById('metrica-barra-relleno');
        if (elPorcentaje) elPorcentaje.textContent = `${porcentajeFinal}%`;
        if (elBarra) elBarra.style.width = `${porcentajeFinal}%`;
    }

    function guardarYRenderizarTareas() {
        localStorage.setItem('tareas_dashboard_cedes_v3', JSON.stringify(estadoTareas));
        renderizarTareas();
    }

    function renderizarTareas() {
        const ulPend = document.getElementById('lista-tareas-pendientes');
        const ulReal = document.getElementById('lista-tareas-realizadas');

        if (estadoTareas.pendientes.length === 0) {
            ulPend.innerHTML = `<li class="tarea-vacia">¡Excelente! No tienes tareas pendientes.</li>`;
        } else {
            ulPend.innerHTML = estadoTareas.pendientes.map((t, i) => `
                <li class="item-tarea-pendiente" onclick="completarTarea(${i})" title="Clic para marcar como realizada">
                    <span class="check-cuadro">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                    <span class="texto-tarea">${t.texto}</span>
                    <span class="accion-hover-tag">Realizada →</span>
                </li>
            `).join("");
        }

        if (estadoTareas.realizadas.length === 0) {
            ulReal.innerHTML = `<li class="tarea-vacia">Aún no hay tareas completadas.</li>`;
        } else {
            ulReal.innerHTML = estadoTareas.realizadas.map((t, i) => `
                <li class="item-tarea-realizada" onclick="devolverAPendientes(${i})" title="Clic para devolver a tareas pendientes">
                    <span class="check-circulo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                    <span class="texto-tarea">${t.texto}</span>
                    <span class="accion-hover-tag devolver">← Pendiente</span>
                </li>
            `).join("");
        }

        actualizarMetricasDinamicas();
    }

    function completarTarea(index) {
        const [tareaMovida] = estadoTareas.pendientes.splice(index, 1);
        if (tareaMovida) {
            estadoTareas.realizadas.unshift(tareaMovida);
            guardarYRenderizarTareas();
        }
    }

    function devolverAPendientes(index) {
        const [tareaDevuelta] = estadoTareas.realizadas.splice(index, 1);
        if (tareaDevuelta) {
            estadoTareas.pendientes.push(tareaDevuelta);
            guardarYRenderizarTareas();
        }
    }

    function agregarNuevaTarea(e) {
        e.preventDefault();
        const input = document.getElementById('input-nueva-tarea');
        const texto = input.value.trim();
        if (!texto) return;

        estadoTareas.pendientes.push({ id: Date.now(), texto: texto });
        input.value = "";
        guardarYRenderizarTareas();
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

        const fechaDesdeBD = "<?php echo htmlspecialchars($fecha_limite_bd); ?>";
        const fechaGuardada = localStorage.getItem('fecha_limite_mep_cedes') || fechaDesdeBD;

        aplicarFechaYCalendarioMep(fechaGuardada);

        const tareasGuardadas = localStorage.getItem('tareas_dashboard_cedes_v3');
        if (tareasGuardadas) {
            estadoTareas = JSON.parse(tareasGuardadas);
        }
        renderizarTareas();
    };

    window.addEventListener('focus', function() {
        sincronizarPerfilMenu();
        actualizarMetricasDinamicas();
    });
    window.addEventListener('storage', function() {
        sincronizarPerfilMenu();
        actualizarMetricasDinamicas();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cancelarEdicionFecha();
        }
    });
</script>

</body>
</html>