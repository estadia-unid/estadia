<?php
    session_start();
    $rpe = $_POST['rpe'];
     $contra = $_POST['contraseña'];
        if($rpe!=0){
            $_SESSION['rpe'] = $rpe;
            header("Location: principal.php");
            exit();
    }
?>