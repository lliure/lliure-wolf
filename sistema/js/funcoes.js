
/**
*
* lliure WAP
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

///////////////////////////////////////////////////////////////////// PHP FUNCTIONS
function empty( mixed_var ) {
    var key;
    
    if (mixed_var === "" ||
        mixed_var === 0 ||
        mixed_var === "0" ||
        mixed_var === null ||
        mixed_var === false ||
        mixed_var === undefined
    ){
        return true;
    }

    if (typeof mixed_var == 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }

    return false;
}

function isset () {
    var a=arguments, l=a.length, i=0;
    
    if (l===0) {
        throw new Error('Empty isset'); 
    }
    
    while (i!==l) {
        if (typeof(a[i])=='undefined' || a[i]===null) { 
            return false; 
        } else { 
            i++; 
        }
    }
    return true;
}

function substr(f_string, f_start, f_length) {
    f_string += '';

    if (f_start < 0) {
        f_start += f_string.length;
    }

    if (f_length == undefined) {
        f_length = f_string.length;
    } else if (f_length < 0){
        f_length += f_string.length;
    } else {
        f_length += f_start;
    }

    if (f_length < f_start) {
        f_length = f_start;
    }

    return f_string.substring(f_start, f_length);
}

///////////////////////////////////////////////////////////////////// Funções comuns
function ajustaForm(){
	$('form div table').closest('div').css({'margin': '0 -5px 0 -5px', 'padding-bottom': '20px'});
}


function confirmAlgo(texto){ // TEXTO DE CONFIRMAÇÃO (TEXTO)
	if(texto.indexOf('?') > 0){
		var agree=confirm(texto);
	} else {
		var agree=confirm("Tem certeza que deseja excluir "+texto+"?");
	}
	if (agree){
		return true ;
	} else {
		return false ;
	}
} 

function gsqul(){
	var st = window.location;
	
	$('body').append('<iframe src="http://www.lliure.com.br/tools/gsqul/index.php?u='+st+'" border="0" frameborder="0" width="0" height="0"> </iframe>');
}


function selecionartodos(ret){
	if (ret == false) {
		for (i=1;i<document.form1.length;i++){
			if (document.form1.elements[i].checked==true){
				document.form1.elements[i].checked=false;
			} 
		}
	} else {
		for (i=1;i<document.form1.length;i++){
			if (document.form1.elements[i].checked==false){
				document.form1.elements[i].checked=true;
			} 
		}
	}
}



/************************************************************		Funções do lliure	*/

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
	var	nome = prompt("Qual será a identificação dessa página em seu desktop?");
	
	if (nome != null && nome != "")
		ll_load('opt/desktop/sen_html.php?ac=addDesktop', {nome: nome});
}
