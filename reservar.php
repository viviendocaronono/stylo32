<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include('php/db.php');

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

if ($fecha) {
    $query = "SELECT id FROM fechas WHERE fecha = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $fecha);
    $stmt->execute();
    $stmt->bind_result($fecha_id);
    $stmt->fetch();
    $stmt->close();

    if ($fecha_id) {
        echo "<h1>Reserva para la fecha: $fecha</h1>";

        echo "<form id='form-horario' method='POST' action='php/guardar_reserva.php'>";
        echo "<input type='hidden' name='fecha_id' value='$fecha_id'>";
        echo "<input type='hidden' name='horario_id' id='horario_id'>";
        echo "<label for='hora'>Selecciona un horario:</label>";
        echo "<select name='hora' id='hora'>";
        for ($i = 7; $i <= 17; $i++) {
            $hora = sprintf('%02d:00', $i);
            echo "<option value='$hora'>$hora</option>";
        }
        echo "</select>";
        echo "<div id='disponibilidad'></div>";
        echo "<button type='submit' id='btn-enviar'>Reservar</button>";
        echo "</form>";

        echo "
        <script>
        function verificarDisponibilidad(hora, fecha_id) {
            fetch('php/verificar_horario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'hora=' + encodeURIComponent(hora) + '&fecha_id=' + fecha_id
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('disponibilidad').innerText = data.mensaje;
                document.getElementById('btn-enviar').disabled = !data.disponible;
                if (data.disponible) {
                    document.getElementById('horario_id').value = data.horario_id;
                } else {
                    document.getElementById('horario_id').value = '';
                }
            });
        }

        const fecha_id = $fecha_id;

        document.getElementById('hora').addEventListener('change', function() {
            const hora = this.value;
            verificarDisponibilidad(hora, fecha_id);
        });

        window.addEventListener('DOMContentLoaded', function () {
            const horaInicial = document.getElementById('hora').value;
            verificarDisponibilidad(horaInicial, fecha_id);
        });

        document.getElementById('form-horario').addEventListener('submit', function(e) {
            const horarioId = document.getElementById('horario_id').value;
            if (!horarioId) {
                e.preventDefault();
                alert('Por favor, selecciona un horario válido disponible.');
            }
        });
        </script>";
    } else {
        echo "<h1>No se encontró la fecha en la base de datos.</h1>";
    }
} else {
    echo "<h1>No se ha seleccionado ninguna fecha.</h1>";
}
?>
</body>
</html>
