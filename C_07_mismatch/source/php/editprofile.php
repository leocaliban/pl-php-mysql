<?php
require_once('templates/startsession.php');

$page_title = 'Editar Perfil';
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
            <?php
            if (isset($_SESSION['username'])) {
                echo ('<p class="nav__container__user"> - Olá, ' . $_SESSION['username'] . '.</p>');
            }
            ?>
        </div>
        <div class="nav__menu t">

            <div class="nav__menu__mini">
                <div class="nav__menu__mini__row"> </div>
                <div class="nav__menu__mini__row"> </div>
                <div class="nav__menu__mini__row"> </div>
            </div>

            <ul>
                <li class="nav__menu__item">
                    <a class="nav__menu__item__button" href="index.php">Início</a>
                </li>
                <?php
                if (isset($_SESSION['username'])) {
                    ?>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="viewprofile.php">Perfil</a>
                    </li>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="questionnaire.php">Tópicos</a>
                    </li>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="mymismatch.php">MM</a>
                    </li>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="logout.php">Logout</a>
                    </li>
                <?php
                } else {
                    ?>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="login.php">Login</a>
                    </li>
                    <li class="nav__menu__item">
                        <a class="nav__menu__item__button" href="signup.php">Criar conta</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<section class="view-section">
    <h2 class="view-title">Atualizar Perfil</h2>
    <?php
    require_once('constants/app-vars.php');
    require_once('constants/connection-vars.php');

    if (!isset($_SESSION['id'])) {
        echo '<p class="error">Por favor, realize o <a href="login.php">LOGIN</a> para acessar essa página.</p>';
        exit();
    }

    // Conexão com BD
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('Erro de conexão com MySQL server.');

    $id = $_SESSION['id'];

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
                if ($_FILES['nova_foto']['error'] == 0) {
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

            <?php if (!empty($foto_antiga)) {
                echo '<img class="view-image" src="' . MM_UPLOADPATH . $foto_antiga . '" alt="Foto de perfil" />';
            } ?>

            <div class="group">
                <label for="nome" class="float-input">
                    <input type="text" id="nome" name="nome" value="<?php if (!empty($nome)) {
                                                                        echo ($nome);
                                                                    } ?>" placeholder="&nbsp;">
                    <span class="label">Primeiro nome</span>
                    <span class="border"></span>
                </label>
            </div>

            <div class="group">
                <label for="sobrenome" class="float-input">
                    <input type="text" id="sobrenome" name="sobrenome" value="<?php if (!empty($sobrenome)) echo ($sobrenome); ?>" placeholder="&nbsp;">
                    <span class="label">Sobrenome</span>
                    <span class="border"></span>
                </label>
            </div>

            <div class="group">
                <label for="nascimento" class="float-input">
                    <input type="text" id="nascimento" name="nascimento" value="<?php if (!empty($nascimento)) echo $nascimento;
                                                                                else echo 'YYYY-MM-DD'; ?>" placeholder="&nbsp;">
                    <span class="label">Data de nascimento</span>
                    <span class="border"></span>
                </label>
            </div>

            <div class="group">
                <label for="cidade" class="float-input">
                    <input type="text" id="cidade" name="cidade" value="<?php if (!empty($cidade)) echo ($cidade); ?>" placeholder="&nbsp;">
                    <span class="label">Cidade</span>
                    <span class="border"></span>
                </label>
            </div>

            <div class="group">
                <label for="estado" class="float-input">
                    <input type="text" id="estado" name="estado" value="<?php if (!empty($estado)) echo ($estado); ?>" placeholder="&nbsp;">
                    <span class="label">Estado</span>
                    <span class="border"></span>
                </label>
            </div>

            <div class="radio-group">
                <div class="group">
                    <label for="genero" style="color: #747985">Gênero</label>
                </div>
                <div class="select-box">
                    <select id="genero" name="genero">
                        <option value="M" <?php if (!empty($genero) && $genero == 'M') echo 'selected = "selected"'; ?>>Masculino</option>
                        <option value="F" <?php if (!empty($genero) && $genero == 'F') echo 'selected = "selected"'; ?>>Feminino</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="foto_antiga" value="<?php if (!empty($foto_antiga)) echo $foto_antiga; ?>" />


            <div class="file-group">
                <input type="file" id="nova_foto" name="nova_foto" />
                <input class="file-button" value="Escolher foto" type="button">
                <span class="file-info">Envie uma foto</span>
            </div>

            

            <div class="group">
                <input class="cadastro-button" type="submit" value="Salvar perfil" name="submit" />
            </div>
        </fieldset>
    </form>
</section>
<script>
    const uploadButton = document.querySelector('.file-button');
    const fileInfo = document.querySelector('.file-info');
    const realInput = document.getElementById('nova_foto');

    uploadButton.addEventListener('click', () => {
        realInput.click();
    });

    realInput.addEventListener('change', () => {
        const name = realInput.value.split(/\\|\//).pop();
        const truncated = name.length > 20 ?
            name.substr(name.length - 20) :
            name;

        fileInfo.innerHTML = truncated;
    });
</script>
<?php
require_once('templates/footer.php');
?>