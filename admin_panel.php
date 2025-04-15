<?php
session_start();
include('php/db.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$query = $conn->prepare("SELECT es_admin FROM usuarios WHERE id = ?");
$query->bind_param("i", $usuario_id);
$query->execute();
$query->bind_result($es_admin);
$query->fetch();
$query->close();

if (!$es_admin) {
    echo "<h1> Acceso denegado. No eres administrador.</h1>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_disponibilidad'])) {
        $fecha_id = $_POST['fecha_id'];
        $estado = $_POST['estado'] == 1 ? 0 : 1;
        $conn->query("UPDATE fechas SET disponible = $estado WHERE id = $fecha_id");
    }

    if (isset($_POST['eliminar_fecha'])) {
        $fecha_id = $_POST['fecha_id'];

        $conn->query("DELETE uh FROM usuario_horarios uh 
                      JOIN horarios h ON uh.horario_id = h.id 
                      WHERE h.fecha_id = $fecha_id");

        $conn->query("DELETE FROM horarios WHERE fecha_id = $fecha_id");
        $conn->query("DELETE FROM fechas WHERE id = $fecha_id");
    }

    if (isset($_POST['crear_admin'])) {
        $nombre_usuario = $_POST['nuevo_usuario'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, password, es_admin) VALUES (?, ?, 1)");
        $stmt->bind_param("ss", $nombre_usuario, $password);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['eliminar_usuario'])) {
        $nombre_usuario = $_POST['usuario_eliminar'];
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $stmt->close();
    }
}

$fechas = $conn->query("SELECT * FROM fechas ORDER BY fecha ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #aaa; padding: 10px; text-align: left; }
        th { background-color: #ddd; }
        form.inline { display: inline; margin: 0; }
    </style>
</head>
<body>
<h1>Panel de Administración</h1>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Disponibilidad</th>
            <th>Horarios</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $fechas->fetch_assoc()): ?>
        <tr>
            <td><?= $row['fecha'] ?></td>
            <td><?= $row['disponible'] ? 'Disponible' : 'No disponible' ?></td>
            <td>
                <?php
                $fecha_id = $row['id'];
                $horarios = $conn->query("SELECT hora FROM horarios WHERE fecha_id = $fecha_id ORDER BY hora ASC");
                while ($h = $horarios->fetch_assoc()) {
                    echo $h['hora'] . "<br>";
                }
                ?>
            </td>
            <td>
                <form method="POST" class="inline">
                    <input type="hidden" name="fecha_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="estado" value="<?= $row['disponible'] ?>">
                    <button type="submit" name="toggle_disponibilidad">
                        <?= $row['disponible'] ? '❌ Desactivar' : '✅ Activar' ?>
                    </button>
                </form>

                <form method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta fecha y todos sus datos asociados?');">
                    <input type="hidden" name="fecha_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="eliminar_fecha">🗑️ Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<hr>

<h2>👤 Crear Usuario Administrador</h2>
<form method="POST">
    <input type="text" name="nuevo_usuario" placeholder="Nombre de usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit" name="crear_admin">Crear</button>
</form>

<h2>❌ Eliminar Usuario</h2>
<form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
    <input type="text" name="usuario_eliminar" placeholder="Nombre de usuario" required>
    <button type="submit" name="eliminar_usuario">Eliminar</button>
</form>

</body>
</html>
