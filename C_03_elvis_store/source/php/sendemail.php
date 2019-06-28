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

    <?php

    $email = $_POST['nazzawd@gmail.com'];
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];

    $dbc = mysqli_connect('localhost', 'root', 'root', 'php_elvis_store')
        or die('Erro de conexão com MySQL server.');

    $query =
        "SELECT * FROM email_list";

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

    ?>

</body>

</html>