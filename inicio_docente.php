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
$titulo_miga = "Inicio";

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
    <title>Inicio Docente - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="inicio_docente.css?v=5">
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
            <a href="inicio_docente.php" class="activo" title="Home">
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

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido docente-main">
        <div class="docente-wrapper">

            <?php if (file_exists('breadcrumbs.php')) include 'breadcrumbs.php'; ?>

            <!-- TARJETA DE BIENVENIDA -->
            <header class="card-bienvenida-docente">
                <div class="bienvenida-docente-izq">
                    <span class="saludo-tag-docente">PANEL DOCENTE · EN VIVO</span>
                    <h1 id="saludo-docente">¡Hola, <?php echo htmlspecialchars(explode(' ', trim($nombre))[0]); ?>!</h1>
                    <p>Te damos la bienvenida al sistema de evaluaciones docentes.</p>
                </div>

                <div class="bienvenida-docente-der">
                    <div class="pill-anio-escolar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>Año escolar <strong><?php echo $anio_escolar; ?></strong></span>
                    </div>
                    <span class="sub-periodo-docente"><?php echo htmlspecialchars($periodo_mep_auto . " " . $anio_escolar); ?></span>
                </div>
            </header>

            <!-- TARJETA ENTREGA DE PLANEAMIENTOS -->
            <section class="card-periodo-docente">
                <div class="cabecera-widget-docente">
                    <div class="icono-widget-docente azul">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <h2>Entrega de planeamientos</h2>
                        <p>Periodo vigente, estado de entrega y fecha límite de recepción</p>
                    </div>
                </div>

                <div class="franja-entrega-docente">
                    <div class="entrega-col">
                        <span class="etiqueta-suave">Periodo vigente (MEP)</span>
                        <strong class="periodo-mep-texto" id="periodo-vigente-mep"><?php echo htmlspecialchars($periodo_mep_auto . " " . $anio_escolar); ?></strong>
                    </div>

                    <div class="divisor-vertical"></div>

                    <div class="entrega-col">
                        <span class="etiqueta-suave">Estado actual</span>
                        <span class="badge-activo-docente" id="estado-periodo-docente">
                            <span class="punto-estado"></span>
                            Activo
                        </span>
                    </div>

                    <div class="divisor-vertical"></div>

                    <div class="entrega-col">
                        <span class="etiqueta-suave">Fecha límite de entrega</span>
                        <strong class="fecha-limite-texto" id="fecha-limite-docente">27 Noviembre 2026</strong>
                    </div>
                </div>
            </section>

            <!-- 3 TARJETAS KPI INTERACTIVAS -->
            <section class="grid-kpis-docente">
                <article class="kpi-docente-card borde-azul kpi-interactivo" onclick="window.location.href='planeamiento_docente.php'" title="Ir a Mis archivos">
                    <div class="kpi-top-docente">
                        <span>Planeamientos entregados</span>
                        <span class="kpi-icono-docente azul">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </span>
                    </div>
                    <div class="kpi-fila-subtexto">
                        <strong class="kpi-docente-valor" id="kpi-planeamientos-entregados">1/2</strong>
                        <span class="kpi-ayuda-link" id="kpi-subtexto-planeamientos">Ver entregas →</span>
                    </div>
                </article>

                <article class="kpi-docente-card borde-verde kpi-interactivo" onclick="window.location.href='mis_evaluaciones.php'" title="Ver mis evaluaciones">
                    <div class="kpi-top-docente">
                        <span>Evaluaciones recibidas</span>
                        <span class="kpi-icono-docente verde">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                    </div>
                    <div class="kpi-progreso-fila">
                        <strong class="kpi-docente-valor" id="kpi-evaluaciones-recibidas">0</strong>
                        <div class="barra-progreso-docente">
                            <div class="barra-progreso-relleno" id="barra-progreso-eval" style="width: 0%;"></div>
                        </div>
                    </div>
                </article>

                <article class="kpi-docente-card borde-naranja">
                    <div class="kpi-top-docente">
                        <span>Próximo Cierre:</span>
                        <span class="kpi-icono-docente naranja">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </span>
                    </div>
                    <div class="kpi-cierre-fila">
                        <strong class="kpi-docente-valor" id="kpi-proximo-cierre-num">2</strong>
                        <span class="kpi-cierre-unidad">días</span>
                    </div>
                </article>
            </section>

            <!-- SECCIÓN INFERIOR: SUBIR PLANEAMIENTO Y ESTATUS EN TIEMPO REAL -->
            <section class="grid-inferior-docente">
                <article class="tarjeta-subir-planeamiento">
                    <div class="cabecera-subir">
                        <div class="cabecera-subir-izq">
                            <h2>Subir planeamiento</h2>
                            <span class="check-cabecera" id="icono-check-subida" title="Listo para cargar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="9 12 11 14 15 10"></polyline>
                                </svg>
                            </span>
                        </div>
                        <span class="tag-formato-pdf">PDF</span>
                    </div>

                    <div class="zona-arrastre-pdf" id="zona-arrastre-pdf"
                         ondragover="event.preventDefault(); this.classList.add('arrastrando');"
                         ondragleave="this.classList.remove('arrastrando');"
                         ondrop="manejarDropArchivo(event)">
                        <div class="circulo-nube-pdf">
                            <svg class="icono-nube-subida" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <p id="texto-arrastre-pdf">Arrastra o suelta tu planeamiento aquí</p>
                        <input type="file" id="input-pdf-docente" accept="application/pdf,.pdf" style="display: none;" onchange="manejarSeleccionArchivo(event)">
                        <button type="button" class="btn-seleccionar-archivo" onclick="document.getElementById('input-pdf-docente').click()">
                            Seleccionar archivo
                        </button>
                    </div>
                </article>

                <article class="tarjeta-estatus-planeamiento">
                    <div class="cabecera-estatus-docente">
                        <h2>Estatus de mi planeamiento</h2>
                        <span class="tag-seguimiento-docente" id="tag-estado-vivo">Tiempo real</span>
                    </div>

                    <ul class="lista-estatus-docente" id="lista-estatus-docente">
                        <li class="item-estatus completado" id="estatus-subido">
                            <span class="icono-estatus verde">✓</span>
                            <span>Planeamiento subido: <strong id="fecha-subido-texto">15 de Octubre</strong></span>
                        </li>
                        <li class="item-estatus en-proceso" id="estatus-revision">
                            <span class="icono-estatus azul">◔</span>
                            <span id="texto-estatus-revision">En revisión por Coordinación</span>
                        </li>
                        <li class="item-estatus pendiente" id="estatus-calificacion">
                            <span class="icono-estatus gris">•</span>
                            <span id="texto-estatus-calificacion">Calificación pendiente de entrega</span>
                        </li>
                    </ul>

                    <div class="pie-estatus-interactivo" id="pie-estatus-detalle">
                        Sincronizado con Coordinación y Mis evaluaciones.
                    </div>
                </article>
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
            <a href="inicio_docente.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const cedulaActual = "<?php echo addslashes($cedula_sesion); ?>";
    const totalPlaneamientosMeta = 2;

    function sincronizarPerfilDocente() {
        const guardado = JSON.parse(localStorage.getItem('perfil_docente_cedes') || 'null');
        const nombreBase = (guardado && guardado.nombre) ? guardado.nombre : document.getElementById('menu-nombre-usuario').textContent;

        const primerNombre = nombreBase.trim().split(/\s+/)[0] || "Docente";
        document.getElementById('saludo-docente').textContent = `¡Hola, ${primerNombre}!`;
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

    function obtenerFechaLimiteISO() {
        const clavesPosibles = [
            'fecha_limite_planeamiento_cedes',
            'fecha_limite_entrega_cedes',
            'fecha_cierre_periodo_cedes'
        ];
        for (const clave of clavesPosibles) {
            const valor = localStorage.getItem(clave);
            if (valor && /^\d{4}-\d{2}-\d{2}$/.test(valor.trim())) {
                return valor.trim();
            }
        }
        return "2026-11-27";
    }

    function calcularDiasRestantes(fechaISO) {
        const partes = fechaISO.split('-').map(Number);
        const fechaLimite = new Date(partes[0], partes[1] - 1, partes[2]);
        const ahora = new Date();
        const hoy = new Date(ahora.getFullYear(), ahora.getMonth(), ahora.getDate());
        const diffMs = fechaLimite.getTime() - hoy.getTime();
        return Math.max(0, Math.round(diffMs / (1000 * 60 * 60 * 24)));
    }

    function obtenerEntregasDocente() {
        const rawHist = localStorage.getItem(`historial_planeamientos_${cedulaActual}`);
        if (rawHist) {
            try {
                const arr = JSON.parse(rawHist);
                if (Array.isArray(arr) && arr.length > 0) return arr;
            } catch (e) {}
        }

        const docentes = JSON.parse(localStorage.getItem('lista_docentes_cedes') || '[]');
        const doc = docentes.find(d => d.cedula === cedulaActual);
        if (doc && doc.pdf) {
            return [{ titulo: "Planeamiento 1", archivo: doc.pdf, fecha: "15 de Octubre" }];
        }

        return [{ titulo: "Planeamiento 1", archivo: "Planeamiento_01_2026.pdf", fecha: "15 de Octubre" }];
    }

    function obtenerEvaluacionesDelDocente() {
        const evalGlobal = JSON.parse(localStorage.getItem('evaluaciones_docentes_cedes') || '{}');
        const evalHist = JSON.parse(localStorage.getItem(`historial_evaluaciones_${cedulaActual}`) || '[]');

        const listaUnica = [];
        const clavesVistas = new Set();

        const agregarEvaluacion = (ev, indiceFallback) => {
            if (!ev || typeof ev !== 'object') return;
            const idUnico = ev.idEvaluacion || ev.evaluacionId || ev.codigo || ev.fecha || `eval_${indiceFallback}`;
            if (!clavesVistas.has(idUnico)) {
                clavesVistas.add(idUnico);
                listaUnica.push(ev);
            }
        };

        if (Array.isArray(evalHist)) {
            evalHist.forEach((ev, idx) => agregarEvaluacion(ev, `h_${idx}`));
        }

        const porCedula = evalGlobal[cedulaActual];
        if (Array.isArray(porCedula)) {
            porCedula.forEach((ev, idx) => agregarEvaluacion(ev, `c_${idx}`));
        } else if (porCedula && typeof porCedula === 'object') {
            agregarEvaluacion(porCedula, "main");
        }

        return listaUnica;
    }

    function estaCoordinadorEvaluandoAhora() {
        const activoLocal = JSON.parse(localStorage.getItem('evaluando_docente_cedes') || 'null');
        const activoSession = JSON.parse(sessionStorage.getItem('evaluando_docente_cedes') || 'null');
        const estado = activoLocal || activoSession;

        if (!estado) return false;
        const coincideCedula = estado.cedula && String(estado.cedula) === String(cedulaActual);
        const coincideNombre = estado.nombre && document.getElementById('menu-nombre-usuario').textContent.trim().toLowerCase() === String(estado.nombre).trim().toLowerCase();
        return Boolean(estado.enProgreso !== false && (coincideCedula || coincideNombre));
    }

    function actualizarDashboardEnTiempoReal() {
        sincronizarPerfilDocente();

        // 1. Periodo MEP y Próximo Cierre
        const periodoGuardado = localStorage.getItem('periodo_vigente_mep_cedes');
        if (periodoGuardado) {
            document.getElementById('periodo-vigente-mep').textContent = periodoGuardado;
        }

        const fechaISO = obtenerFechaLimiteISO();
        const partes = fechaISO.split('-');
        const meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
        const fechaObj = new Date(Number(partes[0]), Number(partes[1]) - 1, Number(partes[2]));
        document.getElementById('fecha-limite-docente').textContent = `${fechaObj.getDate()} ${meses[fechaObj.getMonth()]} ${fechaObj.getFullYear()}`;

        const diasRestantes = calcularDiasRestantes(fechaISO);
        document.getElementById('kpi-proximo-cierre-num').textContent = String(diasRestantes);
        document.querySelector('.kpi-cierre-unidad').textContent = diasRestantes === 1 ? 'día' : 'días';

        const badgeEstado = document.getElementById('estado-periodo-docente');
        if (diasRestantes === 0) {
            badgeEstado.innerHTML = `<span class="punto-estado"></span>Cerrado`;
            badgeEstado.classList.add('cerrado');
        } else {
            badgeEstado.innerHTML = `<span class="punto-estado"></span>Activo`;
            badgeEstado.classList.remove('cerrado');
        }

        // 2. Planeamientos entregados (1/2 -> 2/2)
        const entregas = obtenerEntregasDocente();
        const entregadosCount = entregas.length;
        document.getElementById('kpi-planeamientos-entregados').textContent = `${entregadosCount}/${totalPlaneamientosMeta}`;
        document.getElementById('kpi-subtexto-planeamientos').textContent =
            entregadosCount >= totalPlaneamientosMeta ? "Entrega completa ✓" : "Falta 1 planeamiento →";

        const ultimaEntrega = entregas[entregadosCount - 1];
        document.getElementById('fecha-subido-texto').textContent = ultimaEntrega?.fecha || "15 de Octubre";

        // 3. Evaluaciones recibidas (cada evaluación distinta)
        const evaluaciones = obtenerEvaluacionesDelDocente();
        const cantEvaluaciones = evaluaciones.length;
        document.getElementById('kpi-evaluaciones-recibidas').textContent = String(cantEvaluaciones);
        const porcentajeBarra = Math.min(100, Math.round((cantEvaluaciones / totalPlaneamientosMeta) * 100));
        document.getElementById('barra-progreso-eval').style.width = `${porcentajeBarra}%`;

        // 4. Estatus de mi planeamiento en tiempo real
        const evaluandoAhora = estaCoordinadorEvaluandoAhora();
        const itemRev = document.getElementById('estatus-revision');
        const txtRev = document.getElementById('texto-estatus-revision');
        const itemCal = document.getElementById('estatus-calificacion');
        const txtCal = document.getElementById('texto-estatus-calificacion');
        const tagVivo = document.getElementById('tag-estado-vivo');
        const pieDetalle = document.getElementById('pie-estatus-detalle');

        if (evaluandoAhora) {
            itemRev.className = "item-estatus evaluando-vivo";
            itemRev.querySelector('.icono-estatus').className = "icono-estatus ambar";
            itemRev.querySelector('.icono-estatus').textContent = "!";
            txtRev.innerHTML = `<strong>Coordinación está evaluando tu planeamiento ahora</strong>`;
            tagVivo.textContent = "Evaluando";
            tagVivo.className = "tag-seguimiento-docente vivo";
            pieDetalle.textContent = "El coordinador tiene abierta tu evaluación en este momento.";
        } else if (cantEvaluaciones > 0) {
            itemRev.className = "item-estatus completado";
            itemRev.querySelector('.icono-estatus').className = "icono-estatus verde";
            itemRev.querySelector('.icono-estatus').textContent = "✓";
            txtRev.textContent = "Revisión completada por Coordinación";
            tagVivo.textContent = "Actualizado";
            tagVivo.className = "tag-seguimiento-docente";
            pieDetalle.textContent = `Has recibido ${cantEvaluaciones} ${cantEvaluaciones === 1 ? 'evaluación' : 'evaluaciones'} con respaldo en correo y sistema.`;
        } else {
            itemRev.className = "item-estatus en-proceso";
            itemRev.querySelector('.icono-estatus').className = "icono-estatus azul";
            itemRev.querySelector('.icono-estatus').textContent = "◔";
            txtRev.textContent = "En revisión por Coordinación";
            tagVivo.textContent = "Tiempo real";
            tagVivo.className = "tag-seguimiento-docente";
            pieDetalle.textContent = "Aún no se ha entregado calificación final.";
        }

        if (cantEvaluaciones > 0) {
            const ultimaEv = evaluaciones[cantEvaluaciones - 1];
            const fechaEv = ultimaEv?.fecha ? ` (${ultimaEv.fecha})` : "";
            itemCal.className = "item-estatus completado";
            itemCal.querySelector('.icono-estatus').className = "icono-estatus verde";
            itemCal.querySelector('.icono-estatus').textContent = "✓";
            txtCal.innerHTML = `Calificación finalizada y entregada${fechaEv}`;
        } else {
            itemCal.className = "item-estatus pendiente";
            itemCal.querySelector('.icono-estatus').className = "icono-estatus gris";
            itemCal.querySelector('.icono-estatus').textContent = "•";
            txtCal.textContent = "Calificación pendiente de entrega";
        }
    }

    function guardarArchivoPlaneamiento(archivo) {
        if (!archivo) return;
        const lector = new FileReader();
        lector.onload = function(ev) {
            const hoy = new Date();
            const fechaFormateada = `${String(hoy.getDate()).padStart(2, '0')}/${String(hoy.getMonth() + 1).padStart(2, '0')}/${hoy.getFullYear()}`;

            const entregas = obtenerEntregasDocente();
            entregas.push({
                titulo: `Planeamiento ${entregas.length + 1}`,
                archivo: archivo.name,
                fecha: fechaFormateada,
                estado: "Entregado"
            });
            localStorage.setItem(`historial_planeamientos_${cedulaActual}`, JSON.stringify(entregas));

            const docentes = JSON.parse(localStorage.getItem('lista_docentes_cedes') || '[]');
            const idx = docentes.findIndex(d => d.cedula === cedulaActual);
            if (idx !== -1) {
                docentes[idx].pdf = archivo.name;
                docentes[idx].pdfData = ev.target.result;
                localStorage.setItem('lista_docentes_cedes', JSON.stringify(docentes));
            }

            document.getElementById('texto-arrastre-pdf').innerHTML = `Archivo listo: <strong>${archivo.name}</strong>`;
            actualizarDashboardEnTiempoReal();
        };
        lector.readAsDataURL(archivo);
    }

    function manejarSeleccionArchivo(e) {
        const archivo = e.target.files[0];
        guardarArchivoPlaneamiento(archivo);
        e.target.value = "";
    }

    function manejarDropArchivo(e) {
        e.preventDefault();
        document.getElementById('zona-arrastre-pdf').classList.remove('arrastrando');
        const archivo = e.dataTransfer.files[0];
        guardarArchivoPlaneamiento(archivo);
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

    window.addEventListener('storage', actualizarDashboardEnTiempoReal);

    window.onload = function() {
        actualizarDashboardEnTiempoReal();
        setInterval(actualizarDashboardEnTiempoReal, 1500);
    };
</script>

</body>
</html>