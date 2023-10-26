$(function(){

    //mobile
    mobile();
    function mobile(){
        btn = $('.mobileMenu');
        dark = $('.dark');
        menu = $('aside');
        ln1 = $('.line1');
        ln2 = $('.line2');
        ln3 = $('.line3');
        ativado = false;
        animando = false;

        btn.click(function(){
            if(animando) return

            if(!ativado){
                ativado = true
                animando = true
                dark.fadeIn();
                ln1.css("transform",'rotate(45deg)').css("top",'3px');
                ln2.css("display",'none');
                ln3.css("transform",'rotate(-45deg)').css("top",'-4px');
                
                menu.toggle('slide',{direction: 'left'},'slow',function(){
                    menu.css("display",'flex');
                    animando = false;
                },1000).css("display",'flex');
            }else{
                animando = true
                ativado = false
                dark.fadeOut();
                ln1.css("transform",'rotate(0)').css("top",'unset');
                ln2.css("display",'block');
                ln3.css("transform",'rotate(0)').css("top",'unset');
                coluna = $('body').find('.newcolumn');
                coluna.fadeOut();
                menu.toggle('slide',{direction: 'left'},'slow',function(){
                    animando = false;
                },1000)
            }
        });

        dark.click(function(){
                animando = true
                ativado = false
                dark.fadeOut();
                ln1.css("transform",'rotate(0)').css("top",'unset');
                ln2.css("display",'block');
                ln3.css("transform",'rotate(0)').css("top",'unset');
                coluna = $('body').find('.newcolumn');
                coluna.fadeOut();
                menu.toggle('slide',{direction: 'left'},'slow',function(){
                    animando = false;
                },1000)
        });

        $(window).resize(function(){
            if( $(window).width() > 980 ){
                if(ativado){
                    animando = true;
                    ativado = false;
                    dark.fadeOut();
                    ln1.css("transform",'rotate(0)').css("top",'unset');
                    ln2.css("display",'block');
                    ln3.css("transform",'rotate(0)').css("top",'unset');
                    coluna = $('body').find('.newcolumn');
                    coluna.fadeOut();
                    menu.toggle('slide',{direction: 'left'},'slow',function(){
                        animando = false;
                    },1000)
                }
                if(menu != 'flex'){
                    menu.css("display",'flex');
                }
                
            }else{
                if(ativado){
                    animando = true;
                    ativado = false;
                    dark.fadeOut();
                    ln1.css("transform",'rotate(0)').css("top",'unset');
                    ln2.css("display",'block');
                    ln3.css("transform",'rotate(0)').css("top",'unset');
                    coluna = $('body').find('.newcolumn');
                    coluna.fadeOut();
                    menu.toggle('slide',{direction: 'left'},'slow',function(){
                        animando = false;
                    },1000)
                }else{
                    coluna = $('body').find('.newcolumn');
                    coluna.fadeOut();
                    menu.css("display",'none')
                }
            }
        });

    }

    if($('.errorMain').length || $('.sucessoMain').length > 0){
        setInterval(function(){
            $('.errorMain,.sucessoMain').slideUp();
        },6000);
    }

    //exitIcon
    exitCP()
    function exitCP(){
        var btn = $('.exitCP a');
        btn.hover(function(){
            var el = $('.exitCP').find('.fa-door-closed');
            el.removeClass("fa-door-closed").addClass("fa-door-open")
        },function(){
            var el = $('.exitCP').find('.fa-door-open');
            el.removeClass("fa-door-open").addClass("fa-door-closed")
        });
    }


});
