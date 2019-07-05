<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <title>Guitar Wars</title>
  <meta name="description" content="Inscrever-se na lista de e-mails" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
  <h2>Guitar Wars - Registre o sua maior pontuação</h2>

  <?php
  if (isset($_POST['submit'])) {

    $nome = $_POST['nome'];
    $pontuacao = $_POST['pontuacao'];

    if (!empty($nome) && !empty($pontuacao)) {
      $dbc = mysqli_connect('localhost', 'root', 'root', 'php_guitar_wars')
        or die('Erro de conexão com MySQL server.');

      $query = "INSERT INTO guitarwars VALUES (0, NOW(), '$nome', '$pontuacao')";
      mysqli_query($dbc, $query);

      echo '<p>Sua pontuação foi registrada com sucesso!</p>';
      echo '<p><strong>Nome:</strong> ' . $nome . '<br />';
      echo '<strong>Pontuação:</strong> ' . $pontuacao . '</p>';
      echo '<p><a href="index.php">&lt;&lt; Voltar para ranking</a></p>';

      $nome = "";
      $pontuacao = "";

      mysqli_close($dbc);
    } else {
      echo '<p class="error">Por favor preencha todos os campos do formulário.</p>';
    }
  }
  ?>

  <hr />
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php if (!empty($nome)) echo $nome; ?>" /><br />
    <label for="pontuacao">Pontuação:</label>
    <input type="text" id="pontuacao" name="pontuacao" value="<?php if (!empty($pontuacao)) echo $pontuacao; ?>" />
    <hr />
    <input type="submit" value="Enviar" name="submit" />
  </form>
</body>

</html>