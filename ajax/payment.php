<?php

    require('../config.php');
    require('../classes/vendor/autoload.php');

    use MercadoPago\Client\Payment\PaymentClient;
    use MercadoPago\Exceptions\MPApiException;
    use MercadoPago\MercadoPagoConfig;

    MercadoPagoConfig::setAccessToken("APP_USR-5042467939743218-102013-749e543de5c41d11a5d73c6ccb95a035-1518667378");
    

    $data = [];

    $data['sucesso'] = true;

    $valueFinal = $_SESSION['finalizarValue'];

    $tipo = $_POST['type'];


    if($tipo == 'paypal'){
        //paypal

        $_SESSION['tipo'] = $tipo;

        $api = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential("ATBgol0q0v7ayq1E9YkW4zUSBy-Vbiy-DZrx7IRoHJDlgGVSTOYjk7hQvxGfy_NS3-KZYHkiJ9l4x9-V","EOlad5dw-DEl-QvtlFFLxwR6bz6NlIHoNk8B07JNc6f3Yijnt5TfHdtlgHvF_jQGeN0uBTmUtvJu7953"),
        );

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($valueFinal);
        $amount->setCurrency('BRL');

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
    
        //gerar ID Unico
        $transaction->setInvoiceNumber(time());
    
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(INCLUDE_PATH.'compraSucesso')
        ->setCancelUrl(INCLUDE_PATH."compraError");
    
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);
    
    
        // After Step 3
        try {
            $payment->create($api);

            $link = $payment->links[1]->href;

            /*
            echo "<pre>";
            var_dump($payment);
            echo "</pre>";
            */

            $data['url'] = $link;
            $data['tipo'] = 'paypal';

        }catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING

            //echo $ex->getData();
            $data['sucesso'] = false;
        }



    }else if($tipo == 'card'){
        //cartão de credito
        $data['tipo'] = 'card';





    }else if($tipo == 'pix'){
        $data['tipo'] = 'pix';

        if(isset($_POST['data'])){

            $nome = $_POST['nome'];
            $sobrenome = $_POST['sobrenome'];
            $email = $_POST['email'];
            $cpf = $_POST['cpf'];

            if(($nome && $sobrenome && $email && $cpf) != ''){
                $cpf = str_replace('.','',$cpf);
                $cpf = str_replace('-','',$cpf);
                
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    //continua o pagamento PIX
                    $_SESSION['tipo'] = $data['tipo'];

                    $client = new PaymentClient();

                    $dataHora = date('Y-m-d\TH:i:s',time() + (60*30));

                    $payment = $client->create([
                        "transaction_amount" => (float) $valueFinal,
                        "description" => "e-commerce pagamento",
                        "payment_method_id" => "pix",
                        "payer" => [
                            "email" => $email,
                            "first_name" => $nome,
                            "last_name" => $sobrenome,
                            "identification" => [
                                //pegar cpf
                                "type" => 'CPF', //tipo cpf/cnpj
                                "number" => $cpf //numero do documento
                            ]
                        ],
                    ]);
                    

                    /*
                    echo "<pre>";
                    print_r($payment);
                    echo "</pre>";
                    */

                    $_SESSION['ref'] = $payment->id;
                    $_SESSION['pixEmail'] = $email;
                    $data['url'] = $payment->point_of_interaction->transaction_data->ticket_url;

                }else{
                    $data['sucesso'] = false;
                    $data['msg'] = "Email Inválido!";
                }

            }else{
                //campos vazios!!
                $data['sucesso'] = false;
                $data['msg'] = "Todos os campos devem ser preenchido!";
            }

        }
    }else{
        $data['sucesso'] = false;
    }


    die(json_encode($data));
?>