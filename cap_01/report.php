<!DOCTYPE html>
<html>

<head>
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

	echo 'Obrigado por submeter o formulário.<br />';
	echo 'Você foi abduzido ' . $quando_ocorreu;
	echo ' e ficou desaparecido por ' . $duracao . '<br />';
	echo 'Descrição: ' . $descricao . '<br />';
	echo 'Seu endereço de email é ' . $email;
	?>
</body>

</html>