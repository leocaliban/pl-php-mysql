<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <title>Mismatch - Perfil</title>
  <meta name="description" content="Mismatch - Perfil" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
  <nav>
    <a class="menu" href="index.php">Início</a>
    <a class="menu" href="editprofile.php">Atualizar Perfil</a>
  </nav>

  <section class="view-section">
    <h2 class="view-title">Perfil do Usuário</h2>

    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');

    // Conexão com BD
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
      or die('Erro de conexão com MySQL server.');

    $id = $_COOKIE['id'];
    // Recuperar dados do BD
    if (!isset($_GET['id'])) {
      $query = "SELECT username, nome, sobrenome, genero, nascimento, cidade, estado, imagem FROM usuario WHERE id = '$id'";
    } else {
      $query = "SELECT username, nome, sobrenome, genero, nascimento, cidade, estado, imagem FROM usuario WHERE id = '" . $_GET['id'] . "'";
    }

    $data = mysqli_query($dbc, $query);

    if (mysqli_num_rows($data) == 1) {
      $row = mysqli_fetch_array($data);

      echo '<img class="view-image" src="' . MM_UPLOADPATH . $row['imagem'] . '" alt="Foto de perfil" />';

      echo '<table>';
      if (!empty($row['username'])) {
        echo '<tr><td class="label">Nome do usuário:</td><td>' . $row['username'] . '</td></tr>';
      }
      if (!empty($row['nome'])) {
        echo '<tr><td class="label">Nome:</td><td>' . $row['nome'] . '</td></tr>';
      }
      if (!empty($row['sobrenome'])) {
        echo '<tr><td class="label">Sobrenome:</td><td>' . $row['sobrenome'] . '</td></tr>';
      }
      if (!empty($row['genero'])) {
        echo '<tr><td class="label">Gênero:</td><td>';
        if ($row['genero'] == 'M') {
          echo 'Masculino';
        } else if ($row['genero'] == 'F') {
          echo 'Feminino';
        } else {
          echo '?';
        }
        echo '</td></tr>';
      }
      if (!empty($row['nascimento'])) {
        if (!isset($_GET['id']) || ($id == $_GET['id'])) {
          // Show the user their own birthdate
          echo '<tr><td class="label">Data de nascimento:</td><td>' . $row['nascimento'] . '</td></tr>';
        } else {
          // Show only the birth year for everyone else
          list($year, $month, $day) = explode('-', $row['nascimento']);
          echo '<tr><td class="label">Ano de nascimento:</td><td>' . $year . '</td></tr>';
        }
      }
      if (!empty($row['cidade']) || !empty($row['estado'])) {
        echo '<tr><td class="label">Endereço:</td><td>' . $row['cidade'] . ', ' . $row['estado'] . '</td></tr>';
      }
      echo '</table>';
    } else {
      echo '<p class="error">Ocorreu um problema ao acessar seu perfil.</p>';
    }

    mysqli_close($dbc);
    ?>
  </section>

</body>

</html>