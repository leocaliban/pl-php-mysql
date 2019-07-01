<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Elvis Store</title>
    <meta name="description" content="Remover cliente da lista de e-mails" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>

    <img src="assets/images/blankface.jpg" alt="Imagem do Elvis sem rosto." class="elvis-image" />
    <img name="elvislogo" src="assets/images/elvislogo.gif" class="elvis-logo" alt="Make Me Elvis" />
    <p>Selecione os e-mails para remover da lista.</p>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <?php

        $dbc = mysqli_connect('localhost', 'root', 'root', 'php_elvis_store')
            or die('Erro de conexão com MySQL server.');

        if (isset($_POST['submit'])) {
            $quantidade = 0;
            $mensagem = 'O cliente foi removido da sua lista com sucesso!';
            foreach ($_POST['todelete'] as $id) {
                $query = "DELETE FROM email_list WHERE id = $id";

                mysqli_query($dbc, $query)
                    or die('Erro na consulta do banco de dados');

                $quantidade++;
            }

            if ($quantidade > 1) {
                $mensagem = $quantidade . ' clientes foram removidos da lista com sucesso!';
            }
            echo $mensagem . '<br/>';
        }

        $query = "SELECT * FROM email_list";
        $result = mysqli_query($dbc, $query);

        while ($row = mysqli_fetch_array($result)) {
            echo '<input type="checkbox" value="' . $row['id'] . '" name="todelete[]">';
            echo $row['primeiro_nome'];
            echo ' ' . $row['sobrenome'];
            echo ' ' . $row['email'];
            echo '<br />';
        }

        mysqli_close($dbc);
        ?>
        <br />
        <input type="submit" name="submit" value="Remover" />
    </form>

</body>

</html>