<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Elvis Store - Enviar E-mail</title>
    <meta name="description" content="Enviar lista de e-mails" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
    <img src="assets/images/blankface.jpg" alt="Imagem do Elvis sem rosto." class="elvis-image" />
    <img name="elvislogo" src="assets/images/elvislogo.gif" class="elvis-logo" alt="Make Me Elvis" />
    <p>
        <strong>Privado:</strong> Apenas para uso de Elmer.<br />
        Escreva e envie um e-mail para a sua lista de contatos.
    </p>
    <br />
    <?php

    if (isset($_POST['submit'])) {
        $email = 'nazzawd@gmail.com';
        $assunto = $_POST['assunto'];
        $mensagem = $_POST['mensagem'];

        $exibir_formulario = false;

        if (empty($assunto) && empty($mensagem)) {
            echo 'Você esqueceu de informar o assunto e a mensagem do e-mail <br />';
            $exibir_formulario = true;
        }

        if (empty($assunto) && (!empty($mensagem))) {
            echo 'Você esqueceu de informar o assunto do e-mail <br />';
            $exibir_formulario = true;
        }

        if ((!empty($assunto)) && empty($mensagem)) {
            echo 'Você esqueceu de informar a mensagem do e-mail <br />';
            $exibir_formulario = true;
        }
    } else {
        $exibir_formulario = true;
    }

    if ((!empty($assunto)) && (!empty($mensagem))) {
        $dbc = mysqli_connect('localhost', 'root', 'root', 'php_elvis_store')
            or die('Erro de conexão com MySQL server.');

        $query = "SELECT * FROM email_list";

        $result = mysqli_query($dbc, $query)
            or die('Erro na consulta do banco de dados');

        while ($row = mysqli_fetch_array($result)) {

            $primeiro_nome = $row['primeiro_nome'];
            $sobrenome = $row['sobrenome'];

            $mensagem_email = "Olá, $primeiro_nome $sobrenome, \n $mensagem";

            $email_destinatario = $row['email'];

            mail($email_destinatario, $assunto, $mensagem_email, 'From: ' . 'Elvis Store' . " <" . $email . "> ");

            echo 'E-mail enviado com sucesso para: ' . $email_destinatario . '<br/ >';
        }
        mysqli_close($dbc);
    }

    if ($exibir_formulario) {
        ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="assunto">Assunto do e-mail:</label><br />
            <input id="assunto" name="assunto" type="text" size="30" value="<?php echo $assunto; ?>" />
            <br />
            <label for="mensagem">Corpo do e-mail:</label><br />
            <textarea id="mensagem" name="mensagem" rows="8" cols="40"><?php echo $mensagem; ?></textarea><br />
            <input type="submit" name="submit" value="Enviar" />
        </form>

    <?php
    }
    ?>

</body>

</html>