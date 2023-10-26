<?php require('config.php'); ?>

<?php

    $url = isset($_GET['url']) ? $_GET['url'] : 'main';

    if(is_file("pages/".$url.".php")){
        include("pages/".$url.".php");
        die();
    }else if(@is_file("pages/".explode('/',$url)[1].'.php') && explode('/',$url)[0] == 'home' ){
        include("pages/".explode('/',$url)[1].'.php');
        die();
    }else{
        include("pages/error404.php");
        die();
    }

?>