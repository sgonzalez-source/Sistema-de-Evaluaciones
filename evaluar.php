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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación y revisión - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=13">
    <link rel="stylesheet" href="evaluar.css?v=3">
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

    <!-- ÁREA DERECHA: MÓDULO DE EVALUACIÓN Y REVISIÓN -->
    <main class="contenido contenido-evaluacion">

        <?php include 'breadcrumbs.php'; ?>

        <!-- =================================================================
             VISTA 1: BUSCADOR Y SELECCIÓN DE DOCENTE A EVALUAR
        ================================================================== -->
        <section id="vista-seleccion-docente" class="seccion-vista">
            <div class="cabecera-buscador-eval">
                <div>
                    <h1 class="titulo-pagina">Evaluar Docente</h1>
                    <p class="subtitulo-pagina">Busca y selecciona un docente a cargo para revisar su planeamiento y aplicar la rúbrica vigente.</p>
                </div>

                <div class="caja-busqueda-eval">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="input-buscar-docente-eval" placeholder="Buscar docente por nombre, cédula o materia..." oninput="renderizarListaDocentesEval()">
                </div>
            </div>

            <div class="grid-docentes-eval" id="grid-docentes-eval"></div>
        </section>

        <!-- =================================================================
             VISTA 2: EVALUACIÓN Y REVISIÓN (PLANEAMIENTO REAL + RÚBRICA CLICKEABLE)
        ================================================================== -->
        <section id="vista-panel-evaluacion" class="seccion-vista" style="display: none;">
            <div class="cabecera-panel-eval">
                <div>
                    <h1 class="titulo-pagina">Evaluación y revisión</h1>
                    <p class="ayuda-clic">Haz clic directamente sobre la celda del nivel correspondiente en cada criterio para asignar la calificación.</p>
                </div>

                <button type="button" class="btn-cambiar-docente" onclick="volverASeleccionDocente()">← Cambiar docente</button>
            </div>

            <!-- CONTENEDOR DIVIDIDO -->
            <div class="grid-division-evaluacion">

                <!-- PANEL IZQUIERDO: VISOR DE PLANEAMIENTO -->
                <div class="panel-planeamiento">
                    <div class="barra-archivo-pdf">
                        <div class="info-archivo-pdf">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                            <span id="eval-nombre-pdf">Planeamiento.pdf</span>
                        </div>

                        <button type="button" class="btn-descargar-pdf" onclick="descargarPlaneamientoSimulado()" title="Descargar planeamiento">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </button>
                    </div>

                    <div class="visor-documento-blanco" id="visor-documento-blanco"></div>
                </div>

                <!-- PANEL DERECHO: RÚBRICA VIGENTE -->
                <div class="panel-rubrica-eval">
                    <div class="hoja-rubrica-eval">
                        <table class="tabla-rubrica-eval">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="celda-meta-eval">
                                        <label>Docente: <input type="text" id="eval-docente" readonly></label>
                                    </td>
                                    <td colspan="2" class="celda-meta-eval">
                                        <label>Sección: <input type="text" id="eval-seccion" placeholder="Ej. 10-A"></label>
                                    </td>
                                    <td rowspan="2" class="celda-anio-eval">
                                        <span>Año: <strong id="eval-anio">2026</strong></span>
                                    </td>
                                    <td rowspan="2" class="celda-logo-eval">
                                        <img src="logo_black.png" alt="CEDES Don Bosco" class="logo-rubrica-eval">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="celda-meta-eval">
                                        <label>Curso lectivo: <input type="text" id="eval-curso" value="2026"></label>
                                    </td>
                                    <td colspan="2" class="celda-meta-eval">
                                        <label>Periodo: <input type="text" id="eval-periodo" value="I Periodo"></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="7" class="banner-amarillo-eval" id="eval-titulo-banner">
                                        RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO
                                    </td>
                                </tr>

                                <tr class="encabezados-rubrica-eval">
                                    <th class="col-crit">Criterio</th>
                                    <th class="col-nivel">Excelente (4 puntos)</th>
                                    <th class="col-nivel">Muy Bueno (3 puntos)</th>
                                    <th class="col-nivel">Bueno (2 puntos)</th>
                                    <th class="col-nivel">Insuficiente (1 punto)</th>
                                    <th class="col-sel">Selección</th>
                                    <th class="col-pts">Puntaje obtenido</th>
                                </tr>
                            </tbody>

                            <tbody id="cuerpo-criterios-eval"></tbody>

                            <tfoot>
                                <tr class="fila-total-salmon">
                                    <td colspan="6">Puntuación total obtenida</td>
                                    <td class="celda-total-val" id="eval-total-puntos"></td>
                                </tr>
                                <tr class="fila-interp-amarilla">
                                    <td colspan="6">Interpretación de la puntuación</td>
                                    <td class="celda-interp-val" id="eval-interpretacion"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

            <!-- RECOMENDACIONES -->
            <div class="bloque-recomendaciones">
                <label for="eval-recomendaciones">Recomendaciones</label>
                <textarea id="eval-recomendaciones" placeholder="Escribe observaciones o comentarios para el docente..."></textarea>
            </div>

            <!-- BOTONES INFERIORES -->
            <div class="acciones-inferiores-eval">
                <button type="button" class="btn-guardar-borrador" onclick="guardarBorradorEvaluacion()">Guardar borrador</button>
                <button type="button" class="btn-finalizar-eval" onclick="solicitarConfirmacionFinalizar()">Finalizar evaluación</button>
            </div>
        </section>

    </main>
</div>

<!-- MODAL 1: CONFIRMACIÓN Y VERIFICACIÓN DE CORREO DESTINO -->
<div class="fondo-modal" id="modal-confirmar-finalizar" onclick="cerrarSiFuera(event, 'modal-confirmar-finalizar')">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-confirmar-finalizar')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-modal-verde">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </div>

        <h2>¿Deseas finalizar y enviar la evaluación al docente?</h2>
        <p id="texto-confirmar-docente">Verifica el correo electrónico al que se enviará el desglose completo:</p>

        <div class="campo-correo-confirmar">
            <label for="input-correo-destino">Correo del docente:</label>
            <input type="email" id="input-correo-destino" placeholder="correo@cedesdonbosco.ed.cr" required>
        </div>

        <div class="botones-modal">
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModal('modal-confirmar-finalizar')">Cancelar</button>
            <button type="button" class="btn-finalizar-eval" id="btn-confirmar-envio-real" onclick="confirmarYEnviarCorreo()">Sí, finalizar y enviar</button>
        </div>
    </div>
</div>

<!-- MODAL 2: DESGLOSE DEL CORREO ENVIADO -->
<div class="fondo-modal" id="modal-correo-enviado" onclick="cerrarSiFuera(event, 'modal-correo-enviado')">
    <div class="caja-modal caja-modal-correo">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModal('modal-correo-enviado')" aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="icono-modal-verde">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </div>

        <h2 id="titulo-resultado-envio">¡Evaluación finalizada y enviada!</h2>
        <p id="subtitulo-correo-enviado">Se ha enviado el desglose oficial de la evaluación al correo del docente.</p>

        <div class="resumen-correo-box" id="cuerpo-desglose-correo"></div>

        <div class="botones-modal">
            <a id="enlace-mailto-respaldo" href="#" class="btn-modal-cancelar">Abrir en Outlook / Gmail</a>
            <button type="button" class="btn-finalizar-eval" onclick="cerrarModalYVolver()">Aceptar y volver</button>
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
            <a href="evaluar.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const docentesRespaldo = [
        { cedula: "100564782", nombre: "Lucía Rodríguez", materia: "Español", pdf: "Planeamiento_Espanol_Lucia.pdf", pdfData: "", activo: false },
        { cedula: "203648647", nombre: "Mario López", materia: "Química", pdf: "Planeamiento_Quimica_Mario.pdf", pdfData: "", activo: true },
        { cedula: "187263944", nombre: "Randall Ocón", materia: "Matemáticas", pdf: "Planeamiento_Mate_Randall.pdf", pdfData: "", activo: false },
        { cedula: "166478230", nombre: "Sharon Mendes", materia: "Música", pdf: "Planeamiento_Musica_Sharon.pdf", pdfData: "", activo: true }
    ];

    const criteriosRespaldo = [
        {
            criterio: "Observación de clase",
            exc: "Las clases son dinámicas, participativas y promueven el aprendizaje activo.",
            mb: "Las clases son generalmente participativas y promueven el aprendizaje.",
            b: "Las clases pueden ser un poco pasivas y no siempre promueven el aprendizaje.",
            m: "Las clases son generalmente pasivas y no promueven el aprendizaje."
        },
        {
            criterio: "Dominio de los contenidos",
            exc: "Demuestra un dominio excepcional del contenido y las estrategias de enseñanza.",
            mb: "Tiene un buen dominio del contenido y las estrategias de enseñanza.",
            b: "Tiene un dominio básico del contenido y las estrategias de enseñanza.",
            m: "Tiene un dominio limitado del contenido y las estrategias de enseñanza."
        },
        {
            criterio: "Planeación",
            exc: "Los planes de clase son claros, concisos, están bien organizados y se alinean con los estándares de aprendizaje y las necesidades de los estudiantes.",
            mb: "Los planes de clase son generalmente claros, concisos, están organizados y se alinean con los estándares de aprendizaje y las necesidades de la mayoría de los estudiantes.",
            b: "Los planes de clase pueden ser un poco confusos y desorganizados. No siempre se alinean con los estándares de aprendizaje o las necesidades de todos los estudiantes.",
            m: "Los planes de clase son confusos y desorganizados. No se alinean con los estándares de aprendizaje ni con las necesidades de los estudiantes."
        },
        {
            criterio: "Mediación pedagógica",
            exc: "Utiliza una variedad de estrategias de enseñanza efectivas para involucrar a todos los estudiantes.",
            mb: "Utiliza algunas estrategias de enseñanza efectivas para involucrar a la mayoría de los estudiantes.",
            b: "Utiliza pocas estrategias de enseñanza efectivas para involucrar a los estudiantes.",
            m: "No utiliza estrategias de enseñanza efectivas para involucrar a los estudiantes."
        },
        {
            criterio: "Metacognición del contenido (retroalimentación)",
            exc: "Brinda retroalimentación clara y oportuna a los estudiantes.",
            mb: "Brinda retroalimentación a los estudiantes, pero no siempre es clara u oportuna.",
            b: "La retroalimentación a los estudiantes es escasa o no es útil.",
            m: "No brinda retroalimentación a los estudiantes."
        },
        {
            criterio: "Orden y aseo del aula",
            exc: "El aula está organizada, limpia y segura. Los materiales didácticos totalmente guardados y accesibles.",
            mb: "El aula está organizada y limpia. Los materiales didácticos están guardados y accesibles.",
            b: "El aula está organizada, pero puede estar un poco sucia. Los materiales didácticos están guardados, pero pueden no ser fácilmente accesibles.",
            m: "El aula está desorganizada y sucia. Los materiales didácticos no están guardados y no son accesibles."
        },
        {
            criterio: "Relación con los estudiantes",
            exc: "Establece relaciones de comunicación respetuosa y asertiva con todos los estudiantes.",
            mb: "Establece algunas relaciones de comunicación respetuosa con algunos estudiantes.",
            b: "Establece muy pocas relaciones de comunicación respetuosa con los estudiantes.",
            m: "No establece relaciones positivas con los estudiantes."
        },
        {
            criterio: "Ambiente en el aula",
            exc: "Crea un ambiente de aula seguro y acogedor donde todos los estudiantes se sienten valorados y respetados.",
            mb: "Crea un ambiente de aula generalmente seguro y acogedor donde la mayoría de los estudiantes se sienten valorados y respetados.",
            b: "El ambiente de aula puede ser un poco tenso o no acogedor para algunos estudiantes.",
            m: "El ambiente de aula es tenso y no acogedor para la mayoría de los estudiantes."
        }
    ];

    let listaDocentes = [];
    let docenteSeleccionado = null;
    let criteriosEvaluacion = [];
    let htmlMigasBase = "";

    function generarCorreoInstitucional(doc) {
        if (doc.correo && doc.correo.includes('@')) {
            return doc.correo;
        }
        const limpio = doc.nombre.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
        const partes = limpio.split(/\s+/);
        if (partes.length >= 2) {
            return `${partes[0].charAt(0)}${partes[1]}@cedesdonbosco.ed.cr`;
        }
        return `${partes[0]}@cedesdonbosco.ed.cr`;
    }

    function renderizarListaDocentesEval() {
        const grid = document.getElementById('grid-docentes-eval');
        if (!grid) return;

        const filtro = document.getElementById('input-buscar-docente-eval').value.toLowerCase().trim();
        const evaluacionesGuardadas = JSON.parse(localStorage.getItem('evaluaciones_realizadas_cedes')) || {};

        grid.innerHTML = "";

        const filtrados = listaDocentes.filter(d =>
            d.nombre.toLowerCase().includes(filtro) ||
            d.cedula.toLowerCase().includes(filtro) ||
            d.materia.toLowerCase().includes(filtro)
        );

        if (filtrados.length === 0) {
            grid.innerHTML = `<div class="sin-docentes-eval">No se encontraron docentes con ese criterio de búsqueda.</div>`;
            return;
        }

        filtrados.forEach(doc => {
            const evalPrevia = evaluacionesGuardadas[doc.cedula];
            let estadoHtml = `<span class="badge-estado-eval pendiente">Pendiente</span>`;
            if (evalPrevia) {
                estadoHtml = evalPrevia.borrador
                    ? `<span class="badge-estado-eval borrador">Borrador guardado</span>`
                    : `<span class="badge-estado-eval completado">Evaluado (${evalPrevia.total} pts)</span>`;
            }

            const correoMostrar = (evalPrevia && evalPrevia.correo) ? evalPrevia.correo : generarCorreoInstitucional(doc);

            const card = document.createElement('div');
            card.className = "tarjeta-docente-eval";
            card.innerHTML = `
                <div class="top-card-docente">
                    <div class="avatar-docente-eval">${doc.nombre.charAt(0)}</div>
                    <div class="datos-docente-eval">
                        <h3>${doc.nombre}</h3>
                        <p>Cédula: ${doc.cedula} • <strong>${doc.materia}</strong></p>
                        <span class="correo-docente-tag">${correoMostrar}</span>
                    </div>
                </div>
                <div class="bottom-card-docente">
                    ${estadoHtml}
                    <button type="button" class="btn-iniciar-eval" onclick="abrirEvaluacionDocente('${doc.cedula}')">
                        ${evalPrevia && !evalPrevia.borrador ? 'Ver / Reevaluar' : 'Evaluar docente →'}
                    </button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function abrirEvaluacionDocente(cedula) {
        docenteSeleccionado = listaDocentes.find(d => d.cedula === cedula);
        if (!docenteSeleccionado) return;

        document.getElementById('vista-seleccion-docente').style.display = 'none';
        document.getElementById('vista-panel-evaluacion').style.display = 'flex';

        const navMigas = document.getElementById('migas-pan-dinamicas');
        if (navMigas) {
            if (!htmlMigasBase) htmlMigasBase = navMigas.innerHTML;
            const temp = document.createElement('div');
            temp.innerHTML = htmlMigasBase;
            const ultimoSpan = temp.querySelector('.miga-actual');
            if (ultimoSpan) {
                const enlaceVolver = document.createElement('a');
                enlaceVolver.href = "evaluar.php";
                enlaceVolver.textContent = ultimoSpan.textContent;
                enlaceVolver.setAttribute('onclick', 'volverASeleccionDocente(); return false;');
                ultimoSpan.replaceWith(enlaceVolver);
            }
            navMigas.innerHTML = temp.innerHTML + ` <span>/</span> <span class="miga-actual">${docenteSeleccionado.nombre}</span>`;
        }

        const nombrePdf = docenteSeleccionado.pdf || "Planeamiento.pdf";
        document.getElementById('eval-nombre-pdf').textContent = nombrePdf;

        const contenedorVisor = document.getElementById('visor-documento-blanco');
        if (docenteSeleccionado.pdfData && docenteSeleccionado.pdfData.startsWith('data:')) {
            contenedorVisor.innerHTML = `<iframe class="iframe-pdf-real" src="${docenteSeleccionado.pdfData}" title="${nombrePdf}"></iframe>`;
        } else {
            contenedorVisor.innerHTML = `
                <div class="hoja-preview-doc">
                    <div class="preview-header">
                        <img src="logo_black.png" alt="CEDES Don Bosco" class="preview-logo">
                        <div>
                            <strong>PLANEAMIENTO DIDÁCTICO 2026</strong>
                            <p>${docenteSeleccionado.nombre} • ${docenteSeleccionado.materia}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="preview-cuerpo">
                        <p><strong>Institución:</strong> Colegio Técnico Profesional Don Bosco</p>
                        <p><strong>Departamento:</strong> ${docenteSeleccionado.materia}</p>
                        <p><strong>Documento adjunto:</strong> ${nombrePdf}</p>
                        <div class="preview-bloque-texto">
                            <h4>1. Aprendizajes esperados y estrategias de mediación</h4>
                            <p>Desarrollo de actividades participativas, resolución de casos prácticos y acompañamiento continuo en el aula alineado con los objetivos trimestrales.</p>
                            <h4>2. Indicadores de evaluación</h4>
                            <p>Participación activa, dominio conceptual, orden en el espacio de trabajo y entrega oportuna de evidencias.</p>
                        </div>
                    </div>
                </div>
            `;
        }

        const plantillas = JSON.parse(localStorage.getItem('plantillas_cedes')) || [];
        const plantillaVigente = plantillas.find(p => p.activo && !p.borrador) || plantillas[0];

        const bannerTitulo = (plantillaVigente && plantillaVigente.datos && plantillaVigente.datos.tituloBanner)
            ? plantillaVigente.datos.tituloBanner
            : "RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO";
        const anioVigente = (plantillaVigente && plantillaVigente.anio) ? plantillaVigente.anio : "2026";
        const baseCriterios = (plantillaVigente && plantillaVigente.datos && plantillaVigente.datos.filas && plantillaVigente.datos.filas.length > 0)
            ? plantillaVigente.datos.filas
            : criteriosRespaldo;

        document.getElementById('eval-docente').value = docenteSeleccionado.nombre;
        document.getElementById('eval-anio').textContent = anioVigente;
        document.getElementById('eval-curso').value = anioVigente;
        document.getElementById('eval-titulo-banner').textContent = bannerTitulo;

        const evaluacionesGuardadas = JSON.parse(localStorage.getItem('evaluaciones_realizadas_cedes')) || {};
        const previa = evaluacionesGuardadas[docenteSeleccionado.cedula];

        document.getElementById('eval-seccion').value = previa ? (previa.seccion || "") : "";
        document.getElementById('eval-periodo').value = previa ? (previa.periodo || "I Periodo") : "I Periodo";
        document.getElementById('eval-recomendaciones').value = previa ? (previa.recomendaciones || "") : "";

        criteriosEvaluacion = baseCriterios.map((item, idx) => {
            const selPrevia = (previa && previa.resultados && previa.resultados[idx]) ? previa.resultados[idx].puntos : null;
            return {
                criterio: item.criterio,
                exc: item.exc,
                mb: item.mb,
                b: item.b,
                m: item.m,
                puntos: selPrevia
            };
        });

        renderizarTablaRubricaClickeable();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function volverASeleccionDocente() {
        docenteSeleccionado = null;
        document.getElementById('vista-panel-evaluacion').style.display = 'none';
        document.getElementById('vista-seleccion-docente').style.display = 'flex';
        const navMigas = document.getElementById('migas-pan-dinamicas');
        if (navMigas && htmlMigasBase) {
            navMigas.innerHTML = htmlMigasBase;
        }
        renderizarListaDocentesEval();
    }

    function obtenerEtiquetaNivel(puntos) {
        if (puntos === 4) return "Excelente";
        if (puntos === 3) return "Muy Bueno";
        if (puntos === 2) return "Bueno";
        if (puntos === 1) return "Insuficiente";
        return "";
    }

    function renderizarTablaRubricaClickeable() {
        const tbody = document.getElementById('cuerpo-criterios-eval');
        tbody.innerHTML = "";

        criteriosEvaluacion.forEach((fila, index) => {
            const pts = fila.puntos;
            const etiqueta = obtenerEtiquetaNivel(pts);

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="celda-crit-eval">${fila.criterio}</td>
                <td class="celda-opcion-eval ${pts === 4 ? 'seleccionada-4' : ''}" onclick="seleccionarNivelCriterio(${index}, 4)">${fila.exc}</td>
                <td class="celda-opcion-eval ${pts === 3 ? 'seleccionada-3' : ''}" onclick="seleccionarNivelCriterio(${index}, 3)">${fila.mb}</td>
                <td class="celda-opcion-eval ${pts === 2 ? 'seleccionada-2' : ''}" onclick="seleccionarNivelCriterio(${index}, 2)">${fila.b}</td>
                <td class="celda-opcion-eval ${pts === 1 ? 'seleccionada-1' : ''}" onclick="seleccionarNivelCriterio(${index}, 1)">${fila.m}</td>
                <td class="celda-seleccion-auto">${etiqueta}</td>
                <td class="celda-puntaje-auto">${pts !== null ? pts : ''}</td>
            `;
            tbody.appendChild(tr);
        });

        recalcularTotalesEvaluacion();
    }

    function seleccionarNivelCriterio(indiceCriterio, puntos) {
        criteriosEvaluacion[indiceCriterio].puntos = puntos;
        renderizarTablaRubricaClickeable();
    }

    function recalcularTotalesEvaluacion() {
        let suma = 0;
        let respondidos = 0;
        const maxPosible = criteriosEvaluacion.length * 4;

        criteriosEvaluacion.forEach(c => {
            if (c.puntos !== null) {
                suma += c.puntos;
                respondidos++;
            }
        });

        const totalEl = document.getElementById('eval-total-puntos');
        const interpEl = document.getElementById('eval-interpretacion');

        if (respondidos === 0) {
            totalEl.textContent = "";
            interpEl.textContent = "";
            return;
        }

        totalEl.textContent = suma;

        const porcentaje = maxPosible > 0 ? (suma / maxPosible) * 100 : 0;
        if (porcentaje >= 85) {
            interpEl.textContent = "Desempeño Excelente";
        } else if (porcentaje >= 70) {
            interpEl.textContent = "Desempeño Muy Bueno";
        } else if (porcentaje >= 50) {
            interpEl.textContent = "Desempeño Bueno";
        } else {
            interpEl.textContent = "Desempeño Insuficiente";
        }
    }

    function construirObjetoEvaluacion(esBorrador, correoFinal) {
        let suma = 0;
        const resultados = criteriosEvaluacion.map(c => {
            if (c.puntos !== null) suma += c.puntos;
            return {
                criterio: c.criterio,
                nivel: obtenerEtiquetaNivel(c.puntos) || "Sin calificar",
                puntos: c.puntos
            };
        });

        return {
            cedula: docenteSeleccionado.cedula,
            docente: docenteSeleccionado.nombre,
            correo: correoFinal || generarCorreoInstitucional(docenteSeleccionado),
            materia: docenteSeleccionado.materia,
            seccion: document.getElementById('eval-seccion').value.trim(),
            curso: document.getElementById('eval-curso').value.trim(),
            periodo: document.getElementById('eval-periodo').value.trim(),
            recomendaciones: document.getElementById('eval-recomendaciones').value.trim(),
            total: suma,
            maximo: criteriosEvaluacion.length * 4,
            interpretacion: document.getElementById('eval-interpretacion').textContent || "Sin completar",
            borrador: esBorrador,
            fecha: new Date().toLocaleDateString('es-ES'),
            resultados: resultados
        };
    }

    function guardarBorradorEvaluacion() {
        if (!docenteSeleccionado) return;
        const evaluaciones = JSON.parse(localStorage.getItem('evaluaciones_realizadas_cedes')) || {};
        evaluaciones[docenteSeleccionado.cedula] = construirObjetoEvaluacion(true);
        localStorage.setItem('evaluaciones_realizadas_cedes', JSON.stringify(evaluaciones));
        volverASeleccionDocente();
    }

    function solicitarConfirmacionFinalizar() {
        if (!docenteSeleccionado) return;

        const sinCalificar = criteriosEvaluacion.filter(c => c.puntos === null).length;
        if (sinCalificar > 0) {
            alert(`Aún faltan ${sinCalificar} criterio(s) por seleccionar. Haz clic sobre cada criterio en la rúbrica antes de finalizar.`);
            return;
        }

        const correoSugerido = generarCorreoInstitucional(docenteSeleccionado);
        document.getElementById('input-correo-destino').value = correoSugerido;
        document.getElementById('texto-confirmar-docente').innerHTML =
            `Confirma o escribe el correo electrónico de <strong>${docenteSeleccionado.nombre}</strong> para enviarle el desglose completo de la evaluación:`;

        abrirModal('modal-confirmar-finalizar');
    }

    async function confirmarYEnviarCorreo() {
        if (!docenteSeleccionado) return;

        const inputCorreo = document.getElementById('input-correo-destino');
        const correoDestino = inputCorreo.value.trim();

        if (!correoDestino || !correoDestino.includes('@')) {
            alert("Por favor escribe un correo electrónico válido para enviar la evaluación.");
            inputCorreo.focus();
            return;
        }

        const btnEnviar = document.getElementById('btn-confirmar-envio-real');
        btnEnviar.disabled = true;
        btnEnviar.textContent = "Enviando correo...";

        const datosEval = construirObjetoEvaluacion(false, correoDestino);

        const idxDoc = listaDocentes.findIndex(d => d.cedula === docenteSeleccionado.cedula);
        if (idxDoc !== -1) {
            listaDocentes[idxDoc].activo = true;
            listaDocentes[idxDoc].correo = correoDestino;
            localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
        }

        const evaluaciones = JSON.parse(localStorage.getItem('evaluaciones_realizadas_cedes')) || {};
        evaluaciones[docenteSeleccionado.cedula] = datosEval;
        localStorage.setItem('evaluaciones_realizadas_cedes', JSON.stringify(evaluaciones));

        const payload = {
            _subject: `Resultados de Evaluación Docente - ${datosEval.periodo} (${datosEval.curso}) - CEDES Don Bosco`,
            _template: "table",
            _captcha: "false",
            Docente: datosEval.docente,
            Cedula: datosEval.cedula,
            Materia: datosEval.materia,
            Seccion: datosEval.seccion || "No especificada",
            Periodo: `${datosEval.periodo} - ${datosEval.curso}`,
            Puntuacion_Total: `${datosEval.total} / ${datosEval.maximo} puntos`,
            Interpretacion: datosEval.interpretacion,
            Recomendaciones: datosEval.recomendaciones || "Sin observaciones adicionales"
        };

        datosEval.resultados.forEach((r, i) => {
            payload[`Criterio_${i + 1}_${r.criterio}`] = `${r.nivel} (${r.puntos} pts)`;
        });

        let enviadoOk = false;
        try {
            const respuesta = await fetch(`https://formsubmit.co/ajax/${encodeURIComponent(correoDestino)}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(payload)
            });
            const resultado = await respuesta.json();
            enviadoOk = respuesta.ok && (resultado.success === "true" || resultado.success === true);
        } catch (error) {
            enviadoOk = false;
        }

        btnEnviar.disabled = false;
        btnEnviar.textContent = "Sí, finalizar y enviar";
        cerrarModal('modal-confirmar-finalizar');

        document.getElementById('titulo-resultado-envio').textContent = enviadoOk
            ? "¡Evaluación finalizada y enviada!"
            : "Evaluación guardada (revisa tu bandeja de activación)";

        document.getElementById('subtitulo-correo-enviado').innerHTML = enviadoOk
            ? `El desglose fue enviado a <strong>${datosEval.correo}</strong>.`
            : `No se pudo conectar al servicio automático en este momento, pero puedes enviarlo directamente usando el botón <strong>Abrir en Outlook / Gmail</strong>.`;

        let listaHtml = datosEval.resultados.map(r =>
            `<li><span>${r.criterio}:</span> <strong>${r.nivel} (${r.puntos} pts)</strong></li>`
        ).join("");

        document.getElementById('cuerpo-desglose-correo').innerHTML = `
            <div class="correo-meta">
                <p><strong>Para:</strong> ${datosEval.docente} <${datosEval.correo}></p>
                <p><strong>Asunto:</strong> Resultados de Evaluación Docente - ${datosEval.periodo} (${datosEval.curso})</p>
            </div>
            <ul class="correo-lista-criterios">${listaHtml}</ul>
            <div class="correo-total-footer">
                <span>Puntuación total: <strong>${datosEval.total} / ${datosEval.maximo} pts</strong></span>
                <span>Resultado: <strong>${datosEval.interpretacion}</strong></span>
            </div>
            ${datosEval.recomendaciones ? `<p class="correo-recomendacion"><strong>Recomendaciones:</strong> ${datosEval.recomendaciones}</p>` : ''}
        `;

        const cuerpoTexto = encodeURIComponent(
            `Estimado(a) ${datosEval.docente},\n\n` +
            `Adjuntamos el desglose de su evaluación docente (${datosEval.periodo} - ${datosEval.curso}):\n\n` +
            datosEval.resultados.map(r => `- ${r.criterio}: ${r.nivel} (${r.puntos} pts)`).join("\n") +
            `\n\nPuntuación total obtenida: ${datosEval.total} / ${datosEval.maximo} pts\n` +
            `Interpretación: ${datosEval.interpretacion}\n` +
            (datosEval.recomendaciones ? `\nRecomendaciones del coordinador:\n${datosEval.recomendaciones}\n` : "") +
            `\nAtentamente,\nCoordinación Académica - CEDES Don Bosco`
        );
        document.getElementById('enlace-mailto-respaldo').href =
            `mailto:${datosEval.correo}?subject=${encodeURIComponent("Desglose de Evaluación Docente - CEDES Don Bosco")}&body=${cuerpoTexto}`;

        abrirModal('modal-correo-enviado');
    }

    function cerrarModalYVolver() {
        cerrarModal('modal-correo-enviado');
        volverASeleccionDocente();
    }

    function descargarPlaneamientoSimulado() {
        if (!docenteSeleccionado) return;
        const enlace = document.createElement('a');
        if (docenteSeleccionado.pdfData && docenteSeleccionado.pdfData.startsWith('data:')) {
            enlace.href = docenteSeleccionado.pdfData;
            enlace.download = docenteSeleccionado.pdf || 'Planeamiento.pdf';
        } else {
            const contenido = `PLANEAMIENTO DIDÁCTICO - CEDES DON BOSCO\nDocente: ${docenteSeleccionado.nombre}\nCédula: ${docenteSeleccionado.cedula}\nMateria: ${docenteSeleccionado.materia}\nArchivo: ${docenteSeleccionado.pdf}`;
            const blob = new Blob([contenido], { type: 'text/plain;charset=utf-8' });
            enlace.href = URL.createObjectURL(blob);
            enlace.download = (docenteSeleccionado.pdf || 'Planeamiento.pdf').replace('.pdf', '.txt');
        }
        enlace.click();
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
        const navMigas = document.getElementById('migas-pan-dinamicas');
        if (navMigas) {
            htmlMigasBase = navMigas.innerHTML;
        }

        try {
            const guardados = localStorage.getItem('lista_docentes_cedes');
            const parsed = guardados ? JSON.parse(guardados) : null;
            if (Array.isArray(parsed) && parsed.length > 0) {
                listaDocentes = parsed;
            } else {
                listaDocentes = docentesRespaldo;
                localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
            }
        } catch (err) {
            listaDocentes = docentesRespaldo;
            localStorage.setItem('lista_docentes_cedes', JSON.stringify(listaDocentes));
        }

        renderizarListaDocentesEval();
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal('modal-salir');
            cerrarModal('modal-confirmar-finalizar');
            cerrarModal('modal-correo-enviado');
        }
    });
</script>

</body>
</html>