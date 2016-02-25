/**
*
* API navigi - lliure
*
* @Versão 6.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


$(function() {
	navigi_start();
});



function navigi_start(){
	navigi_limpAllEvent();
	
	$('#navigi').html('<span class="load"><img src="api/navigi/img/load.gif" alt=""/></span>');
	
	navigi_token = $('#navigi').attr('token');
	$('#navigi').load('api/navigi/navigi_load.php',{token: navigi_token},function(){
			$('#navigi').navigi();
		}
	);
}

navigi_selecionado = null;

jQuery.fn.extend({
	navigi: function (){			
		($(this).find('.navigi_item')).bind({
			click: function(event){
				event.stopPropagation();
				//alert(navigi_clickCount());			
				
				var id = $(this).attr('id');
				if(navigi_clickCount() >= 5 && navigi_selecionado == id) { // duplo clique					
					location = $(this).attr('dclick');
				} else if($("#navigi_inp_ren").length == 1 && navigi_selecionado != id){
					var seletor = ($('#'+navigi_selecionado).attr('seletor') == 'undefined' ? null : $('#'+navigi_selecionado).attr('seletor') );		
					navigi_rename(navigi_selecionado, $('#navigi_inp_ren').val(), seletor);
					
				} else if(	(navigi_selecionado != null && navigi_selecionado == id && navigi_clickCount() > 0 && navigi_clickCount() < 5)
							&& ($('#'+navigi_selecionado).attr('permicao') == 11 || $('#'+navigi_selecionado).attr('permicao') == 10)) {
					navigi_edit();
					return false;
				} else {
					// Clique unico //////////
					navigi_clickCount('novo');
					
					$('.navigi_item').removeClass('navigi_selecionado');
					navigi_selecionado = id;

					$('#'+id).addClass('navigi_selecionado');
				}			
			}
		});
		
		($(this).find('.navigi_tr')).bind({
			click: function(event){
				if($(this).attr('dclick') != '')
					location = $(this).attr('dclick');
			}
		});
		
		($(this).find('.navigi_del')).bind({			
			click: function(event){
				var id = $(this).parent().attr('id');
				event.stopPropagation();
			
				navigi_apaga(id);
			}
		});
		
		
		($(this).find('.navigi_bmod')).bind({			
			click: function(event){
				var href = $(this).attr('href');
				var tamanho = $(this).attr('rel');				
				tamanho = tamanho.split("x")
				
				$().jfbox({carrega: href, width: tamanho[0], height: tamanho[1]});
				return false;
			}
		});
		
		($(this).find('.navigi_ren')).bind({			
			click: function(event){
				event.stopPropagation();
				var id = $(this).parent().attr('id');
				navigi_limpAllEvent();
				
				$('#'+id).find('.navigi_nome').append('<div class="navigi_rename"><span class="nvg_ren_left">Renomear</span> <div class="nvg_ren_right"><input type="text" id="navigi_inp_lis_ren" value="'+$('#'+id).attr('nome')+'"> <div class="botoes"><span class="botao"><a href="javascript: void(0);" class="nvg_ren_ok">Renomear</a></span> <span class="botao out"><a href="javascript: navigi_limpAllEvent();">Cancelar</a></span></div> </div> </div>');
				
				$('.navigi_rename').click(function(event){
					event.stopPropagation();
				});
				
				$('.nvg_ren_ok').click(function(){
					var texto = $('#navigi_inp_lis_ren').val();
					var seletor = ($('#'+id).attr('seletor') == 'undefined' ? null : $('#'+id).attr('seletor') );	
					navigi_rename(id, texto, seletor);
				});
				
				$('.navigi_rename input').select();
			}
		});
		

		return this;
	}	
});

/***************************************			EVENTOS			***************************************/
 $('html').click(function() {
	if($('#navigi_inp_ren').length > 0){
		var seletor = ($('#'+navigi_selecionado).attr('seletor') == 'undefined' ? null : $('#'+navigi_selecionado).attr('seletor') );	
		navigi_rename(navigi_selecionado, $('#navigi_inp_ren').val(), seletor);
	} else {
		navigi_limpAllEvent();
	}
 });

$('html').jfkey('left', function(e){
	if(navigi_selecionado != null && $('#navigi_inp_ren').length == 0){
		navigi_clickCount('zera');
		$('#'+navigi_selecionado).prev().click();
	} else {
		return true;
	}
});

$('html').jfkey('right', function(e){
	if(navigi_selecionado != null && $('#navigi_inp_ren').length == 0){
		navigi_clickCount('zera');
		$('#'+navigi_selecionado).next().click();
	} else {
		return true;
	}
});

$('html').jfkey('delete,osxdelete', function(){
	if($('#navigi_inp_ren, #navigi_inp_lis_ren').length == 1){
		return true;
	} else if(navigi_selecionado != null && ($('#'+navigi_selecionado).attr('permicao') == 11 || $('#'+navigi_selecionado).attr('permicao') == 01)){
		navigi_apaga(navigi_selecionado);
	} else {
		return true;
	}
});

$('html').jfkey('f2', function(){
	if(navigi_selecionado != null && ($('#'+navigi_selecionado).attr('permicao') == 11 || $('#'+navigi_selecionado).attr('permicao') == 10))
		navigi_edit();
	else
		return true;
});

$('html').jfkey('enter', function(){	
	if($('#navigi_inp_ren').length == 1){
		var seletor = ($('#'+navigi_selecionado).attr('seletor') == 'undefined' ? null : $('#'+navigi_selecionado).attr('seletor') );		
		navigi_rename(navigi_selecionado, $('#navigi_inp_ren').val(), seletor);
		
	} else if($('#navigi_inp_lis_ren').length == 1) {
		var id = $('#navigi_inp_lis_ren').closest('tr').attr('id');
		var texto = $('#navigi_inp_lis_ren').val();
		var seletor = ($('#'+id).attr('seletor') == 'undefined' ? null : $('#'+id).attr('seletor') );				
		navigi_rename(id, texto, seletor);
		
	} else if(navigi_selecionado != null) {
		location = $('#'+navigi_selecionado).attr('dclick');
		
	} else {
		return true;
	}
});

$('html').jfkey('esc', function(){	
	navigi_limpAllEvent();
});


/***************************************			FUNÇÕES			***************************************/

function navigi_limpAllEvent(){
	if(navigi_selecionado != null){
		if($('#navigi_inp_ren').length > 0){
			$('#'+navigi_selecionado+' .navigi_nome').html($('#'+navigi_selecionado).attr('nome'));
		}
		
		$('.navigi_item').removeClass('navigi_selecionado');
		navigi_selecionado = null
		navigi_clickCount('zera');
	} else if($('.navigi_rename').length > 0){
		$('.navigi_rename').fadeOut('100', function(){
			$(this).remove();
		});
	}
}

function navigi_apaga(id){
	if(confirm('Tem certeza que deseja apagar este registro?'))
		$.post('api/navigi/delete.php', {id: id, token: navigi_token}, function(e){
			if(e == ''){
				jfAlert('Registro excluido com sucesso!', 0.7);
				$('#'+id).remove();
			} else if(e == 403)
				jfAlert('Você não tem permissão para excluir esse registro!', 2);
			else if(e == 412)
				jfAlert('Não foi possível excluir esse registro!', 2);
			else
				alert(e);
				
			
		});
}

function navigi_edit(){
	$('#'+navigi_selecionado+' .navigi_nome').html('<input value="'+$('#'+navigi_selecionado).attr('nome')+'" id="navigi_inp_ren"/>');
	$('#'+navigi_selecionado+' input').select().click(function(e){
		e.stopPropagation();
	});
}

function navigi_rename(id, texto, seletor){
	var as_id = $('#'+id).attr('as_id');
	$.post('api/navigi/rename.php', {id: as_id, texto: texto, seletor: seletor, token: navigi_token},function(e){
		if(e == ''){
			$('#'+id).find('.navigi_nome').html(texto);
			$('#'+id).attr({nome: texto});			
		} else if(e == 403)
				jfAlert('Você não tem permissão para alterar esse registro!', 2);
			else
				alert(e);
	});
}

navigi_vcc = null;
function navigi_clickCount(acao){
	if(acao == 'novo'){
		navigi_vcc = 6;
		navigi_clickCount('start');
	} else if(acao == 'start'){
		navigi_vcc--;
		
		if(navigi_vcc < 0)
			navigi_vcc = 0;
		
		if(navigi_vcc > 0)
			setTimeout(function(){
				navigi_clickCount('start');
			}, 500);
	} else if(acao == 'zera'){
		navigi_vcc = null;
	} else {
		return navigi_vcc;
	}
	
	return false;
}
