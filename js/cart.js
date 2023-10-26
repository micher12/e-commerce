$(function(){

    $('.menosClick').click(function(){
        var inputElement = $(this).siblings('input');
        var max = parseInt(inputElement.attr('max'));
        var quantidade = parseInt(inputElement.val());
        var include_path = $('body').find('base').attr('path');
        var id = $(this).parent().parent().find('input[name="produtoId"]').val();
        var min = parseInt(inputElement.attr('min'));

        if (quantidade > min) {
            quantidade -= 1;
            if (quantidade > max) {
                quantidade = max;
                $('.sucessoMain,.errorMain').remove();
                $('body').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> Limite máximo ultrapassado!</div>');
                setTimeout(function(){
                    $('.errorMain').slideUp();
                }, 6000);
            }
            inputElement.val(quantidade);
            
        } else if (quantidade === min) {
            $('.sucessoMain,.errorMain').remove();
            $('body').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> Limite mínimo alcançado!</div>');
            setTimeout(function(){
                $('.errorMain').slideUp();
            }, 6000);
        }

        $.ajax({
            type: 'POST',
            url: include_path+'ajax/cart.php',
            data: {'tipo':'quantidade',id:id,quantidade:quantidade},
            success: function(data){
                info = JSON.parse(data);
                if(info.sucesso){
                    window.location.href = include_path+'carrinho';
                }
            },
        });

    });

    $('.maisClick').click(function(){
        var inputElement = $(this).siblings('input');
        var max = parseInt(inputElement.attr('max'));
        var quantidade = parseInt(inputElement.val());
        var id = $(this).parent().parent().find('input[name="produtoId"]').val();
        var include_path = $('body').find('base').attr('path');

        if(quantidade < max){
            quantidade += 1;
            inputElement.val(quantidade)
        }

        if(quantidade == max){
            $('.sucessoMain,.errorMain').remove();
            $('body').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> Limite máximo alcançado!</div>');
            setTimeout(function(){
                $('.errorMain').slideUp();
            }, 6000);
        }

        if(quantidade > max){
            quantidade = max;
            inputElement.val(quantidade)
            $('.sucessoMain,.errorMain').remove();
            $('body').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> Limite máximo ultrapassado!</div>');
            setTimeout(function(){
                $('.errorMain').slideUp();
            }, 6000);
        }

        $.ajax({
            type: 'POST',
            url: include_path+'ajax/cart.php',
            data: {'tipo':'quantidade',id:id,quantidade:quantidade},
            success: function(data){
                info = JSON.parse(data);
                if(info.sucesso){
                    window.location.href = include_path+'carrinho';
                }
            },
        });

    }); 


    $('.excluir-item').click(function(){
        var el = $(this).parent();
        var id = el.find('input[name="produtoId"]').val()
        var include_path = $('body').find('base').attr('path');
        $.ajax({
            type: "POST",
            url: include_path+"ajax/cart.php",
            data: {'tipo':'deletar',id:id},
            success: function(data){
                info = JSON.parse(data);
                if(info.sucesso){
                    window.location.href = include_path+'carrinho';
                }
            }
        })
        
    });
    

});