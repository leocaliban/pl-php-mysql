<?php
require_once('authorize.php');
?>
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
    <h2>Guitar Wars - Aprovar pontuação</h2>
    <hr />

    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');

    if (isset($_GET['id']) && isset($_GET['data']) && isset($_GET['nome']) && isset($_GET['pontuacao'])) {

        $id = $_GET['id'];
        $nome = $_GET['nome'];
        $pontuacao = $_GET['pontuacao'];
        $data = $_GET['data'];
        $imagem = $_GET['imagem'];
    } else if (isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['pontuacao'])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $pontuacao = $_POST['pontuacao'];
    } else {
        echo '<p class="error">Desculpe, nenhuma pontuação para aprovar.</p>';
    }

    // Formulário submetido
    if (isset($_POST['submit'])) {
        if ($_POST['confirm'] == 'Sim') {
            // Aprovar uma pontuação
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or die('Erro de conexão com MySQL server.');

            $query = "UPDATE guitarwars SET aprovado = 1 WHERE id = $id LIMIT 1";

            mysqli_query($dbc, $query);
            mysqli_close($dbc);

            // Confirmação da aprovação
            echo '<p>A pontuação de ' . $nome . ' com ' . $pontuacao . ' pontos, foi aprovada com sucesso.';
        } else {
            echo '<p class="error">Ocorreu um erro e a pontuação não foi aprovada.</p>';
        }
    } else if (isset($id) && isset($nome) && isset($data) && isset($pontuacao)) {
        echo '<p>Você tem certeza que quer aprovar esse registro?</p>';
        echo '<p><strong>Nome: </strong>' . $nome . '<br /><strong>Data: </strong>' . $data .
            '<br /><strong>Pontuação: </strong>' . $pontuacao . '</p>';
        echo '<form method="post" action="aprove.php">';
        echo '<img src="' . MY_UPLOADPATH . $imagem . '" width="160" alt="Imagem do usuário" /><br />';
        echo '<input type="radio" name="confirm" value="Sim" /> Sim ';
        echo '<input type="radio" name="confirm" value="Não" checked="checked" /> Não <br /><br />';
        echo '<input type="submit" value="Aprovar" name="submit" />';

        echo '<input type="hidden" name="id" value="' . $id . '" />';
        echo '<input type="hidden" name="nome" value="' . $nome . '" />';
        echo '<input type="hidden" name="pontuacao" value="' . $pontuacao . '" />';
        echo '</form>';
    }
    echo '<p><a href="admin.php">&lt;&lt; Voltar para  o Painel de controle</a></p>';
    ?>
</body>

</html>