<?php
session_start(); // Habilita uso de sesiones
include('db.php');

// Comprobar si hay sesión activa
if (!isset($_SESSION['user_id'])) {
    echo "<h1> No hay sesión iniciada.</h1>";
    echo "<p>Debes iniciar sesión para reservar un horario.</p>";
    exit;
}

$usuario_id = $_SESSION['user_id'];

// Mostrar datos recibidos
echo "<h2>Datos recibidos por POST:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

$horario_id = $_POST['horario_id'] ?? '';
$fecha_id = $_POST['fecha_id'] ?? '';
$hora = $_POST['hora'] ?? '';

if ($horario_id && $usuario_id) {
    // Verificar si el horario ya fue reservado
    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario_horarios WHERE horario_id = ?");
    $stmt->bind_param("i", $horario_id);
    $stmt->execute();
    $stmt->bind_result($ocupado);
    $stmt->fetch();
    $stmt->close();

    if ($ocupado == 0) {
        $insert = $conn->prepare("INSERT INTO usuario_horarios (usuario_id, horario_id) VALUES (?, ?)");
        $insert->bind_param("ii", $usuario_id, $horario_id);
        if ($insert->execute()) {
            echo "<h1>✅ Reserva realizada con éxito</h1>";
            echo "<ul>
                    <li><strong>Usuario ID:</strong> $usuario_id</li>
                    <li><strong>Horario ID:</strong> $horario_id</li>
                    <li><strong>Hora seleccionada:</strong> $hora</li>
                    <li><strong>Fecha ID:</strong> $fecha_id</li>
                  </ul>";
        } else {
            echo "<h1> Error al guardar la reserva.</h1>";
        }
        $insert->close();
    } else {
        echo "<h1> Este horario ya ha sido reservado por otro usuario.</h1>";
    }
} else {
    echo "<h1> Datos inválidos. No se pudo realizar la reserva.</h1>";
}
?>
