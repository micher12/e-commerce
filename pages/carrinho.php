<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce | Carrinho</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <?php Painel::googleFonts() ?>
</head>
<body id='mainHome'>

    <?php
        if(isset($_COOKIE['pendente'])){
            Painel::alert("alert","Pagemento em aberto!");

            $accessToken = '<access token>';

            $id = explode('/',$_COOKIE['pendente'])[5];


            $ch = curl_init();
            $url = "https://api.mercadopago.com/v1/payments/{$id}";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
            ]);
            $response = curl_exec($ch);
            
            curl_close($ch);

            $response = json_decode($response);

            if($response->status == 'pending'){
                //continua pendente
            }else if($response->status == 'approved'){
                Painel::approvedPayment($id);
                setcookie('pendente',null,-1,'/');
            }


        }

        if(isset($_SESSION['cardApproved'])){
            unset($_SESSION['finalizarValue']);
            unset($_SESSION['cardApproved']);
        }
    
    ?>


    <base path='<?php echo INCLUDE_PATH ?>' />

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

            

    <section class='cart'>
        <div class="container">
            <h2 class='title'>Carrinho: </h2>

            <div class="flex jc-between gap35 align-start pt20">
                <form action='<?php echo INCLUDE_PATH.'finalizar_compra'; ?>'  method='post' class='w100 flex jc-between gap35 align-start pt20'>

                <div class="cart-items">

                <?php

                //setcookie('cart',null,-1,'/');

                if(isset($_COOKIE['cart'])){ 
                    $subtotal = 0;
                $table = Painel::listar('tb_admin.estoque');
                foreach ($table as $key => $value) {
                    if(isset($_COOKIE[$value['id']])){
                        
                        $imagem = Painel::fetch('tb_admin.estoque-imagens',"WHERE `produto_id` = ? ORDER BY `id` DESC ",$value['id'])['imagem'];
                        $preco = Painel::formatar($value['preco']);
                        $subtotal += $preco * unserialize($_COOKIE[$value['id']])['quantidade'];

                        $qnt = unserialize($_COOKIE[$value['id']])['quantidade'];

                        $_SESSION['infoData'.'_'.$value['id']] = [
                            'nome'=>$value['nome'],
                            'quantidade'=>unserialize($_COOKIE[$value['id']])['quantidade'],
                            'preco'=> Painel::formatar($value['preco']),
                        ];  

                ?>
                    <div class='cart-single__item'>
                        <div class="image" style='background-image: url("<?php echo INCLUDE_PATH.'uploads/'.$imagem ?>")' ></div>
                        <div class="flex column">
                            <h2 class='subtitle-down'><?php echo $value['nome'] ?></h2>
                            <p>Preço R$: <?php echo $value['preco'] ?></p>

                            <div class='pre-form flex column'>
                                <input type="hidden" name='produtoId' value='<?php echo $value['id']; ?>'>
                                <div class="flex input-value">
                                    <input id='quantidade' name='quantidade' type="number" min='1' value='<?php echo unserialize($_COOKIE[$value['id']])['quantidade'] ?>' max="<?php echo $value['quantidade'] ?>" >
                                    <p class='menosClick'>-</p>
                                    <p class='maisClick'>+</p>
                                </div>
                            
                            </div>

                        </div>
                        
                        <a class='excluir-item' >excluir <i class="fa-solid fa-trash"></i></a>
                        
                    </div>

                    <hr class='m20'>

                    <?php } } } else { 
                       
                        $table2 = Painel::listar('tb_admin.estoque');
                        foreach ($table2 as $key => $value) {
                            setcookie($value['id'],null,-1,'/');
                        }
                        
                        echo "<h2>Nenhum item adicionado!</h2>"; 
                        
                    } ?>


                </div><!--items-->
                

                <div class="cart-price">

                    <div class="flex column gap8">
                        <h2 class='subtitle'>Sub-total R$: <?php echo str_replace('.',',',@$subtotal) ?></h2>
                        <p>Frete: R$: Gratis</p>
                    </div>
                    <hr class='m20'>
                    <h2 class='subtitle-up'>Total: R$: <?php echo str_replace('.',',',@$subtotal) ?></h2>
                    <div class='finalizar-compra'>
                        <?php 
                        if($subtotal > 0){ 
                            $_SESSION['finalizarValue'] = $subtotal;
                            ?>
                            <?php if(isset($_COOKIE['pendente'])){
                            ?>
                                <div class="flex column mt-10">
                                <a target="_blank" class='continuePAY' href="<?php echo $_COOKIE['pendente'] ?>">Finalizar pagamento aberto</a>
                                </div>
                            <?php }else{ ?>
                                <input type="submit" name='comprar' value="Finalizar Compra">
                            <?php } ?>
                        <?php }else { ?>
                            <input class='block' type="submit" name='' value="Finalizar Compra">

                        <?php } ?>
                    </div>
                </div><!--price-->

                </form>

            </div><!--flex-->

        </div><!--container-->
    </section>

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
<script src="<?php echo INCLUDE_PATH.'js/cart.js' ?>"></script>
</body>
</html>
