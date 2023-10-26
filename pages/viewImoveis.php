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
    
    <?php
        if(isset($_GET['excluir']) && isset($_GET['id'])){

    ?>
        <div class="alertExcluir">
            <h2>Você deseja realmente deletar este item permanentemente?</h2>
            <form method="POST">
                <input type="submit" name="yes" value="SIM">
                <input type="submit" name="no" value="NÃO">
            </form>
        </div>
        
        <?php
            if(isset($_POST['yes'])){
                $id = $_GET['id'];
                $imagem = Painel::fetch("tb_admin.gestaoimoveis","WHERE id = ?","$id")['imagem'];
                
                Painel::deleteFile($imagem);

                $sql = MySql::conectar()->prepare("DELETE FROM `tb_admin.gestaoimoveis` WHERE id = ?");
                if($sql->execute(array($id))){
                    Painel::alert("sucesso","Item deletado com sucesso!");

                    header("Location: ".INCLUDE_PATH.'home/viewImoveis');
                    die();
                }else{
                    Painel::alert("error","Não foi possível deletar o item!");
                }


            }
            if(isset($_POST['no'])){
                header("Location: ".INCLUDE_PATH.'home/viewImoveis');
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
            <h2 class="title w100 blue text-center">Gestão de Imóveis</h2>
            <h2 class="subtitle text-center">visualizar imóveis</h2>
            <div class="content">
                <div id='box' class="box">
                    <div id='sortable' class="flex gap15 wrap">
                        <?php 
                        $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.gestaoimoveis` ORDER BY `posicao` DESC ");
                        $sql->fetchAll();
                        $sql->execute();
                        foreach ($sql as $key => $value) { ?>

                            <div id="item-<?php echo $value['id'] ?>" class="single-view">
                                <div class="topImage" style="background-image: url('<?php echo INCLUDE_PATH.'uploads/'.$value['imagem'] ?>')"></div>
                                <div class="singlecontent">

                                    <p><b>Nome:</b> <?php echo $value['nome'] ?></p>
                                    <a class='viewBtn' href="<?php echo INCLUDE_PATH.'home/view_imovel?id='.$value['id']; ?>"><i class="fa-regular fa-eye"></i> Visualizar</a>
                                    <div class="flex mt-10 gap35 jc-center">
                                        <a href="<?php echo INCLUDE_PATH.'home/editar_imovel?id='.$value['id']; ?>" class="editBtn">Editar</a>
                                        <a href="<?php echo INCLUDE_PATH.'home/viewImoveis?id='.$value['id'].'&excluir'; ?>" class='excluiBtn'>Excluir</a>
                                    </div>
                                </div><!--singlecontent-->
                            </div><!--single-view-->

                        <?php } ?>
                    </div><!--flex-->
                </div><!--box-->
            </div><!--content-->
        </div><!--container-->

    </main>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/main.js' ?>"></script>
<script>
$(function(){

    


    $('#sortable').sortable({
        start: function(){
            var el = $(this);
            el.find('.single-view').css("border",'dashed 2px #252525');
            $('#box').css("padding","30px 5%")
        },
        update: function(){
            var info = $(this).sortable('serialize')
            var el = $(this);
            el.css("padding",'0').css('gap','15px');
            el.find('.single-view').css("border",'solid 2px rgb(114, 152, 255)');
            $('#box').css("padding","20px 2%")
            $.ajax({
                type: "POST",
                url: "<?php echo INCLUDE_PATH.'ajax/ordenar.php'; ?>",
                data: {info:info},
                success: function(data){
                    info = JSON.parse(data);

                },
            });
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


});
</script>
</body>
</html>


<?php }else{ ?>
    <h2>Sem login</h2>
<?php } ?>
