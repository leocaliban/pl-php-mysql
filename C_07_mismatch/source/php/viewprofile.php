<?php
require_once('templates/startsession.php');

$page_title = 'Perfil';
require_once('templates/header.php');
?>

<nav class="nav">
  <div class="nav__container">
    <div class="nav__container__logo">
      <p class="nav__container__logo__name">M</p>
      <a href="index.php">
        <img class="nav__container__logo__image" src="assets/images/mm.png" alt="Mismatch logo">
      </a>
      <p class="nav__container__logo__name">M</p>
      <?php
      if (isset($_SESSION['username'])) {
        echo ('<p class="nav__container__user"> - Olá, ' . $_SESSION['username'] . '.</p>');
      }
      ?>
    </div>
    <div class="nav__menu t">

      <div class="nav__menu__mini">
        <div class="nav__menu__mini__row"> </div>
        <div class="nav__menu__mini__row"> </div>
        <div class="nav__menu__mini__row"> </div>
      </div>

      <ul>
        <li class="nav__menu__item">
          <a class="nav__menu__item__button" href="index.php">Início</a>
        </li>
        <?php
        if (isset($_SESSION['username'])) {
          ?>
          <li class="nav__menu__item">
            <a class="nav__menu__item__button" href="editprofile.php">Alterar</a>
          </li>
          <li class="nav__menu__item">
            <a class="nav__menu__item__button" href="questionnaire.php">Tópicos</a>
          </li>
          <li class="nav__menu__item">
            <a class="nav__menu__item__button" href="mymismatch.php">MM</a>
          </li>
          <li class="nav__menu__item">
            <a class="nav__menu__item__button" href="logout.php">Logout</a>
          </li>
        <?php
        } else {
          ?>
          <li class="nav__menu__item">
            <a class="nav__menu__item__button" href="login.php">Login</a>
          </li>
          <li class="nav__menu__item">
            <a class="nav__menu__item__button" href="signup.php">Criar conta</a>
          </li>
        <?php
        }
        ?>
      </ul>
    </div>
  </div>
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

<?php
require_once('templates/footer.php');
?>