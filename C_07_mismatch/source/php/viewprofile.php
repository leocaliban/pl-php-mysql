<?php
require_once('templates/startsession.php');

$page_title = 'Perfil';
require_once('templates/header.php');
?>
<nav>
  <a class="menu" href="index.php">Início</a>
  <?php
  if (isset($_SESSION['username'])) {
    echo ('<p class="login">Bem vindo ' . $_SESSION['username'] . '.</p>');
    ?>
    <a class="menu" href="editprofile.php">Atualizar Perfil</a>
    <a class="menu" href="logout.php">Logout</a>
  <?php
  } else {
    ?>
    <a class="menu" href="login.php">Login</a>
    <a class="menu" href="signup.php">Criar conta</a>
  <?php
  }
  ?>
</nav>

<section class="view-section" id="view">
  <h2 class="view-title">Perfil do Usuário</h2>

  <?php
  require_once('constants/app-vars.php');
  require_once('constants/connection-vars.php');

  if (!isset($_SESSION['id'])) {
    echo '<p class="error">Por favor, realize o <a href="login.php">LOGIN</a> para acessar essa página.</p>';
    exit();
  }
  // Conexão com BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Erro de conexão com MySQL server.');

  $id = $_SESSION['id'];
  // Recuperar dados do BD
  if (!isset($_GET['id'])) {
    $query = "SELECT username, nome, sobrenome, genero, nascimento, cidade, estado, imagem FROM usuario WHERE id = '$id'";
  } else {
    $query = "SELECT username, nome, sobrenome, genero, nascimento, cidade, estado, imagem FROM usuario WHERE id = '" . $_GET['id'] . "'";
  }

  $data = mysqli_query($dbc, $query);

  if (mysqli_num_rows($data) == 1) {
    $row = mysqli_fetch_array($data);
    echo '<div style="display:flex;flex-wrap: wrap;">';
    echo '<img class="view-image" src="' . MM_UPLOADPATH . $row['imagem'] . '" alt="Foto de perfil" />';

    echo '<table style="align-self:center; margin-left:20px;">';
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
    echo '</div>';
  } else {
    echo '<p class="error">Ocorreu um problema ao acessar seu perfil.</p>';
  }

  mysqli_close($dbc);
  ?>
</section>

</body>

</html>