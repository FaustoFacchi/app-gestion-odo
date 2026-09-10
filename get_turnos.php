<?php
// get_turnos.php
session_start();
header('Content-Type: application/json');
require_once 'conexion.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'session_expired', 'message' => 'No autorizado. Sesión expirada.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

try {
    $start = isset($_GET['start']) ? $_GET['start'] : null;
    $end   = isset($_GET['end']) ? $_GET['end'] : null;

    // Usamos COALESCE por si apellido es NULL, para evitar que rompa el título
    $sql = "SELECT 
                t.id, 
                t.motivo, 
                t.fecha_hora_inicio AS start, 
                t.fecha_hora_fin AS end,
                t.observaciones,
                TRIM(CONCAT(COALESCE(p.nombre, ''), ' ', COALESCE(p.apellido, ''))) AS paciente_nombre
            FROM turnos t
            INNER JOIN pacientes p ON t.paciente_id = p.id
            WHERE t.usuario_id = :usuario_id";

    // Rango flexible para que FullCalendar no ignore turnos por segundos o límites exactos
    if ($start && $end) {
        $sql .= " AND t.fecha_hora_inicio < :end AND t.fecha_hora_fin > :start";
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);

    if ($start && $end) {
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $eventos = [];
    foreach ($result as $row) {
        $motivo = $row['motivo'];

        // Definición de colores pastel según el motivo
        $colorFondo = '#62a1ff'; // Azul pastel por defecto (Consulta General)
        $colorBorde = '#9ec5fe';

        if (str_contains($motivo, 'Limpieza')) {
            $colorFondo = '#94d1b6'; // Verde pastel
            $colorBorde = '#badbcc';
        } elseif (str_contains($motivo, 'Ortodoncia')) {
            $colorFondo = '#f0db99'; // Amarillo pastel / Crema
            $colorBorde = '#ffeeba';
        } elseif (str_contains($motivo, 'Conducto')) {
            $colorFondo = '#bba5e2'; // Violeta pastel
            $colorBorde = '#d1c4e9';
        } elseif (str_contains($motivo, 'Extracción')) {
            $colorFondo = '#e28890'; // Rojo/Rosa pastel
            $colorBorde = '#f5c2c7';
        } elseif (str_contains($motivo, 'Otro')) {
            $colorFondo = '#dfb28d'; // Naranja pastel
            $colorBorde = '#ffd8b5';
        }

        $eventos[] = [
            'id'              => $row['id'],
            'title'           => (!empty($row['paciente_nombre']) ? $row['paciente_nombre'] : 'Sin nombre') . ' - ' . $motivo,
            'start'           => $row['start'],
            'end'             => $row['end'],
            'backgroundColor' => $colorFondo,
            'borderColor'     => $colorBorde,
            'textColor'       => '#212529', // Texto oscuro para una lectura formal y limpia
            'extendedProps'   => [
                'observaciones' => $row['observaciones'] ?? '',
                'motivo'        => $motivo
            ]
        ];
    }

    echo json_encode($eventos);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>