<?php
$usuario = 'dana';
$senha = '123123';
if (
    !isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
    || $_SERVER['PHP_AUTH_USER'] != $usuario || $_SERVER['PHP_AUTH_PW'] != $senha
) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Guitar Wars"');
    exit('<h2>Guitar Wars</h2>Desculpe, você deve digitar o nome de usuário e senha válidos para acessar essa página.');
}
?>