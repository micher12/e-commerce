<link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
<?php

    require('classes/vendor/autoload.php');

    $success = false;

    if($_SESSION['tipo'] == 'paypal'){
        //PayPal
        
        $api = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential("public-key","private-key"),
        );
    
        $paymentId = $_GET['paymentId'];
    
        $payment = \PayPal\Api\Payment::get($paymentId,$api);
    
        $execute = new \PayPal\Api\PaymentExecution();
    
        $execute->setPayerId($_GET['PayerID']);
    
    
        try{
    
            $result = $payment->execute($execute,$api);
            try{
                $payment = \PayPal\Api\Payment::get($paymentId,$api);
                $status = $payment->getState();
    
                if($status == 'approved'){
                    //pagamento aprovado!
    
                    //echo "Pagamento Aprovado!";
                    $info = current($payment->getTransactions());
    
                    $info = $info->toArray();
    
                    $referencia = $info['invoice_number'];
    
                    //ID da fatura
    
                    //echo $referencia;
    
                    $val = $_SESSION['finalizarValue'];
    
                    if($referencia != ''){
                        if(Painel::addPayment($referencia,0,$val,$_SESSION['tipo'],1)){
                            $success = true;
                            Painel::alert("sucesso","Pagamento Aprovado!");
                            setcookie("cart",null,-1,'/');
                            setcookie('count',null,-1,'/');
                        }
                    }
    
    
                }else{
                    //Deu algo ruim
                }
    
    
            }catch(Exception $e){
                //echo $e->getMessage();
            }
    
        }catch(Exception $e){
           // echo $e->getMessage();
        }


    }else if($_SESSION['tipo'] == 'pix'){
        //PIX

        $accessToken = '<access token>';

        if(isset($_SESSION['ref'])){
            $paymentId = $_SESSION['ref'];
            $ch = curl_init();
            $url = "https://api.mercadopago.com/v1/payments/{$paymentId}";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
            ]);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                //echo 'Erro cURL: ' . curl_error($ch);
            }
            
            curl_close($ch);

            /*
            echo "<pre>";
            print_r(json_decode($response));
            echo "</pre>";
            */
           
         

            $response = json_decode($response);

            @$caminhoPayment = $response->point_of_interaction->transaction_data->ticket_url;


            if($response->status == 'pending'){
                Painel::alert("alert","Aguardando pagamento!");
                if(Painel::issetPayment($paymentId)){
                    setcookie('pendente',$caminhoPayment,time()+(60*60*24),'/');
                    Painel::addPayment($paymentId,$_SESSION['pixEmail'],$_SESSION['finalizarValue'],$_SESSION['tipo'],0);
                }
                
            }else if ($response->status == "approved"){
                Painel::approvedPayment($paymentId);
                Painel::alert("sucesso","Pagamento Aprovado!");
                setcookie('pendente',null,-1,'/');
                setcookie("cart",null,-1,'/');
                setcookie('count',null,-1,'/');
                $success = true;
                
            }else{
                Painel::cancelPayment($paymentId);
                Painel::alert("error","Pagamento cancelado!");

            }

        }

    }else if($_SESSION['tipo'] == 'card'){

    }

?>

<script>
    var tipo = "<?php echo $_SESSION['tipo']; ?>"
    var success = "<?php echo @$success ?>";

    
    if(tipo == 'paypal'){
        if(success == "1"){
            setTimeout(function() {
                window.location.href = "<?php echo INCLUDE_PATH.'carrinho'; ?>";
            }, 3000); 
        }
    }else if(tipo == 'pix'){
        
        if(success == "1"){
            setTimeout(function() {
                window.location.href = "<?php echo INCLUDE_PATH.'carrinho'; ?>";
            }, 3000); 
        }
    }



</script>
