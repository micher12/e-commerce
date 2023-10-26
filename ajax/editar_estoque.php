<?php
require("../config.php");


$data = [];

$nome = $_POST['nome'];
$peso = $_POST['peso'];
$largura = $_POST['largura'];
$altura = $_POST['altura'];
$comprimento = $_POST['comprimento'];
$quantidade = $_POST['quantidade'];
@$imagem = $_FILES['imagem'];
$id = $_POST['id'];
$preco = $_POST['valor'];
$descricao = $_POST['desc'];



if(isset($imagem['name'])){
    //verificar imagem
    @$count = count($imagem['name']);

    for($i = 0; $i < $count; $i++){
        $imagemAtual = [
            'type'=>$imagem['type'][$i],
            'size'=>$imagem['size'][$i],
            'name'=>$imagem['name'][$i],
            'tmp_name'=>$imagem['tmp_name'][$i],
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
        $sql = MySql::conectar()->prepare("UPDATE `tb_admin.estoque` SET `nome` = ?, `descricao` = ? , `preco` = ? ,  `peso` = ? , `largura` = ?, `altura` = ?, `comprimento` = ?, `quantidade` = ? WHERE id = ?");
        if($sql->execute(array($nome,$descricao,$preco,$peso,$largura,$altura,$comprimento,$quantidade,$id))){

            for($i = 0; $i < $count; $i++){
                $imagemAtual = [
                    'type'=>$imagem['type'][$i],
                    'size'=>$imagem['size'][$i],
                    'name'=>$imagem['name'][$i],
                    'tmp_name'=>$imagem['tmp_name'][$i],
                ];

                $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.estoque-imagens` VALUES(null,?,?,?) ");
                $nomeImagem = Painel::uploadFile($imagemAtual);
                $sql->execute(array($id,$nomeImagem,0));

            };

            $data['sucesso'] = true;
            $data['msg'] = "Imagem adicionada e itens editado com sucesso!";
        }else{
            $data['sucesso'] = false;
            $data['msg'] = "Algo deu errado";
        }
    }


}else{
    //apenas dados
    $sql = MySql::conectar()->prepare("UPDATE `tb_admin.estoque` SET `nome` = ?,`descricao` = ?, `preco` = ? ,`peso` = ? , `largura` = ?, `altura` = ?, `comprimento` = ?, `quantidade` = ? WHERE id = ?");
    if($sql->execute(array($nome,$descricao,$preco,$peso,$largura,$altura,$comprimento,$quantidade,$id))){
        $data['sucesso'] = true;
        $data['msg'] = "Item editado com sucesso!";
    }else{
        $data['sucesso'] = false;
        $data['msg'] = "Algo deu errado";
    }
}


die(json_encode($data));

?>