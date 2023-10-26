<?php
    require("../config.php");

    $data = [];

    $data['sucesso'] = true;

    $nome = $_POST['nome'];
    $peso = $_POST['peso'];
    $largura = $_POST['largura'];
    $altura = $_POST['altura'];
    $quantidade = $_POST['quantidade'];
    $comprimento = $_POST['comprimento'];
    $descricao = $_POST['desc'];
    $preco = $_POST['valor'];


    if($nome == '' || $altura == '' || $largura == '' || $peso == '' || $quantidade == '' || $comprimento == '' || $descricao == '' || $preco == ""){
        $data['sucesso'] = false;
        $data['msg'] = 'Campus vazios não são permitidos!';
    }else{
        
        if(!isset($_FILES['imagem']['name'])){
            $data['sucesso'] = false;
            $data['msg'] = 'É preciso inserir pelo menos 1 imagem!';
        }else{

            $imagens = $_FILES['imagem'];
            $count = count($_FILES['imagem']['name']);

            for($i = 0; $i < $count; $i++){
                $imagemAtual = [
                    'type'=>$_FILES['imagem']['type'][$i],
                    'size'=>$_FILES['imagem']['size'][$i],
                    'name'=>$_FILES['imagem']['name'][$i],
                    'tmp_name'=>$_FILES['imagem']['tmp_name'][$i],
                ];
                if(Painel::imagemValida($imagemAtual)){
                    $avancar = true;
                }else{
                    $avancar = false;
                    $data['sucesso'] = false;   
                    $data['msg'] = 'Imagem invalida!';
                    break;
                }
            }

            if(@$avancar == true){

                $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.estoque` VALUES(null,?,?,?,?,?,?,?,?)");
                if($sql->execute(array($nome,$descricao,$preco,$peso,$largura,$altura,$comprimento,$quantidade))){
                    $lastId = MySql::conectar()->lastInsertId();
                    $data['sucesso'] = true;
                    $data['msg'] = 'Produto cadastrado com sucesso!';
                }else{
                    $data['sucesso'] = false;   
                    $data['msg'] = 'Ocorreu algum error ao conectar-se!';
                }

                if($data['sucesso'] == true){
                    for($i = 0; $i < $count; $i++){
                        $imagemAtual = [
                            'type'=>$_FILES['imagem']['type'][$i],
                            'size'=>$_FILES['imagem']['size'][$i],
                            'name'=>$_FILES['imagem']['name'][$i],
                            'tmp_name'=>$_FILES['imagem']['tmp_name'][$i],
                        ];
                        $imagemNome = Painel::uploadFile($imagemAtual);
                        $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.estoque-imagens` VALUES(null,?,?,?) ");
                        $sql->execute(array($lastId,$imagemNome,0));
                        
                    }
                }

            }
        }
        
    }

    die(json_encode($data));

?>