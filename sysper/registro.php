<?php 
require_once("seguridad.php");
include '../conexion.php';
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
     <!-- Estilos de jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Estilos de flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
    <style>
        .textarea {
            width: 100%;
            margin-bottom: 15px;
        }
    </style>
   
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
                        <center>
                            <h2>SOLICITUD DE AUTORIZACION DE TIEMPO EXTRAORDINARIO</h2>
                        </center>
                        <form action="procesar_horas.php" method="POST">
                            <div>
                                <!-- Datos comunes -->
                                <div class="col-md-12 mb-3">
                                    <label>Fecha:</label>
                                    <input type="text" id="fecha" name="fecha" class="form-control" required>
                                </div>
                                <div id="empleados-container">
                                <div class="empleado-item">
                                    <h3>EQUIPO 1</h3>
                                    <button type="button" class="btn btn-danger remove-equipo">Eliminar equipo</button>
                                    <div class="mb-3">
                                        <label for="empleados" class="form-label">Selecciona empleados:</label>
                                        <select class="textarea empleados-select" id="empleados" name="empleados[]" multiple="multiple">
                                            <!-- Las opciones se llenarán dinámicamente -->
                                        </select>
                                    </div>
                                <div class="col-md-12 mb-3">
                                        <label>No. Orden:</label>
                                        <input type="number" name="numero_orden[]" class="form-control" required>
                                    </div>
                                <div class="col-md-12 mb-3">
                                    <label>Actividades realizadas:</label>
                                    <textarea name="actividades" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Justificación técnica:</label>
                                    <textarea name="justificacion" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                                    <div class="col-md-12 mb-3">
                                        <label>OM:</label>
                                        <input type="number" name="om[]" class="form-control" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Hora de inicio:</label>
                                            <input type="text" name="hora_inicio[]" class="form-control hora" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Hora de término:</label>
                                            <input type="text" name="hora_termino[]" class="form-control hora" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">    
                                        <label class="form-label">Vista Previa PDF</label>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <iframe id="viewer" src="sate.php" frameborder="0" scrolling="yes" width="100%" height="600"></iframe>
                                        </div>

                                        <script>
                                            // Función para actualizar el iframe en intervalos regulares
                                            setInterval(function() {
                                                var iframe = document.getElementById('viewer');
                                                iframe.src = iframe.src;  // Recarga el iframe
                                            }, 5000); // Cambia 5000 a cualquier intervalo en milisegundos que desees (5 segundos en este caso)
                                        </script>
                            <button type="button" id="add-empleado">Agregar otro equipo</button>
                            <button type="submit">Guardar</button>
                        </form> 
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script>
                let empleadoIndex = 1;
                let empleadosAsignados = new Set();

                flatpickr("#fecha", {
                    dateFormat: "Y-m-d",
                });

                document.querySelectorAll('.hora').forEach(function(input) {
                    flatpickr(input, {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true
                    });
                });

                document.getElementById('add-empleado').addEventListener('click', function() {
                    empleadoIndex++;
                    const newEmpleado = `
                        <div class="empleado-item">
                            <h3>EQUIPO ${empleadoIndex}</h3>
                            <button type="button" class="btn btn-danger remove-equipo">Eliminar equipo</button>
                            <div class="mb-3">
                                <label for="empleados" class="form-label">Selecciona empleados:</label>
                                <select class="textarea empleados-select" name="empleados[]" multiple="multiple">
                                </select>
                            </div>
                              <div class="col-md-12 mb-3">
                                        <label>No. Orden:</label>
                                        <input type="number" name="numero_orden[]" class="form-control" required>
                                    </div>
                                <div class="col-md-12 mb-3">
                                    <label>Actividades realizadas:</label>
                                    <textarea name="actividades" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Justificación técnica:</label>
                                    <textarea name="justificacion" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                        <label>OM:</label>
                                        <input type="number" name="om[]" class="form-control" required>
                                    </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Hora de inicio:</label>
                                    <input type="text" name="hora_inicio[]" class="form-control hora" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Hora de término:</label>
                                    <input type="text" name="hora_termino[]" class="form-control hora" required>
                                </div>
                            </div
                        </div>
                    `;
                    

                    document.getElementById('empleados-container').insertAdjacentHTML('beforeend', newEmpleado);
                    const lastEmpleadoSelect = document.querySelectorAll('.empleados-select');
                    const newSelect = lastEmpleadoSelect[lastEmpleadoSelect.length - 1];

                    $(newSelect).select2({
                        placeholder: 'Busca empleados por RPE',
                        allowClear: true,
                        ajax: {
                            url: 'conexion.php',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    term: params.term
                                };
                            },
                            processResults: function(data) {
                                const filteredData = data.filter(item => !empleadosAsignados.has(item.id));
                                return { results: filteredData };
                            },
                            cache: true
                        }
                    });

                    $(newSelect).on('change', function() {
                        empleadosAsignados.clear();
                        document.querySelectorAll('.empleados-select').forEach(select => {
                            $(select).val().forEach(id => empleadosAsignados.add(id));
                        });
                        updateEmployeeOptions();
                    });

                    document.querySelectorAll('.hora').forEach(function(input) {
                        flatpickr(input, {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: "H:i",
                            time_24hr: true
                        });
                    });
                });

                document.getElementById('empleados-container').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-equipo')) {
                        e.target.closest('.empleado-item').remove();
                        empleadosAsignados.clear();
                        document.querySelectorAll('.empleados-select').forEach(select => {
                            $(select).val().forEach(id => empleadosAsignados.add(id));
                        });
                        updateEmployeeOptions();
                    }
                });

                function updateEmployeeOptions() {
                    document.querySelectorAll('.empleados-select').forEach(select => {
                        const selectedEmployees = $(select).val();
                        $(select).select2({
                            placeholder: 'Busca empleados por RPE',
                            allowClear: true,
                            ajax: {
                                url: 'conexion.php',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term
                                    };
                                },
                                processResults: function(data) {
                                    const filteredData = data.filter(item => 
                                        !empleadosAsignados.has(item.id) || selectedEmployees.includes(item.id)
                                    );
                                    return { results: filteredData };
                                },
                                cache: true
                            }
                        });
                    });
                }
            </script>
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
     <!-- Select2 -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Ocultar el spinner cuando el contenido está listo
            $("#spinner").fadeOut("slow");
            
            $('#empleados').select2({
                placeholder: 'Busca empleados por RPE',
                allowClear: true,
                ajax: {
                    url: 'conexion.php',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        });

        function fetchData() {
            var rpe = document.getElementById('rpe').value;

            if (rpe.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'buscar_id.php?rpe=' + rpe, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        var empleados = JSON.parse(this.responseText);
                        var options = '';
                        for (var i = 0; i < empleados.length; i++) {
                            options += '<option value="' + empleados[i].id + '">' + empleados[i].nombre + '</option>';
                        }
                        document.getElementById('empleados').innerHTML = options;
                    }
                };
                xhr.send();
            }
        }
    </script>


</body>
</html>