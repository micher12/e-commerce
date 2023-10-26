<?php

    class MySql{

        private static $pdo;

        public static function conectar(){
            if(self::$pdo == null){
                try {
                    self::$pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE, USER, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (Exception $e) {
                    echo '<div style="text-align: center; padding-bottom: 20px; color: #e32f2f;"><h2>Error ao se conectar</h2></div>';
                }
            }

            return self::$pdo;
        }

    }

?>