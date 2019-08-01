<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <title>Mismatch - Onde os opostos se atraem!</title>
  <meta name="description" content="Mismatch - Onde os opostos se atraem!" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
  <nav>
    <a class="menu" href="viewprofile.php">Ver Perfil</a>
    <a class="menu" href="editprofile.php">Editar Perfil</a>
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

          echo '<img src="' . MM_UPLOADPATH . $row['imagem'] . '" alt="' . $row['nome'] . '" />';
        } else {
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

</body>

</html>