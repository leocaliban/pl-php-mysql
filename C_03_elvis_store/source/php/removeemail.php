<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Elvis Store</title>
    <meta name="description" content="Inscrever-se na lista de e-mails" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>

    <?php

    $email = $_POST['email'];

    $dbc = mysqli_connect('localhost', 'root', 'root', 'php_elvis_store')
        or die('Erro de conexão com MySQL server.');

    $query = "DELETE FROM email_list WHERE email = '$email'";

    mysqli_query($dbc, $query)
        or die('Erro na consulta do banco de dados');

    echo 'Você foi removido com sucesso: ' . $email;

    mysqli_close($dbc);
    ?>

</body>

</html>