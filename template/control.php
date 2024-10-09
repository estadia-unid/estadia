<?php
require_once("conexion.php");  // Conectar a la base de datos

// Recoger los datos del formulario
$miusuario = $_POST['usuario'];
$password = $_POST['clave'];

// Proteger contra inyección SQL y XSS
$miuser = mysqli_real_escape_string($conecta, htmlentities($miusuario));
$miclave = htmlentities($password);

// Consulta para verificar si el usuario existe y está activo
$sql = "SELECT * FROM usuarios WHERE rpe = '$miuser' AND estado = 1";
$result = mysqli_query($conecta, $sql);

if (mysqli_num_rows($result) > 0) {
    // Extraer el usuario de la base de datos
    $usuario = mysqli_fetch_assoc($result);

    // Verificar la contraseña usando password_verify
    if (password_verify($miclave, $usuario['clave'])) {
        // Iniciar sesión
        session_start();
        $_SESSION['autentica'] = "SIP";
        $_SESSION['usuarioactual'] = $miuser;

        // Actualizar el estado de login en la base de datos
        $ensesion = "UPDATE usuarios SET login = 1 WHERE rpe = '$miuser'";
        mysqli_query($conecta, $ensesion);

        // Redirigir al usuario a la página principal
        header("Location: index.php");
    } else {
        // Si la contraseña es incorrecta
        echo "<script>alert('La contraseña del usuario no es correcta');
              window.location.href = 'login.php';</script>";
    }
} else {
    // Si el usuario no existe o no está activo
    echo "<script>alert('El usuario no existe o no está activo');
          window.location.href = 'login.php';</script>";
}

// Cerrar la conexión
mysqli_close($conecta);
?>