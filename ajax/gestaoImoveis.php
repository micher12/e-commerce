<?php

    require('../config.php');

    $data = [];

    $data['sucesso'] = true;

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['valor'];
    $imagem = @$_FILES['imagem'];

    if(($nome && $descricao && $preco ) != ''){

        if(isset($_FILES['imagem']['name'])){
            //sem imagem1
            
            if(Painel::imagemValida($imagem)){
                $imagemName = Painel::uploadFile($imagem);

                $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.gestaoimoveis` values(null,?,?,?,?,?) ");
                if($sql->execute(array($nome,$descricao,$preco,$imagemName,0))){
                    $data['msg'] = "Cadastrado com sucesso!";
                }else{
                    $data['sucesso'] = false;
                    $data['msg'] = "Ocorreu um erro";
                }
            }else{
                $data['sucesso'] = false;
                $data['msg'] = "Imagem inválida!";
            }

        }else{  
            //precisa de uma imagem
            $data['sucesso'] = false;
            $data['msg'] = "É preciso selecionar uma imagem!";
        }

    }else{
        $data['sucesso'] = false;
        $data['msg'] = "Campus vazios não são permitidos!";
    }
    


    die(json_encode($data))
?>
