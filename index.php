<?php
/**
 * Iniciação do lliure
 *
 * @Versão do lliure 8.0
 * @Pacote lliure
 *
 * Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 */

//echo '<pre>'. print_r($_SERVER, true). '</pre>'; // die();
//header('Content-Type: text/html; charset=iso-8859-1');
//if(!file_exists("etc/bdconf.php")) header('location: opt/install/index.php');

/** verifica o status de instalação e carrega bdconf se existir */
if(!($ll_install =! file_exists($f = realpath(dirname(__FILE__). '/etc/bdconf.php'))) == true) require_once $f;
require_once("usr/stuff/functions.php");
require_once("usr/lliure.php");
require_once('usr/stuff/carrega_conf.php');


/** Define as bases da url */
$_ll['url']['local']     = ($_SERVER['HTTP_HOST'] . '/');
$_ll['url']['host']      = (isset($_SERVER['REQUEST_SCHEME'])? $_SERVER['REQUEST_SCHEME']: (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])? 'https' : 'http')). '://';
$_ll['url']['path']      = (trim(dirname($_SERVER['PHP_SELF']), '\\/'));
$_ll['url']['path']     .= (!empty($_ll['url']['path'])? '/': '');
$_ll['url']['request']   = (ltrim($_SERVER['REQUEST_URI'], '\\/'));
$_ll['url']['request']   = (explode('?', $_ll['url']['request'], 2));
$_ll['url']['get']       = ( isset($_ll['url']['request'][1])? '?'. $_ll['url']['request'][1]: '');
$_ll['url']['request']   = (!empty($_ll['url']['request'][0])?      $_ll['url']['request'][0]: '');

$_ll['url']['request']   = (!empty($_ll['url']['request'])? explode('/', $_ll['url']['request']): array());
$_ll['url']['query'] 	 = array_splice($_ll['url']['request'], (count((!empty($_ll['url']['path'])? explode('/', trim($_ll['url']['path'], '\\/')): array()))));

$_ll['url']['request']   = implode('/', $_ll['url']['request']);
$_ll['url']['get'] 	 	 = implode('/', $_ll['url']['query']) . $_ll['url']['get'];
$_ll['url']['endereco']  = $_ll['url']['host'];
$_ll['url']['host']     .= $_ll['url']['local'];
$_ll['url']['real']      = $_ll['url']['host']. $_ll['url']['path'];
$_ll['url']['local']    .= (!empty($_ll['url']['request'])? $_ll['url']['request']. '/': '');
$_ll['url']['endereco'] .= $_ll['url']['local'];
$_ll['url']['full']      = $_ll['url']['endereco']. $_ll['url']['get'];


/** quando carregando o sistema, retorna os scripts carregados e os dados precessados de url. */
if(!($_ll['insystem'] = (dirname(realpath($_SERVER["SCRIPT_FILENAME"])) == dirname(realpath(__FILE__))))) return $_ll;


/** configura o charset do sitema */
header('Content-Type: text/html; charset=UTF-8');


/** Define a base para selecionar o app */
$_ll['enter_mode']      = (isset($_ll['enter_mode'])? $_ll['enter_mode']: 'wli');
$_ll['operation_mode']  = (isset($_ll['operation_mode'])? $_ll['operation_mode']: 'x');
$_ll['operation_type']  = FALSE;
$_ll['operation_load']  = FALSE;
$_ll['operation_types'] = array('opt', 'api', 'app');
$_ll['ling'] = ll_ling();
$_ll['install'] = false;
$_ll['titulo'] = 'lliure Wap';


/** Inicia a seção do sistema */
ll::usr('sessionfix');


/** Define o tema do sistema */
ll::usr('persona');


$arrURL = $_ll['url']['query'];
$UA = (isset($_ll['tema']['exec']) && $_ll['tema']['exec'] == URL_AMIGAVEL);
if($UA) for ($i = 0; $i <= count($arrURL) -1; $i++){

	// monta os get quando uma chave foi configurada
	if(strpos($arrURL[$i], '=') !== false){
		$va = explode('=', $arrURL[$i], 2);
		$_GET[$va[0]] = $va[1];

		// monta os gets a partir de suas posisoes
	} else
		$_GET[$i] = $arrURL[$i]; //monta os get

// carrega o $_GET no $arrURL
} $arrURL = $_GET;


/** Define o enter mode */
foreach ($arrURL as $k => $v){
	if(!($k === 'nli' || $v === 'nli')) continue;
	$_ll['enter_mode'] = 'nli';
	unset($arrURL[$k]); break;
}


if ($_ll['operation_type'] === false){
	foreach ($arrURL as $k => $v){

		if(!(($c = array_search($k, $_ll['operation_types'], true)) !== false
		||  (($c = array_search($v, $_ll['operation_types'], true)) !== false)))
			continue;

		if($_ll['operation_types'][$c] === $k) $_ll['operation_load'] = $v;
		$_ll['operation_type'] = $_ll['operation_types'][$c];
		unset($arrURL[$k]);
		break;
	}
}


/** define o modo de operação do sistema [onserver, onclient]; */
foreach ($arrURL as $k => $v){
	if(!($k === 'onclient' || $v === 'onclient')) continue;
	$_ll['operation_mode'] = 'oc';
	unset($arrURL[$k]); break;}

foreach ($arrURL as $k => $v){
	if(!($k === 'onserver' || $v === 'onserver')) continue;
	$_ll['operation_mode'] = 'os';
	unset($arrURL[$k]); break;}


// Isso tem de ser revisto.
/** Define o operation_load se ainda não existir */
//if($_ll['operation_load'] === false)
//	$_ll['operation_load'] = array_shift($arrURL);


/** caso esteja no modo de inatalação, mas a url não seja a de instalação, redireciona para ela. */
if($ll_install && !($_ll['operation_type'] == 'opt' && $_ll['operation_load'] == 'install')){
	$_SESSION['ll']['retorno'] = $_ll['url']['full'];
	header('location: nli.php?opt=install');}


/** Muda a url a partir do modo de execução */
if(!$ll_install && $_ll['operation_mode'] == 'x' && (($url = ll_gourl($_ll['url']['get'], $_ll['tema']['exec'])) !== false))
	header('location: '.$_ll['url']['endereco']. $url);



// volta o $arrURL para $_GET
$_GET = $arrURL;



//echo '<pre>'. print_r($_ll, true). '</pre>';
//echo '<pre>'. print_r($_GET, true). '</pre>';
//echo '<pre>'. print_r($arrURL, true). '</pre>';
//echo '<pre>'. ll_gourl($_ll['url']['get'], $_ll['tema']['exec']). '</pre>'; die();


if($_ll['enter_mode'] == 'wli'){
	if(!lliure::autentica()){
		$_SESSION['ll']['retorno'] = $_ll['url']['full'];
		header('location: nli.php');
	}
} else {
	if (!isset($_SESSION['ll']['user']) || empty($_SESSION['ll']['user'])){
		ll::usr('token');
		$_SESSION['ll']['user'] = array(
			'id' => null,
			'login' => null,
			'nome' => 'Anonimo',
			'grupo' => 'nli',
			'tema' => $_ll['tema']['name'],
			'token' => Token::create()
		);
	}
}
$_ll['user'] = &$_SESSION['ll']['user'];



$_ll['desktop'] = false;
$get = array_keys($_GET);

//if(!isset($_GET['app']) && !isset($_GET['api']) && !isset($_GET['opt'])){
if($_ll['operation_load'] === false){

	$desk = explode('=', $_ll['tema']['home_wli']);
	if($_ll['enter_mode'] == 'nli')
		$desk = explode('=', $_ll['tema']['home_nli']);

	if(isset($_ll['conf']->grupo->{$_ll['user']['grupo']}->desktop))
		$desk = explode('=', $_ll['conf']->grupo->{$_ll['user']['grupo']}->desktop);

	$_GET[$desk[0]] = $desk[1];
	$get[0] = $desk[0];

	$_ll['desktop'] = true;
	$_ll['operation_type'] = $desk[0];
	$_ll['operation_load'] = $desk[1];
}


//////////////////////////////////////////////////////////////////////////////////////
///////////////////			Seguramça (isso vai mudar)		//////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
/* $ll_segok = false;

if($_ll['enter_mode'] == 'nli')
	$ll_segok = true;

elseif (ll_tsecuryt())
	$ll_segok = true;

elseif (($config = @simplexml_load_file($_ll['operation_type']. '/'. $_ll['operation_load']. '/sys/config.ll')) === false)
	$ll_segok = true;

elseif ($config->seguranca == 'public')
	$ll_segok = true;

elseif ((ll_securyt($_GET['app']) == true) || (ll_tsecuryt($config->seguranca)))
	$ll_segok = true;

if (!$ll_segok){
	$_ll['operation_type'] = 'opt';
	$_ll['operation_load'] = 'mensagens';
} */

if($_ll['enter_mode'] != 'nli') ll::usr('liberacao');
if(!($_ll['liberado'] = ($_ll['enter_mode'] == 'nli' || Liberacao::test($_ll['operation_type'], $_ll['operation_load'])))){
	$_ll['desktop'] = false;
	$_ll['operation_type'] = 'opt';
	$_ll['operation_load'] = 'mensagens';
	$_ll['mensagens'] = 'detido';}
//////////////////////////////////////////////////////////////////////////////////////




/** carrega as configuração do app */
ll::confg_app($_ll['operation_type'], $_ll['operation_load'], true);


/* Define os dados do APP da requisição */
$k = '?'. $_ll['operation_type']. '='. $_ll['operation_load'];

/** entradas padrões para wli */
$_ll[$_ll['operation_type']]['home'] = $_ll['url']['endereco']. $k;
$_ll[$_ll['operation_type']]['onserver'] = $_ll['url']['endereco']. 'onserver.php'. $k;
$_ll[$_ll['operation_type']]['onclient'] = $_ll['url']['endereco']. 'onclient.php'. $k;
//script para retro compatibilidade. retirar no 9{
//$_ll[$_ll['operation_type']]['sen_html'] = $_ll['url']['endereco']. 'sen_html.php'. $k;
//}
$_ll[$_ll['operation_type']]['pasta'] = $_ll['operation_type']. '/'. $_ll['operation_load']. '/';

/** entradas padrões para nli */
$_ll[$_ll['operation_type']]['nli']['home'] = $_ll['url']['endereco']. 'nli.php'. $k;
$_ll[$_ll['operation_type']]['nli']['onserver'] = $_ll['url']['endereco']. 'nli.os.php'. $k;
$_ll[$_ll['operation_type']]['nli']['onclient'] = $_ll['url']['endereco']. 'nli.oc.php'. $k;

//script para retro compatibilidade. retirar no 9{
//$_ll[$_ll['operation_type']]['nli']['sen_html'] = $_ll['url']['endereco']. 'nli.oc.php'. $k;
//}

$_ll[$_ll['operation_type']]['nli']['pasta'] = $_ll['operation_type']. '/'. $_ll['operation_load']. '/nli/';


$_ll[$_ll['operation_type']]['header'] = array(
	$_ll[$_ll['operation_type']]['pasta']. (($_ll['enter_mode'] == 'nli')? 'nli/': ''). ($_ll['operation_load']). ('.hd.php'),
	$_ll[$_ll['operation_type']]['pasta']. (($_ll['enter_mode'] == 'nli')? 'nli/': ''). ('header.php')
);

$_ll[$_ll['operation_type']]['pagina'] = array(
	$_ll[$_ll['operation_type']]['pasta']. (($_ll['enter_mode'] == 'nli')? 'nli/': ''). ($_ll['operation_load']). '.'. ($_ll['operation_mode']). ('.php'),
	$_ll[$_ll['operation_type']]['pasta']. (($_ll['enter_mode'] == 'nli')? 'nli/': ''). (array_search($_ll['operation_mode'], array('start' => 'x', 'onserver' => 'os', 'onclient' => 'oc')). '.php')
);


//script para retro compatibilidade. retirar no 9{
//if($_ll['operation_mode'] == 'oc')
//	$_ll[$_ll['operation_type']]['pagina'][] = $_ll[$_ll['operation_type']]['pasta']. (($_ll['enter_mode'] == 'nli')? 'nli/': '').  ('sen_html.php');
//}


//echo '<pre>'. print_r($get, true). '</pre>';
//echo '<pre>'. print_r($_GET, true). '</pre>';


/** Configuração do main meu normalmente usado na barra superior */
ll::menu(array(
	ll::menuItem($_ll['url']['endereco'], 'Home'),
	ll::menuItem($_ll['url']['endereco']. '?opt=user&en=minhaconta', 'Minha conta'),
	((ll::valida())? ll::menuItem($_ll['url']['endereco']. '?opt=stirpanelo', 'Painel de controle'): array()),
	ll::menuItem($_ll['url']['endereco']. 'nli.os.php?opt=singin&ac=logout', 'Sair'),
));


if($_ll['operation_mode'] == 'x'){

	ll::usr('open-sans');
	ll::usr('font-awesome');
	ll::usr('normalize');
	ll::usr('bootstrap');
	ll::usr('sessionfix');


	/** carrega o estilo do layout */
	if($_ll['enter_mode'] == 'wli') {
		if (!empty($_ll['tema']['wli']['css'])) lliure::add($_ll['tema']['wli']['css'], 'css', 5);

	} else
		if (!empty($_ll['tema']['nli']['css'])) lliure::add($_ll['tema']['nli']['css'], 'css', 5);

	/** carrega o estilo da pagina */
	if($_ll['enter_mode'] == 'wli') {
		if(isset($_ll[$_ll['operation_type']]['pasta']) && file_exists($f = $_ll[$_ll['operation_type']]['pasta']. 'estilo.css')) ll::add($f, 'css');

	} else
		if(isset($_ll[$_ll['operation_type']]['nli']['pasta']) && file_exists($f = $_ll[$_ll['operation_type']]['nli']['pasta']. 'estilo.css')) ll::add($f, 'css');

}


/** carrega o heder do layout */
if($_ll['enter_mode'] == 'wli') {
	if (!empty($_ll['tema']['wli']['hd'])) require_once $_ll['tema']['wli']['hd'];

} else
	if (!empty($_ll['tema']['nli']['hd'])) require_once $_ll['tema']['nli']['hd'];


//echo '<pre>'. print_r($get, true). '</pre>';
//echo '<pre>'. print_r($_ll, true). '</pre>';


/** boot */
if(file_exists($f = ($_ll[$_ll['operation_type']]['pasta']. (($_ll['enter_mode'] == 'nli')? 'nli/': ''). 'boot.php')))
	require_once $f;

/** Header */
if($_ll[$_ll['operation_type']]['header'] != null)
	foreach ($_ll[$_ll['operation_type']]['header'] as $f)
		if(file_exists($f)) require_once($f);


/** On Server | On Client */
if($_ll['operation_mode'] == 'os' || $_ll['operation_mode'] == 'oc'){
	foreach ($_ll[$_ll['operation_type']]['pagina'] as $f)
		if(file_exists($f)) require_once($f); die(); }


//echo '<pre>'. print_r($_ll, true). '</pre>'; //die();


//Inicia o históico
ll_historico('inicia'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<head>

	<base href="<?php echo $_ll['url']['endereco']; ?>" />
	<meta charset="utf-8">
	<meta name="url" content="<?php echo $_ll['url']['endereco']; ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="SHORTCUT ICON" href="usr/img/favicon.ico" type="image/x-icon" />
	<meta name="author" content="Jeison Frasson" />
	<meta name="DC.creator.address" content="lliure@lliure.com.br" />

	<title><?php echo $_ll['titulo']?></title>

	<?php require_once ll::header();?>

</head>
<body>

<?php if($_ll['enter_mode'] == 'wli')
	require_once $_ll['tema']['wli']['x'];

else
	require_once $_ll['tema']['nli']['x']; ?>

<?php require_once ll::footer(); ?>

</body>
</html>