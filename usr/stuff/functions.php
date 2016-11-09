<?php
/**
*
* lliure WAP
*
* @Vers?o 7.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licen?a http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once("jf.funcoes.php"); 	// include no pacote JF funções

// Apelido de funcoes
function plg_historic($mods = null, $modsQnt = 1){
	return ll_historico($mods, $modsQnt);
	}

function navig_historic(){
	return ll_historico('inicia');
	}

define('URL_NORMAL', 'URL_NORMAL');
define('URL_AMIGAVEL', 'URL_AMIGAVEL');
function ll_gourl($url, $execucao = URL_NORMAL){

	$url = parse_url($url);

	if($execucao == URL_AMIGAVEL && isset($url['query'])){
		$url['path'] = trim(str_replace('index.php', '', (isset($url['path'])? $url['path']: '')), '\\/');
		parse_str($url['query'], $url['query']);
		$url['query'] = http_build_query($url['query'], '', '/');
		$url = $url['path']. ((empty($url['path']) || empty($url['query']))? '': '/'). $url['query'];

	} elseif ($execucao == URL_NORMAL && isset($url['path']) && !empty($url['path'])) {

		global $_ll;

		if(!(file_exists($_ll['dir']. $url['path']) && is_file($_ll['dir']. $url['path']))) {
			parse_str(str_replace(array('/', '?'), array('&', ''), $url['path']), $url['path']);
			$url['path'] = http_build_query($url['path']);

			$url['query'] = trim((isset($url['query'])? $url['query']: ''), '&?');
			$url = $url['path']. ((empty($url['path']) || empty($url['query']))? '': '&'). $url['query'];
			$url = (!empty($url)? '?': ''). $url;

		}else
			return false;

	} else
		return false;

	return $url;
}

///***///
function lltoObject($file){
	if(($file = @simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA)) != false){
		$file = jf_ota($file);
		array_walk_recursive($file, function(&$item, $key){
			$item = utf8_decode($item);
		});			
	}
	
	$file = jf_ato($file);
	
	//var_dump($file); die();
	
	return $file;
}

function get_include_contents($filename) {
    if (is_file($filename)) {
		ob_start();
        include 'etc/llconf.ll';
        $var = ob_get_contents();
		ob_end_clean();
		
		return $var;
    }
    return false;
}

// define a constante ll_dir com o diret?rio atual de onde est? o sistema
function ll_dir(){

	global $_ll;
	$dir = realpath(dirname(__FILE__). '/../../'). DIRECTORY_SEPARATOR;

	define("ll_dir", $dir);
	$_ll['dir'] = $dir;
	
	return true;
}

	
function ll_historico($mods = null, $modsQnt = 1){
	global $backReal;
	global $backNome;

	$retorno = true;
	
	switch($mods){
	case 'inicia':
		if(!empty($_GET)){
			$keyGet = array_keys($_GET);

			$pageatual = '?'.$_SERVER['QUERY_STRING'];
			if(isset($_SESSION['historicoNav']) && !empty($_SESSION['historicoNav'])){
				$count = count($_SESSION['historicoNav']);
				
				if($count > 1 && $pageatual == $_SESSION['historicoNav'][$count-2]){
					array_pop($_SESSION['historicoNav']);					
				} elseif($pageatual == $_SESSION['historicoNav'][$count-1]){
					// n?o faz nada caso a p?gina atual for igual a ?ltima p?gina visitada
				} else {
					$_SESSION['historicoNav'][] = $pageatual;
				}
			} else {				
				$_SESSION['historicoNav'][0] = $pageatual;
			}
			
			ll_historico();
			
		} else {
			if(isset($_SESSION['historicoNav'])){
				unset($_SESSION['historicoNav']);
			}		
		}

		return true;	
	break;

	case 'reinicia':
		$pageatual = '?'.$_SERVER['QUERY_STRING'];
		unset($_SESSION['historicoNav']);
		$_SESSION['historicoNav'][0] = $pageatual;
	break;
	
	case 'return':
		for($i = 0; $i < $modsQnt;$i++)
			array_pop($_SESSION['historicoNav']); // APAGA ESSA P?GINA DO HIST?RICO
	break;
	
	default:
	break;
	}

	$historico = $_SESSION['historicoNav'];
	$i = count($historico)-1;
	
	if($i > 0){
		$i--;
		$backReal = $historico[$i];
		$backNome = "Voltar";
	} else {	
		$backReal = "index.php";
		$backNome = "Voltar a área de trabalho";
	}
	
	return $retorno;
};

// fun??o que testa a segurança de uma página
function ll_securyt($app){
	global $_ll;
	/*
	No aquivo config.plg contido na pasta sys do aplicativo voc? insere a url de onde est?ra o arquivo de configura??o de seguran?a, que normalmente est?ra em etc/nome_do_aplicativo/seguranca.ll
	
	Exemplo de um arquivo de seguran?a:
	
	<?xml version="1.0" encoding="iso-8859-1"?>
	<seguranca>
		<user> <p>banners</p> <grupo>$</grupo> </user>
		<user> <p>banners</p> <grupo>$</grupo> <id>$</id> </user>
	</seguranca>


	<sguranca> ? o container onde est?ram as diretrizes
	<user> ? o nome do grupo que tera permiss?o para acessar a url
		
		para configurar coloque a chave do get depois o valor <chave>valor</chave>
		exemplos para as urls:
			app=teste&p=usuarios		=	<user> <p>usuarios</p> </user>
			app=teste&p=modulos			=	<user> <p>modulos</p> </user>
		
		
		caso possa acessar qualquer valor dentro deste get utilize '%'
			app=teste&p=usuarios&id=5	=	<user> <p>usuarios</p> <id>%</id> </user>
			app=teste&p=usuarios&id=10	=	<user> <p>usuarios</p> <id>%</id></user>
		
		utilize "$" como id do usu?rio logado
			app=teste&p=usuarios&user=10&ac=editar	=	<user> <p>usuarios</p> <id>$</id> <ac>editar</ac></user>
			// nesse caso se o usu?rio logado n?o possuir o id 10 ele n?o ter? acesso a essa tela
		
		como voc? pode verificar n?o ? necess?rio setar o primeiro get, no caso o que aponta para o aplicativo em quest?o
			app=teste	=	<user></user>
	*/

	$grupo = $_SESSION['ll']['user']['grupo'];
	if(($appConfig = @simplexml_load_file('etc/'.$app.'/seguranca.ll')) == true){
		
		$i = 0;
		if($appConfig->$grupo == 'public')
			return true;
		
		foreach($appConfig->$grupo as $urls){
			$permissao[$i] = array('app' => $_GET['app']);
			
			foreach((array) $urls as $indice => $valor)
				$permissao[$i][$indice] = (
							isset($_GET[$indice]) && 
							(!isset($valor) || 
								$valor == '%' || 
								($valor == '$' && $_GET[$indice] == $_ll['user']['id'])
							) ? $_GET[$indice] : $valor );
			
			$final = array_merge(array_diff($_GET, $permissao[$i]), array_diff($permissao[$i], $_GET));
				if(empty($final))
					return true;
			
			$i++;
		}
	}
	return false;
}

// fun??o para testar permi??o do usu?rio
function ll_tsecuryt($grupo = null){
	/*
	Para usar basta puxar esta fun??o dentro de um if() ela ir? retornar true quando o usu?rio for desenvolverdor ou quando for especificado
	exemplos de utiliza??o
	
	if(ll_tsecuryt()) // se estiver logado como desenvolvedor ir? retornar true
	if(ll_tsecuryt('admin')) // se estiver logado como admin ir? retornar true
	if(ll_tsecuryt('user')) // se estiver logado como user ir? retornar true
	if(ll_tsecuryt(array('user', 'admin'))) ou if(ll_tsecuryt('user,admin')) ou if(ll_tsecuryt('user','admin'))// se estiver logado como user ou como admin ir? retornar true
	*/
	
	if(func_num_args() > 1){
		$grupo = func_get_args();
	} else {
		if(!is_array($grupo) && strpos($grupo, ','))
			$grupo = explode(',', $grupo);
	}
	
	$grupo_user = $_SESSION['ll']['user']['grupo'];
	switch($grupo_user){
		case 'dev':
			return true;
		break;
		
		default:
			if((is_array($grupo) && in_array($grupo_user, $grupo)) || $grupo == $grupo_user)
				return true;
			else
				return false;
		break;
	}
}

//função que retorna a linguagem nativa, caso não tenha nenhuma retorna false
function ll_ling(){
	global $_ll;		
	
	$retorno = false;	
	
	if(isset($_ll['conf']->idiomas) && !empty($_ll['conf']->idiomas))
		$retorno = (string) $_ll['conf']->idiomas->nativo;
		
	return $retorno;
}

$ll_lista_idiomas = array(
	'pt_br' => 'Português (Brasil)',
	'en' => 'Inglês',
	'es' => 'Espanhol',
	'fr' => 'Francês',
	'it' => 'Italiano',
	'de' => 'Alemão',
	'ar' => 'Árabe',
	'zh' => 'Chinês',
	'ja' => 'Japonês',
	'ru' => 'Russo',
);

// in
function in($var,$type = 'VALUE') {
	$in = '';
	foreach ($var as $k=>$v) {
		if($type=='VALUE')
			$in.="'".$v."',";
		else 
			$in.="'".$k."',";
	}
	$in = substr($in,0,-1);
	return $in;
}
?>
