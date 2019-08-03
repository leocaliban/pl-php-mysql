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

                header('Location: editprofile.php'); 

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

    <div class="cadastro">
        <div class="formulario">
            <img class="icon" src="assets/images/conversa.png" alt="icon">
            <h1 class="title">Cadastro Mismatch</h1>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <div class="group">
                    <label for="username" class="float-input">
                        <input type="text" id="username" name="username" value="<?php if (!empty($username)) {
                                                                                    echo ($username);
                                                                                } ?>" placeholder="&nbsp;">
                        <span class="label">Nome do usuário</span>
                        <span class="border"></span>
                    </label>
                </div>

                <div class="group">
                    <label for="password1" class="float-input">
                        <input type="password" id="password1" name="password1" placeholder="&nbsp;">
                        <span class="label">Senha</span>
                        <span class="border"></span>
                    </label>
                </div>

                <div class="group">
                    <label for="password2" class="float-input">
                        <input type="password" id="password2" name="password2" placeholder="&nbsp;">
                        <span class="label">Confirme a senha</span>
                        <span class="border"></span>
                    </label>
                </div>
                <div class="group">
                    <input class="cadastro-button" type="submit" value="Cadastrar-se" name="submit" />
                </div>
            </form>
        </div>

    </div>
    <footer class="footer">DESIGNED BY <strong>Leocaliban</strong> &copy; 2019</footer>
</body>

</html>