<?php
require "../config/cors.php";
require '../vendor/autoload.php';
require '../config/database.php';

try {
    // Leer la entrada JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar entrada
    if (isset($input['role']) && isset($input['email'])) {
        $role = $input['role'];
        $email = $input['email'];

        // Comprobar si el correo electrónico existe
        $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $checkStmt = $pdo->prepare($checkEmailSql);
        $checkStmt->execute([$email]);
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
     

            // Preparar la consulta SQL para actualizar la contraseña
            $sql = "UPDATE users SET role = ? WHERE email = ?";
            $stmt = $pdo->prepare($sql);

            // Ejecutar la consulta
            if ($stmt->execute([$role, $email])) { // Cambiar $role a $hashedrole si hasheas
                header('Content-Type: application/json; charset=utf-8'); 
                echo json_encode([
                    'message' => "El rol ha sido actualizado exitosamente a $role",
                    'email' => $email
                ]);
            } else {
                header('Content-Type: application/json; charset=utf-8'); 
                echo json_encode(['message' => 'Error al actualizar el rol del usuario']);
            }
        } else {
            // El correo electrónico no existe en la base de datos
            header('Content-Type: application/json; charset=utf-8'); 
            echo json_encode(['message' => 'El correo electronico no existe',
            'email' => $email]);
        }
    } else {
        header('Content-Type: application/json; charset=utf-8'); 
        echo json_encode(['message' => 'Datos incompletos']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8'); 
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}
?>