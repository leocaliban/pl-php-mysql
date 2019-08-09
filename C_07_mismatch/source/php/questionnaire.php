<?php
require_once('templates/startsession.php');

$page_title = 'Questionário';
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

<section class="section">
    <h2 class="section__tittle">Questionário</h2>


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
        $query_topicos = "SELECT id FROM topico ORDER BY id_categoria, id";
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
        echo '<p class="success">Sua resposta foi salva!</p>';
    }

    $query = "SELECT r.id, r.id_topico, r.resposta, t.nome AS topico_nome, c.nome AS categoria_nome FROM resposta AS r INNER JOIN topico AS t on(r.id_topico = t.id) INNER JOIN categoria AS c on(t.id_categoria = c.id) WHERE r.id_usuario = '" . $_SESSION['id'] . "'";
    $data = mysqli_query($dbc, $query);

    $respostas = array();

    while ($row = mysqli_fetch_array($data)) {
        array_push($respostas, $row);
    }

    mysqli_close($dbc);
    echo '<p class="section__subtitle">Marque os itens que você ama ou odeia de cada tópico para achar seu par imperfeito!</p> <hr/>>';
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<div class="section__topicos">';

    $categoria = $respostas[0]['categoria_nome'];

    echo '<div class="section__topicos__grupo"><img class="section__topicos__grupo__icone" src="assets/images/' . $respostas[0]['categoria_nome'] . '.png" alt="icon"><h1 class="section__topicos__grupo__titulo">' . $respostas[0]['categoria_nome'] . '</h1><hr/>';
    foreach ($respostas as $resposta) {
        if ($categoria != $resposta['categoria_nome']) {
            $categoria = $resposta['categoria_nome'];
            echo '</div><div class="section__topicos__grupo"><img class="section__topicos__grupo__icone" src="assets/images/' . $resposta['categoria_nome'] . '.png" alt="icon"><h1 class="section__topicos__grupo__titulo">' . $resposta['categoria_nome'] . '</h1><hr/>';
        }

        echo '<div class="rating-container">';
        echo '<label class="rating-label"' . ($resposta['resposta'] == NULL ? 'style="color:#f28e8e"' : '') . ' for="' . $resposta['id'] . '">' . $resposta['topico_nome'] . '</label>';

        echo '<div class="rating">';
        echo '<label for="' . $resposta['id'] . '">';
        echo '<input class="super-happy" type="radio" id="' . $resposta['id'] . '" name="' . $resposta['id'] . '" value="1" ' . ($resposta['resposta'] == 1 ? 'checked="checked"' : '') . ' />';
        echo '<svg viewBox="0 0 24 24"><path d="M23,10C23,8.89 22.1,8 21,8H14.68L15.64,3.43C15.66,3.33 15.67,3.22 15.67,3.11C15.67,2.7 15.5,2.32 15.23,2.05L14.17,1L7.59,7.58C7.22,7.95 7,8.45 7,9V19A2,2 0 0,0 9,21H18C18.83,21 19.54,20.5 19.84,19.78L22.86,12.73C22.95,12.5 23,12.26 23,12V10M1,21H5V9H1V21Z" /></svg>';
        echo '</label>';

        echo '<label for="' . $resposta['id'] . 'b">';
        echo '<input class="super-sad" type="radio" id="' . $resposta['id'] . 'b" name="' . $resposta['id'] . '" value="2" ' . ($resposta['resposta'] == 2 ? 'checked="checked"' : '') . ' />';
        echo '<svg viewBox="0 0 24 24"><path d="M19,15H23V3H19M15,3H6C5.17,3 4.46,3.5 4.16,4.22L1.14,11.27C1.05,11.5 1,11.74 1,12V14A2,2 0 0,0 3,16H9.31L8.36,20.57C8.34,20.67 8.33,20.77 8.33,20.88C8.33,21.3 8.5,21.67 8.77,21.94L9.83,23L16.41,16.41C16.78,16.05 17,15.55 17,15V5C17,3.89 16.1,3 15,3Z" /></svg>';
        echo '</label>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
    echo '<div><input class="save-button" type="submit" value="Salvar" name="submit" /></div>';
    echo '<input style="visibility: hidden;" class="save-button" type="submit" value="Salvar" name="submit" />';
    echo '</form>';
    ?>
</section>

<?php
require_once('templates/footer.php');
?>