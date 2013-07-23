/**
*
* jf_box
*
* @Versão 2.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$(function(){
	$('body').append('<div id="jfboxMargin"><span id="jfboxX"></span> <div id="jfboxBar"> <div id="jfboxLoad"></div> </div> </div>'
				+'<div id="jfboxFundo"></div><span id="gifJfbox" style="display: none;"></span>'
				+'<div id="jfAviso"></div>');

	$('#jfboxFundo').click(function () {
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
		
		//função do jquery que substitui os parametros que não foram informados pelos defaults
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
			gifJfbox();

			$('#jfboxLoad').load(carrega, campos, function(response, status, xhr) {
				if (status == "error")
					$("#jfboxLoad").html('Houve um erro ao carregar essa página:' + xhr.status + " " + xhr.statusText);
				
				jfboxVars.abreBox = false
				$("#jfboxLoad  .jfbox").jfbox(jfboxVars);
				gifJfbox(true);
				
				if($('#jfboxMargin').css('display') == 'none' && abreBox == true)
					abreJfbox();	

				if(typeof callback == 'function') //checa se o retorno é uma função
					callback.call(this, nthis); // executa
					
				//jfboxBar = $('#jfboxBar').jScrollPane({	contentWidth: '0px'	}).data('jsp');
			});
			
			return true;
		}
	
		function abreJfbox(){
			
			
			var scrollX = 0;
			
			if(options.height == 'auto') {
				$('html, body').animate({scrollTop: 0}, 500);
			}else{
				scrollX = $(window).scrollTop();
			}
			
			$('#jfboxBar').css({width: (jfboxVars.width != undefined ? jfboxVars.width : options.width), height: options.height});

			//Carrega o height e width da Janela
			var winH = $(window).height();
			var winW = $(window).width();
			
			$('#jfboxLoad').removeClass();
			
			if(options.addClass != '')
				$('#jfboxLoad').addClass(options.addClass);
			
			if((jfboxVars.position != undefined ? jfboxVars.position : options.position) == false){
				var top = ((winH-($('#jfboxMargin').height()+50))/2)+scrollX;
				top = top < 20 ? 20 :  top ;
				$('#jfboxMargin').css({'top': top, 'left': winW/2-($('#jfboxMargin').width()+35)/2, 'right': 'auto', 'button': 'auto'});
				
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

			$('#jfboxMargin').fadeIn(150);

			var maskHeight = $(document).height();
			var maskWidth = $(window).width();

			$('#jfboxFundo').css({'width':maskWidth,'height':maskHeight, 'background': '#efefef'});
			$('#jfboxFundo').fadeTo(300,0.75);
		}
		
		$('#fermi').click(function(){
			fechaJfbox(true);
			return false
		});
	},	
	
	jfaviso: function (texto, tempo){	/************************************************************	EXTENÃO DE AVISO	*/
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



function fechaJfbox(force){
	var temtexto = false;

	if(jfboxVars.manaFermi == false || force == true){
		if(typeof jfboxVars.fermi == 'function')
			jfboxVars.fermi.call(undefined);
			
		if(jfboxVars.inputTest == true){
			$('#jfboxLoad textarea, #jfboxLoad input[type=text]').each(function(){
				if($(this).val() != '')
					temtexto = true;
			});
		
			if(temtexto == true){
				if(confirm("Você preencheu alguns campos nesta página, tem certeza que deseja fechá-la?")) {
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
			$('#jfboxMargin').fadeOut(150);
			$('#jfboxFundo').fadeOut(300);
			$('#jfboxLoad').html('');
			
			jfboxVars();
		}	
	}
	
	return false;
}

function carregaJfbox(load){
	$().jfbox({carrega: load}); 
	return false;
}
