<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce | Main</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
            <li><a href="#home" class='navhover' >Home</a></li>
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
                        <li><a href="#home" class='navhover' >Home</a></li>
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

    <section class='inicio'>
        <div class="container">
            <div class='flex jc-between'>
                <div>
                    <h2 class="title-up">Seja bem vindo!</h2>
                    <h2 class="subtitle" >Loja de e-commerce</h2>
                    <h2 class="title-down mt-20" >Compre agora mesmo!</h2>
                </div>
                <div>
                    <div class="image" style="background-image:url('img/img2.png')"></div>
                </div>
            </div><!--flex-->
        </div><!--container-->
        <div class="thumb-start"></div>
    </section>

    <section class='article' id='sobre'>
        <div class="container">
            <h2 class="title p20" data-aos='fade-up'>Conheça nosso e-commerce!</h2>
            
            <div class="flex mt-40 align-center gap35">

                <div class="box2" >
                    <h2 class='title-up'><i class="fa-brands fa-squarespace"></i></h2>
                    <h2 class='title-down' >DIVERSIDADE</h2>
                    <p class='color-white subtitle'>Uma ampla variedade ao seu alcance!<br> Venha exoplorar.</p>
                </div>
                <div class="box2">
                    <h2 class='title-up'><i class="fa-solid fa-cart-shopping"></i></h2>
                    <h2 class='title-down' >FACILIDADE</h2>
                    <p class='color-white subtitle'>Com poucos cliques você já garante o seu produto!</p>
                </div>
            </div>
            <div class="flex mt-40 align-center gap35 ">

                <div class="box2">
                    <h2 class="title-up"><i class="fa-solid fa-hand-holding-dollar"></i></h2>
                    <h2 class='title-down' >PREÇO</h2>
                    <p class='color-white subtitle'>Um preço justo que cabe no seu bolso!</p>
                </div>

                <a data-aos='fade-up' class='btn mt-40' href="<?php echo INCLUDE_PATH.'produtos' ?>">Explorar! <i class="fa-solid fa-arrow-right"></i></a>

            </div>

        </div>
    </section>

    <section class='secondArticle'>
        <div class="container">
            <h2 class='title p20' data-aos='fade-up'>Tudo a um clique de distância</h2>
            <div class="flex align-center jc-between">
                <div class="box2">
                    <h2 class="title-up"><i class="fa-solid fa-mobile"></i></h2>
                    <h2 class='title-down'>Faça seus pedidos pelo celular!</h2>
                </div>
                <div class="image"  style="background-image: url('img/hand.png')"></div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="foot1">
                <div class="singlefoot">
                    <h2>E-commerce</h2>
                </div>
                <div class="singlefoot">
                    <h2>Navegue</h2>
                    <a href="#home">Home</a>
                    <a href="#sobre">Sobre</a>
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
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>