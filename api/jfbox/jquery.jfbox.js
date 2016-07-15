/**
*
* jf_box
*
* @VersÃ£o 3.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @LicenÃ§a http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$(function(){
	$('body').append('<div id="jfboxScroll"> <div id="jfboxMargin"><span id="jfboxX"></span> <div id="jfboxBar"> <div id="jfboxLoad"></div> </div> </div></div>'
				+'<span id="gifJfbox" style="display: none;">Carregando...</span>'
				+'<div id="jfAviso"></div>');

	$('#jfboxMargin').bind({
		click: function(event){
			event.stopPropagation();
		}
	});
	
	$('#jfboxScroll').click(function () {
		
		fechaJfbox();
		return false;
	});

	$('#jfboxX').click(function () {
		fechaJfbox();
		return false;
	});

 });
 
 jQuery.fn.extend({
	jfbox: function (parametros, callback){
		//parametros default
		var sDefaults = {
			width: 763,
			height: 470,
			abreBox: true,
			carrega: false,
			campos: false,
			position: false,
			addClass: '',
			fermi: null,
			manaFermi: false
		}
		
		//funÃ§Ã£o do jquery que substitui os parametros que nï¿½o foram informados pelos defaults
		var options = jQuery.extend(sDefaults, parametros);		
		
		$(this).bind({
			submit: function() {
				var carrega = $(this).attr('action');
				var campos =  $(this).serializeArray();

				loadJfbox(carrega, campos);

				return false;
			},
		
			click: function() {
				if(typeof $(this).attr('href') !== "undefined" && $(this).attr('href')){
					var carrega = $(this).attr('href');
					loadJfbox(carrega, null, options.abreBox, this);

					return false;
				}
				
			}
		});	
		
		if(options.carrega){
			loadJfbox(options.carrega, options.campos, options.abreBox); 
		}
		
		function loadJfbox(carrega, campos, abreBox, nthis){
			jfboxVars.fermi = options.fermi == null && jfboxVars.fermi != 'undefined' ? jfboxVars.fermi : options.fermi ;		
			jfboxVars.manaFermi = options.manaFermi;

			
			nthis = nthis != 'undefined' ? nthis : null;
			
			
			$('#jfboxScroll').show(0, function(){
				
				$('body').css('overflow','hidden');
				gifJfbox();
				//$('#jfboxScroll').animate({ 'background-color': 'rgba(0, 0, 0, 0.25)' }, 500);
				$('#jfboxScroll').css({ 'background-color': 'rgba(0, 0, 0, 0.25)' });
				
			});		
			
			
			$('#jfboxLoad').load(carrega, campos, function(response, status, xhr) {
				
				if (status == "error")
					$("#jfboxLoad").html('Houve um erro ao carregar essa página:' + xhr.status + " " + xhr.statusText);
				
				jfboxVars.abreBox = false
				$("#jfboxLoad  .jfbox").jfbox(jfboxVars);
				gifJfbox(true);
				
				if($('#jfboxMargin').css('display') == 'none' && abreBox == true)
					abreJfbox();	

				if(typeof callback == 'function') //checa se o retorno ï¿½ uma funï¿½ï¿½o
					callback.call(this, nthis); // executa
			});
			
			return true;
		}
	
		function abreJfbox(){	
			$('#jfboxScroll').scrollTop('0');
						
			$('#jfboxBar').css({width: (jfboxVars.width != undefined ? jfboxVars.width : options.width), height: options.height});
		
			//Carrega o height e width da Janela
			var winH = $(window).height();
			var winW = $(window).width();
			
			$('#jfboxLoad').removeClass();
			
			if(options.addClass != '')
				$('#jfboxLoad').addClass(options.addClass);
			if((jfboxVars.position != undefined ? jfboxVars.position : options.position) == false){
				var top = ((winH-($('#jfboxMargin').height())-40)/2);
				var left = winW/2-($('#jfboxMargin').width()+35)/2;
				top = top < 20 ?  0 : top;	
				
				$('#jfboxMargin').css({'top': top, 'left': left, 'right': 'auto', 'button': 'auto'});
				
			} else {	
				if(options.position == 'maximized'){
					$('#jfboxMargin').css({'top':  '15px', 'bottom':  '15px', 'right':'15px', 'left': '15px', padding: '0'});
					$('#jfboxBar').css({'width':'100%', 'height':'100%'});
				} else {					
					(options.position[2] == 'button' 
							? $('#jfboxMargin').css({'top':  'auto', 'button': options.position[0]})
							: $('#jfboxMargin').css({'top':  options.position[0], 'button': 'auto'})
					);
					
					(options.position[3] == 'right' 
							? $('#jfboxMargin').css({'right':  options.position[1], 'left': 'auto'})
							: $('#jfboxMargin').css({'right':  'auto', 'left': options.position[1]})
					);
				}
			}

			$('#jfboxMargin').fadeIn(300);
			
		}
		
		$('#fermi').click(function(){
			fechaJfbox(true);
			return false
		});
	},	
	
	jfaviso: function (texto, tempo){	/************************************************************	EXTENï¿½O DE AVISO	*/
		jfAlert(texto, tempo);
	}

});

function gifJfbox(fechar){
	if(typeof fechar !== "undefined" && fechar){
		$('#gifJfbox').css({display: 'none'});
	} else {
		var scrollX = $(window).scrollTop();
		var winH = $(window).height();
		var winW = $(window).width();
		
		$('#gifJfbox').css('top',  (winH/2-32/2)+scrollX);
		$('#gifJfbox').css('left', winW/2-32/2);
		
		$('#gifJfbox').css({display: 'block'});
		
	}
}

function jfAlert(texto, tempo){
	$(function(){
		if(typeof tempo == "undefined" && !tempo)
				tempo = 2;
				
		tempo = tempo*1000;
		
		$("#jfAviso").html('<div class="msm">'+texto+'</div>');
		
		var scrollX = $(window).scrollTop();
		var winW = $(window).width();
		var winH = $(window).height();
		
		winW = winW/2-($('#jfAviso').width()+20)/2;
		winH = (winH/2-(($('#jfAviso').height()+40)/2))+scrollX;
		
		$('#jfAviso').css({top: winH, left: winW});
		
		$('#jfAviso').stop(true, true).fadeIn(300, function(){
			
			setTimeout(function(){
				$("#jfAviso").stop(true, true).fadeOut(300, function(){
					$('#jfAviso').html('');
				});
			}, tempo);
			
		});
	});
	
}

function jfboxVars(){ 
	jfboxVars.width = undefined;
	jfboxVars.height = undefined;
	jfboxVars.inputTest = undefined;
	jfboxVars.position = undefined;
	jfboxVars.fermi = undefined;
	jfboxVars.manaFermi = undefined;
}

function jfConfirm(texto){	
	$("#jfAviso").html('<div class="msm">'+texto+'</div> <span class="fechar"></span>');

	var scrollX = $(window).scrollTop();
	var winW = $(window).width();
	var winH = $(window).height();
	
	winW = winW/2-($('#jfAviso').width()+20)/2;
	winH = (winH/2-(($('#jfAviso').height()+40)/2))+scrollX;
	
	$('#jfAviso').css({top: winH, left: winW});
	
	$('#jfAviso').stop(true, true).fadeIn(300, function(){

	});	
}



function fechaJfbox(force, args){
	var temtexto = false;

	if(jfboxVars.manaFermi == false || force == true){
		if(typeof jfboxVars.fermi == 'function')
			jfboxVars.fermi.call(undefined, args);
			
		if(jfboxVars.inputTest == true){
			$('#jfboxLoad textarea, #jfboxLoad input[type=text]').each(function(){
				if($(this).val() != '')
					temtexto = true;
			});
		
			if(temtexto == true){
				if(confirm("Você preencheu alguns campos nesta página, tem certeza que deseja fechar?")) {
					jfboxVars.inputTest = false;
					fechaJfbox();
				} else {
					return false;
				}
			} else {
				jfboxVars.inputTest = false;
				fechaJfbox();
			}
				
		
		} else {			
			$('#jfboxScroll').fadeOut('150', function(){
				$('body').css('overflow','visible');
				$('#jfboxMargin').hide();
				$('#jfboxMargin').css({'top': 'auto', 'bottom':  'auto', 'right': 'auto', 'left': 'auto'});
				$('#jfboxLoad').html('');				
				$('#jfboxScroll').css({'background-color': 'rgba(0, 0, 0, 0)' });
			});
			
			
			jfboxVars();
		}	
	}
	
	return false;
}

function carregaJfbox(load){
	$().jfbox({carrega: load}); 
	return false;
}
