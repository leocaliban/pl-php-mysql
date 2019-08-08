<?php
session_start();
require_once('constants/connection-vars.php');

$mensagem_erro = "";
$exibir_erro = false;

if (!isset($_SESSION['id'])) {
    if (isset($_POST['submit'])) {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('Erro de conexão com MySQL server.');

        $usuario = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $senha = mysqli_real_escape_string($dbc, trim($_POST['password']));

        if (!empty($usuario) && !empty($senha)) {
            $query = "SELECT id, username FROM usuario WHERE username = '$usuario' AND password = SHA('$senha')";
            $data = mysqli_query($dbc, $query);

            if (mysqli_num_rows($data) == 1) {
                $row = mysqli_fetch_array($data);

                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                setcookie('id', $row['id'], time() + (60 * 60 * 24 * 30));
                setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));

                // Redirecionar
                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                header('Location: ' . $home_url);
            } else {
                $exibir_erro = true;
                $mensagem_erro = 'Desculpe, você deve digitar um nome e senha válidos para realizar o login.';
            }
        } else {
            $exibir_erro = true;
            $mensagem_erro = 'Desculpe, você deve digitar o seu nome de usuário e a senha para fazer login.';
        }
    }
}

$page_title = 'Login';
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
                    <a class="nav__menu__item__button" href="signup.php">Criar conta</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
if (empty($_COOKIE['id'])) {
    if ($exibir_erro == true) {

        echo '<p class="error">' . $mensagem_erro . '</p>';
    }
    ?>

    <div class="cadastro">
        <div class="formulario">
            <img class="icon" src="assets/images/conversa.png" alt="icon">
            <h1 class="title">Login Mismatch</h1>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="group">
                    <label for="username" class="float-input">
                        <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo ($username); ?>" placeholder="&nbsp;">
                        <span class="label">Nome do usuário</span>
                        <span class="border"></span>
                    </label>
                </div>

                <div class="group">
                    <label for="password" class="float-input">
                        <input type="password" id="password" name="password" placeholder="&nbsp;">
                        <span class="label">Senha</span>
                        <span class="border"></span>
                    </label>
                </div>

                <div class="group">
                    <input class="cadastro-button" type="submit" value="Entrar" name="submit" />
                </div>
            </form>
        </div>
    </div>

<?php
} else {
    echo ('<p class="login">Você já está logado como ' . $_COOKIE['username'] . '!</p>');
}
?>
<?php
require_once('templates/footer.php');
?>