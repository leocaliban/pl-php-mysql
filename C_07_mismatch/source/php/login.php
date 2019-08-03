<?php
require_once('constants/connection-vars.php');

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Mismatch"');
    exit('<h2>Mismatch</h2>Desculpe, você deve digitar o nome de usuário e senha válidos para acessar essa página. Se você ainda não é membro, <a href="signup.php">REGISTRE-SE</a>.');
}

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Erro de conexão com MySQL server.');

$usuario = mysqli_real_escape_string($dbc, trim($_SERVER['PHP_AUTH_USER']));
$senha = mysqli_real_escape_string($dbc, trim($_SERVER['PHP_AUTH_PW']));

$query = "SELECT id, username FROM usuario WHERE username = '$usuario' AND password = SHA('$senha')";
$data = mysqli_query($dbc, $query);

if (mysqli_num_rows($data) == 1) {
    $row = mysqli_fetch_array($data);
    $id = $row['id'];
    $nome_usuario = $row['username'];
} else {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Mismatch"');
    exit('<h2>Mismatch</h2>Desculpe, você deve digitar o nome de usuário e senha válidos para acessar essa página. Se você ainda não é membro, <a href="signup.php">REGISTRE-SE</a>.');
}
echo ('<p class="login">Bem vindo ' . $nome_usuario . '!</p>');

?>
