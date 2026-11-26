<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (file_exists(__DIR__ . '/conexion.php')) {
    require_once __DIR__ . '/conexion.php';
}

$rol_actual = $_SESSION['rol'] ?? 'Coordinador';

// Si hay cédula en sesión y conexión PDO disponible, consulta el rol real en la BD
if (!empty($_SESSION['cedula']) && isset($pdo) && ($pdo instanceof PDO)) {
    try {
        $stmtRolMiga = $pdo->prepare("
            SELECT r.nombre AS nombre_rol
            FROM usuarios u
            LEFT JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.cedula = :cedula
            LIMIT 1
        ");
        $stmtRolMiga->execute([':cedula' => $_SESSION['cedula']]);
        $filaRolMiga = $stmtRolMiga->fetch();
        if ($filaRolMiga && !empty($filaRolMiga['nombre_rol'])) {
            $rol_actual = $filaRolMiga['nombre_rol'];
            $_SESSION['rol'] = $rol_actual;
        }
    } catch (PDOException $e) {
        // Conserva el rol en sesión si ocurre un error de consulta
    }
}

$es_docente     = (strcasecmp($rol_actual, 'Docente') === 0);
$url_inicio_rol = $es_docente ? 'inicio_docente.php' : 'principal.php';

$mapa_paginas = [
    'principal.php'            => 'Inicio',
    'inicio_docente.php'       => 'Inicio',
    'docentes.php'             => 'Gestión de Docentes',
    'plantilla_evaluacion.php' => 'Plantillas de evaluación',
    'evaluar.php'              => 'Evaluación',
    'estadisticas.php'         => 'Estadística',
    'estadistica.php'          => 'Estadística',
    'planeamiento_docente.php' => 'Planeamiento',
    'mis_evaluaciones.php'     => 'Mis evaluaciones',
    'perfil_docente.php'       => 'Perfil de usuario',
    'perfil.php'               => 'Perfil de usuario'
];

$archivo_actual = basename($_SERVER['PHP_SELF']);
$titulo_actual  = $titulo_miga ?? ($mapa_paginas[$archivo_actual] ?? ucfirst(str_replace('.php', '', $archivo_actual)));

// Si cambió el rol o la raíz guardada no coincide con el rol actual, reinicia el rastro
if (
    !isset($_SESSION['ruta_navegacion']) ||
    !is_array($_SESSION['ruta_navegacion']) ||
    empty($_SESSION['ruta_navegacion']) ||
    (($_SESSION['ruta_navegacion'][0]['url'] ?? '') !== $url_inicio_rol)
) {
    $_SESSION['ruta_navegacion'] = [
        ['titulo' => 'Inicio', 'url' => $url_inicio_rol]
    ];
}

// Detecta si el usuario viene directamente desde el Inicio de su rol
$pagina_anterior = $_SERVER['HTTP_REFERER'] ?? '';
$viene_de_inicio = (strpos($pagina_anterior, $url_inicio_rol) !== false);

// 1. Si hace clic directamente en una migaja anterior (?miga_idx=X), recorta hasta ese punto
if (isset($_GET['miga_idx'])) {
    $idx_clic = (int) $_GET['miga_idx'];
    if (isset($_SESSION['ruta_navegacion'][$idx_clic])) {
        $_SESSION['ruta_navegacion'] = array_slice($_SESSION['ruta_navegacion'], 0, $idx_clic + 1);
    }
}

// 2. Si está en el Inicio de su rol, reinicia el rastro a ese Inicio
if ($archivo_actual === $url_inicio_rol) {
    $_SESSION['ruta_navegacion'] = [
        ['titulo' => 'Inicio', 'url' => $url_inicio_rol]
    ];
} else {
    if ($viene_de_inicio) {
        $_SESSION['ruta_navegacion'] = [
            ['titulo' => 'Inicio', 'url' => $url_inicio_rol]
        ];
    }

    // Evita duplicar la misma sección si ya existe en el rastro
    $indice_existente = false;
    foreach ($_SESSION['ruta_navegacion'] as $idx => $paso) {
        if ($paso['url'] === $archivo_actual) {
            $indice_existente = $idx;
            break;
        }
    }

    if ($indice_existente !== false) {
        $_SESSION['ruta_navegacion'] = array_slice($_SESSION['ruta_navegacion'], 0, $indice_existente + 1);
        $_SESSION['ruta_navegacion'][$indice_existente]['titulo'] = $titulo_actual;
    } else {
        $_SESSION['ruta_navegacion'][] = [
            'titulo' => $titulo_actual,
            'url'    => $archivo_actual
        ];
    }
}
?>

<nav class="migas-pan" id="migas-pan-dinamicas" aria-label="Ruta de navegación">
    <?php
    $total_pasos = count($_SESSION['ruta_navegacion']);
    foreach ($_SESSION['ruta_navegacion'] as $i => $paso):
        $es_ultimo = ($i === $total_pasos - 1);
        $separador = (strpos($paso['url'], '?') !== false) ? '&' : '?';
        $url_con_indice = $paso['url'] . $separador . 'miga_idx=' . $i;
    ?>
        <?php if (!$es_ultimo): ?>
            <a href="<?php echo htmlspecialchars($url_con_indice); ?>"><?php echo htmlspecialchars($paso['titulo']); ?></a>
            <span>/</span>
        <?php else: ?>
            <span class="miga-actual"><?php echo htmlspecialchars($paso['titulo']); ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>