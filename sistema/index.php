<?php 
/**
*
* lliure WAP
*
* @Versão 6.4
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if(!file_exists("etc/bdconf.php"))
	header('location: install/index.php');

require_once("etc/bdconf.php"); 
require_once("includes/functions.php");

/* Identifica o diretório atual do sistema */
ll_dir();

if(!isset($_SESSION['logado'])) {
	$_SESSION['ll_url'] = jf_monta_link($_GET);
	header('location: nli.php');
}

$_ll['user'] = $_SESSION['logado'];

if(!isset($_ll['mode_operacion']))
	$_ll['mode_operacion'] = 'kun_html';

$_ll['css'] = array();
$_ll['js'] = array();

require_once('includes/carrega_conf.php');

$llconf = $_ll['conf'];

$_ll['ling'] = ll_ling();
$ll_ling = $_ll['ling'];

require_once("api/gerenciamento_de_api.php"); 

$_ll['app']['header'] = null;
$_ll['app']['pagina'] = "paginas/permissao.php";


$get = array_keys($_GET);
switch(isset($get[0]) ? $get[0] : 'desk' ){
	case 'app':
		if(!empty($_GET['app'])
			&& (file_exists('app/'.$_GET['app']))){
			
			$_ll['app']['home'] = 'index.php?app='.$_GET['app'];
			$_ll['app']['onserver'] = 'onserver.php?app='.$_GET['app'];
			$_ll['app']['sen_html'] = 'sen_html.php?app='.$_GET['app'];
			$_ll['app']['pasta'] = 'app/'.$_GET['app'].'/';
						
			$llAppHome = $_ll['app']['home'];
			$llAppOnServer = $_ll['app']['onserver'];
			$llAppSenHtml = $_ll['app']['sen_html'];			
			$llAppPasta = $_ll['app']['pasta'];
			
			/**		Controle de abertura de páginas		**/			
			switch($_ll['mode_operacion']){
				
			case 'onserver':
				$_ll['app']['pagina'] = $_ll['app']['pasta'].'onserver.php';
				$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				break;
				
			case 'sen_html':
				$_ll['app']['pagina'] = $_ll['app']['pasta'].'sen_html.php';
				$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				break;
			
			case 'kun_html':
				$ll_segok = false;
				
				if(ll_tsecuryt() == false){
					if(($config = @simplexml_load_file($_ll['app']['pasta'].'/sys/config.ll')) !== false){
						
						if($config->seguranca != 'public' && (ll_securyt($_GET['app']) == true))
							$ll_segok = true;
						elseif($config->seguranca == 'public')
							$ll_segok = true;
	
					} else {
						$ll_segok = true;
					}
				} else {
					$ll_segok = true;
				}
				
				if($ll_segok){
					$_ll['app']['pagina'] = $_ll['app']['pasta'].'start.php';
					
					if(file_exists($_ll['app']['pasta'].'header.php'))
						$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';			
				}	
				
				break;			
			}
			
			
		} elseif(ll_tsecuryt('admin')) {
			$_ll['app']['pagina'] = "painel/plugins.php";
		}
		
		break;

	case 'minhaconta':
		$_GET['usuarios'] = $_ll['user']['id'];
		$_ll['css'][] = 'css/usuarios.css';
		$_ll['app']['home'] = '?minhaconta';
		$_ll['app']['header'] = 'opt/user/usuarios.header.php';
		$_ll['app']['pagina'] = 'opt/user/usuarios.php';
		break;

	case 'usuarios':
		if(ll_tsecuryt('admin')){
			$_ll['app']['pagina'] = 'opt/user/usuarios.php';
			$_ll['app']['header'] = 'opt/user/usuarios.header.php';
			$_ll['app']['home'] = '?painel';
			$_ll['css'][] = 'css/usuarios.css';
		}
		break;

	case 'painel':
		if(ll_tsecuryt('admin')){
			$_ll['app']['header'] = 'painel/header.php';
			$_ll['app']['pagina'] = 'painel/index.php';
			$_ll['app']['home'] = '?painel';
		}

		break;

	case 'desk':	
		if(isset($_ll['conf']->desktop->$_ll['user']['grupo']))
			header('location: '.$_ll['conf']->desktop->$_ll['user']['grupo']);
			
		$_ll['app']['pagina'] = "opt/desktop/desktop.php";
		$_ll['app']['header'] = 'opt/desktop/desktop.header.php';
		break;

	default:
		break;
}
/*****/

if($_ll['mode_operacion'] == 'kun_html'){
	lliure::loadJs('js/jquery.js');
	lliure::loadJs('api/tiny_mce/tiny_mce.js');
	lliure::loadJs('js/jquery-ui.js');
	lliure::loadJs('js/funcoes.js');
	lliure::loadJs('js/jquery.jfkey.js');
	lliure::loadJs('js/jquery.easing.js');
	lliure::loadJs('js/jquery.jfbox.js');

	lliure::loadCss('css/base.css');
	lliure::loadCss('css/principal.css');
	lliure::loadCss('css/paginas.css');
	lliure::loadCss('css/predifinidos.css');
	lliure::loadCss('css/jfbox.css');

	$apigem = new api;
	$apigem->iniciaApi('appbar');
	$apigem->iniciaApi('fileup');
}

/*******************************		Header			*/
if($_ll['app']['header'] != null)
	require_once($_ll['app']['header']);


/*******************************		On Server		*/
if($_ll['mode_operacion'] == 'onserver'){	
	require_once($_ll['app']['pagina']);	
	die();
}



/*******************************		Sen HTML		*/
if($_ll['mode_operacion'] == 'sen_html'){
	require_once($_ll['app']['pagina']);	
	die();
}
	
//Inicia o histórico
ll_historico('inicia');
//Inicia o Tema atual 	
if(($ll_tema = lltoObject('temas/'.$_ll['user']['tema'].'/dados.ll')) != false){
	$_ll['tema'] = (array) $ll_tema;
	$ll_icones = $_ll['tema']['icones'];
	$plgIcones = $ll_icones;
	
}
// 

require_once('kun_html.php');
?>
