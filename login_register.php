<?php
session_start();
include('php/db.php'); 

if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $real_name = $_POST['real_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($real_name) || empty($password) || empty($confirm_password)) {
        $error_message = "Todos los campos son obligatorios.";
    } elseif (strlen($password) < 8) {
        $error_message = "La contraseña debe tener al menos 8 caracteres.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } else {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "El nombre de usuario ya está en uso.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nombre_usuario, contrasena, nombre_real) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashed_password, $real_name);
            if ($stmt->execute()) {
                $success_message = "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $error_message = "Hubo un error al registrar al usuario.";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Por favor ingrese todos los campos.";
    } else {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['contrasena'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['nombre_usuario'];
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "Nombre de usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión / Registro</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        // Validar campos antes de habilitar el botón de registro
        function validateRegisterForm() {
            const username = document.getElementById("registerUsername").value;
            const realName = document.getElementById("registerRealName").value;
            const password = document.getElementById("registerPassword").value;
            const confirmPassword = document.getElementById("registerConfirmPassword").value;
            const registerBtn = document.getElementById("registerBtn");

            if (username && realName && password.length >= 8 && password === confirmPassword) {
                registerBtn.disabled = false;
            } else {
                registerBtn.disabled = true;
            }
        }

        function checkUsernameAvailability() {
            const username = document.getElementById("registerUsername").value;
            fetch(`php/check_username.php?username=${username}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        document.getElementById("usernameStatus").innerText = "El nombre de usuario ya está en uso.";
                    } else {
                        document.getElementById("usernameStatus").innerText = "El nombre de usuario está disponible.";
                    }
                });
        }
    </script>
</head>
<body>

<h1>Iniciar Sesión o Registrarse</h1>

<h2>Iniciar Sesión</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Nombre de usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit" name="login">Iniciar Sesión</button>
</form>

<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php elseif (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<hr>

<h2>Registrarse</h2>
<form method="POST" oninput="validateRegisterForm()">
    <input type="text" id="registerUsername" name="username" placeholder="Nombre de usuario" required onblur="checkUsernameAvailability()">
    <p id="usernameStatus"></p>

    <input type="text" id="registerRealName" name="real_name" placeholder="Nombre real" required>
    <input type="password" id="registerPassword" name="password" placeholder="Contraseña" required>
    <input type="password" id="registerConfirmPassword" name="confirm_password" placeholder="Confirmar contraseña" required>

    <button type="submit" id="registerBtn" name="register" disabled>Registrar</button>
</form>

</body>
</html>
