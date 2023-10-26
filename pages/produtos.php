<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce | Produtos</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <?php Painel::googleFonts() ?>
</head>
<body id="mainHome">
    

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
            <li><a href="#home" class='navhover' >Produtos</a></li>
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
                        <li><a href="#home" class='navhover' >Produtos</a></li>
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

    <section class='produtos'>
        <div class="container">
            <h2 class='title'>Explore nosso catálogo!</h2>

            <div class="flex-products flex p60 gap35 wrap">
            <?php
                $produtos = Painel::listar('tb_admin.estoque');
                foreach ($produtos as $key => $value) { ?>

                <a class='box-produto' href="<?php echo INCLUDE_PATH.'view-produtos?id='.$value['id'] ?>">
                    <div class="boxProdutos">
                        <?php
                            $imagem = Painel::fetch('tb_admin.estoque-imagens','WHERE produto_id = ? ORDER BY `prioridade` DESC',$value['id'])['imagem'];
                        ?>
                        <div class="image" style="background-image: url('<?php echo INCLUDE_PATH.'uploads/'.$imagem; ?>')" ></div>
                        <div class="conteudoProdutos">
                            <h2><?php echo $value['nome'] ?></h2>
                            <h3 class='pt20 pb20'><?php echo "R$: ".$value['preco'] ?></h3>
                        </div>
                    </div>
                </a>

            <?php } ?>
            </div>
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
                    <a href="#home">Produtos</a>
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
</body>
</html>