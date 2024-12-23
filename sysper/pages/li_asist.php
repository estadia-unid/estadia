<?php
require_once 'seguridad.php';

// Configurar la zona horaria
date_default_timezone_set('America/Mexico_City');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "skyper", "ctpalm2113", "estadiaunid");

// Verifica si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener la fecha actual
$fecha_hoy = date("Y-m-d");

// Consulta para contar el número de empleados
$consulta_empleados = "SELECT COUNT(*) AS total_empleados FROM empleados";
$resultado_empleados = $conexion->query($consulta_empleados);
if (!$resultado_empleados) {
    die("Error en la consulta de empleados: " . $conexion->error);
}

$total_empleados = 0;
if ($resultado_empleados && $fila = $resultado_empleados->fetch_assoc()) {
    $total_empleados = $fila['total_empleados'];
}

// Consulta para contar los registros realizados hoy
$consulta_registros_hoy = "SELECT COUNT(*) AS nuevos_registros FROM registros WHERE DATE(fecha_registro) = '$fecha_hoy'";
$resultado_registros_hoy = $conexion->query($consulta_registros_hoy);
if (!$resultado_registros_hoy) {
    die("Error en la consulta de registros: " . $conexion->error);
}

$nuevos_registros = 0;
if ($resultado_registros_hoy && $fila = $resultado_registros_hoy->fetch_assoc()) {
    $nuevos_registros = $fila['nuevos_registros'];
}

// Nueva consulta para obtener los detalles, incluyendo departamentos
$consulta_detalle = "
    SELECT 
        ea.rpe,
        e.categ,
        e.nombre,
        e.a_paterno,
        e.a_materno,
        r.id AS id_registro,
        r.fecha_registro,
        r.hora_inicio,
        r.hora_termino,
        r.horas_extra,
        r.actividades,
        don.numero_orden,
        r.justificacion,
        d.departamento
    FROM empleados_asignados ea
    INNER JOIN empleados e ON ea.rpe = e.rpe
    INNER JOIN registros r ON ea.id_registro = r.id
    INNER JOIN detalles_orden don ON r.id = don.id_registro
    INNER JOIN departamentos d ON don.id_departamento = d.id_departamento
    WHERE ea.rpe IS NOT NULL
    ORDER BY d.departamento, r.id;
";

$datos = $conexion->query($consulta_detalle);
if (!$datos) {
    die("Error en la consulta de detalles: " . $conexion->error);
}
$datos = $datos->fetch_all(MYSQLI_ASSOC);

// Agrupar los registros por departamento e id_registro
$registros_por_departamento = [];
foreach ($datos as $registro) {
    $departamento = $registro['departamento'];
    $id_registro = $registro['id_registro'];
    $registros_por_departamento[$departamento][$id_registro][] = $registro;
}

// Obtener la fecha actual en formato "día de mes de año"
setlocale(LC_TIME, 'es_ES.UTF-8');
$fecha_hoy_formateada = date("d") . " de " . strftime("%B") . " de " . date("Y");

// Cerrar la conexión
$conexion->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>LISTA DE ASISTENCIA</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <!-- Flatpickr CSS -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

      <!-- Flatpickr JS -->
      <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // JavaScript para mostrar el reloj en tiempo real
        function actualizarReloj() {
            const fecha = new Date();
            const horas = fecha.getHours().toString().padStart(2, '0');
            const minutos = fecha.getMinutes().toString().padStart(2, '0');
            const segundos = fecha.getSeconds().toString().padStart(2, '0');
            document.getElementById('reloj').textContent = `${horas}:${minutos}:${segundos}`;
        }
        // Actualizar el reloj cada segundo
        setInterval(actualizarReloj, 1000);
    </script>
      
    <!-- Inicialización de Flatpickr -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr(".fecha-picker", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                locale: "es",
                allowInput: true
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
          
    <style>
       #departamento {
        max-height: 200px; /* Altura máxima del desplegable */
        overflow-y: auto; /* Activar desplazamiento vertical */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    .btn-generar {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 10px 0;
    }
    .btn-generar:hover {
        background-color: #45a049;
    }
</style>
  <style> 
      .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background: white;
        }
        .text-primary { color: #5e72e4; }
        .text-warning { color: #fb6340; }
        .text-info { color: #11cdef; }
        .text-success { color: #2dce89; }
        .text-muted { color: #8898aa; }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="../pages/dashboard.html" target="_blank">
                <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">Creative Tim</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
            <li class="nav-item">
          <a class="nav-link" href="index.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">INICIO</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="registro.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">REGISTRO</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="editar.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">EDITAR</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="tablas.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">TABLAS</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="li_asist.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">LISTA DE ASISTENCIA</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="logout.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-collection text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Log-Out</span>
          </a>
        </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Imprimir SATE</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Imprimir SATE</h6>
                </nav>
                <div class=" collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Type here...">
                        </div>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"></span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">FECHA DE HOY</p>
                                        <h5 class="font-weight-bolder">
                                            <?php echo $fecha_hoy_formateada; ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">EMPLEADOS</p>
                                        <h5 class="font-weight-bolder">
                                            Total de empleados: <?php echo $total_empleados; ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Nuevos Registros</p>
                                        <h5 class="font-weight-bolder">
                                            Nuevos registros hoy: <?php echo $nuevos_registros; ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                        <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Reloj</p>
                                        <h5 class="font-weight-bolder" id="reloj">
                                            <!-- La hora se actualizará aquí en tiempo real -->
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                        <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-body p-3">
                        <div class="card-body p-2">
                            <center>
                                <h3 class="text-capitalize">LISTA DE ASISTENCIA</h3>
                            </center>
                            <form id="registroForm">
                                <!-- Fecha de Actividad -->
                                <div class="mb-2">
                                    <label for="fecha" class="form-label">Fecha:</label>
                                    <input type="date" class="form-control form-control-sm fecha-picker" id="fecha_act" name="fecha_act" required>
                                </div>
                                <!-- Seleccionar Departamento -->
                                <div class="mb-3">
                                    <label for="departamento">Seleccionar Departamento</label>
                                    <select id="departamento" name="departamento" class="form-select" required>
                                        <option value="">Seleccione un departamento</option>
                                        <!-- Opciones cargadas dinámicamente -->
                                    </select>
                                </div>
                                <!-- Botón de Enviar -->
                                <button type="submit" class="btn btn-primary btn-sm">Consultar Asistencia</button>
                            </form>
                            <!-- Contenedor para los resultados -->
                            <div id="tabla-resultados" class="mt-4"></div>
                        </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>

      
            </div>
          </div>
        </div>
      </div>
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Personaliza la pagina</h5>
          <p>Mira todas las opciones que tenemos</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0 overflow-auto">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Color de la barra lateral</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Tipo de la barra lateral</h6>
          <p class="text-sm">Escoge a tu gusto, puede ser clara u oscura.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white" onclick="sidebarType(this)">Claro</button>
          <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default" onclick="sidebarType(this)">Oscuro</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">Puede cambiar el tipo de navegación lateral solo en la vista de escritorio.</p>
        <hr class="horizontal dark my-sm-4">
        <div class="mt-2 mb-5 d-flex">
          <h6 class="mb-0">Claro u Oscuro</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
    $(document).ready(function() {
    // Cargar lista de departamentos al cargar la página
    $.ajax({
        url: 'obtener_departamentos.php', 
        type: 'GET',
        success: function(data) {
            var departamentos = JSON.parse(data); // Parsear la respuesta JSON
            var selectDepartamentos = $('#departamento');
            
            $.each(departamentos, function(id, nombre) {
                selectDepartamentos.append('<option value="' + id + '">' + nombre + '</option>');
            });

            // Manejar el evento submit del formulario DESPUÉS de cargar los departamentos
            $('#registroForm').submit(function(event) {
                event.preventDefault(); 
                $.ajax({
                    type: 'POST',
                    url: 'asist.php', 
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tabla-resultados').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al enviar el formulario:", error);
                    }
                });
            });
        },
        error: function() {
            alert('Error al cargar los departamentos. Inténtalo de nuevo.');
        }
    });
});
</script>

  <script>
    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
    new Chart(ctx1, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Mobile apps",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#5e72e4",
          backgroundColor: gradientStroke1,
          borderWidth: 3,
          fill: true,
          data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#fbfbfb',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#ccc',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>