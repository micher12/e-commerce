$(function(){

    mobile();
    function mobile(){
        var btn = $('.mobileBtn')
        var mobile = $('.mobile')
        var ln1 = $('.ln1')
        var ln2 = $('.ln2')
        var ln3 = $('.ln3')
        var ativado = false
        var animando = false 

        btn .click(function(){
            if(animando) return

            if(!ativado){
                animando = true
                ativado = true
                ln1.css("transform","rotate(45deg)").css("top","10px")
                ln2.css("display",'none')
                ln3.css("transform","rotate(-45deg)").css("top","10px")
                mobile.toggle('slide',{direction:'up'},'slow',function(){
                    animando = false;
                })
            }else{
                ativado = false
                animando = true
                ln1.css("transform","rotate(0)").css("top","0")
                ln2.css("display",'block')
                ln3.css("transform","rotate(0)").css("top","20px")
                mobile.toggle('slide',{direction:'up'},'slow',function(){
                    animando = false;
                })
            }

        });


        $(window).resize(function(){
            if($(window).width() > 721){
                if(ativado){
                    ativado = false
                    animando = true
                    ln1.css("transform","rotate(0)").css("top","0")
                    ln2.css("display",'block')
                    ln3.css("transform","rotate(0)").css("top","20px")
                    mobile.toggle('slide',{direction:'up'},'slow',function(){
                        animando = false;
                    })
                }
            }
        });
    }

    navegation($('.singlefoot a, nav a, .mobile a'),0)
    function navegation(el,res){
        el.click(function(){
            var altura = $(el.attr(href)).offset().top;

            if(res){
                altura = altura - res;
            }else{
                altura = altura;
            }

            $('body,html').animate({
                scrollTop: altura,
            },1000)

        });
    }

});