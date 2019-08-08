<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <?php echo '<title>Mismatch - ' . $page_title . '</title>' ?>

    <meta name="description" content="Mismatch - <?php echo $page_title ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />

    <script>
        window.onload = function() {
            document.querySelector(".nav__menu__mini").addEventListener("click", function() {
                if (document.querySelector(".nav__menu ul").style.display == 'flex') {
                    document.querySelector(".nav__menu ul").style.display = 'none';
                } else {
                    document.querySelector(".nav__menu ul").style.display = 'flex';
                }
            });
        };
    </script>
</head>

<body>
    <div class="master-container">