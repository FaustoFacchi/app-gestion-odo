<?php
// eliminar_turno.php
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
    $id = $_POST['id'] ?? null;
    $usuario_id = $_SESSION['usuario_id'];

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID de turno requerido.']);
        exit;
    }

    try {
        // Garantizamos que solo elimine turnos del usuario en sesión
        $sql = "DELETE FROM turnos WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Turno eliminado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar turno: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>