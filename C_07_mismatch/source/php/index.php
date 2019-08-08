<?php
require_once('templates/startsession.php');

$page_title = 'Onde os opostos se atraem!';
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
    <div class="nav__menu">

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
          // TODO: Identificar usuario logado
          // echo ('<p class="login">Bem vindo ' . $_SESSION['username'] . '.</p>');
          ?>
          <li class="nav__menu__item">
            <a class="nav__menu__item__button" href="viewprofile.php">Ver Perfil</a>
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

<header>
  <div class="text">
    <h2>Mismatch</h2>
    <h3>Onde os opostos se atraem!</h3>
  </div>
</header>
<section>
  <h2>ÚLTIMOS MEMBROS</h2>
  <main>
    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');

    // Conexão com BD
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
      or die('Erro de conexão com MySQL server.');

    // Recuperar dados do BD
    $query = "SELECT id, nome, cidade, estado, imagem FROM usuario WHERE nome IS NOT NULL ORDER BY data_cadastro DESC LIMIT 15";

    $data = mysqli_query($dbc, $query);


    while ($row = mysqli_fetch_array($data)) {
      echo '<div class="card">';
      if (is_file(MM_UPLOADPATH . $row['imagem']) && filesize(MM_UPLOADPATH . $row['imagem']) > 0) {

        if (isset($_SESSION['id'])) {
          echo '<a href="viewprofile.php?id=' . $row['id'] . '"><img src="' . MM_UPLOADPATH . $row['imagem'] . '" alt="' . $row['nome'] . '" /></a>';
        } else {
          echo '<img src="' . MM_UPLOADPATH . $row['imagem'] . '" alt="' . $row['nome'] . '" />';
        }
      } else {
        if (isset($_SESSION['id'])) {
          echo '<a href="viewprofile.php?id=' . $row['id'] . '"><img src="' . MM_UPLOADPATH . 'not-image.png' . '" alt="' . $row['nome'] . '" /></a>';
        } else {
          echo '<img src="' . MM_UPLOADPATH . 'not-image.png' . '" alt="' . $row['nome'] . '" />';
        }
      }

      echo '<div class="card-name">' . $row['nome'] . '</div>';
      echo '<div class="card-city">' . $row['cidade'] . ' - ' . $row['estado'] . '</div>';
      echo '</div>';
    }
    mysqli_close($dbc);
    ?>
  </main>
</section>

<?php
require_once('templates/footer.php');
?>