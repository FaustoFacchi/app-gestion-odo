<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

try {
    require_once 'conexion.php'; 

    $paciente_id = $_GET['paciente_id'] ?? null;

    if (!$paciente_id || intval($paciente_id) <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Falta el ID del paciente']);
        exit;
    }

    $sql = "SELECT * FROM anamnesis WHERE paciente_id = ?";
    
    // Compatibilidad en caso de que tu variable sea $pdo o $conexion
    $pdo_conn = isset($pdo) ? $pdo : $conexion; 
    $stmt = $pdo_conn->prepare($sql);
    
    $stmt->execute([$paciente_id]); 
    $anamnesis = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($anamnesis) {
        echo json_encode([
            'status' => 'success',
            'exists' => true,
            'data'   => $anamnesis
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'exists' => false
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}