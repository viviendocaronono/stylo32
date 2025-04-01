<?php
include('db.php');

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

$sql = "SELECT * FROM fechas WHERE fecha = '$fecha'";
$result = $conn->query($sql);

$response = array("disponible" => false);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['disponible'] == 1) {
        $response['disponible'] = true;
    }
} else {
    $insertSql = "INSERT INTO fechas (fecha, disponible, max_personas) VALUES ('$fecha', 1, 20)";
    if ($conn->query($insertSql) === TRUE) {
        $response['disponible'] = true;
    } else {
        $response['error'] = "Error al crear la fecha: " . $conn->error;
    }
}

echo json_encode($response);
?>
