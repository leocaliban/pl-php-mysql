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

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
      or die('Erro de conexão com MySQL server.');

    $nome = mysqli_real_escape_string($dbc, trim($_POST['nome']));
    $pontuacao = mysqli_real_escape_string($dbc, trim($_POST['pontuacao']));
    $imagem = mysqli_real_escape_string($dbc, trim($_FILES['imagem']['name']));
    $imagem_type = $_FILES['imagem']['type'];
    $imagem_size = $_FILES['imagem']['size'];

    if (!empty($nome) && is_numeric($pontuacao) && !empty($imagem)) {
      if ((($imagem_type == 'image/gif') || ($imagem_type == 'image/jpeg') || ($imagem_type == 'image/pjpeg') || ($imagem_type == 'image/png'))
        && ($imagem_size > 0) && ($imagem_size <= MY_MAXFILESIZE)
      ) {
        if ($_FILES['imagem']['error'] == 0) {
          $file_name = time() . $imagem;
          $target_path = MY_UPLOADPATH . $file_name;
          if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_path)) {

            $query = "INSERT INTO guitarwars (data, nome, pontuacao, imagem) VALUES (NOW(), '$nome', '$pontuacao', '$file_name')";
            mysqli_query($dbc, $query) or die('Ocorreu um erro na query.');

            echo '<p>Sua pontuação foi registrada com sucesso!</p>';
            echo '<p><strong>Nome:</strong> ' . $nome . '<br />';
            echo '<strong>Pontuação:</strong> ' . $pontuacao . '</p>';
            echo '<p><a href="index.php">&lt;&lt; Voltar para ranking</a></p>';
            echo '<img src="' . MY_UPLOADPATH . $file_name . '" alt="Foto de pontuação." />';

            $nome = "";
            $pontuacao = "";

            mysqli_close($dbc);
          } else {
            echo '<p class="error">Desculpe, ocorreu um problema como o envio da sua imagem.</p>';
          }
        }
      } else {
        echo '<p class="error">Desculpe, sua imagem deve ser no formato GIF, JPEG ou PNG, tamanho máximo é de ' . (MY_MAXFILESIZE / 1024) . ' KB.</p>';
      }
      @unlink($_FILES['imagem']['tmp_name']);
    } else {
      echo '<p class="error">Por favor preencha todos os campos do formulário.</p>';
    }
  }
  ?>

  <hr />
  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="600000">
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