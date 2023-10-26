<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>">
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <?php Painel::googleFonts() ?>
    <title>Erro Pagamento</title>
</head>
<body id='error404'>

    
    <div class="container">
        <div class="box text-center column gap15">
            <h2>Ocorreu um erro ao processar o pagamento!</h2>
            <p class='subtitle'>Infelizmente não foi possível concluir do pagamento. <b>🙁</b></p>
            <a href="<?php echo INCLUDE_PATH ?>">voltar ao início</a>
        </div>
    </div>

<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
</body>
    
</html>