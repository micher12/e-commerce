<?php

    session_start();
    date_default_timezone_set('America/Sao_Paulo');

    $autoload = function($class){

        if($class == 'Email'){
            include("classes/vendor/autoload.php");
        }


        include("classes/".$class.'.php');

    };

    spl_autoload_register($autoload);

    define("INCLUDE_PATH","http://localhost/e-commerce/");
    define("DIR",__DIR__);
    define("HOST",'localhost');
    define("USER",'root');
    define("DATABASE",'e-commerce');
    define("PASSWORD",'');


    //session_destroy()

?>