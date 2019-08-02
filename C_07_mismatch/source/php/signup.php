<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Mismatch - Cadastro</title>
    <meta name="description" content="Mismatch - Cadastro" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>

    <header>
        <div class="text">
            <h2>Mismatch</h2>
            <h3>Cadastro de usuário</h3>
        </div>
    </header>

    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');

    // Conexão com BD
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('Erro de conexão com MySQL server.');

    if (isset($_POST['submit'])) {
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
        $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

        if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
            $query = "SELECT * FROM usuario WHERE username = '$username'";

            $data = mysqli_query($dbc, $query);

            if (mysqli_num_rows($data) == 0) {
                $query_insert = "INSERT INTO usuario (username, password, data_cadastro) VALUES ('$username', SHA('$password1'), NOW())";

                mysqli_query($dbc, $query_insert);

                echo '<p>Sua conta foi criada com sucesso! <a href="editprofile.php">Atualize seu perfil.</a></p>';

                mysqli_close($dbc);
                exit();
            } else {
                echo '<p class="error">Já existe um usuário com esse nome, escolha um diferente por favor.</p>';
                $username = "";
            }
        } else {
            echo '<p class="error">Preencha todos os campos.</p>';
        }
    }

    mysqli_close($dbc);
    ?>

    <p>Por favor informe seu nome e senha para se cadastrar no Mismatch.</p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Dados de login</legend>
            <label for="username">Nome do usuário</label>
            <input type="text" id="username" name="username" value="<?php if (!empty($username)) { echo ($username);} ?>" /> 
            <br />

            <label for="password1">Senha</label>
            <input type="password" id="password1" name="password1" />
            <br />

            <label for="password2">Confirme a senha</label>
            <input type="password" id="password2" name="password2" />
            <br />
        </fieldset>
        <input type="submit" value="Cadastrar-se" name="submit" />
    </form>
</body>

</html>