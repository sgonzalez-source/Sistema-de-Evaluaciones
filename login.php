<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($cedula) || empty($password)) {
        header("Location: index.php?error=1");
        exit();
    }

    // Consulta preparada contra inyecciones SQL
    $stmt = $pdo->prepare("SELECT id, cedula, nombre, password, rol FROM usuarios WHERE cedula = :cedula LIMIT 1");
    $stmt->execute(['cedula' => $cedula]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Credenciales correctas
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redirección según rol
        if ($usuario['rol'] === 'coordinador') {
            header("Location: dashboard_coordinador.php");
        } else {
            header("Location: dashboard_docente.php");
        }
        exit();
    } else {
        // Falló usuario o clave
        header("Location: index.php?error=1");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}