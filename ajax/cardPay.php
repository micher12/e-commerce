<?php
    
    require("../classes/vendor/autoload.php");

    session_start();

    define("INCLUDE_PATH","http://localhost/e-commerce/");

    use MercadoPago\Client\Payment\PaymentClient;
    use MercadoPago\Exceptions\MPApiException;
    use MercadoPago\MercadoPagoConfig;


    MercadoPagoConfig::setAccessToken("TEST-5042467939743218-102013-37d9548b31c7c3d8b612e6c127552500-1518667378");

    $data = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recupere o corpo JSON da solicitação
        $json = file_get_contents('php://input');
        
        // Decodifique o JSON em um array associativo
        $data = json_decode($json, true);
    
        if ($data !== null) {
            // Agora você pode acessar os dados enviados
            $token = $data['token'];
            $issuer_id = $data['issuer_id'];
            $payment_method_id = $data['payment_method_id'];
            $transaction_amount = $data['transaction_amount'];
            $installments = $data['installments'];
            $description = $data['description'];
            $payer_email = $data['payer']['email'];
            $identification_type = $data['payer']['identification']['type'];
            $identification_number = $data['payer']['identification']['number'];

            $client = new PaymentClient();

            try{
                $payment = $client->create([
                    "transaction_amount" => $transaction_amount,
                    "token" => $token,
                    "description" => $description,
                    "installments" =>  $installments,
                    "payment_method_id" => $payment_method_id,
                    "issuer_id" => $issuer_id,
                    "payer" => [
                        "email" => $payer_email,
                        "identification" => [
                            "type" => $identification_type,
                            "number" => $identification_number,
                        ]
                    ]
                ]);


                //pegando resposta ===========
                
                $accessToken = 'TEST-5042467939743218-102013-37d9548b31c7c3d8b612e6c127552500-1518667378';
                $paymentId = $payment->id;

                $ch = curl_init();
                $url = "https://api.mercadopago.com/v1/payments/$paymentId";
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

                $info = json_decode($response);

                if($info->status == 'approved'){
                    $_SESSION['cardApproved'] = $info->id;
                    $_SESSION['emailApproved'] = $payer_email;
                    $_SESSION['amountFinal'] = $info->transaction_details->total_paid_amount;
                }


            }catch(MercadoPago\Exceptions\MPApiException $e){
                echo 'Erro MercadoPago: ' . $e->getMessage();
            }


        } else {
            // Erro ao decodificar JSON
            echo "Erro ao decodificar o JSON.";
        }
    }


    die();
?>

