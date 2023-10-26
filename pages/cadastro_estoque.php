<?php
    if(@$_SESSION['login'] == true){
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce | Cadastro</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <?php Painel::googleFonts() ?>
</head>
<body>
    
    <div class="dark"></div>

    <div class="mobileMenu">
        <span>
            <div class="line line1"></div>
            <div class="line line2"></div>
            <div class="line line3"></div>
        </span>
    </div>


    <div class="loader-main">
        <div class="loader">
            <div class="loader-small"></div>
            <div class="loader-large"></div>
        </div>
    </div><!--loader-->

    <aside>
        <?php  
            if(isset($_GET['logout'])){ ?>
            <div class="alertExcluir">
                <h2>Você realmente deseja desconectar?</h2>
                <form method="POST">
                    <input type="submit" name="desconectar" value="SIM">
                    <input type="submit" name="ficar" value="NÃO">
                </form>
            </div>
            <?php
                if(isset($_POST['desconectar'])){
                    session_destroy();
                    setcookie('lembrar',null,-1,'/');
                    header("Location: ".INCLUDE_PATH);
                }

                if(isset($_POST['ficar'])){
                    $url = $_GET['url'];
                    $url = explode('?',$url)[0];
                    header("Location: ".$url);
                }
            ?>
        <?php } ?>
        
        <a href='<?php echo INCLUDE_PATH.'home' ?>' class='subtitle'>Painel</a>
        
        <nav>
            
            <a href="<?php echo INCLUDE_PATH ?>" class='square' ><i class="fa-solid fa-house"></i></a>

            <?php 
                $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.configmenu`");
                $sql->fetchAll();
                $sql->execute();
                foreach ($sql as $key => $value) { ?>
                    

            <a ide='<?php echo $value['categoria_id'] ?>' class='square'><?php echo $value['icon'] ?></a>

            <?php } ?>
            
        </nav>

        <div class="exitCP">
            <h2 class='subtitle-up'>Sair</h2>
            <a href="<?php $url = $_GET['url']; echo $url.'?logout';  ?>" class='subtitle-up'><i class="fa-solid fa-door-closed"></i></a>
        </div><!--exitCP-->
        
    </aside><!--menu-->

    <main>
        <div class="container">
            <h2 class="title w100 blue text-center">Controle de Estoque</h2>
            <h2 class="subtitle text-center">cadastrar Item</h2>
            <div class="content">
                <div class="box">

                    <form id='formEstoque' method='post' action='<?php echo INCLUDE_PATH.'ajax/estoque.php' ?>' enctype="multipart/form-data">
                        <h2><i class="fa-solid fa-box-open"></i> Cadastrar Item</h2>
                        <div class="singleform">
                            <label>Nome do Produto:</label>
                            <input type="text" name="nome">
                        </div>
                        <div class="singleform">
                            <label>Descrição:</label>
                            <textarea  name="desc"></textarea>
                        </div>
                        <div class="singleform">
                            <label>Preço:</label>
                            <input id="precoValor" type="text" name="valor">
                        </div>
                        <div class="singleform">
                            <label>Altura cm:</label>
                            <input type="number" name="altura" min='0' max='999'>
                        </div>
                        <div class="singleform">
                            <label>Largura cm:</label>
                            <input type="number" name="largura" min='0' max='999'>
                        </div>
                        <div class="singleform">
                            <label>Comprimento cm:</label>
                            <input type="number" name="comprimento" min='0' max='999'>
                        </div>
                        <div class="singleform">
                            <label>Peso kg:</label>
                            <input type="number" name="peso" min='0' max='999'>
                        </div>
                        <div class="singleform">
                            <label>Quantidade:</label>
                            <input type="number" name="quantidade" min='1' max='999'>
                        </div>
                        <div class="singleform">
                            <label class='sendPic' for='arquivos'>Upload de fotos <i class="fa-solid fa-cloud"></i></label>
                            <input id='arquivos' multiple type="file" name="imagem[]" >
                            <span id='nomearquivos'></span>
                        </div>
                        <div class="singleform">
                            <input type="submit" name="acao" value="Cadastrar">
                        </div>
                    </form>
                </div>
            </div>
        </div><!--container-->

    </main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.maskMoney.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.form.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/estoque.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/main.js' ?>"></script>
<script>
$(function(){


    $('#precoValor').maskMoney({prefix:'R$: ', allowNegative: false, thousands:'.', decimal:',', affixesStay: false})


//menu
function menu(){
    var btn = $('.square');
    btn.click(function(){
        var id = $(this).attr('ide');
        var caminho = "<?php echo INCLUDE_PATH.'home/'; ?>";

        $.ajax({
            type: 'POST',
            url: 'http://localhost/e-commerce/ajax/menu.php',
            data: {id:id},
            success: function(data){
                $('.newcolumn').remove()
                info = JSON.parse(data);
                $('body').prepend(`
                    <div class='newcolumn'>
                        <h2 class='subtitle-up'>${info.sql.titulo}</h2>
                        ${info.sql.cadastro ? `<a href='${caminho + info.caminho.cadastro}' class='subtitle'>${info.sql.cadastro}</a>` : ''}
                        ${info.sql.estoque ? `<a href='${caminho + info.caminho.visualizar}' class='subtitle'>${info.sql.estoque}</a>` : ''}
                        ${info.sql.editar ? `<a href='${caminho + info.caminho.editar}' class='subtitle'>${info.sql.editar}</a>` : ''}
                        ${info.sql.falta ? `<a href='${caminho + info.caminho.falta}' class='subtitle'>${info.sql.falta}</a>` : ''}

                    </div>
                `);
            }
        });

        btn.removeClass("hovado");
        $(this).addClass("hovado");
    });

}
menu();


});
</script>
</body>
</html>

<?php }else{ ?>
    <h2>Sem login</h2>
<?php } ?>
