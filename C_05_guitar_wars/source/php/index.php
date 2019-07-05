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

  <hr />

  <?php
  $dbc = mysqli_connect('localhost', 'root', 'root', 'php_guitar_wars')
    or die('Erro de conexão com MySQL server.');

  $query = "SELECT * FROM guitarwars";
  $data = mysqli_query($dbc, $query);

  echo '<table>';
  while ($row = mysqli_fetch_array($data)) {
    echo '<tr><td class="scoreinfo">';
    echo '<span class="score">' . $row['pontuacao'] . '</span><br />';
    echo '<strong>Nome:</strong> ' . $row['nome'] . '<br />';
    echo '<strong>Data:</strong> ' . $row['data'] . '</td></tr>';
  }
  echo '</table>';

  mysqli_close($dbc);
  ?>

</body>

</html>