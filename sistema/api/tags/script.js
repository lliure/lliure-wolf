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

bufferCarregamentoOpcoes = 0;

tag = {};

tag.loadTopicos = function(contexto){
	
	var action = contexto.attr('action');
	
    $('.sugestao', contexto)
    .on( 'click', 'a', function (){
        tag.tagSugestaoCele(contexto, action, this);
        return false;
    })
    .on('mouseenter', 'a', function (){
		console.log('aqui');
        $('.sugestao a', contexto).removeClass('cele')
        $(this).addClass('cele');
    });
	
	$('.relacionados', contexto)
    .on('click', 'a', function(){
		
		var dele = $(this).closest('span');
        
		if($(dele).attr('data-deletando') != 'true'){
			$(dele).attr('data-deletando', 'true');
			$('img', this).attr('src', 'api/tags/ajax-loader.gif');
			$('div.tag-content', dele).css('text-decoration', 'line-through');

			$.getJSON(action + '&tag=del&del=' + $(dele).attr('data-id'), function(retorno){
				if(retorno.status == 'ok'){
					$(dele).remove();
				}else{
					$('img', this).attr('src', 'imagens/icones/delete.png');
					$('div.tag-content', dele).removeAttr('style');
				}
			});
		}
        return false;
    })

    $('.pesquisa', contexto).keydown(function (event){
		
        var lista = $('.sugestao a', contexto);
        var cele = null;

        if (event.keyCode === 13){

            if(bufferCarregamentoOpcoes == 0){
				
                cele = tag.achaCele(lista);
				
                if ($('.sugestao', contexto).css('display') === 'block' && cele !== null){
                    tag.tagSugestaoCele(contexto, action, lista.eq(cele));
                }else{
                    tag.tagSubmit(contexto, action);
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
					bufferCarregamentoOpcoes = 0;
                    event.isPropagationStopped();
                    $('.sugestao', contexto).removeAttr('style').fadeOut(500, function (){$(this).html('')});
                    return false;
                break;

            }

        }

    }).keyup(function(event){

        if(event.keyCode != 13 && event.keyCode != 32 && event.keyCode != 38 && event.keyCode != 40 && event.keyCode != 27){

            var termo = new Array();

            termo = $(this).val().replace(/^\s+|\s+$/g,"").replace(/ /gi, '+');

            if(termo.length > 2 && termo != ''){

                tag.carregaSugestoes(contexto, action, termo);

            }else{
                $('.sugestao', contexto).removeAttr('style').hide().html('');
                return false;
            }
        }
		
    });
	
	
	
	$.get(action + "&tag=get", function(retorno){
		$('.relacionados', contexto).html(retorno);
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

tag.carregaSugestoes = function(contexto, action, termo){

    if($(contexto).attr('data-sugestoes') == 'false')
        return false;

    bufferCarregamentoOpcoes += 1;
	
	$('.sugestao', contexto).html('').css('background-image', 'url(api/tags/ajax-loader.gif)').fadeIn(500);

	$.get(action + "&tag=query&query=" + termo, function(retorno){
		bufferCarregamentoOpcoes -= 1;
		if(bufferCarregamentoOpcoes <= 0){
			
			bufferCarregamentoOpcoes = 0;
			
			if(retorno.length)
				$('.sugestao', contexto).html(retorno).css('background-image', 'none');
			
			else
				$('.sugestao', contexto).css('background-image', 'none').fadeOut(500, function (){$(this).html('')});
			
		}
	});
	
}

tag.adedida = function(contexto, tag){
	var achou = false;
	$('.relacionados .tag-adedida', contexto).each(function(index, value){
		achou = ($(value).attr('data-tag') == tag? true: achou);
	});
	return achou;
};

tag.tagSubmit = function(contexto, action){
	
    if($(contexto).attr('data-novos') == 'false')
        return false;

    var add = $('.pesquisa', contexto).val();
	
	
	if(!tag.adedida(contexto, add)){
		
		tag.addTag(contexto, action, add);
		
		$('.pesquisa', contexto).val('');
		$('.sugestao', contexto).removeAttr('style').hide().html('');
		
	}

    return false;
};

tag.tagSugestaoCele = function(contexto, action, cele){
	
    var add = $(cele).attr('data-tag');
    var id = $(cele).attr('data-id');
    var value = $(cele).html();

    tag.addTag(contexto, action, add, id, value);
	
    $('.pesquisa', contexto).val('');
    $('.sugestao', contexto).removeAttr('style').fadeOut(500, function (){$(this).html('')});
	
    return false;
	
}

tag.addTag = function(contexto, action, add, id, value){

	var span = 
	$('<span>', {class: 'tag-adedida', 'data-id': (id !== undefined? id: 'NULL'), 'data-tag': add}).append([
		$('<a>', {class: 'tag-bot-del'}).append([
			$('<img>', {src: 'api/tags/ajax-loader.gif'})
		]),
		$('<div>', {class: 'tag-content'}).html(
			(value !== undefined? value: add)
		)
	]);

	$('.relacionados', contexto).append(span);

	$.getJSON(action + '&tag=set&set='+ add+ (id !== undefined? '&id='+ id: ''), function(retorno){

		$(span).attr('data-id', retorno.id);
		$(span).attr('data-tag', retorno.tag);

		if(retorno.value != undefined)
			$('div.tag-content', span).html(retorno.value);

		$('a.tag-bot-del img', span).attr('src', 'imagens/icones/delete.png');

		console.log(retorno);
	});
	
}
