<?php
require_once('constants/connection-vars.php');

$mensagem_erro = "";
$exibir_erro = false;

if (!isset($_COOKIE['id'])) {
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

                setcookie('id', $row['id']);
                setcookie('username', $row['username']);
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
?>

<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Mismatch - Login</title>
    <meta name="description" content="Mismatch - Login" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
    <nav>
        <a class="menu" href="index.php">Início</a>
        <a class="menu" href="signup.php">Criar conta</a>
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
    <footer class="footer">DESIGNED BY <strong>Leocaliban</strong> &copy; 2019</footer>
</body>

</html>