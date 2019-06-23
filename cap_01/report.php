<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Aliens Me Abduziram - Denuncie uma Abdução</title>
</head>

<body>
	<h2>Aliens Me Abduziram - Denuncie uma Abdução</h2>
	<?php

	$quando_ocorreu = $_POST['quandoOcorreu'];
	$duracao = $_POST['duracao'];
	$descricao = $_POST['descricaoAlien'];
	$viuFang = $_POST['fang'];
	$email = $_POST['email'];
	$quantidade = $_POST['quantosVistos'];
	$que_fizeram = $_POST['queFizeram'];
	$comentarios = $_POST['outro'];
	$nome = $_POST['primeiroNome'];
	$sobrenome = $_POST['sobrenome'];
	$nome_completo = $nome . ' ' . $sobrenome;

	$assunto = 'Aliens Me Abduziram - Denuncia de Abdução';
	$email_destinatario = 'leocaliban@gmail.com';
	$mensagem =
		"$nome_completo foi abduzido $quando_ocorreu e ficou desaparecido por $duracao.\n" .
		"Número de aliens: $quantidade \n" .
		"Descrição: $descricao \n" .
		"O que eles fizeram? $que_fizeram\n" .
		"Fang estava lá? $viuFang\n" .
		"Comentários adicionais: $comentarios\n";

	mail($email_destinatario, $assunto, $mensagem, 'From: ' . $nome_completo . " <" . $email . "> ");

	$dbc = mysqli_connect('localhost', 'root', 'root', 'php')
		or die('Erro de conexão com MySQL server.');

	$query =
		"INSERT INTO aliens_abducao 
		(primeiro_nome, sobrenome, quando_ocorreu, duracao, quantidade, descricao, que_fizeram, 		viuFang, comentarios, email)
		VALUES
		('$nome', '$sobrenome', '$quando_ocorreu', '$duracao', '$quantidade', '$descricao', 
			'$que_fizeram', '$viuFang', '$comentarios', '$email')";

	$result = mysqli_query($dbc, $query)
		or die('Erro na consulta do banco de dados');

	mysqli_close($dbc);

	echo 'Obrigado por submeter o formulário.<br />';
	echo 'Você foi abduzido em ' . $quando_ocorreu;
	echo ' e ficou desaparecido por ' . $duracao . '<br />';
	echo 'Número de aliens: ' . $quantidade . '<br />';
	echo 'Descrição: ' . $descricao . '<br />';
	echo 'O que eles fizeram? ' . $que_fizeram . '<br />';
	echo 'Fang estava lá? ' . $viuFang . '<br />';
	echo 'Comentários adicionais: ' . $comentarios . '<br />';
	echo 'Seu endereço de email é ' . $email;
	?>
</body>

</html>