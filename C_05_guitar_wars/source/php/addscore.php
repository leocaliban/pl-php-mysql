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
  require_once('constants/app-vars.php');
  require_once('constants/connection-vars.php');

  if (isset($_POST['submit'])) {

    $nome = $_POST['nome'];
    $pontuacao = $_POST['pontuacao'];
    $imagem = $_FILES['imagem']['name'];

    if (!empty($nome) && !empty($pontuacao) && !empty($imagem)) {
      $file_name = time() . $imagem;
      $target_path = MY_UPLOADPATH . $file_name;
      if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_path)) {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
          or die('Erro de conexão com MySQL server.');

        $query = "INSERT INTO guitarwars VALUES (0, NOW(), '$nome', '$pontuacao', '$file_name')";
        mysqli_query($dbc, $query) or die('Ocorreu um erro na query.');

        echo '<p>Sua pontuação foi registrada com sucesso!</p>';
        echo '<p><strong>Nome:</strong> ' . $nome . '<br />';
        echo '<strong>Pontuação:</strong> ' . $pontuacao . '</p>';
        echo '<p><a href="index.php">&lt;&lt; Voltar para ranking</a></p>';
        echo '<img src="' . MY_UPLOADPATH . $file_name . '" alt="Foto de pontuação." />';

        $nome = "";
        $pontuacao = "";

        mysqli_close($dbc);
      }
    } else {
      echo '<p class="error">Por favor preencha todos os campos do formulário.</p>';
    }
  }
  ?>

  <hr />
  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="32768">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php if (!empty($nome)) echo $nome; ?>" /><br />
    <label for="pontuacao">Pontuação:</label>
    <input type="text" id="pontuacao" name="pontuacao" value="<?php if (!empty($pontuacao)) echo $pontuacao; ?>" />
    <br />
    <label for="imagem">Envie uma imagem:</label>
    <input type="file" id="imagem" name="imagem">
    <hr />
    <input type="submit" value="Enviar" name="submit" />
  </form>
</body>

</html>