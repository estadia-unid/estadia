<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['clave'];

    // Consulta para obtener el usuario y la contraseña de la base de datos
    $sql = "SELECT rpe, clave FROM usuarios WHERE rpe = ?";
    $stmt = mysqli_prepare($conecta, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario_data = mysqli_fetch_assoc($resultado);

    if ($usuario_data) {
        // Verificar si la contraseña almacenada está cifrada o en texto plano
        if (password_verify($password, $usuario_data['clave'])) {
            // La contraseña está cifrada correctamente, iniciar sesión
            session_start();
            $_SESSION['autentica'] = "SIP";
            $_SESSION['usuarioactual'] = $usuario_data['rpe'];

            // Consulta a la tabla empleados
            $sql_empleado = "SELECT nombre, categ FROM empleados WHERE rpe = ?";
            $stmt_empleado = mysqli_prepare($conecta, $sql_empleado);
            mysqli_stmt_bind_param($stmt_empleado, "s", $usuario_data['rpe']);
            mysqli_stmt_execute($stmt_empleado);
            $resultado_empleado = mysqli_stmt_get_result($stmt_empleado);
            $empleado = mysqli_fetch_assoc($resultado_empleado);

            // Almacenar datos en sesión
            $_SESSION['nombre_empleado'] = $empleado['nombre'];
            $_SESSION['categoria'] = $empleado['categ'];

            header("Location: index.php");
        } else {
            // Manejar error de contraseña incorrecta
            echo "<script>alert('La contraseña es incorrecta'); window.location.href = 'login.php';</script>";
        }
    } else {
        // Manejar error de usuario no encontrado
        echo "<script>alert('El usuario no existe'); window.location.href = 'login.php';</script>";
    }

    mysqli_close($conecta);
}
?>
