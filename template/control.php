<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['clave'];

    // Consulta para obtener el usuario y la contraseña de la base de datos
    $sql = "SELECT rpe, clave FROM usuarios WHERE rpe = '$usuario'";
    $resultado = mysqli_query($conecta, $sql);
    $usuario = mysqli_fetch_assoc($resultado);

    if ($usuario) {
        // Verificar si la contraseña almacenada está cifrada o en texto plano
        if (password_verify($password, $usuario['clave'])) {
            // La contraseña está cifrada correctamente, iniciar sesión
            session_start();
            $_SESSION['autentica'] = "SIP";
            $_SESSION['usuarioactual'] = $usuario['rpe'];
            header("Location: index.php");
        } elseif ($password == $usuario['clave']) {
            // La contraseña está en texto plano, cifrarla ahora
            $password_cifrada = password_hash($password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE usuarios SET clave = '$password_cifrada' WHERE rpe = '$usuario[rpe]'";
            mysqli_query($conecta, $sql_update);

            // Iniciar sesión después de actualizar la contraseña
            session_start();
            $_SESSION['autentica'] = "SIP";
            $_SESSION['usuarioactual'] = $usuario['rpe'];
            header("Location: index.php");
        } else {
            echo "<script>alert('La contraseña es incorrecta'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('El usuario no existe'); window.location.href = 'login.php';</script>";
    }

    mysqli_close($conecta);
}
?>