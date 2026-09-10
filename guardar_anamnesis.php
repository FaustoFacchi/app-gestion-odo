<?php
// Desactivar impresión directa de errores para evitar HTML no deseado
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

try {
    require_once 'conexion.php'; // Usa la variable de conexión PDO (ej: $pdo o $conexion)

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $paciente_id = intval($_POST['paciente_id'] ?? 0);

        if ($paciente_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Paciente no válido']);
            exit;
        }

        $sg_tratamiento = isset($_POST['sg_tratamiento']) ? 1 : 0;
        $sg_tratamiento_txt = trim($_POST['sg_tratamiento_txt'] ?? '');
        $sg_medicacion = isset($_POST['sg_medicacion']) ? 1 : 0;
        $sg_medicacion_txt = trim($_POST['sg_medicacion_txt'] ?? '');
        $sg_alergia = isset($_POST['sg_alergia']) ? 1 : 0;
        $sg_alergia_txt = trim($_POST['sg_alergia_txt'] ?? '');
        $sg_cirugia = isset($_POST['sg_cirugia']) ? 1 : 0;
        $sg_cirugia_txt = trim($_POST['sg_cirugia_txt'] ?? '');
        $sg_presion = isset($_POST['sg_presion']) ? 1 : 0;
        $sg_presion_alta = isset($_POST['sg_presion_alta']) ? 1 : 0;
        $sg_presion_baja = isset($_POST['sg_presion_baja']) ? 1 : 0;
        $sg_diabetico = isset($_POST['sg_diabetico']) ? 1 : 0;
        $sg_cardiaco = isset($_POST['sg_cardiaco']) ? 1 : 0;
        $sg_epilepsia = isset($_POST['sg_epilepsia']) ? 1 : 0;
        $sg_asma = isset($_POST['sg_asma']) ? 1 : 0;
        $sg_hemorragia = isset($_POST['sg_hemorragia']) ? 1 : 0;
        $sg_fuma = isset($_POST['sg_fuma']) ? 1 : 0;
        $sg_embarazo = isset($_POST['sg_embarazo']) ? 1 : 0;

        $sb_dolor = isset($_POST['sb_dolor']) ? 1 : 0;
        $sb_sangrado = isset($_POST['sb_sangrado']) ? 1 : 0;
        $sb_bruxismo = isset($_POST['sb_bruxismo']) ? 1 : 0;
        $sb_movilidad = isset($_POST['sb_movilidad']) ? 1 : 0;
        $sb_anestesia = isset($_POST['sb_anestesia']) ? 1 : 0;
        $sb_frecuencia_cepillado = trim($_POST['sb_frecuencia_cepillado'] ?? '2');
        $sb_motivo_consulta = trim($_POST['sb_motivo_consulta'] ?? '');

        $sql = "INSERT INTO anamnesis (
                    paciente_id, sg_tratamiento, sg_tratamiento_txt, sg_medicacion, sg_medicacion_txt,
                    sg_alergia, sg_alergia_txt, sg_cirugia, sg_cirugia_txt, sg_presion, sg_presion_alta,
                    sg_presion_baja, sg_diabetico, sg_cardiaco, sg_epilepsia, sg_asma, sg_hemorragia,
                    sg_fuma, sg_embarazo, sb_dolor, sb_sangrado, sb_bruxismo, sb_movilidad, sb_anestesia,
                    sb_frecuencia_cepillado, sb_motivo_consulta
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    sg_tratamiento=VALUES(sg_tratamiento), sg_tratamiento_txt=VALUES(sg_tratamiento_txt),
                    sg_medicacion=VALUES(sg_medicacion), sg_medicacion_txt=VALUES(sg_medicacion_txt),
                    sg_alergia=VALUES(sg_alergia), sg_alergia_txt=VALUES(sg_alergia_txt),
                    sg_cirugia=VALUES(sg_cirugia), sg_cirugia_txt=VALUES(sg_cirugia_txt),
                    sg_presion=VALUES(sg_presion), sg_presion_alta=VALUES(sg_presion_alta),
                    sg_presion_baja=VALUES(sg_presion_baja), sg_diabetico=VALUES(sg_diabetico),
                    sg_cardiaco=VALUES(sg_cardiaco), sg_epilepsia=VALUES(sg_epilepsia),
                    sg_asma=VALUES(sg_asma), sg_hemorragia=VALUES(sg_hemorragia),
                    sg_fuma=VALUES(sg_fuma), sg_embarazo=VALUES(sg_embarazo),
                    sb_dolor=VALUES(sb_dolor), sb_sangrado=VALUES(sb_sangrado),
                    sb_bruxismo=VALUES(sb_bruxismo), sb_movilidad=VALUES(sb_movilidad),
                    sb_anestesia=VALUES(sb_anestesia), sb_frecuencia_cepillado=VALUES(sb_frecuencia_cepillado),
                    sb_motivo_consulta=VALUES(sb_motivo_consulta)";

        // Ajustar la variable de conexión según corresponda ($pdo o $conexion)
        $pdo_conn = isset($pdo) ? $pdo : $conexion; 
        $stmt = $pdo_conn->prepare($sql);

        // Se envían todos los parámetros ordenados directamente en el execute
        $stmt->execute([
            $paciente_id,
            $sg_tratamiento,
            $sg_tratamiento_txt,
            $sg_medicacion,
            $sg_medicacion_txt,
            $sg_alergia,
            $sg_alergia_txt,
            $sg_cirugia,
            $sg_cirugia_txt,
            $sg_presion,
            $sg_presion_alta,
            $sg_presion_baja,
            $sg_diabetico,
            $sg_cardiaco,
            $sg_epilepsia,
            $sg_asma,
            $sg_hemorragia,
            $sg_fuma,
            $sg_embarazo,
            $sb_dolor,
            $sb_sangrado,
            $sb_bruxismo,
            $sb_movilidad,
            $sb_anestesia,
            $sb_frecuencia_cepillado,
            $sb_motivo_consulta
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Anamnesis guardada correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
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