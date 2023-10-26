<?php
    if(@$_SESSION['login'] == true){
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce | Editar</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/magnific-popup.css' ?>">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <?php Painel::googleFonts() ?>
</head>
<body>

    <?php
        if(isset($_GET['delete']) && isset($_GET['id'])){

    ?>
        <div class="alertExcluir">
            <h2>Você realmente quer EXCLUIR este item permanentemente?</h2>
            <form method="POST">
                <input type="submit" name="yes" value="SIM">
                <input type="submit" name="no" value="NÃO">
            </form>
        </div>
        
        <?php
            if(isset($_POST['yes'])){

                $sql = MySql::conectar()->prepare("DELETE FROM `tb_admin.estoque` WHERE id = ?");
                if($sql->execute(array($_GET['id']))){

                    $imagens = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque-imagens` WHERE produto_id = ? ");
                    $imagens->fetchAll();
                    $imagens->execute(array($_GET['id']));

                    foreach ($imagens as $key => $value) {
                        if(Painel::deleteFile($value['imagem'])){
                            $sucesso = true;
                        }else{
                            $sucesso = false;
                        }
                    }

                    $deleteImg = MySql::conectar()->prepare("DELETE FROM `tb_admin.estoque-imagens` WHERE produto_id = ?");
                    $deleteImg->execute(array($_GET['id']));

                    header("Location: ".INCLUDE_PATH.'home/view_estoque');
                    

                }else{
                    Painel::alert("error","Algo deu errado :(");
                }


            }
            if(isset($_POST['no'])){
                header("Location: ".INCLUDE_PATH.'editar_estoque?id='.$_GET['id']);
                exit;
            }
        ?>
    <?php } ?>


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
            <h2 class="title w100 blue text-center">Controle de Estoque</h2>
            <h2 class="subtitle text-center">editar Item</h2>
            <div class="content">
               <div class="box">
                    <?php
                    if(isset($_GET['id'])){   
                        $id = $_GET['id'];
                        $value = Painel::fetch('tb_admin.estoque',"WHERE id = ?",$id); 
                    ?>  
                        <div class="flex gap15">
                            <h2 class='subtitle-up'><i class="fa-solid fa-boxes-packing"></i> Editar item</h2>
                            <a class='excluiBtn' href="<?php echo INCLUDE_PATH.'editar_estoque?id='.$_GET['id'].'&delete' ?>">DELETAR ITEM</a>
                        </div><!--flex-->
                        <form id='editarEstoque' method='post' action='<?php echo INCLUDE_PATH.'ajax/editar_estoque.php' ?>'>
                            <div class="singleform">
                                <label>Nome do Produto:</label>
                                <input type="text" name="nome" value="<?php echo $value['nome'] ?>">
                            </div>
                            <div class="singleform">
                                <label>Descição:</label>
                                <textarea name="desc" ><?php echo $value['descricao'] ?></textarea>
                            </div>
                            <div class="singleform">
                                <label>Preço:</label>
                                <input type="text" name="valor" value="<?php echo $value['preco'] ?>">
                            </div>
                            <div class="singleform">
                                <label>Altura cm:</label>
                                <input type="number" name="altura" min='0' max='999' value="<?php echo $value['altura'] ?>">
                            </div>
                            <div class="singleform">
                                <label>Largura cm:</label>
                                <input type="number" name="largura" min='0' max='999' value="<?php echo $value['largura'] ?>">
                            </div>
                            <div class="singleform">
                                <label>Comprimento cm:</label>
                                <input type="number" name="comprimento" min='0' max='999' value="<?php echo $value['comprimento'] ?>">
                            </div>
                            <div class="singleform">
                                <label>Peso kg:</label>
                                <input type="number" name="peso" min='0' max='999' value="<?php echo $value['peso'] ?>">
                            </div>
                            <div class="singleform">
                                <label>Quantidade:</label>
                                <input type="number" name="quantidade" min='0' max='999' value="<?php echo $value['quantidade'] ?>">
                            </div>
                            <div class="singleform">
                                <label class='sendPic' for='arquivos'>Upload de fotos <i class="fa-solid fa-cloud"></i></label>
                                <input id='arquivos' multiple type="file" name="imagem[]" >
                                <span id='nomearquivos'></span>
                            </div>
                            <div class="singleform">
                                <input type="hidden" name='id' value="<?php echo $value['id'] ?>">
                                <input type="submit" name="acao" value="Atualizar">
                            </div>
                        </form>
                        <h2><i class="fa-regular fa-images"></i> Imagens cadastradas:</h2>
                        <div class="slider">
                            <?php
                                //pegar imagens
                                $imagem = Painel::imagesEstoque($value['id']);
                                if($imagem->rowCount() > 0){
                                foreach ($imagem as $key => $image) {
                               
                            ?>
                                <div class="singleslider" style="background-image: url('<?php echo INCLUDE_PATH.'uploads/'.$image['imagem'] ?>')"></div>
                            <?php } }else{ ?>
                                <div class="singleslider" style="background-image: url('https://cdn1.staticpanvel.com.br/produtos/15/produto-sem-imagem.jpg')"></div>
                            <?php } ?>
                        </div>


                        <div class="bullets">
                            <ul>
                                <li class="prev">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </li>
                                <li class="next">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </li>
                            </ul>
                        </div>

                        <form id='thumb' method='POST'>
                            <input type="submit" name='thumb' value='Definir como capa'>
                            <input type="submit" name='thumb-remove' value='Excluir imagem'>
                        </form>
                    <?php }else{ ?>
                        <h2 class='editNoId'><i class="fa-solid fa-triangle-exclamation" style="color: #ffffff;"></i> É preciso selecionar um item antes de editar! <i class="fa-solid fa-triangle-exclamation"></i></h2>
                        
                    <?php } ?>

               </div><!--box-->
            </div><!--content-->
        </div><!--container-->

    </main>



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src='<?php echo INCLUDE_PATH."js/jquery.magnific-popup.min.js" ?>'></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.form.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/estoque.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/main.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.maskMoney.min.js' ?>"></script>
<script>
$(function(){


    $('input[name="valor"]').maskMoney({prefix:'R$: ', allowNegative: false, thousands:'.', decimal:',', affixesStay: false})

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

$('.slider').slick({
    arrows: true,
    dots: true,
    nextArrow: $('.next'),
    prevArrow: $('.prev'),
    responsive: [
        {
            breakpoint: 501,
            settings: { 
                dots: true,
                arrows: false,
            }
        }
    ],
});

$('input[name="thumb"]').click(function(){
    imagem = $('.slick-current').css("background-image").split('/')[5].split('"')[0]
    caminho = "<?php echo INCLUDE_PATH.'ajax/updateImagem.php' ?>"
    id = "<?php echo @$_GET['id']; ?>"

    $.ajax({
        type: "POST",
        url: caminho,
        data: {imagem:imagem, id:id},
        success: function(data){
            data = JSON.parse(data)
            if(data.sucesso){
                //sucesso
                $('.sucessoMain,.errorMain').remove();
                $('#thumb').prepend('<div class="sucessoMain"><i class="fa-regular fa-circle-check" style="color: #ffffff;"></i> '+data.msg+'</div>')
                setTimeout(function(){
                    $('.sucessoMain').stop().slideUp();
                },6000);
            }else{
                //error
                $('.sucessoMain,.errorMain').remove();
                $('#thumb').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> '+data.msg+'</div>')
                setTimeout(function(){
                    $('.sucessoMain').slideUp();
                },6000);
            }
        }
    })

    return false
});


function deleteImagem(){

    $('input[name="thumb-remove"]').click(function(){
        
        var path = "<?php echo INCLUDE_PATH.'ajax/deleteCapa.php' ?>"

        var imagemAtual = $('.slick-current').css("background-image").split('/')[5].split('"')[0]
        var id = "<?php echo @$_GET['id']; ?>"


        $('body').append(`
            <div class="alertExcluir">
            <h2>Você deseja realmente deletar esta imagem?</h2>
            <form id='alertDelete' method="POST">
                <a id='DELETEYES'>SIM</a>
                <a href="<?php echo INCLUDE_PATH.'editar_estoque?id=' ?>`+id+`">NÃO</a>
            </form>
            </div>
        `)

        $('#DELETEYES').click(function(){
            $.ajax({
            type: "POST",
            url: path,
            data: {name:imagemAtual, id:id},
            success: function(data){
                data = JSON.parse(data)
                if(data.sucesso){
                    $('.alertExcluir').remove()
                    $('.sucessoMain,.errorMain').remove();
                    $('#thumb').prepend('<div class="sucessoMain"><i class="fa-regular fa-circle-check" style="color: #ffffff;"></i> '+data.msg+'</div>')
                    setTimeout(function(){
                        $('.sucessoMain').stop().slideUp();
                    },3000);
                    setTimeout(() => {
                        window.location.href = "<?php echo INCLUDE_PATH.'editar_estoque?id='.@$_GET['id'];  ?>"
                    },3000);
                }else{
                    $('.alertExcluir').remove()
                    $('.sucessoMain,.errorMain').remove();
                    $('#thumb').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> '+data.msg+'</div>')
                    setInterval(function(){
                        $('.sucessoMain').stop().slideUp();
                    },3000);
                }
            }
        })
        });
        return false
    });
}

deleteImagem();

});
</script>

</body>
</html>

<?php }else{ ?>
    <h2>Sem login</h2>
<?php } ?>

