<?php
// editar_horario_turno.php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no válida.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id  = $_SESSION['usuario_id'];
    $id          = !empty($_POST['id']) ? intval($_POST['id']) : null;
    $fecha       = $_POST['fecha'] ?? null;
    $hora_inicio = $_POST['hora_inicio'] ?? null;
    $hora_fin    = $_POST['hora_fin'] ?? null;

    if (!$id || !$fecha || !$hora_inicio || !$hora_fin) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos.']);
        exit;
    }

    $fecha_hora_inicio = $fecha . ' ' . $hora_inicio . ':00';
    $fecha_hora_fin    = $fecha . ' ' . $hora_fin . ':00';

    if (strtotime($fecha_hora_fin) <= strtotime($fecha_hora_inicio)) {
        echo json_encode(['status' => 'error', 'message' => 'La hora de fin debe ser posterior a la de inicio.']);
        exit;
    }

    try {
        // Verificar solapamiento omitiendo el turno actual que estamos editando
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
            echo json_encode(['status' => 'error', 'message' => '⚠️ Ya tenés un turno ocupando ese horario.']);
            exit;
        }

        // Actualizar solo fecha y horarios
        $sql = "UPDATE turnos 
                   SET fecha_hora_inicio = :inicio, 
                       fecha_hora_fin = :fin 
                 WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':inicio'     => $fecha_hora_inicio,
            ':fin'        => $fecha_hora_fin,
            ':id'         => $id,
            ':usuario_id' => $usuario_id
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Turno actualizado con éxito.']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?>