<?php
require_once("seguridad.php");
require_once("conexion.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CyberMed</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

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
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>Cybermed</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/jozet.JPEG" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Jozet Ramirez</h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Inicio</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Herramientas</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="registro.php" class="dropdown-item">Registro</a>
                            <a href="actualizareg.php" class="dropdown-item">Actualizar</a>
                        </div>
                    </div>
                    <a href="buscar_id.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Busqueda</a>
                    <a href="table.php" class="nav-item nav-link active"><i class="fa fa-table me-2"></i>Tablas</a>
                    <a href="imprimir.php" class="nav-item nav-link">
                        <i class="far fa-file-alt me-2"></i>Imprimir
                    </a>
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
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/jozet.JPEG" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">Jozet Ramirez</span>
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

            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="col-12">
                    <div class="bg-secondary rounded h-100 p-4">
                        <h6 class="mb-4">Todos los Cibermedios</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Cibermedio</th>
                                        <th scope="col">Grupo Editorial</th>
                                        <th scope="col">URL</th>
                                        <th scope="col">País</th>
                                        <th scope="col">Idioma</th>
                                        <th scope="col">Categoría</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $consulta = mysqli_query($conecta, "SELECT * FROM cibermedios");
                                    while ($dato = mysqli_fetch_array($consulta)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($dato['folio']) . "</td>";
                                        echo "<td>" . htmlspecialchars($dato['user']) . "</td>";
                                        echo "<td>" . htmlspecialchars($dato['cibermedio']) . "</td>";
                                        echo "<td>" . htmlspecialchars($dato['gpo_edit']) . "</td>";
                                        echo "<td>" . htmlspecialchars($dato['url']) . "</td>";
                                        echo "<td>" . htmlspecialchars($dato['pais']) . "</td>";
                                        echo "<td>" . htmlspecialchars($dato['idioma']) . "</td>";
                                        echo "<td>" . htmlspecialchars($dato['categorizacion']) . "</td>";
                                        echo "</tr>";
                                    }
                                    mysqli_close($conecta);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table End -->

        </div>
        <!-- Content End -->
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="btn btn-primary btn-lg rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template JavaScript -->
    <script src="js/main.js"></script>
</body>

</html>
