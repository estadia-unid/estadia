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

// Obtener fecha actual
$fecha_hoy = date("Y-m-d");

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

// Cerrar la conexión
$conexion->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../global/favicon.ico">
    <title>TABLAS</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    <style>
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
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" ../pages/dashboard.html " target="_blank">
        <img src="../global/favicon.ico" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">SATE</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
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
          <a class="nav-link active" href="tablas.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">TABLAS</span>
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
            <div class="card-header pb-0 pt-3 bg-transparent">
                <h2 class="text-capitalize">Solicitud tiempo extra</h2>
            </div>
            <div class="card-body p-3">
                <!-- Select para filtrar por departamento -->
                <div class="mb-3">
                    <label for="departamentoSelect" class="form-label">Filtrar por Departamento:</label>
                    <select id="departamentoSelect" class="form-select">
                        <option value="todos">Todos los Departamentos</option>
                        <?php foreach ($registros_por_departamento as $departamento => $registros_por_id): ?>
                            <option value="<?php echo htmlspecialchars($departamento); ?>">
                                <?php echo htmlspecialchars($departamento); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Mostrar las tablas por departamento -->
                <?php foreach ($registros_por_departamento as $departamento => $registros_por_id): ?>
                    <div class="departamento-container" data-departamento="<?php echo htmlspecialchars($departamento); ?>">
                        <h2>Departamento: <?php echo htmlspecialchars($departamento); ?></h2>
                        <?php foreach ($registros_por_id as $id_registro => $registros): ?>
                            <h3>Registros con ID de Registro: <?php echo htmlspecialchars($id_registro); ?></h3>

                            <!-- Botón para generar PDF -->
                            <form action="../reports/sate.php" method="post" target="_blank">
                                <input type="hidden" name="id_registro" value="<?php echo htmlspecialchars($id_registro); ?>">
                                <button type="submit" class="btn-generar">Generar PDF</button>
                            </form>

                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>ID REGISTRO</th>
                                        <th>CATEGORIA</th>
                                        <th>NOMBRE DEL TRABAJADOR</th>
                                        <th>RPE</th>
                                        <th>FECHA</th>
                                        <th>INICIO</th>
                                        <th>TERMINO</th>
                                        <th>No. HORAS</th>
                                        <th>ACTIVIDADES REALIZADAS</th>
                                        <th>No. ORDEN</th>
                                        <th>JUSTIFICACION TECNICA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($registros as $registro): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($registro['id_registro']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['categ']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['nombre'] . ' ' . $registro['a_paterno'] . ' ' . $registro['a_materno']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['rpe']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['fecha_registro']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['hora_inicio']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['hora_termino']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['horas_extra']); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($registro['actividades'])); ?></td>
                                            <td><?php echo htmlspecialchars($registro['numero_orden']); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($registro['justificacion'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Script para manejar el filtro -->
<script>
    document.getElementById('departamentoSelect').addEventListener('change', function () {
        const selectedDepartamento = this.value;
        const containers = document.querySelectorAll('.departamento-container');

        containers.forEach(container => {
            if (selectedDepartamento === 'todos' || container.dataset.departamento === selectedDepartamento) {
                container.style.display = '';
            } else {
                container.style.display = 'none';
            }
        });
    });
</script>



                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
          <div class="card card-carousel overflow-hidden h-100 p-0">
            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
              <div class="carousel-inner border-radius-lg h-100">
                <div class="carousel-item h-100 active" style="background-image: url('../assets/img/carousel-1.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-camera-compact text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Ten un buen dia hoy!!</h5>
                    <p>“¡Buenos días! Abraza cada nuevo día con el corazón lleno de gratitud, la mente repleta de positividad y el alma rebosante de alegría.”</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('../assets/img/carousel-2.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">TU PUEDES</h5>
                    <p>“¡Levántate y brilla! Hoy es un regalo, un lienzo en blanco sobre el que puedes pintar tus sueños. Conviértelo en una obra maestra”.</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('../assets/img/carousel-3.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-trophy text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Ya tomaste cafe?</h5>
                    <p>“¡Despierta y huele el café! La vida es demasiado corta para pensar en los problemas de ayer. Abraza el momento presente con los brazos abiertos y haz que el día de hoy cuente.”</p>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
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