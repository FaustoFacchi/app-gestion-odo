<?php
// guardar_evolucion.php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'session_expired', 'message' => 'No tenés permisos para realizar esta acción.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paciente_id  = $_POST['paciente_id'] ?? null;
    $usuario_id   = $_SESSION['usuario_id']; 
    $diente_nro   = $_POST['diente_nro'] ?? null;
    $cara         = $_POST['cara'] ?? null;
    $diagnostico  = trim($_POST['diagnostico'] ?? '');
    $tratamiento  = trim($_POST['tratamiento'] ?? '');

    if (!$paciente_id || empty($tratamiento)) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios para registrar la evolución.']);
        exit;
    }

    try {
        $conexion->beginTransaction();

        $sqlEvolucion = "INSERT INTO evoluciones (paciente_id, usuario_id, diente_nro, diagnostico, tratamiento, fecha) 
                         VALUES (:paciente_id, :usuario_id, :diente_nro, :diagnostico, :tratamiento, NOW())";
        
        $stmt = $conexion->prepare($sqlEvolucion);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':diente_nro', $diente_nro);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':tratamiento', $tratamiento);
        $stmt->execute();

        if ($diente_nro && $cara) {
            $sqlOdonto = "INSERT INTO odontograma (paciente_id, diente_nro, cara, estado) 
                          VALUES (:paciente_id, :diente_nro, :cara, 'tratado')
                          ON DUPLICATE KEY UPDATE estado = 'tratado'";
            
            $stmtOdonto = $conexion->prepare($sqlOdonto);
            $stmtOdonto->bindParam(':paciente_id', $paciente_id);
            $stmtOdonto->bindParam(':diente_nro', $diente_nro);
            $stmtOdonto->bindParam(':cara', $cara);
            $stmtOdonto->execute();
        }

        $conexion->commit();
        echo json_encode(['status' => 'success', 'message' => 'Evolución registrada correctamente.']);

    } catch (PDOException $e) {
        $conexion->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>