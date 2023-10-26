<?php
    if(@$_SESSION['login'] == false){
        if(isset($_COOKIE['lembrar'])){
            echo 'ola';
            $user = $_COOKIE['user'];
            $senha = $_COOKIE['senha'];
            if(Painel::login($user,$senha)){
                $_SESSION['login'] = true;
                header("location: ".INCLUDE_PATH."home");
            }else{
                Painel::alert("error","Não foi pssível entrar automaticamente!");
            }

        }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce | Main</title>
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/style.css' ?>" >
    <link rel='stylesheet' href="<?php echo INCLUDE_PATH.'css/all.min.css' ?>" >
    <?php Painel::googleFonts() ?>
</head>
<body>
    
    
    <section class="form_login">
        <div class="container">

            <?php

            if(isset($_POST['acao'])){
                $user = $_POST['login'];
                $senha = $_POST['senha'];
                

                if(Painel::login($user,$senha)){
                    if(isset($_POST['remember'])){
                        setcookie('lembrar',true,time()+(60*60*24*7), '/');
                        setcookie('user',$user,time()+(60*60*24*7), '/');
                        setcookie('senha',$senha,time()+(60*60*24*7), '/');
                    }
                    $_SESSION['login'] = true;
                    header("location: ".INCLUDE_PATH."home");
                }else{
                    Painel::alert("error","Usuário ou senha incorreto!");
                }

            }

            if(isset($_GET['register']) && isset($_GET['confirmcode'])){
                Painel::alert("sucesso","Email de confirmação enviado para o seu email!");
                $_SESSION['confirmcode'] = true;

            }

            if(isset($_POST['codeConfirm'])){
                @$code = $_POST['code'];
                @$codigo = $_SESSION['code'];

                if($code == $codigo){
                    if(Painel::cadastrar($_SESSION['nome'],$_SESSION['email'],$_SESSION['user'],$_SESSION['senha'])){
                        $_SESSION['login'] = true;
                        header("location: ".INCLUDE_PATH."home");
                    }else{
                        Painel::alert("error","Ocorreu um erro ao cadastrar!");
                    }
                }else{  
                    Painel::alert("error","Codigo inválido!");
                }

            }


            $requesitos = false;
            if(isset($_POST['registrar'])){
                $nome = $_POST['nome'];
                $email = $_POST['email'];
                $user = strtolower($_POST['user']);
                $senha = $_POST['senha'];
                $cofirmPass = $_POST['confirmPass'];
                for ($i = 0; $i < 4; $i++) {
                    @$digito = rand(0, 9);
                    @$digitos .= $digito;
                }
                
                if($nome != '' && $email != '' && $user != '' && $senha != ''){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                        if(Painel::validatePassword($senha)){
                            if(Painel::verifyUser($user)){
                                if($senha == $cofirmPass){
                                    $_SESSION['code'] = @$digitos;
                                    $assunto = "Email de Confirmação";
                                    $body = "<h2>Ola, ".$nome." | email de confirmação da e-commerce</h2> <h3>Faça login utilizando o seu nome de usuário: ".$user."</h3><h3>Codigo de confirmação: ".$_SESSION['code']."</h3>";
                                    $info = array('assunto'=>$assunto,'corpo'=>$body);

                                    $mail = new Email('smtp.gmail.com',"michelasm3@gmail.com","xcadtnrmbiioeenl","e-commerce");

                                    $mail->addAdress($email,$nome);


                                    $mail->formatarEmail($info);

                                    if($mail->enviarEmail()){
                                        $_SESSION['nome'] = $nome;
                                        $_SESSION['email'] = $email;
                                        $_SESSION['user'] = $user;
                                        $_SESSION['senha'] = $senha;

                                        header("Location: ".INCLUDE_PATH.'/login?register&confirmcode');
                                    }else{
                                        Painel::alert("error","Não foi possível enviar o email para confirmar sua identidade.");
                                    }

                                }else{
                                    Painel::alert("error","Senhas diferentes!");
                                }
                            }else{
                                Painel::alert("error","Usuário já está em uso!");
                            }

                        }else{
                            Painel::alert("error","Senha Inválida!");
                            $requesitos = true;
                        }
                    }else{
                        Painel::alert("error","Email inválido!");
                    }

                }else{
                    Painel::alert("error","Campus vazios não são permitidos!");
                }

            }





            if(!isset($_GET['register'])){ ?>

            <div class="formLogin">
                <a class='back' href="<?php echo INCLUDE_PATH ?>"><i class="fa-solid fa-caret-left"></i>  Voltar</a>
                <h2 class="title-down pb20">Login:</h2>
                <form class='formToLogin' method="post">
                    <div class="singleform">
                        <label >Nome de usuário:</label>
                        <input type="text" name="login">
                    </div>
                    <div class="singleform">
                        <label >Senha:</label>
                        <input type="password" name="senha">
                    </div>
                    <div class="singleform">
                            <a class="seePass"><i class="fa-regular fa-eye-slash"></i></a>
                    </div>
                    <div class="singleform">
                        <div class="flex gap8"><input type="checkbox" checked name='remember' >Lembrar senha</div>
                        
                        <div class="flex align-center gap35 mt-10">
                            <input type="submit" name="acao" value="Entrar">
                            <a href="<?php echo INCLUDE_PATH.'login?register' ?>">Registrar</a>
                        </div>
                    </div>
                </form>
            </div>
            <?php }else{ ?>
                <div class="formLogin">
                <a class='back' href="<?php echo INCLUDE_PATH ?>"><i class="fa-solid fa-caret-left"></i>  Voltar</a>
                <h2 class="title-down pb20">Registrar:</h2>
                <form class='formToLogin' method="post">
                    <div class="singleform">
                        <label >Nome completo:</label>
                        <input type="text" name="nome">
                    </div>
                    <div class="singleform">
                        <label >Email:</label>
                        <input type="email" name="email">
                    </div>
                    <div class="singleform">
                        <label >Usuário:</label>
                        <input style="text-transform: lowercase" type="text" name="user">
                    </div>
                    <div class="singleform">
                        <label >Senha:</label>
                        <input type="password" name="senha">
                        <?php if($requesitos == true){
                            echo "<div class='errorPass'>Deve ter ao menos uma letra maiúscula, um número, um caracter especial e mais de 6 caracteres!</div>";
                        } ?>
                    </div>
                    <div class="singleform">
                        <label >Digite novamente a senha:</label>
                        <input type="password" name="confirmPass">
                    </div>
                    <div class="singleform">
                        <a class="seePass"><i class="fa-regular fa-eye-slash"></i></a>
                    </div>
                    <div class="singleform">
                        <div class="flex align-center gap35 mt-10">
                            <input type="submit" name="registrar" value="Registrar">
                            <a href="<?php echo INCLUDE_PATH.'login' ?>">Login</a>
                        </div>
                    </div>
                </form>
                <?php 
                    if(@$_SESSION['confirmcode'] == true){ ?>

                    <form class='formConfirm' method="post">
                        <div class="singleform">
                            <label >Código de confirmação: </label>
                            <div class="confirmFlex flex align-center gap35">
                                <input type="number" name='code'>
                                <input type="submit" name='codeConfirm' value='Enviar'>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
            <?php } ?>

            

        </div>
    </section>



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo INCLUDE_PATH.'js/all.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/jquery-ui.min.js' ?>"></script>
<script src="<?php echo INCLUDE_PATH.'js/main.js' ?>"></script>
<script>
$(function(){
    clicado = false    
    $('.seePass').click(function(){
        if(clicado){
            clicado = false
            $(this).html('<i class="fa-regular fa-eye-slash"></i>')
            $('input[name="senha"],input[name="confirmPass"]').attr('type','password')
        }else{
            clicado = true
            $(this).html('<i class="fa-regular fa-eye"></i>')
            $('input[name="senha"],input[name="confirmPass"]').attr('type','text')
        }

        
    });

    $('input[name="user"]').keyup(function(){
        texto = $(this).val();
        novo = texto.replace(' ','_');
        $(this).val(novo)
    })

})      
</script>
</body>
</html>

<?php }else{
    header("Location: ".INCLUDE_PATH.'home');
} ?>