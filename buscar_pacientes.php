<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Sesión expirada']);
    exit;
}

require_once 'conexion.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT id, nombre, apellido, dni, telefono 
            FROM pacientes 
            WHERE nombre LIKE :q1 
               OR apellido LIKE :q2 
               OR dni LIKE :q3 
               OR CONCAT(nombre, ' ', apellido) LIKE :q4
            ORDER BY apellido ASC, nombre ASC 
            LIMIT 10";

    $stmt = $conexion->prepare($sql);
    
    $param = "%{$query}%";
    $stmt->bindValue(':q1', $param);
    $stmt->bindValue(':q2', $param);
    $stmt->bindValue(':q3', $param);
    $stmt->bindValue(':q4', $param);
    
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($pacientes);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Error SQL: ' . $e->getMessage()
    ]);
}