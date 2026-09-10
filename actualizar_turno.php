<?php
// actualizar_turno.php
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
    
    $id                = $_POST['id'] ?? null;
    $fecha_hora_inicio = $_POST['fecha_hora_inicio'] ?? null;
    $fecha_hora_fin    = $_POST['fecha_hora_fin'] ?? null;
    $usuario_id        = $_SESSION['usuario_id'];

    if (!$id || !$fecha_hora_inicio || !$fecha_hora_fin) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios para actualizar el turno.']);
        exit;
    }

    // Validación lógica del rango de tiempo
    if (strtotime($fecha_hora_fin) <= strtotime($fecha_hora_inicio)) {
        echo json_encode(['status' => 'error', 'message' => 'La hora de fin debe ser posterior a la hora de inicio.']);
        exit;
    }

    try {
        // 1. VALIDACIÓN DE SOLAPAMIENTO
        $sqlCheck = "SELECT COUNT(*) FROM turnos 
                     WHERE usuario_id = :usuario_id 
                     AND id != :id
                     AND fecha_hora_inicio < :fin 
                     AND fecha_hora_fin > :inicio";
        
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->execute([
            ':usuario_id' => $usuario_id,
            ':id'         => $id,
            ':inicio'     => $fecha_hora_inicio,
            ':fin'        => $fecha_hora_fin
        ]);

        if ($stmtCheck->fetchColumn() > 0) {
            echo json_encode([
                'status'  => 'error', 
                'message' => '⚠️ No se puede mover: ya tenés otro turno agendado en ese horario.'
            ]);
            exit;
        }

        // 2. ACTUALIZAR EL TURNO
        $sql = "UPDATE turnos 
                SET fecha_hora_inicio = :fecha_hora_inicio, 
                    fecha_hora_fin = :fecha_hora_fin 
                WHERE id = :id AND usuario_id = :usuario_id";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':fecha_hora_inicio', $fecha_hora_inicio);
        $stmt->bindParam(':fecha_hora_fin', $fecha_hora_fin);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Turno actualizado con éxito.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el turno o no tenés permisos sobre él.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}
?>