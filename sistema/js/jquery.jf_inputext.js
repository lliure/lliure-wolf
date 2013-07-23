/**
*
* inputText
*
* @Versão 1.0.1
* @Desenvolvedor Jeison Frasson <contato@grapestudio.com.br>
* @Entre em contato com o desenvolvedor <contato@grapestudio.com.br> http://www.grapestudio.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

(function($){
	$.fn.jf_inputext = function(settings){
		settings = $.extend(settings, { labelClass: 'label' });
		return $(this).each(function(){
			var oCampo = $(this);
			var oSpan = $('<span class="'+settings.labelClass+'">'+$(this).attr('rel')+'</span>');
			oSpan.click(function(){ oCampo.focus(); });
			if($(this).val().length > 0)
				oSpan.hide();
			
			oCampo.
			after(oSpan).
			focus(function(){
				if($(this).val() == '')
					oSpan.stop(1,1).fadeOut('fast');
			})			
			.blur(function(){
				if($(this).val() == ''){
					oSpan.fadeIn('fast');
					$(this).val('');
				}
			});
		});
	}
})(jQuery)
