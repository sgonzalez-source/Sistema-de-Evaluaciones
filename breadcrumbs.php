<?php
// breadcrumbs.php - Genera la ruta de navegación dinámica según por donde vas entrando

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mapa de nombres amigables y archivos del sistema
$mapa_paginas = [
    'principal.php'   => 'Inicio',
    'docentes.php'    => 'Gestión de Docentes',
    'rubricas.php'    => 'Rúbricas',
    'evaluar.php'     => 'Evaluar',
    'estadistica.php' => 'Estadística'
];

$archivo_actual = basename($_SERVER['PHP_SELF']);
$titulo_actual  = $titulo_miga ?? ($mapa_paginas[$archivo_actual] ?? ucfirst(str_replace('.php', '', $archivo_actual)));

// Si entra a Inicio (principal.php), reinicia el rastro de navegación
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

    
    $indice_existente = null;
    foreach ($_SESSION['ruta_navegacion'] as $indice => $paso) {
        if ($paso['url'] === $archivo_actual) {
            $indice_existente = $indice;
            break;
        }
    }

    if ($indice_existente !== null) {
        // Recorta el rastro hasta esa página
        $_SESSION['ruta_navegacion'] = array_slice($_SESSION['ruta_navegacion'], 0, $indice_existente + 1);
    } else {
        // Agrega el nuevo paso al final del recorrido
        $_SESSION['ruta_navegacion'][] = [
            'titulo' => $titulo_actual,
            'url'    => $archivo_actual
        ];
    }
}
?>

<nav class="migas-pan" aria-label="Ruta de navegación">
    <?php 
    $total_pasos = count($_SESSION['ruta_navegacion']);
    foreach ($_SESSION['ruta_navegacion'] as $i => $paso): 
        $es_ultimo = ($i === $total_pasos - 1);
    ?>
        <?php if (!$es_ultimo): ?>
            <a href="<?php echo htmlspecialchars($paso['url']); ?>"><?php echo htmlspecialchars($paso['titulo']); ?></a>
            <span>/</span>
        <?php else: ?>
            <span class="miga-actual"><?php echo htmlspecialchars($paso['titulo']); ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>