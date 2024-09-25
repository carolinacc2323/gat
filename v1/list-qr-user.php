<?php
require "../config/cors.php";
require '../config/database.php';
require '../vendor/autoload.php';

// Obtener el ID del usuario desde la solicitud
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id'])) {
    $userId = $input['id'];

    // Consulta SQL para obtener los códigos QR creados por el usuario
    $sql = "SELECT
                qr_codes.id AS qr_id,
                qr_codes.data AS qr_data,
                qr_codes.nombre_ref AS qr_nombre_ref,
                qr_codes.description AS qr_description,
                qr_codes.created_at AS qr_created_at,
                users.id AS user_id,
                users.nombre AS user_nombre,
                users.delegacion AS user_delegacion,
                users.email AS user_email,
                users.role AS user_role
            FROM qr_codes
            JOIN users ON qr_codes.created_by = users.id
            WHERE users.id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $qrCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($qrCodes) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['qr_codes' => $qrCodes]);
    } else {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(404);
        echo json_encode(['message' => 'No se encontraron códigos QR para este usuario']);
    }
} else {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['message' => 'ID de usuario no proporcionado']);
}
?>