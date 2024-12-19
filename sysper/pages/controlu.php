<?php
// Iniciar sesión.
session_start();

// Conexión a la base de datos.
$conexion = new mysqli('localhost', 'skyper', 'ctpalm2113', 'estadiaunid');

// Verificar conexión.
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener datos del formulario.
$rpe = $_POST['rpe'];
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT); // Encriptar la contraseña

// Insertar en la base de datos.
$sql = "INSERT INTO usuarios (rpe, clave, estado, login, privilegio, fecha_creacion) 
        VALUES ('$rpe', '$clave', '1', '1', '1', NOW())";


if ($conexion->query($sql) === TRUE) {
    // Guardar datos en sesión para simular que inició sesión.
    $_SESSION['rpe'] = $rpe;
    $_SESSION['login'] = true;
    $_SESSION['privilegio'] = '1'; // Asume que este es el rol del usuario.
    
    // Redirigir a la página principal.
    header('Location: index.php');
    exit; // Terminar el script después de redirigir.
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>
