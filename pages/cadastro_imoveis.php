<?php
    if(@$_SESSION['login'] == true){
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imoveis | Cadastro</title>
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>">
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
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
            <h2 class="title w100 blue text-center">Gestão de Imóveis</h2>
            <h2 class="subtitle text-center">cadastrar imóveis</h2>
            <div class="content">
                <div class="box">
                    <form id='gestaoimoveis' action="<?php echo INCLUDE_PATH.'ajax/gestaoImoveis.php' ?>" method='post' enctype="multipart/form-data">
                        <div class="singleform">
                            <label>Nome: </label>
                            <input type="text" name='nome'>
                        </div><!--single-form-->
                        <div class="singleform">
                            <label>Descrição: </label>
                            <textarea type="text" name='descricao' maxlength="500"></textarea>
                        </div><!--single-form-->
                        <div class="singleform">
                            <label>Preço: </label>
                            <input type="text" name='valor'>
                        </div><!--single-form-->
                        <div class="singleform">
                            <label class='sendPic' for="arq">Foto <i class="fa-solid fa-cloud-arrow-up"></i></label>
                            <input id='arq' type="file" name='imagem'>
                            <span id='nomearquivos'></span>
                        </div><!--single-form-->
                        <div class="singleform">
                            <input type="submit" name='acao' value="Enviar">
                        </div><!--single-form-->
                    </form><!--form-->
                </div><!--box-->
            </div><!--content-->
        </div><!--container-->

    </main>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/main.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.maskMoney.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.form.min.js' ?>"></script>
<script>
$(function(){


    $('#arq').on('change',function(){ 
        var inupttext = $('#nomearquivos');
        var file = $(this)[0];
        var nome = [];

        var qnt = file.files.length

        for(i = 0; i < qnt; i++){
            nome.push(file.files[i].name)
        }

        inupttext.text(qnt+' arquivos foram selecionados: '+nome.join(', '))
    
    });

$('#gestaoimoveis').ajaxForm({
    dataType: 'JSON',
    success: function(data){
        if(data.sucesso){
            $('.sucessoMain,.errorMain').remove();
            $('#gestaoimoveis').prepend('<div class="sucessoMain"><i class="fa-regular fa-circle-check" style="color: #ffffff;"></i> '+data.msg+'</div>')
            setTimeout(function(){
                $('.sucessoMain').slideUp();
            },6000);
        }else{
            $('.sucessoMain,.errorMain').remove();
            $('#gestaoimoveis').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> '+data.msg+'</div>')
            setTimeout(function(){
                $('.sucessoMain').slideUp();
            },6000);
        }
    },
});

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

$('input[name="valor"]').maskMoney({prefix:'R$: ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true})


});
</script>
</body>
</html>

<?php }else{ ?>
    <h2>Sem login</h2>
<?php } ?>
