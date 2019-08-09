<?php
require_once('templates/startsession.php');

$page_title = 'Meu Desencontro';
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
                        <a class="nav__menu__item__button" href="viewprofile.php">Perfil</a>
                    </li>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="editprofile.php">Alterar</a>
                    </li>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="questionnaire.php">Tópicos</a>
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
    <h2 class="view-title"><?php echo $page_title; ?></h2>

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
    if (mysqli_num_rows($data) != 0) {
        $query = "SELECT r.id, r.id_topico, r.resposta, t.nome AS topico_nome, c.nome AS categoria_nome FROM resposta AS r INNER JOIN topico AS t on(r.id_topico = t.id) INNER JOIN categoria AS c on(t.id_categoria = c.id) WHERE r.id_usuario = '" . $_SESSION['id'] . "'";
        $data = mysqli_query($dbc, $query);
        $respostas_usuario_logado = array();

        while ($row = mysqli_fetch_array($data)) {
            array_push($respostas_usuario_logado, $row);
        }

        $pontuacao_melhor_par = 0;
        $id_usuario_desencontro = -1;
        $topicos_relacionados = array();

        // Busca todos os usuários
        $query = "SELECT id FROM usuario WHERE id != '" . $_SESSION['id'] . "'";
        $data = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($data)) {
            $query2 = "SELECT id, id_topico, resposta FROM resposta WHERE id_usuario = '" . $row['id'] . "'";
            $data2 = mysqli_query($dbc, $query2);
            $respostas_usuario_banco = array();
            while ($row2 = mysqli_fetch_array($data2)) {
                array_push($respostas_usuario_banco, $row2);
            }


            $pontuacao_aux = 0;
            $topicos_aux = array();

            for ($i = 0; $i < count($respostas_usuario_logado); $i++) {
                if ($respostas_usuario_logado[$i]['resposta'] + $respostas_usuario_banco[$i]['resposta'] == 3) {
                    $pontuacao_aux += 1;
                    array_push($topicos_aux, $respostas_usuario_logado[$i]['topico_nome']);
                }
            }

            if ($pontuacao_aux > $pontuacao_melhor_par) {
                $pontuacao_melhor_par = $pontuacao_aux;
                $id_usuario_desencontro = $row['id'];
                $topicos_relacionados = array_slice($topicos_aux, 0);
            }
        }

        if ($id_usuario_desencontro != -1) {
            $query = "SELECT username, nome, sobrenome, cidade, estado, imagem FROM usuario WHERE id = '$id_usuario_desencontro'";
            $data = mysqli_query($dbc, $query);

            if (mysqli_num_rows($data) == 1) {
                $row = mysqli_fetch_array($data);

                echo '<table><tr><td class="label">';
                if (!empty($row['nome']) && !empty($row['sobrenome'])) {
                    echo $row['nome'] . ' ' . $row['sobrenome'] . '<br />';
                }
                if (!empty($row['cidade']) && !empty($row['estado'])) {
                    echo $row['cidade'] . ', ' . $row['estado'] . '<br />';
                }
                echo '</td><td>';
                if (!empty($row['imagem'])) {
                    echo '<img src="' . MM_UPLOADPATH . $row['imagem'] . '" alt="Foto do perfil" /><br />';
                }
                echo '</td></tr></table>';

                echo '<h4>Você é incompatível em ' . count($topicos_aux) . ' tópicos:</h4>';
                foreach ($topicos_aux as $topico) {
                    echo $topico . '<br />';
                }

                echo '<h4>Visualizar o perfil de <a href=viewprofile.php?id=' . $id_usuario_desencontro . '>' . $row['nome'] . '</a>.</h4>';
            }
        }
    } else {
        echo '<p>Você deve <a href="questionnaire.php">responder seu questionário</a> antes de buscar um par.</p>';
    }
    mysqli_close($dbc);
    ?>
</section>

<?php
require_once('templates/footer.php');
?>