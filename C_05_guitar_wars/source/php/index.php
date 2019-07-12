<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <title>Guitar Wars - Pontuação</title>
  <meta name="description" content="Inscrever-se na lista de e-mails" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
  <h2>Guitar Wars - Ranking</h2>

  <p>Bem vindo ao Guitar Warrior, você gostaria de entrar no ranking mundial? Envie sua pontuação clicando <a href="addscore.php">AQUI</a>.</p>

  <br>
  <a href="admin.php">Gerenciar pontuações</a>
  <hr />

  <?php
  require_once('constants/app-vars.php');
  require_once('constants/connection-vars.php');

  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Erro de conexão com MySQL server.');

  $query = "SELECT * FROM guitarwars WHERE aprovado = 1 ORDER BY pontuacao DESC, data ASC";
  $data = mysqli_query($dbc, $query);

  echo '<table>';
  $aux = 0;

  while ($row = mysqli_fetch_array($data)) {
    if ($aux == 0) {
      echo '<tr><td colspan="2" class="topscore">Top Score: ' . $row['pontuacao'] . '</td></tr>';
    }

    echo '<tr><td class="scoreinfo">';
    echo '<span class="score">' . $row['pontuacao'] . '</span><br />';
    echo '<strong>Nome:</strong> ' . $row['nome'] . '<br />';
    echo '<strong>Data:</strong> ' . $row['data'] . '<br />';

    if (is_file(MY_UPLOADPATH . $row['imagem']) && filesize(MY_UPLOADPATH . $row['imagem']) > 0) {
      echo '<img src="' . MY_UPLOADPATH . $row['imagem'] . '" alt="Foto de pontuação." /></td></tr>';
    } else {
      echo '<img src="assets/images/not-image.png" alt="Foto de pontuação." /></td></tr>';
    }
    $aux++;
  }

  echo '</table>';

  mysqli_close($dbc);
  ?>

</body>

</html>