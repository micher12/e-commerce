<?php

    class Painel{

        public static function googleFonts(){
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&family=Chivo+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,400&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Lalezar&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,500&family=Open+Sans:wght@300;400;500;600&family=Oxygen:wght@300;400;700&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">';
        }


        public static function validatePassword($senha){
            if(strlen($senha) < 6){
                //tamanho
                return false;
            }

            if(!preg_match("/[A-Z]/",$senha)){
                //maiuscula
                return false;
            }

            if (!preg_match('/[0-9]/', $senha)) {
                //numeros
                return false;
            }

            if (!preg_match('/[^a-zA-Z0-9]/', $senha)) {
                //caracter-especial
                return false;
            }

            return true;
        }

        public static function alert($tipo,$msg){
            if($tipo == 'sucesso'){
                echo '<div class="sucessoMain"><i class="fa-regular fa-circle-check" style="color: #ffffff;"></i> '.$msg.'</div>';
            }else if($tipo == 'error' ){
                echo '<div class="errorMain"><i class="fa-solid fa-triangle-exclamation"></i> '.$msg.'</div>';
            }else if($tipo == 'alert'){
                echo '<div class="alertMain"><i class="fa-solid fa-triangle-exclamation"></i> '.$msg.'</div>';
            }
        }


        public static function updateStoque($id,$qnt){
            $sql = MySql::conectar()->prepare("UPDATE `tb_admin.estoque` SET `quantidade` = ? WHERE id = ?");
            if($sql->execute(array($qnt,$id))){
                return true;
            }else{
                return false;
            }
        }

        public static function atualizar($tb,$where,$execute){
            $sql = MySql::conectar()->prepare("UPDATE `$tb` $where ");
            if($sql->execute($execute)){
                return true;
            }else{
                return false;
            }
        }

        public static function fetchDirection($id){
            if($id == 1){ //controle de estoque
                $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.menuid` WHERE id = ?");
                $sql->execute(array($id));
                $info = $sql->fetch();

                return $info;
            }

        }

        public static function verifyUser($user){
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.user` WHERE `user` = ? ");
            $sql->fetchAll();
            $sql->execute(array($user));
            $info = $sql->rowCount();

            if($info > 0 ){
                return false;
            }else{
                return true;
            }

        }

        public static function login($user,$senha){
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.user` WHERE `user` = ? AND `senha` = ?");
            $sql->fetchAll();
            $sql->execute(array($user,$senha));
            $info = $sql->rowCount();

            if($info > 0 ){
                return true;
            }else{
                return false;
            }

        }

        public static function cadastrar($nome,$email,$user,$senha){
            $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.user` values(null,?,?,?,?) ");
            if($sql->execute(array($nome,$email,$user,$senha))){
                return true;
            }else{
                return false;
            }


        }

        public static function fetch($table,$where,$execute){
            $sql = MySql::conectar()->prepare("SELECT * FROM `$table` $where ");
            $sql->execute(array($execute));
            $info = $sql->fetch();

            return $info;
        }

        public static function imagesEstoque($id){
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque-imagens` WHERE produto_id = ? ORDER BY `prioridade` DESC");
            $sql->fetchAll();
            $sql->execute(array($id));

            return $sql;
        }

        public static function imagemValida($img){
            if($img['type'] == 'image/jpg' || 
            $img['type'] == 'image/jpeg' ||
            $img['type'] == 'image/png'){


                $tamanho = intVal($img['size']/1024);
                if($tamanho < 3000){
                    return true;
                }else{
                    return  false;
                }
            }else{
                return  false;

            }
        }

        public static function produtoValido($id){
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque` WHERE id =  ?");
            $sql->fetchAll();
            $sql->execute(array($id));
            if($sql->rowCount() > 0 ){
                return true;
            }else{
                return false;
            }

        }

        public static function uploadFile($file){
            $formatarArquivo = explode('.',$file['name']);
            $imagemNome = uniqid().'.'.$formatarArquivo[count($formatarArquivo) - 1];
            if(move_uploaded_file($file['tmp_name'],DIR.'\/uploads/'.$imagemNome)){

                return $imagemNome;
            }else{
                return false;
            }
        }

        public static function deleteFile($file){
            @unlink(DIR.'/uploads/'.$file);
        }

        public static function singleProduto($id){
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.estoque` WHERE id = ? ");
            $sql->fetchAll();
            $sql->execute(array($id));
            return $sql;
        }

        public static function listar($table){
            $sql = MySql::conectar()->prepare("SELECT * FROM `$table` ORDER BY `id` DESC ");
            $sql->fetchAll();
            $sql->execute();
            return $sql;
        }

        public static function formatar($number){
            $number = str_replace(',','.',$number);
            return $number;
        }


        public static function addPayment($id,$info,$valor,$tipo,$status){
            $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.pagamentos` values(null,?,?,?,?,?) ");
            if($sql->execute(array($id,$info,$valor,$tipo,$status))){
                return true;
            }else{
                return false;
            }
        }

        public static function issetPayment($id){
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.pagamentos` WHERE `npag` = ?");
            $sql->fetchAll();
            $sql->execute(array($id));
            if($sql->rowCount() > 0){
                return false;
            }else{
                return true;
            }
        }

        public static function approvedPayment($id){
            $sql = MySql::conectar()->prepare("UPDATE `tb_admin.pagamentos` set `status` = ? WHERE `npag` = ?");
            $sql->execute(array(1,$id));
        }
        
        public static function cancelPayment($id){
            $sql = MySql::conectar()->prepare("UPDATE `tb_admin.pagamentos` set `status` = ? WHERE `npag` = ?");
            $sql->execute(array(2,$id));
        }

        public static function validarCPF($cpf) {
            // Elimina possíveis pontos e traços
            $cpf = preg_replace('/[^0-9]/', '', $cpf);
            
            // Verifica se o CPF tem 11 dígitos
            if (strlen($cpf) != 11) {
                return false;
            }
            
            // Verifica se todos os dígitos são iguais
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
            
            // Calcula os dígitos verificadores
            for ($i = 9; $i < 11; $i++) {
                $digito = 0;
                for ($j = 0; $j < $i; $j++) {
                    $digito += $cpf[$j] * (($i + 1) - $j);
                }
                $digito = (($digito % 11) < 2) ? 0 : 11 - ($digito % 11);
                if ($digito != $cpf[$i]) {
                    return false;
                }
            }
            
            return true;
        }

        function validarCNPJ($cnpj) {
            // Elimina possíveis pontos, traços e barras
            $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
            // Verifica se o CNPJ tem 14 dígitos
            if (strlen($cnpj) != 14) {
                return false;
            }
        
            // Calcula os dígitos verificadores
            $soma = 0;
            for ($i = 0; $i < 12; $i++) {
                $soma += ($cnpj[$i]) * (6 - ($i % 6 + 1));
            }
            $digito1 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
            
            $soma = 0;
            for ($i = 0; $i < 13; $i++) {
                $soma += ($cnpj[$i]) * (7 - ($i % 7 + 1));
            }
            $digito2 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        
            return ($cnpj[12] == $digito1 && $cnpj[13] == $digito2);
        }
        

    }


    

?>