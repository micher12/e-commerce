<?php
    require("../config.php");

    $data = [];

    $id = $_POST['id'];


    $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.configmenu` WHERE `categoria_id` = ?");
    $sql->execute(array($id));
    $info = $sql->fetch();
    
    $caminho = MySql::conectar()->prepare("SELECT * FROM `tb_admin.menuid` WHERE id = ?");
    $caminho->execute(array($id));
    $caminho = $caminho->fetch();


    $data['sql'] = $info;

    $data['caminho'] = $caminho;

    $data['sucesso'] = true;
    

    die(json_encode($data));

?>