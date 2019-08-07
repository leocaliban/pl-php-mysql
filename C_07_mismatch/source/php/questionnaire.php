<?php
require_once('templates/startsession.php');

$page_title = 'Questionário';
require_once('templates/header.php');

?>

<nav>
    <a class="menu" href="index.php">Início</a>
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

<section class="view-section" id="view">
    <h2 class="view-title">Questionário</h2>


    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');

    if (!isset($_SESSION['id'])) {
        echo '<p class="error">Por favor, realize o <a href="login.php">LOGIN</a> para acessar essa página.</p>';
        exit();
    }

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('Erro de conexão com MySQL server.');
        mysqli_set_charset($dbc, 'utf8');

    $query_respostas = "SELECT * FROM resposta WHERE id_usuario = '" . $_SESSION['id'] . "'";
    $data = mysqli_query($dbc, $query_respostas);
    if (mysqli_num_rows($data) == 0) {
        $query_topicos = "SELECT id FROM topico ORDER BY categoria, id";
        $data = mysqli_query($dbc, $query_topicos);
        $topicos_id = array();

        while ($row = mysqli_fetch_array($data)) {
            array_push($topicos_id, $row['id']);
        }

        foreach ($topicos_id as $topico_id) {
            $query_inserir_resposta = "INSERT INTO resposta (id_usuario, id_topico) VALUES ('" . $_SESSION['id'] . "', '$topico_id')";
            mysqli_query($dbc, $query_inserir_resposta);
        }
    }

    if (isset($_POST['submit'])) {
        foreach ($_POST as $resposta_id => $resposta) {
            $query_atualizar_resposta = "UPDATE resposta SET resposta = '$resposta' WHERE id = '$resposta_id'";
            mysqli_query($dbc, $query_atualizar_resposta);
        }
        echo '<p class="login">Sua resposta foi salva com sucesso!</p>';
    }

    $query = "SELECT id, id_topico, resposta FROM resposta WHERE id_usuario = '" . $_SESSION['id'] . "'";
    $data = mysqli_query($dbc, $query);

    $respostas = array();

    while ($row = mysqli_fetch_array($data)) {
        $query2 = "SELECT nome, id_categoria FROM topico WHERE id = '" . $row['id_topico'] . "'";
        $data2 = mysqli_query($dbc, $query2);

        if (mysqli_num_rows($data2) == 1) {
            $row2 = mysqli_fetch_array($data2);
            $row['topico'] = $row2['nome'];

            $query3 = "SELECT nome FROM categoria WHERE id = '" . $row2['id_categoria'] . "'";
            $data3 = mysqli_query($dbc, $query3);

            if (mysqli_num_rows($data3) == 1) {
                $row3 = mysqli_fetch_array($data3);
                $row['categoria'] = $row3['nome'];
                array_push($respostas, $row);
            }
        }
    }
    mysqli_close($dbc);

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
  echo '<p>Marque o que você ama e o que odeia.</p>';
  $categoria = $respostas[0]['categoria'];
  echo '<fieldset><legend>' . $respostas[0]['categoria'] . '</legend>';
  foreach ($respostas as $resposta) {
    if ($categoria != $resposta['categoria']) {
      $categoria = $resposta['categoria'];
      echo '</fieldset><fieldset><legend>' . $resposta['categoria'] . '</legend>';
    }

    // TODO: estilo nas respostas nao marcadas 'avisar'
    echo '<label ' . ($resposta['resposta'] == NULL ? 'class="avisar" style="color:#f28e8e"' : '') . ' for="' . $resposta['id'] . '">' . $resposta['topico'] . ':</label>';
    echo '<input type="radio" id="' . $resposta['id'] . '" name="' . $resposta['id'] . '" value="1" ' . ($resposta['resposta'] == 1 ? 'checked="checked"' : '') . ' />Amo ';
    echo '<input type="radio" id="' . $resposta['id'] . '" name="' . $resposta['id'] . '" value="2" ' . ($resposta['resposta'] == 2 ? 'checked="checked"' : '') . ' />Odeio<br />';
  }
  echo '</fieldset>';
  echo '<input type="submit" value="Salvar" name="submit" />';
  echo '</form>';
    ?>
</section>


<?php
require_once('templates/footer.php');
?>