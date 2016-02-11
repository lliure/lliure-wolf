<?php
/**
*
* lliure WAP
*
* @Versão 7.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
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
	if($execucao == 'URL_AMIGAVEL'){	
		if(strpos($url, '?') !== false){
			$url = str_replace(array('index.php', '?','&'), array('' ,'', '/'), $url);
			if($url[0] == '/')
				$url = substr($url, 1);
		} else 
			$url = false;

	} else {
		if(strpos($url, '/') !== false)
			$url = '?'.str_replace(array('/', '?'), array('&', ''), $url);
		else
			$url = false;
	}
	
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

// define a constante ll_dir com o diretório atual de onde está o sistema
function ll_dir(){
	$dir_c = dirname(__FILE__);
	
	if(strstr($dir_c , '/'))
		$dir = explode('/', $dir_c);
	else 
		$dir = explode('\\', $dir_c);

	array_pop($dir);
	array_pop($dir);

	if(strstr($dir_c , '/'))
		$dir = implode('/', $dir).'/';
	else 
		$dir = implode('\\', $dir).'\\';

	define("ll_dir", $dir);
	
	return true;
	}

	
function ll_historico($mods = null, $modsQnt = 1){
	global $backReal;
	global $backNome ;

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
					// não faz nada caso a página atual for igual a última página visitada
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
			array_pop($_SESSION['historicoNav']); // APAGA ESSA PÁGINA DO HISTÓRICO
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
		$backNome = "Voltar à área de trabalho";
	}
	
	return $retorno;
};

// função que testa a segurança de uma página
function ll_securyt($app){
	global $_ll;
	/*
	No aquivo config.plg contido na pasta sys do aplicativo você insere a url de onde estára o arquivo de configuração de segurança, que normalmente estára em etc/nome_do_aplicativo/seguranca.ll
	
	Exemplo de um arquivo de segurança:
	
	<?xml version="1.0" encoding="iso-8859-1"?>
	<seguranca>
		<user> <p>banners</p> <grupo>$</grupo> </user>
		<user> <p>banners</p> <grupo>$</grupo> <id>$</id> </user>
	</seguranca>


	<sguranca> é o container onde estáram as diretrizes
	<user> é o nome do grupo que tera permissão para acessar a url
		
		para configurar coloque a chave do get depois o valor <chave>valor</chave>
		exemplos para as urls:
			app=teste&p=usuarios		=	<user> <p>usuarios</p> </user>
			app=teste&p=modulos			=	<user> <p>modulos</p> </user>
		
		
		caso possa acessar qualquer valor dentro deste get utilize '%'
			app=teste&p=usuarios&id=5	=	<user> <p>usuarios</p> <id>%</id> </user>
			app=teste&p=usuarios&id=10	=	<user> <p>usuarios</p> <id>%</id></user>
		
		utilize "$" como id do usuário logado
			app=teste&p=usuarios&user=10&ac=editar	=	<user> <p>usuarios</p> <id>$</id> <ac>editar</ac></user>
			// nesse caso se o usuário logado não possuir o id 10 ele não terá acesso a essa tela
		
		como você pode verificar não é necessário setar o primeiro get, no caso o que aponta para o aplicativo em questão
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

// função para testar permição do usuário
function ll_tsecuryt($grupo = null){
	/*
	Para usar basta puxar esta função dentro de um if() ela irá retornar true quando o usuário for desenvolverdor ou quando for especificado
	exemplos de utilização
	
	if(ll_tsecuryt()) // se estiver logado como desenvolvedor irá retornar true
	if(ll_tsecuryt('admin')) // se estiver logado como admin irá retornar true
	if(ll_tsecuryt('user')) // se estiver logado como user irá retornar true
	if(ll_tsecuryt(array('user', 'admin'))) ou if(ll_tsecuryt('user,admin')) ou if(ll_tsecuryt('user','admin'))// se estiver logado como user ou como admin irá retornar true
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

//função que grava e retorna texto do alert
function ll_alert($texto = null, $tempo = 1){
	if(empty($texto)){
		if(isset($_SESSION['aviso'])){
			$tempo_m = 1; 
			if(isset($_SESSION['aviso'][1]))
				$tempo_m = $_SESSION['aviso'][1]; 
			echo 'jfAlert("'.$_SESSION['aviso'][0].'", "'.$tempo_m.'");';
			unset($_SESSION['aviso']);
		}
	} else {
		$_SESSION['aviso'][0] = $texto;
		$_SESSION['aviso'][1] = $tempo;
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
	'fr' => 'francês',
	'it' => 'italiano',
	'de' => 'alemão',
	'ar' => 'árabe',
	'zh' => 'chinês',
	'ja' => 'japonês',
	'ru' => 'russo',
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
