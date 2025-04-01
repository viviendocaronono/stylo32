<?php
include('db.php');

$username = isset($_GET['username']) ? $_GET['username'] : '';

$sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$response = array("exists" => false);
if ($result->num_rows > 0) {
    $response['exists'] = true;
}

echo json_encode($response);
?>
