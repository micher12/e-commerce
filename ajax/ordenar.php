<?php
    require('../config.php');

    $data = [];

    $data['sucesso'] = true;

    $data['info'] = $_POST['info'];

    $par = count(explode('&',$data['info']));

    for( $i = 0; $i < count(explode('&',$data['info'])); $i++){
        $items = explode('&',$data['info']);
        $id = explode("=",$items[$i]);

        $sql = MySql::conectar()->prepare("UPDATE `tb_admin.gestaoimoveis` SET `posicao` = ? WHERE id = ? ");
        $sql->execute(array($par,$id[1]));

        $par += - 1;
    }


    die(json_encode($data));

?>