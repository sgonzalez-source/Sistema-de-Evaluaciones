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
$titulo_miga = "Plantillas de evaluación";

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
    <title>Plantillas de Evaluación - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=58">
    <link rel="stylesheet" href="evaluar.css?v=6">
    <style>
        .btn-verde-accion {
            background-color: #15803d;
            color: #ffffff;
            border: none;
            border-radius: 9px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(21, 128, 61, 0.25);
        }
        .btn-verde-accion:hover {
            background-color: #166534;
        }
        .card-lista-plantillas {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            overflow-x: auto;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
        }
        .tabla-plantillas-lista {
            width: 100%;
            min-width: 650px;
            border-collapse: collapse;
        }
        .tabla-plantillas-lista thead th {
            background-color: #c7d9f1;
            color: #071342;
            font-size: 13px;
            font-weight: 800;
            padding: 11px 14px;
            text-align: center;
            border: 1px solid #cbd5e1;
        }
        .tabla-plantillas-lista tbody td {
            border: 1px solid #cbd5e1;
            padding: 14px 16px;
            vertical-align: middle;
            background-color: #ffffff;
        }
        .plantilla-nombre-col strong {
            display: block;
            font-size: 14px;
            font-weight: 800;
            color: #071342;
            margin-bottom: 2px;
        }
        .plantilla-nombre-col span {
            font-size: 12.5px;
            color: #64748b;
        }
        .switch-estado {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }
        .switch-estado input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider-estado {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #cbd5e1;
            border-radius: 999px;
            transition: 0.2s;
        }
        .slider-estado:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: #ffffff;
            border-radius: 50%;
            transition: 0.2s;
        }
        .switch-estado input:checked + .slider-estado {
            background-color: #1d4ed8;
        }
        .switch-estado input:checked + .slider-estado:before {
            transform: translateX(22px);
        }
        .grupo-acciones-fila {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-icon-editar {
            background-color: #e0e7ff;
            color: #1d4ed8;
            border: none;
            border-radius: 7px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .btn-icon-editar:hover {
            background-color: #c7d2fe;
        }
        .btn-icon-borrar {
            background-color: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 7px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .btn-icon-borrar:hover {
            background-color: #fecaca;
        }
        .btn-icon-editar svg,
        .btn-icon-borrar svg {
            width: 15px;
            height: 15px;
        }
        .plantilla-acciones-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }
        .btn-secundario-plantilla {
            background-color: #f1f5f9;
            color: #071342;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-secundario-plantilla:hover {
            background-color: #e2e8f0;
        }
        .btn-primario-plantilla {
            background-color: #eff6ff;
            color: #1e3a8a;
            border: 1px solid #93c5fd;
            border-radius: 9px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-primario-plantilla:hover {
            background-color: #dbeafe;
        }
        .card-meta-plantilla {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
        }
        .campo-inline-plantilla {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1 1 260px;
        }
        .campo-inline-plantilla label {
            font-size: 13px;
            font-weight: 700;
            color: #071342;
            white-space: nowrap;
        }
        .campo-inline-plantilla input {
            width: 100%;
            border: 1.2px solid #cbd5e1;
            border-radius: 8px;
            padding: 7px 11px;
            font-size: 13px;
            color: #0f172a;
        }
        .rubrica-editor-card {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow-x: auto;
        }
        .tabla-rubrica-edicion {
            width: 100%;
            min-width: 860px;
            border-collapse: collapse;
            font-size: 12px;
            color: #0f172a;
        }
        .tabla-rubrica-edicion th,
        .tabla-rubrica-edicion td {
            border: 1px solid #94a3b8;
            padding: 8px;
            vertical-align: middle;
        }
        .fila-encabezado-institucional td {
            background-color: #ffffff;
            font-size: 12.5px;
        }
        .fila-banner-rubrica th {
            background-color: #fde68a;
            color: #0f172a;
            font-size: 12.5px;
            font-weight: 800;
            text-align: center;
            padding: 8px;
        }
        .fila-cols-rubrica th {
            background-color: #8fb3e8;
            color: #071342;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
        }
        .textarea-criterio-edit {
            width: 100%;
            min-height: 68px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 8px;
            font-size: 11.5px;
            line-height: 1.35;
            font-family: inherit;
            color: #0f172a;
            resize: vertical;
            box-sizing: border-box;
        }
        .input-criterio-titulo {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 8px;
            font-size: 12px;
            font-weight: 700;
            font-style: italic;
            color: #071342;
            box-sizing: border-box;
        }
        .btn-eliminar-fila {
            background: transparent;
            border: none;
            color: #dc2626;
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-eliminar-fila svg {
            width: 18px;
            height: 18px;
        }
        .pie-edicion-plantilla {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .btn-cancelar-suave {
            background-color: #fee2e2;
            color: #b91c1c;
            border: none;
            border-radius: 9px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-guardar-plantilla {
            background-color: #15803d;
            color: #ffffff;
            border: none;
            border-radius: 9px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-guardar-plantilla:hover {
            background-color: #166534;
        }
    </style>
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

            <a href="plantilla_evaluacion.php" class="activo" title="Plantillas de evaluación">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span class="texto-menu">Plantillas de evaluación</span>
            </a>

            <a href="evaluar.php" title="Evaluación">
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

            <!-- VISTA 1: LISTA DE PLANTILLAS (VISTA INICIAL) -->
            <section id="vista-lista-plantillas" class="vista-bloque">
                <header class="banner-seccion">
                    <div class="banner-izq">
                        <h1 class="titulo-seccion-bold">Plantillas de evaluación</h1>
                        <p class="subtitulo-banner">Configura, edita y activa las rúbricas oficiales de observación docente para cada periodo escolar.</p>
                    </div>

                    <div class="banner-der">
                        <button type="button" class="btn-verde-accion" onclick="abrirEditorPlantilla(null)">
                            <span>+ Crear plantilla</span>
                        </button>
                    </div>
                </header>

                <div class="card-lista-plantillas">
                    <table class="tabla-plantillas-lista">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Nombre</th>
                                <th style="width: 140px;">Año escolar</th>
                                <th style="width: 130px;">Estado</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo-lista-plantillas"></tbody>
                    </table>
                </div>
            </section>

            <!-- VISTA 2: EDITOR DE PLANTILLA (AL DAR AL LAPICITO O CREAR) -->
            <section id="vista-editor-plantilla" class="vista-bloque" style="display: none;">
                <header class="banner-seccion">
                    <div class="banner-izq">
                        <h1 class="titulo-seccion-bold" id="titulo-modo-editor">Editar plantilla</h1>
                        <p class="subtitulo-banner">Edita libremente cualquier campo, criterio, puntaje o descripción de la rúbrica institucional.</p>
                    </div>

                    <div class="banner-der plantilla-acciones-top">
                        <button type="button" class="btn-secundario-plantilla" onclick="volverAListaPlantillas()">← Volver a plantillas</button>
                        <button type="button" class="btn-secundario-plantilla" onclick="restaurarRubricaInstitucional()">Restaurar 5 criterios</button>
                        <button type="button" class="btn-primario-plantilla" onclick="agregarCriterioPlantilla()">+ Agregar criterio</button>
                    </div>
                </header>

                <section class="card-meta-plantilla">
                    <div class="campo-inline-plantilla">
                        <label for="input-nombre-plantilla">Nombre en lista:</label>
                        <input type="text" id="input-nombre-plantilla" value="Rúbrica General Docente <?php echo $anio_escolar; ?>">
                    </div>

                    <div class="campo-inline-plantilla">
                        <label for="input-subtitulo-plantilla">Subtítulo en lista:</label>
                        <input type="text" id="input-subtitulo-plantilla" value="Evaluación de clase y planeamiento">
                    </div>
                </section>

                <section class="rubrica-editor-card">
                    <table class="tabla-rubrica-edicion">
                        <tbody>
                            <tr class="fila-encabezado-institucional">
                                <td colspan="2"><strong>Docente:</strong> Randall Ocón</td>
                                <td colspan="2"><strong>Sección:</strong> 12-2</td>
                                <td colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">
                                    <strong>Año:</strong> <?php echo $anio_escolar; ?>
                                </td>
                                <td rowspan="2" style="text-align: center; vertical-align: middle; width: 160px;">
                                    <?php if (file_exists('logo_black.png')): ?>
                                        <img src="logo_black.png" alt="CEDES Don Bosco" style="max-width: 140px; max-height: 46px; display: block; margin: 0 auto;">
                                    <?php else: ?>
                                        <strong>CEDES Don Bosco</strong>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="fila-encabezado-institucional">
                                <td colspan="2"><strong>Curso lectivo:</strong> <?php echo $anio_escolar; ?></td>
                                <td colspan="2"><strong>Periodo:</strong> <?php echo htmlspecialchars($periodo_actual); ?></td>
                            </tr>
                            <tr class="fila-banner-rubrica">
                                <th colspan="7">RÚBRICA DE OBSERVACIÓN DOCENTE COLEGIO TÉCNICO PROFESIONAL DON BOSCO</th>
                            </tr>
                            <tr class="fila-cols-rubrica">
                                <th style="width: 165px;">Criterio</th>
                                <th>Excelente (4 puntos)</th>
                                <th>Muy Bueno (3 puntos)</th>
                                <th>Bueno (2 puntos)</th>
                                <th>Insuficiente (1 punto)</th>
                                <th style="width: 85px;">Selección</th>
                                <th style="width: 110px;">Puntaje obtenido</th>
                            </tr>
                        </tbody>
                        <tbody id="cuerpo-edicion-plantilla"></tbody>
                    </table>

                    <div class="pie-edicion-plantilla">
                        <button type="button" class="btn-cancelar-suave" onclick="volverAListaPlantillas()">Cancelar</button>
                        <button type="button" class="btn-guardar-plantilla" onclick="guardarPlantillaCompleta()">Guardar plantilla</button>
                    </div>
                </section>
            </section>

        </div>
    </main>
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
            <a href="plantilla_evaluacion.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    const anioEscolar = "<?php echo $anio_escolar; ?>";

    const criteriosInstitucionalesBase = [
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
                4: "Utiliza una variedad de estrategias de enseñanza efectivas para involucrar a los estudiantes.",
                3: "Utiliza algunas estrategias de enseñanza efectivas para guiar el aprendizaje.",
                2: "Utiliza pocas estrategias de enseñanza efectivas para involucrar a los estudiantes.",
                1: "No utiliza estrategias de enseñanza efectivas durante la sesión."
            }
        },
        {
            criterio: "Metacognición del contenido (retroalimentación)",
            niveles: {
                4: "Brinda retroalimentación clara y oportuna a los estudiantes.",
                3: "Brinda retroalimentación a los estudiantes, pero no siempre es clara u oportuna.",
                2: "La retroalimentación a los estudiantes es escasa o no es útil.",
                1: "No brinda retroalimentación a los estudiantes."
            }
        }
    ];

    const plantillaPorDefecto = {
        id: "plantilla_general_cedes",
        nombre: `Rúbrica General Docente ${anioEscolar}`,
        subtitulo: "Evaluación de clase y planeamiento",
        anio: anioEscolar,
        activa: true,
        criterios: JSON.parse(JSON.stringify(criteriosInstitucionalesBase))
    };

    let plantillas = [];
    let indiceEditando = null;
    let criteriosEdicion = [];

    function obtenerPlantillasGuardadas() {
        try {
            const raw = JSON.parse(localStorage.getItem('plantillas_evaluacion_cedes') || '[]');
            if (Array.isArray(raw) && raw.length > 0) {
                return raw.map(p => ({
                    id: p.id || `plantilla_${Date.now()}`,
                    nombre: p.nombre || `Rúbrica General Docente ${anioEscolar}`,
                    subtitulo: p.subtitulo || "Evaluación de clase y planeamiento",
                    anio: p.anio || anioEscolar,
                    activa: typeof p.activa === 'boolean' ? p.activa : true,
                    criterios: (Array.isArray(p.criterios) && p.criterios.length >= 5)
                        ? JSON.parse(JSON.stringify(p.criterios))
                        : JSON.parse(JSON.stringify(criteriosInstitucionalesBase))
                }));
            }
        } catch (e) {}
        return [JSON.parse(JSON.stringify(plantillaPorDefecto))];
    }

    function guardarListaStorage() {
        localStorage.setItem('plantillas_evaluacion_cedes', JSON.stringify(plantillas));
    }

    function renderizarListaPlantillas() {
        plantillas = obtenerPlantillasGuardadas();
        const tbody = document.getElementById('cuerpo-lista-plantillas');

        tbody.innerHTML = plantillas.map((p, idx) => `
            <tr>
                <td class="plantilla-nombre-col">
                    <strong>${escapeHtmlText(p.nombre)}</strong>
                    <span>${escapeHtmlText(p.subtitulo)}</span>
                </td>
                <td style="text-align: center; font-weight: 800; color: #0f172a;">
                    ${escapeHtmlText(p.anio)}
                </td>
                <td style="text-align: center;">
                    <label class="switch-estado" title="Activar o desactivar plantilla">
                        <input type="checkbox" ${p.activa ? 'checked' : ''} onchange="alternarEstadoPlantilla(${idx}, this.checked)">
                        <span class="slider-estado"></span>
                    </label>
                </td>
                <td style="text-align: center;">
                    <div class="grupo-acciones-fila">
                        <button type="button" class="btn-icon-editar" onclick="abrirEditorPlantilla(${idx})" title="Editar plantilla">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button type="button" class="btn-icon-borrar" onclick="eliminarPlantilla(${idx})" title="Eliminar plantilla">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function alternarEstadoPlantilla(idx, activa) {
        if (!plantillas[idx]) return;
        plantillas[idx].activa = Boolean(activa);
        guardarListaStorage();
    }

    function eliminarPlantilla(idx) {
        if (plantillas.length <= 1) {
            alert("Debe mantenerse al menos una plantilla registrada.");
            return;
        }
        plantillas.splice(idx, 1);
        guardarListaStorage();
        renderizarListaPlantillas();
    }

    function abrirEditorPlantilla(idx) {
        indiceEditando = idx;
        if (typeof idx === 'number' && plantillas[idx]) {
            const p = plantillas[idx];
            document.getElementById('titulo-modo-editor').textContent = "Editar plantilla";
            document.getElementById('input-nombre-plantilla').value = p.nombre;
            document.getElementById('input-subtitulo-plantilla').value = p.subtitulo;
            criteriosEdicion = (Array.isArray(p.criterios) && p.criterios.length >= 5)
                ? JSON.parse(JSON.stringify(p.criterios))
                : JSON.parse(JSON.stringify(criteriosInstitucionalesBase));
        } else {
            document.getElementById('titulo-modo-editor').textContent = "Crear plantilla";
            document.getElementById('input-nombre-plantilla').value = `Rúbrica General Docente ${anioEscolar}`;
            document.getElementById('input-subtitulo-plantilla').value = "Evaluación de clase y planeamiento";
            criteriosEdicion = JSON.parse(JSON.stringify(criteriosInstitucionalesBase));
        }

        document.getElementById('vista-lista-plantillas').style.display = 'none';
        document.getElementById('vista-editor-plantilla').style.display = 'flex';
        renderizarTablaEdicion();
    }

    function volverAListaPlantillas() {
        document.getElementById('vista-editor-plantilla').style.display = 'none';
        document.getElementById('vista-lista-plantillas').style.display = 'flex';
        renderizarListaPlantillas();
    }

    function restaurarRubricaInstitucional() {
        criteriosEdicion = JSON.parse(JSON.stringify(criteriosInstitucionalesBase));
        renderizarTablaEdicion();
    }

    function agregarCriterioPlantilla() {
        criteriosEdicion.push({
            criterio: "Nuevo criterio",
            niveles: {
                4: "Descripción nivel Excelente (4 puntos).",
                3: "Descripción nivel Muy Bueno (3 puntos).",
                2: "Descripción nivel Bueno (2 puntos).",
                1: "Descripción nivel Insuficiente (1 punto)."
            }
        });
        renderizarTablaEdicion();
    }

    function eliminarCriterioPlantilla(idx) {
        if (criteriosEdicion.length <= 1) {
            alert("La plantilla debe conservar al menos un criterio.");
            return;
        }
        criteriosEdicion.splice(idx, 1);
        renderizarTablaEdicion();
    }

    function actualizarTextoCriterio(idx, valor) {
        criteriosEdicion[idx].criterio = valor;
    }

    function actualizarNivelCriterio(idx, pts, valor) {
        criteriosEdicion[idx].niveles[pts] = valor;
    }

    function renderizarTablaEdicion() {
        const tbody = document.getElementById('cuerpo-edicion-plantilla');
        tbody.innerHTML = criteriosEdicion.map((item, idx) => `
            <tr>
                <td style="background-color: #dbe7f8;">
                    <input type="text" class="input-criterio-titulo" value="${escapeHtmlAttr(item.criterio)}" oninput="actualizarTextoCriterio(${idx}, this.value)">
                </td>
                <td>
                    <textarea class="textarea-criterio-edit" oninput="actualizarNivelCriterio(${idx}, 4, this.value)">${escapeHtmlText(item.niveles[4] || '')}</textarea>
                </td>
                <td>
                    <textarea class="textarea-criterio-edit" oninput="actualizarNivelCriterio(${idx}, 3, this.value)">${escapeHtmlText(item.niveles[3] || '')}</textarea>
                </td>
                <td>
                    <textarea class="textarea-criterio-edit" oninput="actualizarNivelCriterio(${idx}, 2, this.value)">${escapeHtmlText(item.niveles[2] || '')}</textarea>
                </td>
                <td>
                    <textarea class="textarea-criterio-edit" oninput="actualizarNivelCriterio(${idx}, 1, this.value)">${escapeHtmlText(item.niveles[1] || '')}</textarea>
                </td>
                <td style="text-align: center; font-weight: 700;">v</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-eliminar-fila" onclick="eliminarCriterioPlantilla(${idx})" title="Eliminar criterio">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function guardarPlantillaCompleta() {
        const nombrePlantilla = document.getElementById('input-nombre-plantilla').value.trim() || `Rúbrica General Docente ${anioEscolar}`;
        const subtituloPlantilla = document.getElementById('input-subtitulo-plantilla').value.trim() || "Evaluación de clase y planeamiento";

        if (typeof indiceEditando === 'number' && plantillas[indiceEditando]) {
            plantillas[indiceEditando] = {
                ...plantillas[indiceEditando],
                nombre: nombrePlantilla,
                subtitulo: subtituloPlantilla,
                actualizadaEn: new Date().toISOString(),
                criterios: JSON.parse(JSON.stringify(criteriosEdicion))
            };
        } else {
            plantillas.push({
                id: `plantilla_${Date.now()}`,
                nombre: nombrePlantilla,
                subtitulo: subtituloPlantilla,
                anio: anioEscolar,
                activa: true,
                actualizadaEn: new Date().toISOString(),
                criterios: JSON.parse(JSON.stringify(criteriosEdicion))
            });
        }

        guardarListaStorage();
        volverAListaPlantillas();
    }

    function escapeHtmlAttr(str) {
        return String(str || '').replace(/&/g, '&').replace(/"/g, '"').replace(/</g, '<');
    }

    function escapeHtmlText(str) {
        return String(str || '').replace(/&/g, '&').replace(/</g, '<');
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
        renderizarListaPlantillas();
    };
</script>

</body>
</html>