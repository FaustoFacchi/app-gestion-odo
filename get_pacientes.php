<?php
// get_pacientes.php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'session_expired', 'message' => 'No autorizado. Sesión expirada.']);
    exit;
}

try {
    $stmt = $conexion->query("SELECT id, dni, nombre, apellido, telefono, email, obra_social, alertas_medicas FROM pacientes ORDER BY id DESC");
    $pacientes = $stmt->fetchAll();
    
    echo json_encode(['status' => 'success', 'data' => $pacientes]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>