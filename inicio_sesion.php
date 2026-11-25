<?php
session_start();

// Cuentas temporales de prueba (el sistema identifica el rol automáticamente)
$usuarios_temporales = [
    [
        'id' => 1,
        'cedula' => '18965420',
        'password' => '7894',
        'nombre' => 'Javier Mena',
        'rol' => 'Coordinador',
        'destino' => 'principal.php'
    ],
    [
        'id' => 101,
        'cedula' => '100564782',
        'password' => '1234',
        'nombre' => 'Lucía Rodríguez',
        'rol' => 'Docente',
        'destino' => 'inicio_docente.php'
    ],
    [
        'id' => 102,
        'cedula' => '203648647',
        'password' => '1234',
        'nombre' => 'Mario López',
        'rol' => 'Docente',
        'destino' => 'inicio_docente.php'
    ],
    [
        'id' => 103,
        'cedula' => '187263944',
        'password' => '1234',
        'nombre' => 'Randall Ocón',
        'rol' => 'Docente',
        'destino' => 'inicio_docente.php'
    ],
    [
        'id' => 104,
        'cedula' => '166478230',
        'password' => '1234',
        'nombre' => 'Sharon Mendes',
        'rol' => 'Docente',
        'destino' => 'inicio_docente.php'
    ]
];

function redirigirPorRol() {
    $rol = $_SESSION['rol'] ?? 'Coordinador';
    if ($rol === 'Docente') {
        header("Location: inicio_docente.php");
    } else {
        header("Location: principal.php");
    }
    exit();
}

// Si se envió el formulario al presionar "Ingresar"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $usuario_encontrado = null;
    foreach ($usuarios_temporales as $u) {
        if ($u['cedula'] === $cedula && $u['password'] === $password) {
            $usuario_encontrado = $u;
            break;
        }
    }

    if ($usuario_encontrado !== null) {
        $_SESSION['usuario_id'] = $usuario_encontrado['id'];
        $_SESSION['cedula'] = $usuario_encontrado['cedula'];
        $_SESSION['usuario'] = $usuario_encontrado['nombre'];
        $_SESSION['rol'] = $usuario_encontrado['rol'];

        header("Location: " . $usuario_encontrado['destino']);
        exit();
    } else {
        header("Location: inicio_sesion.php?error=1");
        exit();
    }
}

// Si ya tiene una sesión activa, redirige según su rol
if (isset($_SESSION['usuario_id'])) {
    redirigirPorRol();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEDES Don Bosco - Iniciar Sesión</title>
    <link rel="stylesheet" href="login.css?v=2">
</head>
<body>

    <main class="login-wrapper">
        <section class="card-login">
            
            <header class="logo-container">
                <img src="logo_black.png" alt="CEDES Don Bosco" class="logo-img">
            </header>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert-error">
                    Cédula o contraseña incorrecta.
                </div>
            <?php endif; ?>

            <form action="inicio_sesion.php" method="POST" class="login-form">
                
                <div class="input-box">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="14" x="2" y="5" rx="2"/>
                            <circle cx="8" cy="11" r="2"/>
                            <path d="M14 9h4"/>
                            <path d="M14 13h4"/>
                        </svg>
                    </span>
                    <input type="text" name="cedula" placeholder="Cédula" required autofocus>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>

                <button type="submit" class="btn-submit">Ingresar</button>

            </form>

            <footer class="footer-links">
                <a href="#">¿Olvidó su contraseña?</a>
            </footer>

        </section>
    </main>

</body>
</html>