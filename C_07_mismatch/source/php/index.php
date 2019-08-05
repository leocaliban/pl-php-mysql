<?php
require_once('templates/startsession.php');

$page_title = 'Onde os opostos se atraem!';
require_once('templates/header.php');
?>

<nav>
  <?php
  if (isset($_SESSION['username'])) {
    echo ('<p class="login">Bem vindo ' . $_SESSION['username'] . '.</p>');
    ?>
    <a class="menu" href="viewprofile.php">Ver Perfil</a>
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
    $query = "SELECT id, nome, cidade, estado, imagem FROM usuario WHERE nome IS NOT NULL ORDER BY data_cadastro DESC LIMIT 10";

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
          echo '<a href="viewprofile.php?id=' . $row['id'] . '"><img src="' . MM_UPLOADPATH . 'not-image.jpg' . '" alt="' . $row['nome'] . '" /></a>';
        } else {
          echo '<img src="' . MM_UPLOADPATH . 'not-image.jpg' . '" alt="' . $row['nome'] . '" />';
        }
        echo '<img src="' . MM_UPLOADPATH . 'not-image.jpg' . '" alt="' . $row['nome'] . '" />';
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