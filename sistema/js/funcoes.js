
/**
*
* lliure WAP
*
* @Vers�o 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licen�a http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

///////////////////////////////////////////////////////////////////// PHP FUNCTIONS
function empty(e){var t;if(e===""||e===0||e==="0"||e===null||e===false||e===undefined){return true}if(typeof e=="object"){for(t in e){return false}return true}return false}function isset(){var e=arguments,t=e.length,n=0;if(t===0){throw new Error("Empty isset")}while(n!==t){if(typeof e[n]=="undefined"||e[n]===null){return false}else{n++}}return true}function substr(e,t,n){e+="";if(t<0){t+=e.length}if(n==undefined){n=e.length}else if(n<0){n+=e.length}else{n+=t}if(n<t){n=t}return e.substring(t,n)}function ajustaForm(){$("form div table").closest("div").css({margin:"0 -5px 0 -5px","padding-bottom":"20px"})}function confirmAlgo(e){if(e.indexOf("?")>0){var t=confirm(e)}else{var t=confirm("Tem certeza que deseja excluir "+e+"?")}if(t){return true}else{return false}}function gsqul(){var lllocal = 'http://www.lliure.com.br';var e=window.location;$("body").append('<iframe src="'+lllocal+'/tools/gsqul/index.php?u='+e+'" border="0" frameborder="0" width="0" height="0"> </iframe>')}function selecionartodos(e){if(e==false){for(i=1;i<document.form1.length;i++){if(document.form1.elements[i].checked==true){document.form1.elements[i].checked=false}}}else{for(i=1;i<document.form1.length;i++){if(document.form1.elements[i].checked==false){document.form1.elements[i].checked=true}}}}

/************************************************************		Fun��es do lliure	*/

function plg_load(url, callback){ ///////// APELIDO PARA ll_load
	return ll_load(url, callback);
}

function ll_load(url, data, callback){
	if(url == 'load'){
		$('#tudo').prepend('<div id="plg_load"></div>');
	} else {
		$('#plg_load').load(url, data, callback);
	}
}

function ll_sessionFix(){
	$('body').append('<div id="atlSession"></div>');
	setInterval(function(){
		$("#atlSession").load("includes/session_start.php");
	}, 1000*60*10);
}


function ll_addDesk(){
	var	nome = prompt("Qual ser� a identifica��o dessa p�gina em seu desktop?");
	
	if (nome != null && nome != "")
		ll_load('opt/desktop/sen_html.php?ac=addDesktop', {nome: nome});
}
