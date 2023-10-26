<?php

    require('../config.php');

    $data = [];

    $data['imagem'] = $_POST['name'];
    $data['id'] = $_POST['id'];


    $sql = MySql::conectar()->prepare("DELETE FROM `tb_admin.estoque-imagens` WHERE  `imagem` = ? AND `produto_id` = ? ");
    if($sql->execute(array($data['imagem'],$data['id']))){
        $data['sucesso'] = true;
        Painel::deleteFile($data['imagem']);
        $data['msg'] = "Imagem deletada com sucesso!";
    }else{
        $data['sucesso'] = false;
        $data['msg'] = "Ocorreu um erro ao deletar a imagem!";
    }
    

    die(json_encode($data))

?>