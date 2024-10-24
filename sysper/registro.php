<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seleccionar Empleados por RPE</title>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

  <h1>Seleccionador de Empleados por RPE</h1>

  <form>
    <label for="empleados">Selecciona un empleado:</label>
    <select id="empleados" style="width: 50%;">
      <!-- Las opciones se llenarán dinámicamente -->
    </select>
  </form>

  <script>
    $(document).ready(function() {
      $('#empleados').select2({
        placeholder: 'Busca un empleado por RPE',
        allowClear: true,
        ajax: {
          url: 'buscar_empleados.php', // El archivo PHP que hemos creado
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
              term: params.term // El término de búsqueda, en este caso, el RPE
            };
          },
          processResults: function(data) {
            return {
              results: data // Los datos de la respuesta JSON
            };
          },
          cache: true
        }
      });
    });
  </script>

</body>
</html>