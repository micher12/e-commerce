<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Email{    

        private $mailer;

        public function __construct($host,$username,$senha,$name){
            
        
            $this->mailer = new PHPMailer();

            try {
                //Server settings
                //$this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $this->mailer->isSMTP();                                            //Send using SMTP
                $this->mailer->Host       = $host;                     //Set the SMTP server to send through
                $this->mailer->SMTPAuth   = true;                                   //Enable SMTP authentication
                $this->mailer->Username   = $username;                             //SMTP username
                $this->mailer->Password   = $senha;                               //SMTP password 'xcadtnrmbiioeenl'
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $this->mailer->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                //Recipients

                $this->mailer->setFrom($username, $name);    //EMAIL E NOME DA EMPRESA
                $this->mailer->isHTML(true);                               
                
                $this->mailer->CharSet = 'UTF-8';          //padrão com acetuação correta


            } catch (Exception $e) {
                //eror não mais usado pois está em outro php
               //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        }

        public function addAdress($email,$nome){
            $this->mailer->addAddress($email,$nome);     //EMAIL PARA MANDAR A MENSAGEM
        }

        public function formatarEmail($info){
            $this->mailer->Subject = $info['assunto'];
            $this->mailer->Body    = $info['corpo'];
            $this->mailer->AltBody = strip_tags($info['corpo']);
        }

        public function enviarEmail(){
            if($this->mailer->send()){
                return true;
            }else{
                return false;
            };
        }

    }



?>