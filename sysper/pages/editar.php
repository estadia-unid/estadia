<?php
require_once 'seguridad.php';

// Obtén el ID del registro de la solicitud
$id_registro = $_GET['id_registro'] ?? null;

// Conexión a la base de datos
$conexion = new mysqli("localhost", "skyper", "ctpalm2113", "estadiaunid");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$registro = null;

if ($id_registro) {
    // Consulta para obtener los datos actuales del registro junto con detalles de departamento y jefe
    $consulta = "
        SELECT 
            r.fecha_registro, 
            r.hora_inicio, 
            r.hora_termino, 
            r.horas_extra, 
            r.actividades, 
            r.justificacion, 
            d.numero_orden, 
            d.om,
            d.id_departamento,
            dep.departamento AS nombre_departamento,
            j.id_jefe AS id_jefe_departamento,
            j.nombre_jefe
        FROM registros r
        LEFT JOIN detalles_orden d ON r.id = d.id_registro
        LEFT JOIN departamentos dep ON d.id_departamento = dep.id_departamento
        LEFT JOIN jefes_dpto j ON dep.id_departamento = j.id_departamento
        WHERE r.id = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id_registro);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $registro = $resultado->fetch_assoc();
    $stmt->close();
}

// Consulta para obtener empleados asignados al registro actual
$consulta_empleados = "
    SELECT e.rpe, CONCAT(e.nombre, ' ', e.a_paterno, ' ', e.a_materno) AS nombre_completo
    FROM empleados_asignados ea
    INNER JOIN empleados e ON ea.rpe = e.rpe
    WHERE ea.id_registro = ?";

$stmt_empleados = $conexion->prepare($consulta_empleados);
$stmt_empleados->bind_param("i", $id_registro);
$stmt_empleados->execute();
$resultado_empleados = $stmt_empleados->get_result();

$empleados_asignados = [];
while ($fila = $resultado_empleados->fetch_assoc()) {
    $empleados_asignados[] = $fila['rpe'];
}

$stmt_empleados->close();

// También puedes cargar la lista completa de empleados para el <select>
$consulta_todos_empleados = "SELECT rpe, CONCAT(nombre, ' ', a_paterno, ' ', a_materno) AS nombre_completo FROM empleados";
$resultado_todos_empleados = $conexion->query($consulta_todos_empleados);
$empleados_totales = $resultado_todos_empleados->fetch_all(MYSQLI_ASSOC);


// Consulta para obtener todos los departamentos
$consulta_departamentos = "SELECT id_departamento, departamento FROM departamentos";
$resultado_departamentos = $conexion->query($consulta_departamentos);
$departamentos = $resultado_departamentos->fetch_all(MYSQLI_ASSOC);

// Consulta para obtener todos los jefes de departamento
$consulta_jefes = "
    SELECT id_jefe, id_departamento, nombre_jefe 
    FROM jefes_dpto";
$resultado_jefes = $conexion->query($consulta_jefes);
$jefes = $resultado_jefes->fetch_all(MYSQLI_ASSOC);

$conexion->close();

if (!$registro) {
    die("No se encontró el registro.");
}
?>




<!--
=========================================================
* Argon Dashboard 3 - v2.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../global/favicon.ico">
  <title>
    SATE
  </title>
   <!--     Fonts and icons     -->
   <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
<!-- CSS de Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Time Picker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">

<!-- Fuentes e iconos -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />

<!-- CSS Principal -->
<link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />

<!-- jQuery (Select2, Flatpickr y Timepicker requieren jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JS de Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Time Picker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<style>
    .select2-container--bootstrap-5 .select2-selection {
            height: auto;
            min-height: 38px;
            border-radius: 0.375rem;
        }
        
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.form-group {
    display: flex;
    align-items: center; /* Alinea verticalmente los elementos */
}

.form-select {
    margin-right: 10px; /* Espacio entre los select */
    flex: 1; /* Hace que los select ocupen el mismo espacio */
}
</style>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" ../pages/dashboard.html " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
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
          <a class="nav-link active" href="editar.php">
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
          <a class="nav-link " href="li_asist.php">
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
  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Registro</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Registro</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
              <input type="text" class="form-control" placeholder="Type here...">
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
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
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New message</span> from Laur
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 minutes ago
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New album</span> by Travis Scott
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          1 day
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                  <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          Payment successfully completed
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          2 days
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
    <div class="row mt-4">
    <div class="col-lg-12 mb-lg-0 mb-3">
    <div class="card z-index-2 h-100">
        <div class="card-header pb-0 pt-2 bg-transparent">
            <center>
                <h3 class="text-capitalize">SOLICITUD DE AUTORIZACION DE TIEMPO EXTRAORDINARIO</h3>
            </center>
        </div>
        <div class="card-body p-2">
        <form id="editarForm" method="post" action="guardar_edicion.php">
    <!-- ID de Registro (oculto) -->
    <input type="hidden" name="id_registro" value="<?php echo htmlspecialchars($registro['id']); ?>">

    <!-- Fecha de Actividad -->
    <div class="mb-2">
        <label for="fecha_act" class="form-label">Fecha:</label>
        <input type="date" class="form-control form-control-sm" id="fecha_act" name="fecha_act" value="<?php echo htmlspecialchars($registro['fecha_registro']); ?>" required>
    </div>

    <div class="mb-2">
    <label for="empleados" class="form-label">Seleccionar Empleados:</label>
    <select class="js-example-basic-multiple form-select form-select-sm" id="empleados" name="empleados[]" multiple="multiple" style="width: 100%">
        <?php foreach ($empleados_totales as $empleado): ?>
            <option 
                value="<?php echo htmlspecialchars($empleado['rpe']); ?>" 
                <?php echo in_array($empleado['rpe'], $empleados_asignados) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($empleado['nombre_completo']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>


<div class="form-group">
    <!-- Seleccionar Departamento -->
    <label for="departamento">Seleccionar Departamento</label>
    <select id="departamento" name="departamento" class="form-select" required>
        <option value="">Seleccione un departamento</option>
        <?php foreach ($departamentos as $departamento): ?>
            <option 
                value="<?php echo $departamento['id_departamento']; ?>" 
                <?php echo ($departamento['id_departamento'] == $registro['id_departamento']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($departamento['departamento']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <!-- Seleccionar Jefe -->
    <label for="jefe_departamento">Seleccionar Jefe</label>
    <select id="jefe_departamento" name="jefe_departamento" class="form-select" required>
        <option value="">Seleccione un jefe</option>
        <?php foreach ($jefes as $jefe): ?>
            <?php if ($jefe['id_departamento'] == $registro['id_departamento']): ?>
                <option 
                    value="<?php echo $jefe['id_jefe']; ?>" 
                    <?php echo ($jefe['id_jefe'] == $registro['id_jefe_departamento']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($jefe['nombre_jefe']); ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</div>




    <!-- No. Orden y OM -->
    <div class="mb-2 d-flex gap-2">
    <div class="flex-fill">
        <label>No. Orden:</label>
        <input 
            type="text" 
            name="numero_orden" 
            class="form-control form-control-sm" 
            value="<?php echo htmlspecialchars($registro['numero_orden'] ?? ''); ?>" 
            required>
    </div>
    <div class="flex-fill">
        <label>OM:</label>
        <input 
            type="text" 
            name="om" 
            class="form-control form-control-sm" 
            value="<?php echo htmlspecialchars($registro['om'] ?? ''); ?>" 
            required>
    </div>
</div>


    <!-- Actividades realizadas -->
    <div class="mb-2">
        <label>Actividades realizadas:</label>
        <textarea name="actividades" class="form-control form-control-sm" rows="2" required><?php echo htmlspecialchars($registro['actividades']); ?></textarea>
    </div>

    <!-- Justificación técnica -->
    <div class="mb-2">
        <label>Justificación técnica:</label>
        <textarea name="justificacion" class="form-control form-control-sm" rows="2" required><?php echo htmlspecialchars($registro['justificacion']); ?></textarea>
    </div>

    <!-- Horario de Servicio -->
    <div class="mb-2">
        <label for="horario_servicio" class="form-label">Horario de Servicio</label>
        <div class="d-flex align-items-center gap-1">
            <input type="time" class="form-control form-control-sm" id="hora_inicio" name="hora_inicio" value="<?php echo htmlspecialchars($registro['hora_inicio']); ?>" required>
            <span>a</span>
            <input type="time" class="form-control form-control-sm" id="hora_termino" name="hora_termino" value="<?php echo htmlspecialchars($registro['hora_termino']); ?>" required>
            <span class="ms-2">No. Horas:</span>
            <input type="number" class="form-control form-control-sm" id="horas_extra" name="horas_extra" value="<?php echo htmlspecialchars($registro['horas_extra']); ?>">
        </div>
    </div>

    <!-- Botón de Enviar -->
    <button type="submit" class="btn btn-primary btn-sm">Guardar Cambios</button>
</form>
<script>
// Reemplaza el código JavaScript actual con este:
document.getElementById('registroForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);

    // Debug: Mostrar datos enviados
    console.log('Datos a enviar:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    fetch('procesar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Primero vemos el texto crudo de la respuesta 
        return response.text().then(text => {
            console.log('Respuesta cruda del servidor:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Error al parsear JSON:', text);
                throw new Error('La respuesta del servidor no es JSON válido');
            }
        });
    })
    .then(data => {
        console.log('Respuesta parseada:', data);
        if (data.success) {
            alert('Registro guardado con éxito');
        } else {
            alert('Error: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        alert('Error al procesar la solicitud: ' + error.message);
    });
});
</script>
<script>
$(document).ready(function () {
    // Cargar lista de departamentos al cargar la página
    $.ajax({
        url: 'cargar_departamentos.php', // Script que devuelve los departamentos
        type: 'GET',
        success: function (data) {
            $('#departamento').html('<option value="">Seleccione un departamento</option>' + data);
        },
        error: function () {
            alert('Error al cargar los departamentos. Inténtalo de nuevo.');
        }
    });

    // Actualizar jefe de departamento al seleccionar un departamento
    $('#departamento').on('change', function () {
        const departamentoId = $(this).val();
        if (departamentoId) {
            $.ajax({
                url: 'cargar_jefe_departamento.php', // Script que devuelve el jefe
                type: 'GET',
                data: { departamento_id: departamentoId },
                success: function (data) {
                    $('#jefe_departamento').html('<option value="">Seleccione un jefe</option>' + data).prop('disabled', false);
                },
                error: function () {
                    alert('Error al cargar el jefe del departamento. Inténtalo de nuevo.');
                    $('#jefe_departamento').html('<option value="">Error al cargar</option>').prop('disabled', true);
                }
            });
        } else {
            $('#jefe_departamento').html('<option value="">Seleccione un departamento primero</option>').prop('disabled', true);
        }
    });
});

</script>
<script>
    let horasExtraEditado = false; // Variable para verificar si el usuario editó el campo manualmente

    // Eventos para calcular automáticamente las horas extra si el usuario no ha editado manualmente
    document.getElementById('hora_inicio').addEventListener('change', calcularHorasExtra);
    document.getElementById('hora_termino').addEventListener('change', calcularHorasExtra);

    // Detectar si el usuario modifica el campo de horas extra manualmente
    document.getElementById('horas_extra').addEventListener('input', function() {
        horasExtraEditado = true;
    });

    function calcularHorasExtra() {
        // Solo calcula automáticamente si el usuario no ha modificado manualmente el campo
        if (!horasExtraEditado) {
            const horaInicioLaboral = 8 * 60; // 8:00 AM en minutos
            const horaFinLaboral = 16 * 60; // 4:00 PM en minutos

            // Obtener valores de los inputs
            let horaInicio = document.getElementById('hora_inicio').value;
            let horaTermino = document.getElementById('hora_termino').value;

            if (horaInicio && horaTermino) {
                // Convertir horas de inicio y fin a minutos
                let [hInicio, mInicio] = horaInicio.split(':').map(Number);
                let [hFin, mFin] = horaTermino.split(':').map(Number);

                let minutosInicio = hInicio * 60 + mInicio;
                let minutosFin = hFin * 60 + mFin;

                // Calcular minutos extra fuera del horario laboral
                let minutosExtra = 0;
                if (minutosInicio < horaInicioLaboral) {
                    minutosExtra += (horaInicioLaboral - minutosInicio);
                }
                if (minutosFin > horaFinLaboral) {
                    minutosExtra += (minutosFin - horaFinLaboral);
                }

                // Convertir minutos extra a horas decimales
                let horasExtraAprox = (minutosExtra / 60).toFixed(2);

                // Mostrar el cálculo en el campo de horas extra
                document.getElementById('horas_extra').value = horasExtraAprox;
            }
        }
    }
</script>
<script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                ajax: {
                    url: 'get_empleados.php',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            busqueda: params.term, // término de búsqueda
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                placeholder: 'Selecciona empleados',
                minimumInputLength: 1,
                templateResult: formatEmpleado,
                templateSelection: formatEmpleadoSelection
            });
        });

        function formatEmpleado(empleado) {
    if (empleado.loading) return empleado.text;
    // Muestra la categoría, nombre completo y RPE
    return $('<span>' + empleado.categoria + ' - ' + empleado.nombre + ' - ' + empleado.rpe + '</span>');
}

function formatEmpleadoSelection(empleado) {
    if (!empleado.categoria) return empleado.text;
    // Muestra el mismo formato cuando está seleccionado
    return empleado.categoria + ' - ' + empleado.nombre + ' - ' + empleado.rpe;
}
    </script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#fecha_act", {
        dateFormat: "Y-m-d",
    });
});
</script>

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
          <h6 class="mb-0">Sidebar Colors</h6>
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