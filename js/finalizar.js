$(function(){

   $('input[type=radio][name=type]:checked').parent().css("border-color","rgb(50, 101, 241)");

    $('input[type=radio]').click(function(){
        teste = $('body').find('input[type=radio][name=type]:checked').val();
        $('.single-opt').css("border-color","#ccc");
        $(this).parent().css("border-color","rgb(50, 101, 241)")
    })
   

    $('.finalPAY').submit(function(){
        var include_path = $('body').find('base').attr('path');
        var type = $('body').find('input[type=radio][name=type]:checked').val();
        var loading = $('.loader-main');
        var dark = $('.dark');

        $.ajax({
            beforeSend: function(){
                dark.fadeIn();
                loading.fadeIn().css("display",'flex');
            },
            type: "post",
            url: include_path+'ajax/payment.php',
            data: {type:type},
            success:function(data){
                if(type == 'paypal'){
                    dark.fadeOut();
                }
                loading.css("display",'none');
                var info = JSON.parse(data);
                if(info.sucesso){
                    if(info.tipo == 'paypal'){
                        window.location.href = info.url
                    }   

                    if(info.tipo == 'pix'){
                        $('body').prepend(`
                            <div class="pixPayment">
                                <form id='pixForm' method='post'>
                                    <div class="closePix"><i class="fa-solid fa-xmark"></i></div>
                        
                                    <div class="flex column">
                                        <label >Nome: </label>
                                        <input type="text" name='nome' placeholder="digite o seu primeiro nome" required>
                                    </div>
                        
                                    <div class="flex column">
                                        <label>Sobrenome: </label>
                                        <input type="text" name='sobrenome'  placeholder="digite o seu sobrenome" required>
                                    </div>
                        
                                    <div class='flex column'>
                                        <label>Email: </label>
                                        <input type="email" name='email' placeholder="digite o seu e-mail" required>
                                    </div>
                        
                                    <div class='flex column'>
                                        <label>CPF: </label>
                                        <input type="text" name='cpf' placeholder="digite o seu CPF" required>
                                    </div>
                        
                                    <input class='mt-20' type="submit" name='pixSend' value="PAGAR">
                        
                                </form>
                            </div>
                        `);
                        $('.pixPayment input[name=cpf]').mask('000.000.000-00');
                    }

                    if(info.tipo == 'card'){
                        $('.card-main').fadeIn();
                    }


                }

            },
        });

        return false;
    });

    pix()
    function pix(){
        var dark = $('.dark');

        $(document).on('click','.pixPayment .closePix',function(){
            dark.fadeOut();
            $('.pixPayment').fadeOut();
        });


        $(document).on('submit','.pixPayment',function(e){
            var include_path = $('body').find('base').attr('path');
            var type = $('body').find('input[type=radio][name=type]:checked').val();
            var form = $('.pixPayment');
            var loading = $('.loader-main');
            var nome = $('.pixPayment input[name=nome]').val();
            var sobrenome = $('.pixPayment input[name=sobrenome]').val();
            var email = $('.pixPayment input[name=email]').val();
            var cpf = $('.pixPayment input[name=cpf]').val();

            $.ajax({
                beforeSend: function(){
                    dark.fadeIn();
                    loading.fadeIn().css("display",'flex');
                },
                type: "post",
                url: include_path+'ajax/payment.php',
                data: {type:type,'data':true,nome:nome,sobrenome:sobrenome,email:email,cpf:cpf},
                success: function(data){
                    info = JSON.parse(data);
                    if(info.sucesso){
                        dark.fadeOut();
                        loading.fadeOut();
                        form.fadeOut();
                        window.open(info.url,'_blank');   
                        setTimeout(function(){
                            window.location.href = include_path+'compraSucesso';
                        },1000);
                    


                    }else{
                        loading.fadeOut()
                        $('.errorMain,.sucessoMain').remove();
                        $('body').prepend(`
                            <div class='errorMain'>`+info.msg+`</div>
                        `);     
                        setTimeout(function(){
                            $('.errorMain,.sucessoMain').slideUp();
                        },3000);
                     

                    }
                }
            });

            return false;
        });
    }

    $('.closeCard').click(function(){
        var form = $('.card-main');
        var dark = $('.dark');
        dark.fadeOut();
        form.fadeOut();
    });

    card()
    function card(){
        var form = $('#form-checkout');
        var include_path = $('body').find('base').attr('path');
        var loading = $('.loader-main');
        var dark = $('.dark');
        
        form.on('submit',function(){
            dark.css("z-index","15");
            loading.fadeIn().css("display",'flex').css("z-index","20");

            setTimeout(function(){
                loading.fadeOut()
                dark.css("z-indez","1");
                loading.css("z-index","5")
                window.location.href = include_path+"finalizar_compra";
            },3000);
        })
    }
    

});