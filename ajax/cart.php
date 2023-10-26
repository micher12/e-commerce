<?php

    require('../config.php');

    if(isset($_POST['tipo'])){

        if($_POST['tipo'] == 'quantidade'){

            $data = [];

            $data['id'] = $_POST['id'];
            $data['quantidade'] = $_POST['quantidade'];

            $sql = MySql::conectar()->prepare("SELECT * FROM  `tb_admin.estoque` WHERE id = ?");
            $sql->execute(array($data['id']));
            $info = $sql->fetch();



            $newInfo = [
                'nome'=>$data['id'],
                'preco'=>$info['preco'],
                'quantidade'=>$data['quantidade'],
            ];


            $newData = serialize($newInfo);


            setcookie($data['id'],$newData,time()+(60*60*24*30),'/');

            $data['sucesso'] = true;

            die(json_encode($data));

        }else if($_POST['tipo'] == 'deletar'){
            $data = [];

            $id = $_POST['id'];
            setcookie($id,null,-1,'/');

            if(isset($_COOKIE['pendente'])){
                setcookie('pendente',null,-1,'/');
            }

            if($_COOKIE['count'] > 0){
                setcookie('count',$_COOKIE['count'] - 1, time()+(60*60*24*30),'/');
            }

            $data['sucesso'] = true;
            die(json_encode($data));

        }
    }
    header("Location: ".INCLUDE_PATH.'carrinho');
    die();
?>