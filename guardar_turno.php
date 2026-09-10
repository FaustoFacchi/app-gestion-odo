<?php
// guardar_turno.php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'session_expired', 'message' => 'No autorizado. Debe iniciar sesión.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario_id    = $_SESSION['usuario_id'];
    $paciente_id   = !empty($_POST['paciente_id']) ? intval($_POST['paciente_id']) : null;
    $nuevo_nombre   = is_string($_POST['nuevo_nombre'] ?? null) ? trim($_POST['nuevo_nombre']) : '';
    $nuevo_telefono = is_string($_POST['nuevo_telefono'] ?? null) ? trim($_POST['nuevo_telefono']) : '';
    
    $fecha         = $_POST['fecha'] ?? null;
    $hora_inicio   = $_POST['hora_inicio'] ?? null;
    $hora_fin      = $_POST['hora_fin'] ?? null;
    $observaciones = is_string($_POST['observaciones'] ?? null) ? trim($_POST['observaciones']) : '';

    // 1. REGISTRO DE PACIENTE NUEVO (Si no se seleccionó uno existente)
    if (!$paciente_id) {
        if (empty($nuevo_nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'Debes seleccionar un paciente o ingresar el nombre del nuevo paciente.']);
            exit;
        }

        try {
            $stmtPac = $conexion->prepare("INSERT INTO pacientes (nombre, telefono) VALUES (:nombre, :telefono)");
            $stmtPac->execute([
                ':nombre'   => $nuevo_nombre,
                ':telefono' => $nuevo_telefono
            ]);
            $paciente_id = $conexion->lastInsertId();
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear el nuevo paciente: ' . $e->getMessage()]);
            exit;
        }
    }

    // 2. PROCESAMIENTO SEGURO DEL ARRAY DE MOTIVOS
    $motivos_input = $_POST['motivo'] ?? [];
    if (!is_array($motivos_input)) {
        $motivos_input = [$motivos_input];
    }

    $motivos_finales = [];
    foreach ($motivos_input as $m) {
        if (is_string($m) && trim($m) !== '') {
            $motivos_finales[] = trim($m);
        }
    }

    // Reemplazar "Otro" por el texto personalizado si se completó
    $textoOtro = is_string($_POST['motivo_otro_texto'] ?? null) ? trim($_POST['motivo_otro_texto']) : '';
    if (($key = array_search('Otro', $motivos_finales)) !== false) {
        if (!empty($textoOtro)) {
            $motivos_finales[$key] = $textoOtro;
        }
    }

    $motivo = implode(', ', $motivos_finales);

    // 3. VALIDACIÓN DE CAMPOS OBLIGATORIOS Y CONSTRUCCIÓN DE DATETIME
    if (!$paciente_id || empty($motivo) || !$fecha || !$hora_inicio || !$hora_fin) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos obligatorios (paciente, motivos, fecha y horarios) deben estar completos.']);
        exit;
    }

    // Combinar Fecha y Hora para generar el timestamp de BD (YYYY-MM-DD HH:MM:SS)
    $fecha_hora_inicio = $fecha . ' ' . $hora_inicio . ':00';
    $fecha_hora_fin    = $fecha . ' ' . $hora_fin . ':00';

    if (strtotime($fecha_hora_fin) <= strtotime($fecha_hora_inicio)) {
        echo json_encode(['status' => 'error', 'message' => 'La hora de fin debe ser posterior a la hora de inicio.']);
        exit;
    }

    try {
        // 4. VALIDACIÓN DE SOLAPAMIENTO DE TURNOS POR USUARIO
        $sqlCheck = "SELECT COUNT(*) FROM turnos 
                     WHERE usuario_id = :usuario_id 
                     AND fecha_hora_inicio < :fin 
                     AND fecha_hora_fin > :inicio";
        
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->execute([
            ':usuario_id' => $usuario_id,
            ':inicio'     => $fecha_hora_inicio,
            ':fin'        => $fecha_hora_fin
        ]);

        if ($stmtCheck->fetchColumn() > 0) {
            echo json_encode([
                'status'  => 'error', 
                'message' => '⚠️ Ya tenés un turno agendado en ese rango de horario.'
            ]);
            exit;
        }

        // 5. INSERTAR EL TURNO EN LA BD
        $sql = "INSERT INTO turnos (usuario_id, paciente_id, motivo, fecha_hora_inicio, fecha_hora_fin, observaciones) 
                VALUES (:usuario_id, :paciente_id, :motivo, :fecha_hora_inicio, :fecha_hora_fin, :observaciones)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':fecha_hora_inicio', $fecha_hora_inicio);
        $stmt->bindParam(':fecha_hora_fin', $fecha_hora_fin);
        $stmt->bindParam(':observaciones', $observaciones);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Turno guardado con éxito.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar el turno.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}
?>