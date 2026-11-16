<?php
session_start();

// Si confirmó "Cerrar Sesión" en el modal, destruye la sesión y vuelve a inicio_sesion.php
if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: inicio_sesion.php");
    exit();
}

// Reinicia el historial de migajas de pan al entrar al inicio
$_SESSION['ruta_navegacion'] = [
    ['titulo' => 'Inicio', 'url' => 'principal.php']
];

// Datos iniciales en variables PHP (sin base de datos)
$nombre = $_SESSION["usuario"] ?? "Javier Mena";
$primer_nombre = "Javier";
$rol = $_SESSION["rol"] ?? "Coordinador";
$anio_escolar = "2026";

$estado_periodo = "Activo";
$fecha_limite_texto = "27 Noviembre 2026";
$fecha_limite_iso = "2026-11-27";

$docentes_completados = 12;
$porcentaje_completado = 75;
$dias_cierre = 11;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - CEDES Don Bosco</title>
    <link rel="stylesheet" href="principal.css?v=13">
</head>
<body>

<div class="contenedor-general">

    <!-- MENÚ LATERAL AZUL (COLAPSABLE SIN PARPADEO) -->
    <aside class="menu" id="sidebar-menu">
        <script>if(localStorage.getItem('menu_colapsado')==='1')document.getElementById('sidebar-menu').classList.add('colapsado');</script>

        <div class="logo">
            <img src="logo_white.png" alt="CEDES Don Bosco" class="logo-blanco">
        </div>

        <button type="button" class="btn-hamburguesa-menu" onclick="alternarMenu()" title="Expandir / Contraer menú" aria-label="Alternar menú lateral">
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

            <a href="#" title="Evaluar">
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

            <button type="button" class="salir" onclick="abrirModalSalir()" title="Cerrar Sesión">
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span class="texto-menu">Cerrar Sesión</span>
            </button>
        </div>
    </aside>

    <!-- ÁREA DERECHA: CONTENIDO PRINCIPAL COMPACTO -->
    <main class="contenido">
        
        <!-- ENCABEZADO DE BIENVENIDA -->
        <header class="banner-bienvenida">
            <div class="bienvenida-izq">
                <span class="saludo-tiempo" id="saludo-tiempo">Buenos días</span>
                <h1>¡Hola, <span class="nombre-destacado"><?php echo $primer_nombre; ?></span>!</h1>
                <p class="subtitulo">Te damos la bienvenida al sistema de evaluaciones docentes</p>
            </div>

            <div class="bienvenida-der">
                <div class="pildora-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                    <span>Año escolar <strong><?php echo $anio_escolar; ?></strong></span>
                </div>
                <div class="fecha-hoy" id="fecha-hoy"></div>
            </div>
        </header>

        <!-- WIDGET 1: CONTROL DEL PERIODO EN UNA SOLA FRANJA -->
        <section class="tarjeta tarjeta-periodo">
            <div class="fila-periodo-compacta">
                <div class="titulo-con-icono">
                    <div class="icono-caja azul">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <h2>Control del Periodo</h2>
                        <span class="texto-ayuda">Gestión de fechas de entrega</span>
                    </div>
                </div>

                <div class="datos-periodo-inline">
                    <div class="columna-dato">
                        <span class="etiqueta-mini">Estado actual</span>
                        <span class="insignia-estado activo" id="texto-estado">
                            <span class="punto-estado"></span>
                            <?php echo $estado_periodo; ?>
                        </span>
                    </div>

                    <div class="separador-vertical"></div>

                    <div class="columna-dato">
                        <span class="etiqueta-mini">Fecha límite de entrega</span>
                        <strong class="valor-fecha" id="texto-fecha"><?php echo $fecha_limite_texto; ?></strong>
                        
                        <div class="editor-fecha" id="editor-fecha" style="display: none;">
                            <input type="date" id="input-fecha" value="<?php echo $fecha_limite_iso; ?>">
                            <button type="button" class="boton-guardar" onclick="guardarFecha()">Guardar</button>
                            <button type="button" class="boton-cancelar" onclick="cerrarEditorFecha()">Cancelar</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="boton-naranja" onclick="abrirEditorFecha()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Modificar fecha</span>
                </button>
            </div>
        </section>

        <!-- FILA DE 3 WIDGETS DE MÉTRICAS -->
        <section class="fila-metricas">
            <div class="tarjeta metrica borde-azul">
                <div class="metrica-top">
                    <span class="etiqueta-gris">Evaluaciones completadas</span>
                    <div class="icono-mini azul">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <polyline points="23 11 17 17 14 14"></polyline>
                        </svg>
                    </div>
                </div>
                <strong class="numero-grande"><?php echo $docentes_completados; ?> <span class="unidad">Docentes</span></strong>
            </div>

            <div class="tarjeta metrica borde-verde">
                <div class="metrica-top">
                    <span class="etiqueta-gris">Progreso general</span>
                    <div class="icono-mini verde">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                </div>
                <div class="fila-progreso">
                    <strong class="numero-grande"><?php echo $porcentaje_completado; ?>%</strong>
                    <div class="barra-fondo">
                        <div class="barra-relleno" style="width: <?php echo $porcentaje_completado; ?>%;"></div>
                    </div>
                </div>
            </div>

            <div class="tarjeta metrica borde-naranja">
                <div class="metrica-top">
                    <span class="etiqueta-gris">Próximo Cierre</span>
                    <div class="icono-mini naranja">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
                <strong class="numero-grande" id="texto-dias"><?php echo $dias_cierre; ?> <span class="unidad">días</span></strong>
            </div>
        </section>

        <!-- FILA DE 2 WIDGETS DE TAREAS -->
        <section class="fila-tareas">
            <div class="tarjeta tarjeta-tareas">
                <div class="cabecera-tareas">
                    <h3>Tareas Pendientes</h3>
                    <span class="badge-titulo pendiente">Por hacer</span>
                </div>
                <ul class="lista-tareas" id="lista-pendientes">
                    <li>
                        <label class="item-check">
                            <input type="checkbox" onchange="cambiarEstadoTarea(this)">
                            <span>Evaluar 3 docentes</span>
                        </label>
                    </li>
                    <li>
                        <label class="item-check">
                            <input type="checkbox" onchange="cambiarEstadoTarea(this)">
                            <span>Revisar 5 planeamientos</span>
                        </label>
                    </li>
                    <li>
                        <label class="item-check">
                            <input type="checkbox" onchange="cambiarEstadoTarea(this)">
                            <span>Enviar 2 evaluaciones</span>
                        </label>
                    </li>
                    <li>
                        <label class="item-check">
                            <input type="checkbox" onchange="cambiarEstadoTarea(this)">
                            <span>Evaluar 1 docente</span>
                        </label>
                    </li>
                </ul>
            </div>

            <div class="tarjeta tarjeta-tareas">
                <div class="cabecera-tareas">
                    <h3>Tareas realizadas</h3>
                    <span class="badge-titulo listo">Completadas</span>
                </div>
                <ul class="lista-tareas" id="lista-realizadas">
                    <li>
                        <label class="item-check completada">
                            <input type="checkbox" checked onchange="cambiarEstadoTarea(this)">
                            <span>Evaluación para Manuel finalizada</span>
                        </label>
                    </li>
                    <li>
                        <label class="item-check completada">
                            <input type="checkbox" checked onchange="cambiarEstadoTarea(this)">
                            <span>Evaluación para Esther finalizada</span>
                        </label>
                    </li>
                    <li>
                        <label class="item-check completada">
                            <input type="checkbox" checked onchange="cambiarEstadoTarea(this)">
                            <span>Revisión de planeamientos finalizada</span>
                        </label>
                    </li>
                </ul>
            </div>
        </section>

    </main>
</div>

<!-- MODAL DE CONFIRMACIÓN PARA CERRAR SESIÓN -->
<div class="fondo-modal" id="modal-salir" onclick="cerrarModalSiFuera(event)">
    <div class="caja-modal">
        <button type="button" class="cerrar-modal-x" onclick="cerrarModalSalir()" aria-label="Cerrar">
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
            <button type="button" class="btn-modal-cancelar" onclick="cerrarModalSalir()">Cancelar</button>
            <a href="principal.php?salir=1" class="btn-modal-confirmar">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
    function alternarMenu() {
        const menu = document.getElementById('sidebar-menu');
        menu.classList.add('animar');
        menu.classList.toggle('colapsado');
        localStorage.setItem('menu_colapsado', menu.classList.contains('colapsado') ? '1' : '0');
    }

    function iniciarBienvenida() {
        const ahora = new Date();
        const hora = ahora.getHours();
        let saludo = "Buenos días";

        if (hora >= 12 && hora < 18) {
            saludo = "Buenas tardes";
        } else if (hora >= 18 || hora < 5) {
            saludo = "Buenas noches";
        }

        document.getElementById('saludo-tiempo').textContent = saludo;

        const opcionesFecha = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        let textoHoy = ahora.toLocaleDateString('es-ES', opcionesFecha);
        textoHoy = textoHoy.charAt(0).toUpperCase() + textoHoy.slice(1);
        document.getElementById('fecha-hoy').textContent = textoHoy;
    }

    window.onload = function() {
        iniciarBienvenida();

        const fechaGuardada = localStorage.getItem('fecha_iso');
        if (fechaGuardada) {
            document.getElementById('input-fecha').value = fechaGuardada;
            guardarFecha(false);
        }

        const pendientesGuardadas = localStorage.getItem('html_pendientes');
        const realizadasGuardadas = localStorage.getItem('html_realizadas');
        if (pendientesGuardadas && realizadasGuardadas) {
            document.getElementById('lista-pendientes').innerHTML = pendientesGuardadas;
            document.getElementById('lista-realizadas').innerHTML = realizadasGuardadas;
        }
    };

    function cambiarEstadoTarea(checkbox) {
        const fila = checkbox.closest('li');
        const etiqueta = fila.querySelector('label');

        if (checkbox.checked) {
            checkbox.setAttribute('checked', 'checked');
            etiqueta.classList.add('completada');
            document.getElementById('lista-realizadas').appendChild(fila);
        } else {
            checkbox.removeAttribute('checked');
            etiqueta.classList.remove('completada');
            document.getElementById('lista-pendientes').appendChild(fila);
        }

        localStorage.setItem('html_pendientes', document.getElementById('lista-pendientes').innerHTML);
        localStorage.setItem('html_realizadas', document.getElementById('lista-realizadas').innerHTML);
    }

    function abrirEditorFecha() {
        document.getElementById('editor-fecha').style.display = 'flex';
    }

    function cerrarEditorFecha() {
        document.getElementById('editor-fecha').style.display = 'none';
    }

    function guardarFecha(guardarEnMemoria = true) {
        const valor = document.getElementById('input-fecha').value;
        if (!valor) return;

        const meses = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];

        const partes = valor.split('-');
        const anio = parseInt(partes[0]);
        const mes = parseInt(partes[1]) - 1;
        const dia = parseInt(partes[2]);

        document.getElementById('texto-fecha').textContent = `${dia} ${meses[mes]} ${anio}`;

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaElegida = new Date(anio, mes, dia);
        const diasRestantes = Math.ceil((fechaElegida - hoy) / (1000 * 60 * 60 * 24));

        const insigniaEstado = document.getElementById('texto-estado');
        const textoDias = document.getElementById('texto-dias');

        if (diasRestantes >= 0) {
            insigniaEstado.innerHTML = `<span class="punto-estado"></span> Activo`;
            insigniaEstado.className = "insignia-estado activo";
            textoDias.innerHTML = `${diasRestantes} <span class="unidad">días</span>`;
        } else {
            insigniaEstado.innerHTML = `<span class="punto-estado"></span> Cerrado`;
            insigniaEstado.className = "insignia-estado cerrado";
            textoDias.innerHTML = `0 <span class="unidad">días</span>`;
        }

        if (guardarEnMemoria) {
            localStorage.setItem('fecha_iso', valor);
            cerrarEditorFecha();
        }
    }

    function abrirModalSalir() {
        document.getElementById('modal-salir').classList.add('visible');
    }

    function cerrarModalSalir() {
        document.getElementById('modal-salir').classList.remove('visible');
    }

    function cerrarModalSiFuera(evento) {
        if (evento.target.id === 'modal-salir') {
            cerrarModalSalir();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalSalir();
        }
    });
</script>

</body>
</html>