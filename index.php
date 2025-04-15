<?php
include('php/db.php'); 
?>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login_register.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Fecha</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function checkDisponibilidad() {
            const fecha = document.getElementById("fecha").value;
            
            fetch(`php/check_fecha.php?fecha=${fecha}`)
                .then(response => response.json())
                .then(data => {
                    if (data.disponible) {
                        document.getElementById("reservarBtn").disabled = false;
                        document.getElementById("estado").innerText = "Fecha disponible";
                    } else {
                        document.getElementById("reservarBtn").disabled = true;
                        document.getElementById("estado").innerText = "Fecha no disponible";
                    }
                });
        }
    </script>
</head>
<body>

<h1>Formulario de Reserva</h1>

<form>
    <label for="fecha">Elige una fecha:</label>
    <input type="date" id="fecha" name="fecha" onchange="checkDisponibilidad()">
</form>

<p id="estado"></p>

<button id="reservarBtn" disabled onclick="window.location.href = 'reservar.php?fecha=' + document.getElementById('fecha').value;">
    Reservar Fecha
</button>

</body>

<form method="POST" action="php/logout.php">
    <button type="submit" name="logout">Cerrar Sesión</button>
</form>

<br>

<a href="admin_panel.php">Admin</a>

</html>
        