<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <title>Mismatch - Onde os opostos se atraem!</title>
  <meta name="description" content="Mismatch - Onde os opostos se atraem!" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
  <h2>Mismatch - Onde os opostos se atraem!</h2>

  <?php
  require_once('constants/app-vars.php');
  require_once('constants/connection-vars.php');


  // Menu
  echo '&#10084; <a href="viewprofile.php">Ver Perfil</a><br />';
  echo '&#10084; <a href="editprofile.php">Editar Perfil</a><br />';

  // Conexão com BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Erro de conexão com MySQL server.');

  // Recuperar dados do BD
  $query = "SELECT id, nome, imagem FROM usuario WHERE nome IS NOT NULL ORDER BY data_cadastro DESC LIMIT 5";

  $data = mysqli_query($dbc, $query);

  echo '<h4>Últimos membros:</h4>';
  echo '<table>';

  while ($row = mysqli_fetch_array($data)) {
    if (is_file(MM_UPLOADPATH . $row['imagem']) && filesize(MM_UPLOADPATH . $row['imagem']) > 0) {

      echo '<tr><td><img src="' . MM_UPLOADPATH . $row['imagem'] . '" alt="' . $row['nome'] . '" /></td>';
    } else {
      echo '<tr><td><img src="' . MM_UPLOADPATH . 'not-image.jpg' . '" alt="' . $row['nome'] . '" /></td>';
    }

    echo '<td>' . $row['nome'] . '</td></tr>';
  }
  echo '</table>';
  mysqli_close($dbc);
  ?>


</body>

</html>