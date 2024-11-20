<?php 
require_once("seguridad.php"); 
require_once("conexion.php"); 
include 'conexion.php';

if (isset($_POST['action']) && $_POST['action'] == 'fetchData') {
    $rpe = $conecta->real_escape_string($_POST['rpe']);

    $query = "SELECT nombre, a_paterno, a_materno, categ FROM empleados WHERE rpe = '$rpe'";
    $result = $conecta->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre_completo = $row['nombre'] . ' ' . $row['a_paterno'] . ' ' . $row['a_materno'];
        $response = array(
            'success' => true,
            'nombre' => $nombre_completo,
            'categ' => $row['categ']
        );
    } else {
        $response = array('success' => false);
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SYSPER</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
     <!-- Incluí algunas referencias CSS necesarias -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Favicon -->
    <link href="../favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <script>
        // Función para realizar la consulta AJAX
        function fetchData() {
            var rpe = document.getElementById('rpe').value;

            if (rpe.length > 0) { // Verifica si el campo tiene algo escrito
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "registro.php", true); // Asegúrate de poner el archivo PHP correcto
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if(response.success) {
                            document.getElementById('nombre').value = response.nombre;
                            document.getElementById('categoria').value = response.categ;
                        } else {
                            document.getElementById('nombre').value = '';
                            document.getElementById('categoria').value = '';
                        }
                    }
                };
                xhr.send("rpe=" + rpe + "&action=fetchData");
            }
        }
    </script>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>SYSPER</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="../imagen.php" alt="rpeimg" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $_SESSION['nombre_completo']; ?></h6> <!-- Nombre del usuario -->
                        <span><?php echo $_SESSION['categoria']; ?></span> <!-- Categoría del usuario -->
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Inicio</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Herramientas</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="registro.php" class="dropdown-item">Registro</a>
                            <a href="actualizareg.php" class="dropdown-item">Actualizar</a>
                        </div>
                    </div>
                    <a href="buscar_id.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Busqueda</a>
                    <a href="table.php" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Tablas</a>
                    <a href="imprimir.php" class="nav-item nav-link"><i class="far fa-file-alt me-2"></i>Imprimir</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form id="searchForm" class="d-none d-md-flex ms-4" action="buscar_id.php" method="get">
                    <input id="searchInput" class="form-control bg-dark border-0" type="search" name="sendfolio" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="../imagen.php" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex"><?php echo $_SESSION['nombre']; ?></span> <!-- Nombre del usuario -->
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">.</a>
                            <a href="#" class="dropdown-item">.</a>
                            <a href="logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->
<!-- Form Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row vh-40 bg-secondary rounded align-items-center justify-content-center mx-2">
        <div class="col-12">
            <center><h2>REPORTE DE TIEMPO EXTRA</h2></center>
            <form class="row g-3 bg-light-gray p-4" action="registro.php" method="POST" autocomplete="off">
            <div class="col-md-2">
                        <label class="form-label">RPE</label>
                        <input type="text" class="form-control" id="rpe" onkeyup="fetchData()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <input type="text" class="form-control" id="categoria" disabled>
                    </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha</label>
                    <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="fecha_display" disabled>
                    <input type="hidden" value="<?php echo date("Y-m-d"); ?>" name="fecha">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hora</label>
                    <input type="time" value="<?php echo date("H:i"); ?>" class="form-control" name="hora_display" disabled>
                    <input type="hidden" value="<?php echo date("H:i"); ?>" name="hora">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hora de entrada</label>
                    <input type="text" class="form-control" name="entrada" placeholder="Ingrese el grupo editorial">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hora de salida</label>
                    <input type="url" class="form-control" name="salida">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Horas extra</label>
                    <input type="url" class="form-control" name="extra">
                </div>
                  <div class="col-md-4">
                    <label class="form-label">O.M.</label>
                    <select class="form-select" name="pais">
                        <option selected>Choose...</option>
                        <option>Argentina</option>
                        <option>Brasil</option>
                        <option>Chile</option>
                        <option>Colombia</option>
                        <option>España</option>
                        <option>Estados Unidos</option>
                        <option>Francia</option>
                        <option>Italia</option>
                        <option>México</option>
                        <option>Perú</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stock</label>
                    <select class="form-select" name="idioma">
                        <option selected>Choose...</option>
                        <option>1-5</option>
                        <option>5-10</option>
                        <option>10-15</option>
                        <option>15-20</option>
                        <option>20-25</option>
                        <option>25-30/option>
                        <option>30-35</option>
                        <option>35-40</option>
                        <option>40-45</option>
                        <option>45-50</option>
                    </select>
                </div>
               <div class="col-md-4">
    <label class="form-label">Categoría</label>
    <select class="form-select" name="categoria">
    </select>
</div>
            <div class="col-md-12">
               <label class="form-label">Justificacion:</label>
                 <textarea name="just" class="form-control letra"  rows="3" required></textarea>
                 </div>

                <div class="col-12">
                    <button type="submit" name="guardar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Form End -->



            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Cybermed</a>, All Right Reserved. 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!-- This template is free to use for your project. -->
                            Designed By <a href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>
</html>