<?php

$page_title = 'Cadastro';
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
        </div>
        <div class="nav__menu">
            <div class="nav__menu__mini">
                <div class="nav__menu__mini__row"> </div>
                <div class="nav__menu__mini__row"> </div>
                <div class="nav__menu__mini__row"> </div>
            </div>
            <ul>
                <li class="nav__menu__item">
                    <a class="nav__menu__item__button" href="index.php">Início</a>
                </li>

                <li class="nav__menu__item">
                    <a class="nav__menu__item__button" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
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
            echo '<p class="success">Conta criada com sucesso, realize o <a href="login.php"><strong>Login agora</strong></a>, e complete seu cadastro!</p><div class="cadastro"></div>';

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
                    <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo ($username); ?>" placeholder="&nbsp;">
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
<?php
require_once('templates/footer.php');
?>