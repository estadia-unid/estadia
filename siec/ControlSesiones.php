<?php
include_once "conexion.php";

class ControlSesiones{
    public $usuario;
    public $contraseña;

    function __construct($usuario, $contraseña) {
        $this->usuario = $usuario;
        $this->contraseña = $contraseña; 
    }

    function iniciar_sesion($conecta) {
        $sesion = "SELECT * FROM `usuarios` WHERE `rpe` = :usuario";
        
        $resultado_sesion = $conecta->prepare($sesion);
        $resultado_sesion->execute([':usuario' => $this->usuario]);
        $hash = $resultado_sesion->fetch(PDO::FETCH_ASSOC);
        $empleado = "SELECT * FROM `empleados` WHERE `rpe` = :usuario";
        $resultado_empleado = $conecta->prepare($empleado);
        $resultado_empleado->execute([':usuario' => $this->usuario]);
        $info_empleado = $resultado_empleado->fetch(PDO::FETCH_ASSOC);
        if ($hash !== false) {
            if (password_verify($this->contraseña, $hash['clave'])) {
                $_SESSION['nombre'] = $info_empleado['nombre'] . '  ';
                $_SESSION['apellidos'] = $info_empleado['a_paterno'] . '  '. $info_empleado['a_materno'];
                $_SESSION['rpe'] = $hash['rpe'];
                header("Location: principal.php");
                die();
            } else {
                $_SESSION['mensaje'] = 'Contraseña inválida.';
            }
        } else {
            $_SESSION['mensaje'] = 'Usuario no encontrado.';
        }
    }    

    function cerrar_sesion() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        die();
    }

    function agregar_usuario($conecta){
            $contraseñaCifrada = password_hash($this->contraseña, PASSWORD_BCRYPT);
            $sql="INSERT INTO `usuarios` (`rpe`, `clave`) VALUES (:rpe,:clave)";
            
            $sql = $conecta->prepare($sql);
            
            $sql->bindParam(':rpe',$this->usuario);
            $sql->bindParam(':clave',$contraseñaCifrada);
            
            $sql->execute();
            
    }
}
if(isset($_GET['cerrarSesion'])){
    $controlSesion = new ControlSesiones(null, null);
    $controlSesion->cerrar_sesion();
}

// https://www.php.net/manual/es/function.password-verify.php
// https://www.php.net/manual/es/function.password-hash.php
?>
