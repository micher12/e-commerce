<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <?php Painel::googleFonts() ?>
    <title>Finalizar Compra</title>

</head>
<body> 
    <?php
    
        if(isset($_SESSION['cardApproved'])){
            Painel::alert("sucesso","Pagamento aprovado!");
            //addtabela
            if(Painel::addPayment($_SESSION['cardApproved'],$_SESSION['emailApproved'],$_SESSION['amountFinal'],'cartao',1)){  
                setcookie("cart",null,-1,'/');
                setcookie('count',null,-1,'/');   
            ?>
                <script>
                    $(function(){
                        
                        var include_path = $('body').find('base').attr('path');
                        $('.card-main').fadeOut();

                        setTimeout(() => {
                            $('.sucessoMain').slideUp();
                            window.location.href = include_path+"carrinho";
                        },3000);
                    })
                </script>

            <?php } 
        }
    
    ?>

    <div class='card-main' >
        <div class="closeCard"><i class="fa-solid fa-xmark"></i></div>
        <form id="form-checkout" action='<?php echo INCLUDE_PATH.'ajax/payment.php'; ?>'>
            <div id="form-checkout__cardNumber" class="container"></div>
            <div id="form-checkout__expirationDate" class="container"></div>
            <div id="form-checkout__securityCode" class="container"></div>
            <input type="text" id="form-checkout__cardholderName" />
            <select id="form-checkout__issuer"></select>
            <select id="form-checkout__installments"></select>
            <select id="form-checkout__identificationType"></select>
            <input type="text" id="form-checkout__identificationNumber" />
            <input type="email" id="form-checkout__cardholderEmail" />

            <button type="submit" id="form-checkout__submit">Pagar</button>
        </form>
    </div>

    <div class="dark"></div>

    <div class="loader-main">
        <div class="loader">
            <div class="loader-small"></div>
            <div class="loader-large"></div>
        </div>
    </div><!--loader-->

    <base path='<?php echo INCLUDE_PATH?>'>

   <main class='finalizar'>
    <div class="container">
        <div class="flex jc-between align-start">
            <div class="fit-box flex column gap15 up-box">
                <a class='back' href="<?php echo INCLUDE_PATH.'carrinho' ?>"><i class="fa-solid fa-chevron-left"></i> Voltar</a>
                <h2>Produtos:</h2>
                <?php
                    $table = Painel::listar('tb_admin.estoque');
                    foreach ($table as $key => $value) {
                       if(isset($_COOKIE[$value['id']])){ 
                        $imagem = Painel::fetch('tb_admin.estoque-imagens','WHERE `produto_id` = ? ORDER BY `prioridade` DESC LIMIT 1',$value['id'])['imagem'];
                    ?>
                        
                        <div class='flex jc-between gap35 p20'>
                            <div class="miniImg" style="background-image: url('<?php echo INCLUDE_PATH.'uploads/'.$imagem ?>')"></div>
                            <div>
                                <p><?php echo $value['nome']?></p>
                                <div class="flex jc-end gap35">
                                    <p>x <?php  echo unserialize($_COOKIE[$value['id']])['quantidade']; ?></p>
                                    <p><b>R$: <?php echo $value['preco'] ?></b></p>
                                </div>
                            </div>
                            
                        </div>

                        <hr>

                <?php } }  ?>
                
                <input id="totalVALUE" type="hidden" value="<?php echo $_SESSION['finalizarValue'] ?>">
                <h2 class='right'>Total <?php echo "R$: ".str_replace('.',',',$_SESSION['finalizarValue']) ?></h2>
     

            </div>
            <div class="fit-box up-box">
                <h2>Metodos de pagamentos: </h2>
                <div class="flex column gap8">
                    <form class='finalPAY' method='post'>

                        <div class="single-opt flex gap8">
                            <input id='payPIX' type="radio" name='type' value='pix' checked>
                            <label for="payPIX">PIX <i class="fa-brands fa-pix"></i></label>
                        </div>

                        <div class="single-opt flex gap8">
                            <input id='payPAYPAL' type="radio" name='type' value='paypal'>
                            <label for="payPAYPAL">PAYPAL <i class="fa-brands fa-paypal"></i></label>
                        </div>

                        <div class="single-opt flex gap8">
                            <input id='payCARD' type="radio" name='type' value='card'>
                            <label for="payCARD">CARTÃO <i class="fa-solid fa-credit-card"></i></label>
                        </div>

                        <input type="submit" name='pay' value="PAGAR">
                    </form>
                </div>
            </div>
        </div>
    </div><!--container-->
   </main>

<script src="https://sdk.mercadopago.com/js/v2"></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/sdk.card.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery.mask.min.js' ?>" ></script>
<script src="<?php echo INCLUDE_PATH.'js/finalizar.js' ?>"></script>
</body>
</html>