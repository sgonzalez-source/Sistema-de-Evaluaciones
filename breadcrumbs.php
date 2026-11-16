<?php
// breadcrumbs.php - Acumula el recorrido real de navegación paso a paso

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mapa_paginas = [
    'principal.php'            => 'Inicio',
    'docentes.php'             => 'Gestión de Docentes',
    'plantilla_evaluacion.php' => 'Plantillas de evaluación',
    'evaluar.php'              => 'Evaluar',
    'estadistica.php'          => 'Estadística'
];

$archivo_actual = basename($_SERVER['PHP_SELF']);
$titulo_actual  = $titulo_miga ?? ($mapa_paginas[$archivo_actual] ?? ucfirst(str_replace('.php', '', $archivo_actual)));

// Si el usuario hace clic directamente en una migaja anterior (?miga_idx=X), recorta hasta ese punto
if (isset($_GET['miga_idx']) && isset($_SESSION['ruta_navegacion'])) {
    $idx_clic = (int) $_GET['miga_idx'];
    if (isset($_SESSION['ruta_navegacion'][$idx_clic])) {
        $_SESSION['ruta_navegacion'] = array_slice($_SESSION['ruta_navegacion'], 0, $idx_clic + 1);
    }
}

// Si entra a Inicio (principal.php), reinicia el rastro desde Inicio
if ($archivo_actual === 'principal.php') {
    $_SESSION['ruta_navegacion'] = [
        ['titulo' => 'Inicio', 'url' => 'principal.php']
    ];
} else {
    if (!isset($_SESSION['ruta_navegacion']) || empty($_SESSION['ruta_navegacion'])) {
        $_SESSION['ruta_navegacion'] = [
            ['titulo' => 'Inicio', 'url' => 'principal.php']
        ];
    }

    // Solo agrega el nuevo paso si es diferente al último paso registrado (evita duplicados al recargar F5)
    $ultimo_paso = end($_SESSION['ruta_navegacion']);
    if ($ultimo_paso['url'] !== $archivo_actual) {
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