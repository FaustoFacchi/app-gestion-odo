<?php
// editar_paciente.php
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
    $id              = $_POST['id'] ?? null;
    $dni             = trim($_POST['dni'] ?? '');
    $nombre          = trim($_POST['nombre'] ?? '');
    $apellido        = trim($_POST['apellido'] ?? '');
    $telefono        = trim($_POST['telefono'] ?? '');
    $obra_social     = trim($_POST['obra_social'] ?? '');
    $alertas_medicas = trim($_POST['alertas_medicas'] ?? '');

    if (!$id || empty($dni) || empty($nombre) || empty($apellido)) {
        echo json_encode(['status' => 'error', 'message' => 'ID, DNI, Nombre y Apellido son obligatorios.']);
        exit;
    }

    try {
        $sql = "UPDATE pacientes 
                SET dni = :dni, nombre = :nombre, apellido = :apellido, telefono = :telefono, 
                    obra_social = :obra_social, alertas_medicas = :alertas 
                WHERE id = :id";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':dni'         => $dni,
            ':nombre'      => $nombre,
            ':apellido'    => $apellido,
            ':telefono'    => $telefono,
            ':obra_social' => $obra_social,
            ':alertas'     => $alertas_medicas,
            ':id'          => $id
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Paciente actualizado correctamente.']);

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['status' => 'error', 'message' => 'El DNI ingresado pertenece a otro paciente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>