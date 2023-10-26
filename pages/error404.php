<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | ERROR</title>
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>">
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <?php Painel::googleFonts() ?>
</head>
<body id='error404'>
    <div  class="container">
        <div class="box text-center flex column gap15">
            <h2 class='title-down'><i class="fa-solid fa-triangle-exclamation"></i> Ocorreu um erro! <i class="fa-solid fa-triangle-exclamation"></i></h2>
            <p class='subtitle'>404 - Não foi possível localizar a página <b>🙁</b></p>
            <a href="<?php echo INCLUDE_PATH ?>">voltar ao início</a>
        </div>
    </div>
</body>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>

</html>