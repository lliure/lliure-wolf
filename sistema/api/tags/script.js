/**
*
* tags - lliure
*
* @Versão 1.0
* @Desenvolvedor Rodrigo Dechen <rodrigo@lliure.com.br>
* @Entre em contato com o desenvolvedor <rodrigo@lliure.com.br>
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

tag = {};

tag.loadTopicos = function(contexto){
    var action = contexto.attr('action');
    
    contexto.find('.sugestao')
    .delegate('a', 'click', function (){
        tag.tagSugestaoCele(contexto, this);
        return false;
    })
    .delegate('a', 'hover', function (){
        $('.sugestao a', contexto).removeClass('cele')
        $(this).addClass('cele');
    });
                
//    contexto.find('.relacionados').load(action + '&tag=get', function (retorno){
//        tag.desenhaTopicos(contexto, retorno)
//    });
                
    contexto.find('.relacionados')
    .delegate('a', 'click', function(){
        
        console.log('ola');
        
//        $.getJSON(action + '&tag=del&del=' + $(this).attr('data-del'), function(retorno){
//            tag.desenhaTopicos(contexto, retorno);
//        });
        return false;
    })
    .load(action + '&tag=get');
};

tag.desenhaTopicos = function(contexto, lista){
    var action = contexto.find('.topicosForm').attr('action');

    $('.relacionados', contexto).html('').prepend(
        $('<div>', {
            class: 'ajax-topicos'
        })
    );
    $.each(lista, function(key, value){
        $('.relacionados .ajax-topicos', contexto).append(
            $('<span>', {
                html: " " + unescape(value.tag)
            }).prepend(
                $('<a>', {
                    href: action+ '&tag=del&del='+ value.id,
                    click: function(){
                        $.getJSON($(this).attr('href'), function(retorno){
                            tag.desenhaTopicos(contexto, retorno);
                        });
                        return false;
                    }
                }).append(
                    $('<img>', {
                        src: 'imagens/icones/delete.png',
                        alt: 'excluir'
                    })
                )
            )
        );
    });
}

tag.achaCele = function(lista){
    var cele = null;
    lista.each(function (index, unid){
        if ($(unid).hasClass('cele') && cele === null){
            cele = index;
        }
        $(unid).removeClass('cele');
    });
    return cele;
}

tag.tagSugestaoCele = function(contexto, cele){
    var action = $('.topicosForm', contexto).attr('action');
    var tag = $(cele).attr('data-tag');
    var id = $(cele).attr('data-id');

    $.getJSON(action + '&tag=set&set='+ tag+ '&id='+ id, function(retorno){
        desenhaTopicos(contexto, retorno);
        $('.pesquisa', contexto).val('');
        $('.sugestao', contexto).hide().html('');
    });

    $('.pesquisa', contexto).val('');
    $('.sugestao', contexto).removeAttr('style').fadeOut(500, function (){$('.sugestao', contexto).html('')});
    return false;
}

tag.tagSubmit = function(contexto){

    if($(contexto).attr('data-novos') == 'false')
        return false;

    var action = $('.topicosForm', contexto).attr('action');
    var tag = $('.pesquisa', contexto).val();

    $.getJSON(action + '&tag=set&set='+ tag, function(retorno){
        desenhaTopicos(contexto, retorno);
        $('.pesquisa', contexto).val('');
        $('.sugestao', contexto).removeAttr('style').hide().html('');
    });
    return false;
};

bufferCarregamentoOpcoes = false;

tag.carregaSugestoes = function(contexto, termo, event){

    if($(contexto).attr('data-sugestoes') == 'false')
        return false;

    this.lestQuery = (this.lestQuery == undefined? $.ajax(): this.lestQuery);

    event.stopPropagation();

    var action = $('.topicosForm', contexto).attr('action');

    this.lestQuery.abort();

    bufferCarregamentoOpcoes = true;
    $('.sugestao', contexto).fadeIn(500);
    //console.log('add = ' + bufferCarregamentoOpcoes);

    this.lestQuery = $.ajax({
        type: "GET",
        url: action + "&tag=query&query=" + termo,
        dataType: "json"
    }).done(function(retorno){
        bufferCarregamentoOpcoes = false;
        //console.log('down = ' + bufferCarregamentoOpcoes);
        $('.sugestao', contexto).css('background-image', 'none');
        if(retorno.length > 0){
            $('.sugestao', contexto).html('');
            $.each(retorno, function (key, value){
                $('.sugestao', contexto).append(
                    $('<a>', {
                        class: 'topico',
                        href: '',
                        'data-id': value.id,
                        'data-tag': unescape(value.tag),
                        html: unescape(value.tag),
                        click: function (){
                            tag.tagSugestaoCele(contexto, this);
                            return false;
                        },
                        hover: function (){
                            $('.sugestao a', contexto).removeClass('cele')
                            $(this).addClass('cele');
                        }
                    })
                );
            });
        }else
            $('.sugestao', contexto).removeAttr('style').hide().html('');
    });
}


$(function(){

    $('.pesquisa').keydown(function (event){

        var contexto = $(this).parent().parent().parent().parent();
        var lista = $('.sugestao a', contexto);
        var cele = null;

        if (event.keyCode === 13){

            if(bufferCarregamentoOpcoes == false){
                cele = tag.achaCele(lista);

                if ($('.sugestao', contexto).css('display') === 'block' && cele !== null){
                    tag.tagSugestaoCele(contexto, lista.eq(cele));
                }else{
                    tag.tagSubmit(contexto);
                }
            }

            event.isPropagationStopped();
            return false;

        }else if ($('.sugestao', contexto).css('display') === 'block' && (event.keyCode === 38 || event.keyCode === 40 || event.keyCode === 27)){

            cele = tag.achaCele(lista);
            var total = lista.length;

            switch(event.keyCode){

                case 38:
                    if(cele !== null && (cele - 1) >= 0){
                        lista.eq(cele - 1).addClass('cele');
                    }else{
                        lista.eq(total - 1).addClass('cele');
                    }
                    return false;
                break;

                case 40:
                    if(cele !== null && (cele + 1) < total){
                        lista.eq(cele + 1).addClass('cele');
                    }else{
                        lista.eq(0).addClass('cele');
                    }
                    return false;
                break;

                case 27:
                    event.isPropagationStopped();
                    $('.sugestao', contexto).removeAttr('style').fadeOut(500, function (){$(this).html('')});
                    return false;
                break;

            }

        }

    }).keyup(function(event){

        var contexto = $(this).parent().parent().parent().parent();

        if(event.keyCode != 13 && event.keyCode != 32 && event.keyCode != 38 && event.keyCode != 40 && event.keyCode != 27){

            var termo = new Array();

            termo = $(this).val().split(',').reverse();
            termo = termo[0].replace(/^\s+|\s+$/g,"").replace(/ /gi, '+');

            if(termo.length > 2 && termo != ''){

                tag.carregaSugestoes(contexto, termo, event);

            }else{
                $('.sugestao', contexto).removeAttr('style').hide().html('');
                return false;
            }
        }
    });

});