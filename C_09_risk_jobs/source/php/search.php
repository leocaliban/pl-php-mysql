<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Risk Jobs - Pesquisa</title>
    <meta name="description" content="Risk Jobs" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>

    <?php
    $ordem = $_GET['ordem'];
    $campo_pesquisa = $_GET['pesquisa'];
    $pagina_atual = isset($_GET['page']) ? $_GET['page'] : 1;
    $ANUNCIOS_POR_PAGINA = 5;
    $skip = (($pagina_atual - 1) * $ANUNCIOS_POR_PAGINA);

    echo '<table border="0" cellpadding="2">';

    echo '<tr class="heading">';
    echo gerar_links_ordenacao($campo_pesquisa, $ordem);
    echo '</tr>';

    require_once('constants/connection-vars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    mysqli_set_charset($dbc, 'utf8');

    function gerar_links_ordenacao($campo_pesquisa, $ordem)
    {
        $links_ordenados = '';

        switch ($ordem) {
            case 1:
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=2">Cargo</a></td>';
                $links_ordenados .= '<td>Descrição</td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=3">Estado</a></td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=5">Data de postagem</a></td>';
                break;

            case 3:
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=1">Cargo</a></td>';
                $links_ordenados .= '<td>Descrição</td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=4">Estado</a></td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=5">Data de postagem</a></td>';
                break;

            case 5:
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=1">Cargo</a></td>';
                $links_ordenados .= '<td>Descrição</td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=3">Estado</a></td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=6">Data de postagem</a></td>';
                break;
            default:
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=1">Cargo</a></td>';
                $links_ordenados .= '<td>Descrição</td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=3">Estado</a></td>';
                $links_ordenados .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=5">Data de postagem</a></td>';
                break;
        }

        return $links_ordenados;
    }


    function criar_consulta($campo_pesquisa, $ordem)
    {
        $query = "SELECT * FROM vaga_emprego";
        // * Substitui vírgulas por espaços.
        $pesquisa_limpa =  str_replace(',', ' ', $campo_pesquisa);
        // * Remove os espaços entre as palavras e armazena em array.
        $palavras_pesquisa = explode(' ', $pesquisa_limpa);
        $palavras_pesquisa_final = array();

        if (count($palavras_pesquisa) > 0) {
            foreach ($palavras_pesquisa as $palavra) {
                if (!empty($palavra)) {
                    $palavras_pesquisa_final[] = $palavra;
                }
            }
        }
        $where_list = array();
        if (count($palavras_pesquisa_final) > 0) {
            foreach ($palavras_pesquisa_final as $palavra) {
                $where_list[] = "descricao LIKE '%$palavra%'";
            }
        }

        $where = implode(' OR ', $where_list);

        if (!empty($where)) {
            $query .= " WHERE $where";
        }

        switch ($ordem) {
            case 1:
                $query .= " ORDER BY nome";
                break;
            case 2:
                $query .= " ORDER BY nome DESC";
                break;
            case 3:
                $query .= " ORDER BY estado";
                break;
            case 4:
                $query .= " ORDER BY estado DESC";
                break;
            case 5:
                $query .= " ORDER BY data_postagem";
                break;
            case 6:
                $query .= " ORDER BY data_postagem DESC";
                break;
            default:
                break;
        }
        return $query;
    }

    function criar_links_paginacao($campo_pesquisa, $ordem, $pagina_atual, $numero_paginas)
    {
        $links = '';
        if ($pagina_atual > 1) {
            $links .= '<a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=' . $ordem . '&page=' . ($pagina_atual - 1) . '"> <- </a>';
        } else {
            $links .= '<- ';
        }

        for ($i = 1; $i <= $numero_paginas; $i++) {
            if ($pagina_atual == $i) {
                $links .= ' ' . $i;
            } else {
                $links .= '<a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=' . $ordem . '&page=' . $i . '"> ' . $i . ' </a>';
            }
        }

        if ($pagina_atual < $numero_paginas) {
            $links .= '<a href="' . $_SERVER['PHP_SELF'] . '?pesquisa=' . $campo_pesquisa . '&ordem=' . $ordem . '&page=' . ($pagina_atual + 1) . '"> -> </a>';
        } else {
            $links .= ' ->';
        }
        return $links;
    }

    $query = criar_consulta($campo_pesquisa, $ordem);

    $result = mysqli_query($dbc, $query);
    $total_anuncios_encontrados = mysqli_num_rows($result);
    $numero_paginas = ceil($total_anuncios_encontrados / $ANUNCIOS_POR_PAGINA);

    $query = $query . " LIMIT $skip, $ANUNCIOS_POR_PAGINA";
    $result = mysqli_query($dbc, $query);
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr class="results">';
        echo '<td valign="top" width="20%">' . $row['nome'] . '</td>';
        echo '<td valign="top" width="50%">' . substr($row['descricao'], 0, 100) . '...</td>';
        echo '<td valign="top" width="10%">' . $row['estado'] . '</td>';
        echo '<td valign="top" width="20%">' . substr($row['data_postagem'], 0, 10) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    if ($numero_paginas > 1) {
        echo criar_links_paginacao($campo_pesquisa, $ordem, $pagina_atual, $numero_paginas);
    }
    mysqli_close($dbc);
    ?>



</body>

</html>