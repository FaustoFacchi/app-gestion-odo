<?php
// guardar_paciente.php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'session_expired', 'message' => 'No autorizado. Sesión expirada.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni             = trim($_POST['dni'] ?? '');
    $nombre          = trim($_POST['nombre'] ?? '');
    $apellido        = trim($_POST['apellido'] ?? '');
    $telefono        = trim($_POST['telefono'] ?? '');
    $obra_social     = trim($_POST['obra_social'] ?? '');
    $alertas_medicas = trim($_POST['alertas_medicas'] ?? '');

    if (empty($dni) || empty($nombre) || empty($apellido)) {
        echo json_encode(['status' => 'error', 'message' => 'DNI, Nombre y Apellido son obligatorios']);
        exit;
    }

    try {
        $sql = "INSERT INTO pacientes (dni, nombre, apellido, telefono, obra_social, alertas_medicas) 
                VALUES (:dni, :nombre, :apellido, :telefono, :obra_social, :alertas)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':dni'             => $dni,
            ':nombre'          => $nombre,
            ':apellido'        => $apellido,
            ':telefono'        => $telefono,
            ':obra_social'     => $obra_social,
            ':alertas'         => $alertas_medicas
        ]);

        // OBTENER EL ID RECIÉN CREADO
        $nuevo_id = $conexion->lastInsertId();

        echo json_encode([
            'status'      => 'success', 
            'message'     => 'Paciente guardado correctamente',
            'paciente_id' => $nuevo_id // <--- AQUÍ SE DEVUELVE EL ID
        ]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'El DNI ingresado ya existe']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>