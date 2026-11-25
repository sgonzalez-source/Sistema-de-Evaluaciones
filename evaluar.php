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
$periodo_actual = ($mes_actual >= 2 && $mes_actual <= 7) ? "I Periodo" : "II Periodo";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación Docente - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="evaluar.css?v=2">
</head>
<body>

<div class="contenedor-general">

    <!-- SIDEBAR COORDINADOR -->
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

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenido contenido-evaluar">
        <div class="evaluar-wrapper">

            <?php if (file_exists('breadcrumbs.php')) include 'breadcrumbs.php'; ?>

            <header class="banner-seccion">
                <div class="banner-izq">
                    <h1 class="titulo-seccion-bold">Evaluación docente</h1>
                    <p class="subtitulo-banner">Selecciona el docente, califica cada criterio de la rúbrica y envía el respaldo al correo y al sistema.</p>
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

            <!-- SELECCIÓN DE DOCENTE Y SECCIÓN -->
            <section class="card-seleccion-docente">
                <div class="campo-seleccion">
                    <label for="select-docente-evaluar">Docente a evaluar</label>
                    <select id="select-docente-evaluar" onchange="cambiarDocenteSeleccionado()">
                        <!-- Se llena dinámicamente desde lista_docentes_cedes o por defecto -->
                    </select>
                </div>

                <div class="campo-seleccion">
                    <label for="input-seccion-evaluar">Sección</label>
                    <input type="text" id="input-seccion-evaluar" value="12-2">
                </div>

                <div class="resumen-puntaje-vivo">
                    <span>Puntaje acumulado</span>
                    <strong id="puntaje-vivo-texto">0 / 20 pts</strong>
                </div>
            </section>

            <!-- MATRIZ DE EVALUACIÓN -->
            <section class="card-matriz-evaluar">
                <div class="tabla-evaluar-scroll">
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

        <h2>Evaluación registrada</h2>
        <p id="mensaje-eval-guardada">Se guardó el desglose en el sistema y se notificó por correo al docente.</p>

        <div class="botones-modal" style="justify-content: center;">
            <button type="button" class="btn-modal-guardar-verde" onclick="cerrarModal('modal-eval-guardada')">Aceptar</button>
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
    const anioEscolar = "<?php echo $anio_escolar; ?>";
    const periodoActual = "<?php echo addslashes($periodo_actual); ?>";

    const docentesBase = [
        { cedula: "100564782", nombre: "Lucía Rodríguez", correo: "lrodriguez@cedesdonbosco.ed.cr", materia: "Español" },
        { cedula: "187263944", nombre: "Randall Ocón", correo: "rocon@cedesdonbosco.ed.cr", materia: "Matemáticas" }
    ];

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

    let puntajesSeleccionados = [4, 4, 4, 4, 4];

    function obtenerListaDocentes() {
        const guardados = JSON.parse(localStorage.getItem('lista_docentes_cedes') || '[]');
        if (Array.isArray(guardados) && guardados.length > 0) {
            return guardados.map(d => ({
                cedula: d.cedula || "100564782",
                nombre: d.nombre || "Docente",
                correo: d.correo || "docente@cedesdonbosco.ed.cr",
                materia: d.materia || "General"
            }));
        }
        return docentesBase;
    }

    function notificarEvaluandoEnTiempoReal() {
        const select = document.getElementById('select-docente-evaluar');
        const docentes = obtenerListaDocentes();
        const doc = docentes.find(d => d.cedula === select.value) || docentes[0];
        if (!doc) return;

        localStorage.setItem('evaluando_docente_cedes', JSON.stringify({
            cedula: doc.cedula,
            nombre: doc.nombre,
            enProgreso: true,
            actualizadoEn: Date.now()
        }));
    }

    function limpiarEvaluandoEnTiempoReal() {
        localStorage.removeItem('evaluando_docente_cedes');
    }

    function poblarSelectDocentes() {
        const docentes = obtenerListaDocentes();
        const select = document.getElementById('select-docente-evaluar');
        select.innerHTML = docentes.map(d =>
            `<option value="${d.cedula}">${d.nombre} (${d.materia})</option>`
        ).join('');
        notificarEvaluandoEnTiempoReal();
    }

    function cambiarDocenteSeleccionado() {
        notificarEvaluandoEnTiempoReal();
        renderizarMatrizEvaluar();
    }

    function seleccionarNivel(indiceCriterio, puntos) {
        puntajesSeleccionados[indiceCriterio] = Number(puntos);
        notificarEvaluandoEnTiempoReal();
        renderizarMatrizEvaluar();
    }

    function renderizarMatrizEvaluar() {
        const tbody = document.getElementById('cuerpo-evaluar-matriz');
        let suma = 0;

        tbody.innerHTML = criteriosBase.map((item, idx) => {
            const actualPts = puntajesSeleccionados[idx] ?? 4;
            suma += actualPts;

            const nivelesOrden = [4, 3, 2, 1];
            const celdas = nivelesOrden.map((pts) => {
                const activa = actualPts === pts ? "seleccionada" : "";
                return `
                    <td>
                        <button type="button" class="btn-celda-eval ${activa}" onclick="seleccionarNivel(${idx}, ${pts})">
                            ${item.niveles[pts]}
                        </button>
                    </td>
                `;
            }).join('');

            return `
                <tr>
                    <td class="celda-criterio-eval">${item.criterio}</td>
                    ${celdas}
                    <td class="celda-pts-eval"><strong>${actualPts} pts</strong></td>
                </tr>
            `;
        }).join('');

        document.getElementById('puntaje-vivo-texto').textContent = `${suma} / ${criteriosBase.length * 4} pts`;
    }

    function guardarYEnviarEvaluacion() {
        const select = document.getElementById('select-docente-evaluar');
        const docentes = obtenerListaDocentes();
        const doc = docentes.find(d => d.cedula === select.value) || docentes[0];
        if (!doc) return;

        const hoy = new Date();
        const fechaHoy = `${String(hoy.getDate()).padStart(2, '0')}/${String(hoy.getMonth() + 1).padStart(2, '0')}/${hoy.getFullYear()}`;
        const obs = document.getElementById('textarea-observaciones-eval').value.trim() ||
            "Evaluación completada y respaldada por Coordinación Académica.";
        const seccion = document.getElementById('input-seccion-evaluar').value.trim() || "12-2";

        const criteriosDetalle = criteriosBase.map((item, idx) => ({
            criterio: item.criterio,
            puntajeAsignado: puntajesSeleccionados[idx] ?? 4,
            niveles: item.niveles
        }));

        const totalPuntos = criteriosDetalle.reduce((acc, c) => acc + c.puntajeAsignado, 0);
        const maxPuntos = criteriosDetalle.length * 4;
        const porcentaje = Math.round((totalPuntos / maxPuntos) * 100);
        const idEvaluacion = `eval_${Date.now()}_${Math.random().toString(36).slice(2, 7)}`;

        const registroEvaluacion = {
            idEvaluacion,
            cedula: doc.cedula,
            nombre: doc.nombre,
            correo: doc.correo,
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

        // Guarda evaluación actual por cédula
        const evalGlobal = JSON.parse(localStorage.getItem('evaluaciones_docentes_cedes') || '{}');
        evalGlobal[doc.cedula] = registroEvaluacion;
        localStorage.setItem('evaluaciones_docentes_cedes', JSON.stringify(evalGlobal));

        // Agrega al historial de evaluaciones del docente para contar cada evaluación distinta
        const claveHist = `historial_evaluaciones_${doc.cedula}`;
        const histDocente = JSON.parse(localStorage.getItem(claveHist) || '[]');
        histDocente.push(registroEvaluacion);
        localStorage.setItem(claveHist, JSON.stringify(histDocente));

        // Finaliza el estado "evaluando en vivo" para que el docente vea la calificación entregada
        limpiarEvaluandoEnTiempoReal();

        document.getElementById('mensaje-eval-guardada').textContent =
            `Evaluación de ${doc.nombre} (${totalPuntos}/${maxPuntos} pts) guardada en el sistema y enviada a ${doc.correo}.`;
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
        poblarSelectDocentes();
        renderizarMatrizEvaluar();
    };
</script>

</body>
</html>