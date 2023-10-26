$(function(){

    


    $('#arquivos').on('change',function(){ 
        var inupttext = $('#nomearquivos');
        var file = $(this)[0];
        var nome = [];

        var qnt = file.files.length

        for(i = 0; i < qnt; i++){
            nome.push(file.files[i].name)
        }

        inupttext.text(qnt+' arquivos foram selecionados: '+nome.join(', '))
        
    });

    
    $('#formEstoque').ajaxForm({
        dataType: 'json',
        beforeSubmit: function(){
            var loading = $('.loader-main')
            loading.fadeIn().css("display",'flex');
        },
        success: function(data){
            var loading = $('.loader-main')
            loading.fadeOut();

            if(data.sucesso){
                $('.sucessoMain,.errorMain').remove();
                $('#formEstoque').prepend('<div class="sucessoMain"><i class="fa-regular fa-circle-check" style="color: #ffffff;"></i> '+data.msg+'</div>')
                setTimeout(function(){
                    $('.sucessoMain').slideUp();
                },6000);
            }else{
                $('.sucessoMain,.errorMain').remove();
                $('#formEstoque').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> '+data.msg+'</div>')
                setTimeout(function(){
                    $('.errorMain').slideUp();
                },6000);
            }

        },
    })



    $('#editarEstoque').ajaxForm({
        dataType: 'json',
        success: function(data){
            if(data.sucesso){
                $('.sucessoMain,.errorMain').remove();
                $('#editarEstoque').prepend('<div class="sucessoMain"><i class="fa-regular fa-circle-check" style="color: #ffffff;"></i> '+data.msg+'</div>')
                setInterval(function(){
                    $('.sucessoMain').slideUp();
                },6000);
            }else{
                $('.sucessoMain,.errorMain').remove();
                $('#editarEstoque').prepend('<div class="errorMain"> <i class="fa-solid fa-circle-exclamation" style="color: #ffffff;"></i> '+data.msg+'</div>')
                setInterval(function(){
                    $('.sucessoMain').slideUp();
                },6000);
            }
        },
    })



    $('.single_view').magnificPopup({
        delegate: 'a#popup',
        type:'image',
    });

    

});