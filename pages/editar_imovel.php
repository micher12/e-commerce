<?php
    if(@$_SESSION['login'] == true){
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce</title>
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
            <h2 class="subtitle text-center">editar imóvel</h2>
            <div class="content">
                <div class="box">
                    <?php //form
                        if(isset($_POST['acao'])){
                            $nome = $_POST['nome'];
                            $descricao = $_POST['descricao'];
                            $preco = $_POST['preco'];
                            $id = $_GET['id'];
                            if(Painel::atualizar("tb_admin.gestaoimoveis","SET `nome` = ?, `descricao` = ?, `preco` = ? WHERE id = ?",array($nome,$descricao,$preco,$id))){
                                Painel::alert("sucesso","Atualizado com sucesso!");
                            }else{
                                Painel::alert("error","Ocorreu um error ao atualizar!");
                            }
                            
                        }
                    ?>
                    
                    <?php
                    if(isset($_GET['id'])){  
                        $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.gestaoimoveis` WHERE id = ?");
                        $sql->fetchAll();
                        $sql->execute(array($_GET['id']));
                        foreach ($sql as $key => $value) { ?>

                        <form method="post">
                            <div class="singleform">
                                <label>Nome: </label>
                                <input type="text" name="nome" value="<?php echo $value['nome'] ?>">
                            </div><!--singleform-->
                            <div class="singleform">
                                <label>Descrição: </label>
                                <textarea type="text" name="descricao"><?php echo $value['descricao'] ?></textarea>
                            </div><!--singleform-->
                            <div class="singleform">
                                <label>Preço: </label>
                                <input type="text" name="preco" value="<?php echo $value['preco'] ?>">
                            </div><!--singleform-->
                            <div class="singleform">
                                <input type="submit" name="acao" value="Atuailizar" >
                            </div><!--singleform-->
                        </form>

                    <?php } }else{ ?>

                        <h2 class='editNoId'><i class="fa-solid fa-triangle-exclamation"></i> É preciso selecionar um item antes de editar! <i class="fa-solid fa-triangle-exclamation"></i></h2>

                    <?php } ?>
                </div><!--box-->
            </div><!--content-->
        </div><!--container-->

    </main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/main.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.maskMoney.min.js' ?>"></script>
<script>
$(function(){

    $('input[name="preco"]').maskMoney({prefix:'R$: ', allowNegative: false, thousands:'.', decimal:',', affixesStay: true});

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

