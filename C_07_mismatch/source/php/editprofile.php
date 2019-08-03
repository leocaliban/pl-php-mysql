<!DOCTYPE html>
<?php
require_once('login.php');
?>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Mismatch - Editar Perfil</title>
    <meta name="description" content="Mismatch - Editar Perfil" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>
    <h2>Mismatch - Editar Perfil</h2>

    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');


    // Conexão com BD
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('Erro de conexão com MySQL server.');

    if (isset($_POST['submit'])) {
        $nome = mysqli_real_escape_string($dbc, trim($_POST['nome']));
        $sobrenome = mysqli_real_escape_string($dbc, trim($_POST['sobrenome']));
        $genero = mysqli_real_escape_string($dbc, trim($_POST['genero']));
        $nascimento = mysqli_real_escape_string($dbc, trim($_POST['nascimento']));
        $cidade = mysqli_real_escape_string($dbc, trim($_POST['cidade']));
        $estado = mysqli_real_escape_string($dbc, trim($_POST['estado']));

        $foto_antiga = mysqli_real_escape_string($dbc, trim($_POST['foto_antiga']));

        $nova_foto = mysqli_real_escape_string($dbc, trim($_FILES['nova_foto']['name']));
        $tipo_nova_foto = $_FILES['nova_foto']['type'];
        $tamanho_nova_foto = $_FILES['nova_foto']['size'];

        list($nova_foto_width, $nova_foto_height) = getimagesize($_FILES['nova_foto']['tmp_name']);
        $error = false;

        if (!empty($nova_foto)) {
            if ((($tipo_nova_foto == 'image/gif') || ($tipo_nova_foto == 'image/jpeg') || ($tipo_nova_foto == 'image/pjpeg') || ($tipo_nova_foto == 'image/png')) && ($tamanho_nova_foto > 0) && ($tamanho_nova_foto <= MM_MAX_FILE_SIZE) && ($nova_foto_width <= MM_MAX_IMG_WIDTH) && ($nova_foto_height <= MM_MAX_IMG_HEIGHT)
            ) {
                if ($_FILES['file']['error'] == 0) {
                    $target = MM_UPLOADPATH . basename($nova_foto);

                    if (move_uploaded_file($_FILES['nova_foto']['tmp_name'], $target)) {

                        if (!empty($foto_antiga) && ($foto_antiga != $nova_foto)) {
                            @unlink(MM_UPLOADPATH . $foto_antiga);
                        }
                    } else {
                        @unlink($_FILES['nova_foto']['tmp_name']);
                        $error = true;
                        echo '<p class="error">Desculpe, ocorreu um problema no upload da imagem.</p>';
                    }
                }
            } else {
                @unlink($_FILES['nova_foto']['tmp_name']);
                $error = true;
                echo '<p class="error">Sua foto deve ser do tipo GIF, JPEG, ou PNG e seu tamanho deve ser menor que ' . (MM_MAX_FILE_SIZE / 1024) .
                    ' KB e ' . MM_MAX_IMG_WIDTH . 'x' . MM_MAX_IMG_HEIGHT . ' de dimensão.</p>';
            }
        }

        if (!$error) {
            if (!empty($nome) && !empty($sobrenome) && !empty($genero) && !empty($nascimento) && !empty($cidade) && !empty($estado)) {

                if (!empty($nova_foto)) {
                    $query = "UPDATE usuario SET nome = '$nome', sobrenome = '$sobrenome', genero = '$genero', " .
                        " nascimento = '$nascimento', cidade = '$cidade', estado = '$estado', imagem = '$nova_foto' WHERE id = '$id'";
                } else {
                    $query = "UPDATE usuario SET nome = '$nome', sobrenome = '$sobrenome', genero = '$genero', " .
                        " nascimento = '$nascimento', cidade = '$cidade', estado = '$estado' WHERE id = '$id'";
                }
                mysqli_query($dbc, $query);

                echo '<p>Seu perfil foi atualizado com sucesso. Gostaria de <a href="viewprofile.php">ver seu perfil</a>?</p>';

                mysqli_close($dbc);
                exit();
            } else {
                echo '<p class="error">Você deve preencher todos os dados do perfil.</p>';
            }
        }
    } else {
        $query = "SELECT nome, sobrenome, genero, nascimento, cidade, estado, imagem FROM usuario WHERE id = '$id'";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($data);

        if ($row != NULL) {
            $nome = $row['nome'];
            $sobrenome = $row['sobrenome'];
            $genero = $row['genero'];
            $nascimento = $row['nascimento'];
            $cidade = $row['cidade'];
            $estado = $row['estado'];
            $foto_antiga = $row['imagem'];
        } else {
            echo '<p class="error">Ocorreu um problema ao carregar seus dados.</p>';
        }
    }
    mysqli_close($dbc);

    ?>

    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAX_FILE_SIZE; ?>" />
        <fieldset>
            <legend>Informações Pessoais</legend>
            <label for="nome">Primeiro nome:</label>
            <input type="text" id="nome" name="nome" value="<?php if (!empty($nome)) echo $nome; ?>" /><br />
            <label for="sobrenome">Sobrenome:</label>
            <input type="text" id="sobrenome" name="sobrenome" value="<?php if (!empty($sobrenome)) echo $sobrenome; ?>" /><br />
            <label for="genero">Gênero:</label>
            <select id="genero" name="genero">
                <option value="M" <?php if (!empty($genero) && $genero == 'M') echo 'selected = "selected"'; ?>>Masculino</option>
                <option value="F" <?php if (!empty($genero) && $genero == 'F') echo 'selected = "selected"'; ?>>Feminino</option>
            </select><br />
            <label for="nascimento">Data de nascimento:</label>
            <input type="text" id="nascimento" name="nascimento" value="<?php if (!empty($nascimento)) echo $nascimento;
                                                                        else echo 'YYYY-MM-DD'; ?>" /><br />
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php if (!empty($cidade)) echo $cidade; ?>" /><br />
            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php if (!empty($estado)) echo $estado; ?>" /><br />
            <input type="hidden" name="foto_antiga" value="<?php if (!empty($foto_antiga)) echo $foto_antiga; ?>" />
            <label for="nova_foto">Foto de perfil:</label>
            <input type="file" id="nova_foto" name="nova_foto" />
            <?php if (!empty($foto_antiga)) {
                echo '<img class="profile" src="' . MM_UPLOADPATH . $foto_antiga . '" alt="Foto de perfil" />';
            } ?>
        </fieldset>
        <input type="submit" value="Salvar perfil" name="submit" />
    </form>
</body>

</html>