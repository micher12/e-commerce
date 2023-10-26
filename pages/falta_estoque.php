<?php
    if(@$_SESSION['login'] == true){
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce | Falta</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/magnific-popup.css' ?>">
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
                header("Location: ".INCLUDE_PATH.'falta_estoque');
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

    <?php
        if(isset($_POST['acao'])){
            $quantidade = $_POST['quantidade'];
            $id = $_POST['id'];
            if($quantidade > 0){
                if(Painel::updateStoque($id,$quantidade)){
                    Painel::alert("sucesso",'Atualizado com sucesso!');
                }else{
                    Painel::alert("error","Algo deu errado!");
                }
            }else{
                Painel::alert("error","Você precisa colocar um valor maior do que 0!"); 
            }
            
        }
    
    ?>

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
            <h2 class="subtitle text-center">itens em falta</h2>
            <div class="content">

                <div class="box">
                    <h2><i class="fa-solid fa-dolly"></i> Itens em falta do estoque: </h2>
                    <div class="group-view">
                        <?php
                            
                            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque` WHERE quantidade = ?");
                            $sql->fetchAll();
                            $sql->execute(array(0));
                            if($sql->rowCount() == 1){
                                echo '<h2 class="fullAlter"><i class="fa-solid fa-triangle-exclamation" style="color: #ffffff;"></i> Você tem itens faltando no estoque!</h2>';
                            }else{
                                echo '<h2 class="fullAlterS"><i class="fa-regular fa-circle-check" style="color: #ffffff;"></i> Nenhum item em falta.</h2>';
                            }

                            foreach ($sql as $key => $value) {
                                $imgname = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque-imagens` WHERE produto_id = ? ORDER BY `prioridade` DESC LIMIT 1");
                                $imgname->execute(array($value['id']));
                                if($imgname->rowCount() > 0){
                                    $imagemEncontrada = true;
                                }else{
                                    $imagemEncontrada = false;
                                }
                                $imagem = $imgname->fetch();

                        ?>

                        <div class="single_view">
                            <?php 
                            if ($imagemEncontrada){ ?>
                                    <a id="popup" href="<?php echo INCLUDE_PATH.'uploads/'.$imagem['imagem']; ?>">
                                    <img src="<?php echo INCLUDE_PATH.'uploads/'.$imagem['imagem']; ?>"  class="headerImage">
                                </a>
                            
                            <?php }else{ ?>
                                
                                <a id="popup" href="https://cdn1.staticpanvel.com.br/produtos/15/produto-sem-imagem.jpg">
                                <img src="https://cdn1.staticpanvel.com.br/produtos/15/produto-sem-imagem.jpg"  class="headerImage">
                                </a>

                            <?php } ?>    
                            <div class="singlecontent">
                                <h2>Nome: <b><?php echo $value['nome'] ?></b></h2>
                                <h2>Peso: <b><?php echo $value['peso'] ?> kg</b></h2>
                                <h2>Largura: <b><?php echo $value['largura'] ?> cm</b></h2>
                                <h2>Altura: <b><?php echo $value['altura'] ?> cm</b></h2>
                                <h2>Comprimento: <b><?php echo $value['comprimento'] ?> cm</b></h2>
                                <h2 class='warning'>Quantidade:</b></h2>
                                <form class='faltaForm' method='post'>
                                    <input type="number" name='quantidade' value='<?php echo $value['quantidade'] ?>'>
                                    <input type="hidden" name='id' value='<?php echo $value['id'] ?>'>
                                    <input type="submit" name="acao" value="Atualizar">
                                </form>
                                <div class="flex align-center gap15">
                                    <a class='editBtn' href="<?php echo INCLUDE_PATH.'home/editar_estoque?id='.$value['id'] ?>"><i class="fa-regular fa-pen-to-square"></i> editar</a>

                                    <a class='excluiBtn' href="<?php echo INCLUDE_PATH.'falta_estoque?id='.$value['id'].'&delete'; ?>"><i class="fa-regular fa-circle-xmark"></i> excluir</a>
                                </div>
                            </div>
                        </div><!--single_view-->

                        <?php } ?>
                    </div><!--group-view-->
                </div><!--box-->
            </div><!--content-->
        </div><!--container-->

    </main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src='<?php echo INCLUDE_PATH."js/jquery.magnific-popup.min.js" ?>'></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.form.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/estoque.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/main.js' ?>"></script>
<script>
$(function(){

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
