<?php

    require("../config.php");

    
    $data = [];
    
    $data['sucesso'] = false;
    $data['imagem'] = $_POST['imagem'];
    $data['id'] = $_POST['id'];

    $resete = MySql::conectar()->prepare("UPDATE `tb_admin.estoque-imagens` SET `prioridade` = ? WHERE `produto_id` = ?");
    $resete->execute(arraY(0,$data['id']));

 
    $sql = MySql::conectar()->prepare("UPDATE `tb_admin.estoque-imagens` SET `prioridade` = ? WHERE `produto_id` = ? AND `imagem` = ?");
    if($sql->execute(array(1,$data['id'],$data['imagem']))){
        $data['sucesso'] = true;
        $data['msg'] = "A capa foi atualizada com sucesso!";
    }else{
        $data['msg'] = "Ocorreu algum error!";
    }




    die(json_encode($data))
?>