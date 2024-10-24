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

  <form action="procesar_empleados.php" method="post"> <!-- Asegúrate de apuntar a tu archivo PHP de procesamiento -->
    <label for="empleados">Selecciona empleados:</label>
    <select id="empleados" name="empleados[]" multiple="multiple" style="width: 50%;">
      <!-- Las opciones se llenarán dinámicamente -->
    </select>
    <button type="submit">Enviar</button> <!-- Botón para enviar el formulario -->
  </form>

  <script>
    $(document).ready(function() {
      $('#empleados').select2({
        placeholder: 'Busca empleados por RPE',
        allowClear: true,
        ajax: {
          url: 'conexion.php', // El archivo PHP que hemos creado
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
