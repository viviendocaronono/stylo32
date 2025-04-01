<?php
include('php/db.php');

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

if ($fecha) {
    // SEGUIR ACA AKSJDNLOKADSLSKAD
    
    echo "<h1>Reserva Confirmada para la fecha: $fecha</h1>";
} else {
    echo "<h1>No se ha seleccionado ninguna fecha.</h1>";
}
?>
