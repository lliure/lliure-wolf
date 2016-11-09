/**
*
* jf_box
*
* @Versão 3.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


//'<div id="jfboxScroll"> <div id="jfboxMargin"><span id="jfboxX"></span> <div id="jfboxBar"> <div id="jfboxLoad"></div> </div> </div></div>'
//+'<span id="gifJfbox" style="display: none;">Carregando...</span>'
//+'<div id="jfAviso"></div>'

$(function(){
	$('body').append([
		'<div id="jfBoxModal" class="modal fade" tabindex="-1">',
			'<div id="jfBoxLock"></div>',
			'<div id="jfBoxLoad">Carregando...</div>',
			'<div class="modal-dialog">', '</div>',

			//'<div class="modal-dialog">',
			//	'<div class="modal-content"></div>',
			//'</div>',
		'</div>'
	].join(''));

//	$('#jfboxMargin').bind({
//		click: function(event){
//			event.stopPropagation();
//		}
//	});
	
//	$('#jfboxScroll').click(function () {
//		fechaJfbox();
//		return false;
//	});

//	$('#jfboxX').click(function () {
//		fechaJfbox();
//		return false;
//	});

});


/*
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
		};

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
				if(!!$(this).attr('href')){
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

			jfboxVars.fermi = options.fermi == null && jfboxVars.fermi != 'undefined' ? jfboxVars.fermi : options.fermi;
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

	// EXTENÇÃO DE AVISO
	jfaviso: function (texto, tempo){
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
	var aviso = $("#jfAviso");

	aviso.html('<div class="msm">'+texto+'</div> <span class="fechar"></span>');

	var scrollX = $(window).scrollTop();
	var winW = $(window).width();
	var winH = $(window).height();
	
	winW = winW/2-(aviso.width()+20)/2;
	winH = (winH/2-((aviso.height()+40)/2))+scrollX;
	
	aviso.css({top: winH, left: winW});
	aviso.stop(true, true).fadeIn(300);
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
} */


;jfBox = {};
(function($){

	var modal = function(url, settings){

		var mySettings = {};
		var onAlways = [];
		var onDone = [];
		var onFail = [];
		var onProgress = [];
		var myDeferred = {};
		var self = this;

		url = ((typeof url == "undefined")? {}: url);
		settings = ((typeof settings == 'undefined')? url: settings);
		mySettings = $.extend({

			/**
			 * Largura da modal.
			 * String: [sm, md, lg]
			 * Int: Largura em px.
			 *
			 * @param string|int width
			 */
			width: 'md',
			/**
			 * Altura da modal
			 * Int: altura em px
			 * Bool: se false altomatico.
			 *
			 * @param int|bool height
			 */
			height: false,

			/**
			 * Modo que regula o tamanho da modal.
			 * Se TRUE a modal se ajusta ao tamanho da tela.
			 * Se FALSE a modal asume as configurações de width e height.
			 *
			 * @param bool full
			 */
			full: false,

			/**
			 * Classe adicionada ao modal quando aberto.
			 *
			 * @param string addClass
			 */
			addClass: '',

			/**
			 * trava a box imposibilitando ser feixada.
			 *
			 * @param bool lock
			 */
			lock: false,

			url: ((typeof url == "string")? url:
				((typeof settings == "object" && settings.hasOwnProperty('url') && typeof settings.url == "string")? settings.url: null))

		}, settings);


		var $modal = $('#jfBoxModal');
		var $modalDialog = $modal.find('.modal-dialog');

		this.modal = function(){
			return $modal[0]};

		var modalFunctionHide = function(){
			$modal.off('hide.bs.modal', modalFunctionHide);
			$modal.removeClass('load');
			if(!!myDeferred.promise) myDeferred.reject();
			myDeferred = {}};

		$modal.off('hidden.bs.modal').on('hidden.bs.modal', function(){
			$modalDialog.html(''); self.setClass();
			$modalDialog.removeClass('modal-sm modal-md modal-lg modal-full').removeAttr('style');
		});

		this.lockOn = function(){
			$modal.attr('data-lock', 'on');
			return this;
		};

		this.lockOff = function(){
			$modal.removeAttr('data-lock');
			return this;
		};

		this.always = function(f){
			if($.isFunction(f)) {
				if(!!myDeferred.promise)
					myDeferred.always(f);
				else
					onAlways.push(f);
			}else
				return onAlways;

			return this;
		};

		this.done = function(f){
			if($.isFunction(f)){
				if(!!myDeferred.promise)
					myDeferred.done(f);
				else
					onDone.push(f);
			}else
				return onDone;

			return this;
		};

		this.fail = function(f){
			if($.isFunction(f)) {
				if(!!myDeferred.promise)
					myDeferred.fail(f);
				else
					onFail.push(f);
			}else
				return onFail;

			return this;
		};

		this.progress = function(f){
			if($.isFunction(f)){
				if(!!myDeferred.promise)
					myDeferred.progress(f);
				else
					onProgress.push(f);
			}else
				return onProgress;

			return this;
		};

		this.then = function(d, f, a){
			if(!!myDeferred.promise)
				myDeferred.then(d, f, a);

			else
				this.done(d).fail(f).always(a);

			return this;
		};

		this.promise = function(){
			if(!!myDeferred.promise) return myDeferred.promise();
		};

		this.deferred = function(Deferred){
			//console.log(myDeferred, self.state());

			if(!myDeferred.promise || self.state() != 'pending'){
				Deferred.done(this.done());
				Deferred.fail(this.fail());
				Deferred.always(this.always());
				myDeferred = Deferred; }

			return myDeferred;
		};

		this.state = function(){
			if(!!myDeferred.promise)
				return myDeferred.state();
			return false
		};

		this.setClass = function(addClass){
			if(typeof addClass == "undefined")
				$modal.removeAttr('class').addClass('modal fade');

			else
				$modal.addClass(addClass);

			return this;
		};

		this.setDimension = function(width, height, full){
			width = ((typeof width == "undefined")? 'md': width);
			height = ((typeof height == "undefined")? false: height);
			full = ((typeof full == "undefined")? false: full);

			$modalDialog.removeClass('modal-sm modal-md modal-lg modal-full').removeAttr('style');

			if(full) $modalDialog.addClass('modal-full');

			else {
				if (typeof settings.width == "string")
					$modalDialog.addClass('modal-' + width);

				else
					$modalDialog.css({'max-width': width});

				if (typeof settings.height == "number")
					$modalDialog.css({'min-height': height});}

			return this;
		};

		this.open = function(settings){

			settings = $.extend({}, mySettings, settings);
			$modal.on('hide.bs.modal', modalFunctionHide);

			if(!myDeferred.promise || self.state() != 'pending')
				self.deferred(new $.Deferred());

			if(!$modal.is(':visible')) {
				self.lockOn();
				$modal.modal('show');
				self.setClass(settings.addClass);
				self.setDimension(settings.width, settings.height, settings.full)}

			$modal.removeClass('load').addClass('loading');

			$.when((function(){
				var dfd = new $.Deferred();

				var ajax = {};

				if(settings.hasOwnProperty('url')) ajax.url = settings.url;
				if(settings.hasOwnProperty('method')) ajax.method = settings.method;
				if(settings.hasOwnProperty('data')) ajax.data = settings.data;

				$.ajax(ajax).done(function( content ){
					dfd.resolve(content);
				}); return dfd;

			})(), (function(){

				var dfd = new $.Deferred();
				setTimeout(function(){ dfd.resolve(); }, 300);
				return dfd;

			})()).done(function(content){

				self.content(content);
				$modal.removeClass('loading');

				setTimeout(function () {
					$modal.addClass('load');
					jfBox.ready(null, self);
					if(!settings.lock) self.lockOff();
				}, 10);
			});

			return this;
		};

		this.clouse = function(){
			if($modal.attr('data-lock') != 'on')
				$modal.modal('hide');
			return this;
		};

		this.content = function (content) {
			if(!content) return this;

			var $modalContent = $(['<div class="modal-content">', content, '</div>'].join(''));
			$modalDialog.html($modalContent);

			return $modalContent;
		};
	};


	jfBox = function(url, settings){

		jfBox.aReady = [];
		jfBox.ready = function(cb, modal){
			if($.isFunction(cb)){
				jfBox.aReady.push(cb);

			}else{
				for(var i in jfBox.aReady)
					if(!(jfBox.aReady[i].call(modal.modal(), modal))) break;
				jfBox.aReady = [];
			}
		};

		return new modal(url, settings);
	};

	$.fn.jfbox = function(url, settings){

		var myModal = jfBox(url, settings);
		if(!settings) settings = {};

		$(this).each(function (i, e){
			$(e).filter('form').on('submit', function(){

				console.log('submit-bind');

				var action = $(this).attr('action');
				if(!!action) settings.url = action;

				var method = $(this).attr('method');
				settings.method = ((!!method)? method: 'post');

				settings.data = $(this).serializeArray();

				myModal.open(settings);
				return false;

			});
			$(e).filter(':not(form)').on('click', function(){

				var href = $(this).attr('href');
				if(!!href) settings.url = href;

				myModal.open(settings);
				return false;

			});
		});

		return myModal;
	};

	/* $.jfbox = function(url, settings){

		var myModal = jfBox(url, settings);
		myModal.open();

		return myModal;

	}; */

	$(function(){
		var body = $('body');
		body.on('click', '.jfbox:not(form)', function(){
			var settings = {};

			var href = $(this).attr('href');
			if(!!href) settings.url = href;

			jfBox(settings).open();
			return false;
		});
		body.on('submit', 'form.jfbox', function(){
			var settings = {};

			var action = $(this).attr('action');
			if(!!action) settings.url = action;

			var method = $(this).attr('method');
			settings.method = ((!!method)? method: 'post');

			settings.data = $(this).serializeArray();

			jfBox(settings).open();
			return false;
		});
	});

})(jQuery);