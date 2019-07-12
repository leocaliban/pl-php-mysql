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
    <h2>Guitar Wars - Painel de controle</h2>
    <p>Abaixo estão todos os participantes do ranking. Use essa página pare remover resgistros.</p>
    <hr />


    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('Erro de conexão com MySQL server.');

    $query = "SELECT * FROM guitarwars ORDER BY pontuacao DESC, data ASC";

    $data = mysqli_query($dbc, $query);

    echo '<table>';
    while ($row = mysqli_fetch_array($data)) {
        echo '<tr><td><strong>' . $row['nome'] . '</strong> </td>';
        echo '<td>' . $row['data'] . '</td>';
        echo '<td>' . $row['pontuacao'] . '</td>';

        echo '<td><a href="removescore.php?id=' . $row['id'] . '&amp;data=' . $row['data'] . '&amp;nome=' . $row['nome'] . '&amp;pontuacao=' . $row['pontuacao'] . '&amp;imagem=' . $row['imagem'] . '">Remover</a></td></tr>';
    }
    echo '</table>';

    mysqli_close($dbc);
    ?>
</body>

</html>