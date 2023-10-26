<?php
if(isset($_GET['id']) && Painel::produtoValido($_GET['id']) == true){ //valido ?>
  
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/magnific-popup.css' ?>" >
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <?php Painel::googleFonts() ?>
</head>
<body id='mainHome'>
    

    <div class="mobileBtn">
        <span>
            <div class="line ln1"></div>
            <div class="line ln2"></div>
            <div class="line ln3"></div>
        </span>
    </div>

    <div class='mobile'>
        <ul class='flex jc-center column align-center gap35'>
            <li><a href="main" class='navhover' >Home</a></li>
            <li><a href="produtos" class='navhover' >Produtos</a></li>
            <li style='position: relative'>
            <?php if(@$_COOKIE['count'] > 0){  ?>

            <span class='cart-count'><?php echo $_COOKIE['count'];  ?></span>

            <?php } ?>

            <a href="carrinho"><i class="fa-solid fa-cart-shopping"></i></a></li>
            <li><a href="login" class='login' >Login</a></li>
        </ul>
    </div>

    <header id='home'>
        <div class="container">
            <div id='headerContent' class="flex jc-between align-center">
                <h2>E-commerce</h2>
                <nav class='desktop'>
                    <ul id='desktop' class='flex jc-center gap35'>
                        <li><a href="main" class='navhover' >Home</a></li>
                        <li><a href="produtos" class='navhover' >Produtos</a></li>
                        <li style='position: relative'>
                        <?php if(@$_COOKIE['count'] > 0){  ?>

                        <span class='cart-count'><?php echo $_COOKIE['count'];  ?></span>

                        <?php } ?>

                        <a href="carrinho"><i class="fa-solid fa-cart-shopping"></i></a></li>
                        <li><a href="login" class='login' >Login</a></li>
                    </ul>
                </nav>
            </div>
        </div><!--container-->
    </header>

    <?php  

    if(isset($_POST['acao'])){

        $produtoId = $_GET['id'];
        $preco = $_POST['preco'];
        $quantidade = $_POST['quantidade'];

        $info = [
            'nome'=>$produtoId,
            'preco'=>$preco,
            'quantidade'=>$quantidade,
        ];

        $data = serialize($info);

        if(!isset($_COOKIE['cart'])){
            setcookie('cart',true,time()+(60*60*24*30),'/');
            
            if(isset($_COOKIE[$produtoId])){
                $quantidade += unserialize($_COOKIE[$produtoId])['quantidade'];
                $info = [
                    'nome'=>$produtoId,
                    'preco'=>$preco,
                    'quantidade'=>$quantidade,
                ];
                $data = serialize($info);
                setcookie($produtoId,$data,time()+(60*60*24*30),'/');

            }else{
                if(isset($_COOKIE['count'])){
                    $valor = $_COOKIE['count'] + 1;
                    setcookie('count',$valor,time()+(60*60*24*30),'/');
                }else{
                    setcookie('count',1,time()+(60*60*24*30),'/');
                }
                
                setcookie($produtoId,$data,time()+(60*60*24*30),'/');
            }

        }else{
            if(isset($_COOKIE[$produtoId])){
                $quantidade += unserialize($_COOKIE[$produtoId])['quantidade'];
                $info = [
                    'nome'=>$produtoId,
                    'preco'=>$preco,
                    'quantidade'=>$quantidade,
                ];
                $data = serialize($info);
                setcookie($produtoId,$data,time()+(60*60*24*30),'/');

            }else{
                if(isset($_COOKIE['count'])){
                    $valor = $_COOKIE['count'] + 1;
                    setcookie('count',$valor,time()+(60*60*24*30),'/');
                }else{
                    setcookie('count',1,time()+(60*60*24*30),'/');
                }
                setcookie($produtoId,$data,time()+(60*60*24*30),'/');
            }
        }

        header("Location: ".INCLUDE_PATH.'view-produtos?id='.$_GET['id']);
            
       
    }


    $info = Painel::singleProduto($_GET['id']);
    foreach ($info as $key => $value) { ?>

    <section class='single-produto'>

        <div class="container">
            <div class="flex gap35">

                <div class='w50'>
                    <div class="slider">
                        <?php
                        $imagem = Painel::imagesEstoque($_GET['id']);
                        foreach ($imagem as $key => $image) { ?>
                        
                        <a class='pop-up' href="<?php echo INCLUDE_PATH.'uploads/'.$image['imagem']; ?>">
                            <div class="image" style="background-image: url('<?php echo INCLUDE_PATH.'uploads/'.$image['imagem']; ?>')"></div>
                        </a>

                        <?php } ?>
                    </div><!--slider-->
                </div>

                <div class='w50 content-single'>
                    <h2><?php echo $value['nome']; ?></h2>
                    <p><?php echo $value['descricao']; ?></p>
                    <form method='post'>
                        <div class="flex gap35 align-center">
                            <h2><?php echo "R$: ".$value['preco'] ?></h2>
                            <input type="hidden" value="<?php echo $value['preco'] ?>" name='preco'>
                            <div class="flex input-value">
                                <input id='quantidade' name='quantidade' type="number" min='1' value='1' max="<?php echo $value['quantidade'] ?>" >
                                <p class='menosClick'>-</p>
                                <p class='maisClick'>+</p>
                            </div>
                        </div>
                        
                        
                        <input id='add-cart' type="submit" name='acao'>
                        <label for='add-cart' class='add-card' >Adicionar <i class='icon-card fa-solid fa-cart-shopping'></i></label>
                       
                        
                    </form>
                </div>

            </div><!--flex-->
        </div><!--container-->
    </section>


    <?php } ?>


    <footer>
        <div class="container">
            <div class="foot1">
                <div class="singlefoot">
                    <h2>E-commerce</h2>
                </div>
                <div class="singlefoot">
                    <h2>Navegue</h2>
                    <a href="main">Home</a>
                    <a href="main#sobre">Sobre</a>
                    <a href="produtos">Produtos</a>
                    <a href="login">Login</a>
                </div>
                <div class="singlefoot">
                    <h2>Contato</h2>
                    <a target="_blank" href="mailto:michelasm3@gmail.com?subject=Vim pelo site!&body=Gostária de fazer um orçamento."><i class="fa-regular fa-envelope"></i> michelasm3@gmail.com</a>
                    <h2>Redes Sociais</h2>
                    <div class="flex gap15">
                        <a target="_blank" class='social' href="https://www.instagram.com/micher.12/"><i class="fa-brands fa-instagram"></i></a>
                        <a target="_blank" class='social' href="https://wa.me//+5562985695187?text=Olá, vim pelo site gostaria de entrar em contato"><i class="fa-brands fa-whatsapp"></i></a>
                        <a target="_blank" class='social' href="https://github.com/micher12"><i class="fa-brands fa-github"></i></a>
                        <a target="_blank" class='social' href="https://www.linkedin.com/in/michel-alves-0a1834212/"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="singlefoot">
                    <h2>Problemas?</h2>
                    <p class='text'>Está enfretando problemas, ou achou algum bug? Entre em contato para ajudarmos!</p>
                </div>
            </div>
            <div class="foot2">
                <p class='text-center color-white'>Todos os direitos reservados | &copy; 2023</p>
            </div>


        </div>
    </footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/start.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.magnific-popup.min.js' ?>"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
$(function(){

    $('.slider').slick({
        dots: true,
        arrows: false,
    });

    //quantidade
    function quantidade(){
        $('.maisClick').click(function(){
            var el = parseInt($('#quantidade').val());
            var max = parseInt($('#quantidade').attr('max'));
        
            if(el < max){
                el += 1;
                $('#quantidade').val(el);
            }

            if(el == max){
                $('.sucessoMain,.errorMain').remove();
                $('.single-produto').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> Limite máximo alcançado!</div>')
                setTimeout(function(){
                    $('.errorMain').slideUp();
                },6000);
            }

        });

        $('.menosClick').click(function(){
            var el = parseInt($('#quantidade').val());
            var min = parseInt($('#quantidade').attr('min'));

            if(el > min){
                el -= 1;
                $('#quantidade').val(el);
            }

            if(el == min){
                $('.sucessoMain,.errorMain').remove();
                $('.single-produto').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> Limite mínimo alcançado!</div>')
                setTimeout(function(){
                    $('.errorMain').slideUp();
                },6000);
            }

        });
    };
    quantidade();


    $('.slider').magnificPopup({
        delegate: '.pop-up',
        type:'image',
    })


})
</script>
</body>
</html>

<?php }else{ 
//invalido 

    header("Location: ".INCLUDE_PATH.'produtos');
} ?>