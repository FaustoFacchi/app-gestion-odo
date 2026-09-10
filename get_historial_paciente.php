<?php
// get_historial_paciente.php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'session_expired', 'message' => 'No autorizado. Sesión expirada.']);
    exit;
}

$paciente_id = $_GET['paciente_id'] ?? null;

if (!$paciente_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID de paciente requerido.']);
    exit;
}

try {
    // Obtener historial de evoluciones
    $sqlEvoluciones = "SELECT e.id, e.diente_nro, e.diagnostico, e.tratamiento, e.fecha, 
                             CONCAT(u.nombre, ' ', u.apellido) AS profesional
                      FROM evoluciones e
                      LEFT JOIN usuarios u ON e.usuario_id = u.id
                      WHERE e.paciente_id = :paciente_id
                      ORDER BY e.fecha DESC";
    
    $stmtE = $conexion->prepare($sqlEvoluciones);
    $stmtE->bindParam(':paciente_id', $paciente_id);
    $stmtE->execute();
    $evoluciones = $stmtE->fetchAll(PDO::FETCH_ASSOC);

    // Obtener marcas del odontograma
    $sqlOdonto = "SELECT diente_nro, cara, estado FROM odontograma WHERE paciente_id = :paciente_id";
    $stmtO = $conexion->prepare($sqlOdonto);
    $stmtO->bindParam(':paciente_id', $paciente_id);
    $stmtO->execute();
    $odontograma = $stmtO->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status'      => 'success',
        'evoluciones' => $evoluciones,
        'odontograma' => $odontograma
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>