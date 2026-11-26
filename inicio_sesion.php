<?php
session_start();
require_once "conexion.php";

function redirigirPorRol($rol) {
    if (stripos((string)$rol, 'docente') !== false) {
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

    if ($cedula === '' || $password === '') {
        header("Location: inicio_sesion.php?error=1");
        exit();
    }

    try {
        $stmt = $pdo->prepare("
            SELECT u.cedula, u.nombre, u.apellido1, u.apellido2, u.contrasena,
                   r.nombre AS nombre_rol
            FROM usuarios u
            LEFT JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.cedula = :cedula
            LIMIT 1
        ");
        $stmt->execute([':cedula' => $cedula]);
        $usuario = $stmt->fetch();

        $clave_valida = false;
        if ($usuario) {
            $guardada = (string)$usuario['contrasena'];
            if (password_verify($password, $guardada) || $password === $guardada) {
                $clave_valida = true;
            }
        }

        if ($usuario && $clave_valida) {
            $nombre_completo = trim($usuario['nombre'] . " " . $usuario['apellido1']);
            $rol_usuario = !empty($usuario['nombre_rol']) ? $usuario['nombre_rol'] : 'Coordinador';

            $_SESSION['usuario_id'] = $usuario['cedula'];
            $_SESSION['cedula'] = $usuario['cedula'];
            $_SESSION['usuario'] = $nombre_completo;
            $_SESSION['rol'] = $rol_usuario;

            redirigirPorRol($rol_usuario);
        } else {
            header("Location: inicio_sesion.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: inicio_sesion.php?error=1");
        exit();
    }
}

// Si ya tiene una sesión activa, redirige según su rol
if (isset($_SESSION['usuario_id'])) {
    redirigirPorRol($_SESSION['rol'] ?? 'Coordinador');
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