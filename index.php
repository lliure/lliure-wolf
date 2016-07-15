<?php
/**
*
* Iniciação do lliure
*
* @Versão do lliure 8.0
* @Pacote lliure
*
* Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
header('Content-Type: text/html; charset=iso-8859-1');

if(!file_exists("etc/bdconf.php"))
	header('location: opt/install/index.php');

require_once("etc/bdconf.php");
require_once("usr/lliure.php");
require_once("includes/functions.php");

/* Identifica o diretório atual do sistema */
ll_dir();

if(!lliure::autentica()) {
	$_SESSION['ll_url'] = jf_monta_link($_GET);
	header('location: nli.php');
}

$_ll['user'] = $_SESSION['ll']['user'];


require_once('includes/carrega_conf.php');

if(!isset($_ll['mode_operacion']))
	$_ll['mode_operacion'] = 'normal';


if(!isset($_ll['conf']->grupo->{$_ll['user']['grupo']}->execucao)){
	$_ll['conf']->grupo = new stdClass;
	$_ll['conf']->grupo->$_ll['user']['grupo'] = new stdClass;
	$_ll['conf']->grupo->{$_ll['user']['grupo']}->execucao = URL_NORMAL;
}

/******************************************************		TRATAM URL	*/
$uArray = $_SERVER['REQUEST_URI'];
$_ll['url']['endereco'] = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
$_ll['url']['real'] = $_ll['url']['endereco'].str_replace(array('onserver.php', 'onclient.php', 'index.php'), '', $_SERVER['PHP_SELF']);

$_ll['url']['endereco'] = explode("/", $_ll['url']['endereco'].$uArray);

$uArray = explode("/", $uArray);
$nReal = explode('/', $_ll['url']['real']);

$uArray = array_slice($uArray, count($nReal)-3);

$_ll['url']['endereco'] = array_slice($_ll['url']['endereco'], 0, count($uArray) * -1);
$_ll['url']['endereco'] = implode('/', $_ll['url']['endereco']).'/';

if($_ll['conf']->grupo->{$_ll['user']['grupo']}->execucao == URL_AMIGAVEL){
	for ($i = 0; $i <= count($uArray)-1; $i++) {
		if(strpos($uArray[$i], '=') != false){
			$va = explode('=', $uArray[$i]);
			$_GET[$va[0]] = $va[1]; //monta os get
		} else {
			$_GET[$i] = $uArray[$i]; //monta os get
		}
	}
}

if(!empty($uArray))
	$_ll['url']['get'] = implode('/', $uArray);

if($_ll['mode_operacion'] == 'normal' && ($url = ll_gourl($_ll['url']['get'], $_ll['conf']->grupo->{$_ll['user']['grupo']}->execucao)) && $url !== false)
	header('location: '.$_ll['url']['endereco'].$url);

/******************************************************							*/

$_ll['css'] = array();
$_ll['js'] = array();

$_ll['ling'] = ll_ling();

require_once 'includes/api.inc.php';

$_ll['app']['header'] = null;
$_ll['app']['pagina'] = "opt/mensagens/permissao.php";

$_ll['titulo'] = 'lliure Wap';

$get = array_keys($_GET);

if(!isset($_GET['app']) && !isset($_GET['opt']) && isset($_ll['conf']->grupo->{$_ll['user']['grupo']}->desktop)){
	$desk = explode('=', $_ll['conf']->grupo->{$_ll['user']['grupo']}->desktop);
	$get[0] = 'desk';
	$desk['app'] = $desk[1];
}

switch(isset($get[0]) ? $get[0] : 'desk' ){
	case 'desk':
		$get[0] = 'app';

		$_ll['app']['pagina'] = "opt/desktop/desktop.php";
		$_ll['app']['header'] = 'opt/desktop/desktop.header.php';


		if(isset($desk['app'])){
			$_GET['app'] = $desk['app'];
		} else {
			break;
		}

	case 'app':
		if(!empty($_GET['app'])
			&& (file_exists('app/'.$_GET['app']))){

			$urlApp = '?app='.$_GET['app'];
			if(isset($desk))
				$urlApp = '?';

			$_ll['app']['home'] = $_ll['url']['endereco'].'index.php'.$urlApp;
			$_ll['app']['onserver'] = $_ll['url']['endereco'].'onserver.php'.$urlApp;
			$_ll['app']['onclient'] = $_ll['url']['endereco'].'onclient.php'.$urlApp;
			$_ll['app']['pasta'] = 'app/'.$_GET['app'].'/';

			$_ll['app']['sen_html'] = $_ll['app']['onclient'];
			$llAppHome = $_ll['app']['home'];
			$llAppOnServer = $_ll['app']['onserver'];
			$llAppSenHtml = $_ll['app']['onclient'];
			$llAppPasta = $_ll['app']['pasta'];

			/**		Controle de abertura de páginas		**/
			switch($_ll['mode_operacion']){

			case 'onserver':
				$_ll['app']['pagina'] = $_ll['app']['pasta'].'onserver.php';
				$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				break;

			case 'onclient':
			case 'sen_html':
				$_ll['app']['pagina'] = $_ll['app']['pasta'].'onclient.php';

				/*{*/
				if(!file_exists($_ll['app']['pagina']))
					$_ll['app']['pagina'] = $_ll['app']['pasta'].'sen_html.php';
				/*}*/

				$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				break;

			case 'normal':
				$ll_segok = false;

				if(ll_tsecuryt() == false){
					if(($config = @simplexml_load_file($_ll['app']['pasta'].'/sys/config.ll')) !== false){
						if($config->seguranca != 'public' && ((ll_securyt($_GET['app']) == true) || (ll_tsecuryt($config->seguranca))))
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

					$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				}

				break;
			}

		} elseif(ll_tsecuryt('admin')) {
			$_ll['app']['pagina'] = "opt/stirpanelo/ne_trovi.php";
		}
		break;


	case 'painel':
		$get[0] = 'opt';
		$_GET[$get[0]] = 'stirpanelo';

	case 'opt':
	case 'api':

		if(!empty($_GET[$get[0]])
			&& (file_exists($get[0].'/'.$_GET[$get[0]]))){

			$_ll[$get[0]]['home'] = 'index.php?'.$get[0].'='.$_GET[$get[0]];
			$_ll[$get[0]]['onserver'] = 'onserver.php?'.$get[0].'='.$_GET[$get[0]];
			$_ll[$get[0]]['onclient'] = 'onclient.php?'.$get[0].'='.$_GET[$get[0]];
			$_ll[$get[0]]['pasta'] = $get[0].'/'.$_GET[$get[0]].'/';


			/**		Controle de abertura de páginas		**/
			$_ll[$get[0]]['header'] = $_ll[$get[0]]['pasta'].'header.php';

			switch($_ll['mode_operacion']){

			case 'onserver':
				$_ll[$get[0]]['pagina'] = $_ll[$get[0]]['pasta'].'onserver.php';
				break;

			case 'onclient':
				$_ll[$get[0]]['pagina'] = $_ll[$get[0]]['pasta'].'onclient.php';
				break;

			case 'normal':
				$ll_segok = true;

				if($ll_segok){
					$_ll[$get[0]]['pagina'] = $_ll['opt']['pasta'].'start.php';
				}
				break;
			}

		} else {
			$get[0] = 'opt';
			$_GET[$get[0]] = 'stirpanelo';
		}

		break;


	default:
		$get[0] = 'app';
		break;
}
/*****/

if($_ll['mode_operacion'] == 'normal'){

	lliure::add('js/jquery.js');
	lliure::add('api/tinymce/tinymce.min.js');
	lliure::add('js/jquery-ui.js');
	lliure::add('js/funcoes.js');
	lliure::add('js/jquery.jfkey.js');
	lliure::add('js/jquery.easing.js');

	lliure::add('css/base.css');
	lliure::add('opt/open-sans/open-sans.css');

	lliure::add('css/principal.css');

	lliure::add($_ll['user']['tema']['path'].'estilo.css');

	lliure::add('css/paginas.css');
	lliure::add('css/predefinidos.css');


	lliure::add('opt/font-awesome/css/font-awesome.min.css');

	if(isset($_ll[$get[0]]['pasta'])  && file_exists($_ll[$get[0]]['pasta'].'estilo.css'))
		lliure::add($_ll[$get[0]]['pasta'].'estilo.css', 'css', 20);

	lliure::inicia('appbar');
	lliure::inicia('fileup');
	lliure::inicia('jfbox');
}

/*******************************		Header			*/
if($_ll[$get[0]]['header'] != null)
	if(file_exists($_ll[$get[0]]['header']))
		require_once($_ll[$get[0]]['header']);


/*******************************		On Server		*/
if($_ll['mode_operacion'] == 'onserver'){
	require_once($_ll[$get[0]]['pagina']);
	die();
}

/*******************************		On Client		*/
if($_ll['mode_operacion'] == 'onclient'){
	require_once($_ll[$get[0]]['pagina']);
	die();
}

//Inicia o histórico
ll_historico('inicia');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<head>
	<base href="<?php echo $_ll['url']['real']?>" />
	<meta name="url" content="<?php echo $_ll['url']['real']?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="SHORTCUT ICON" href="usr/img/favicon.ico" type="image/x-icon" />
	<meta name="author" content="Jeison Frasson" />
	<meta name="DC.creator.address" content="lliure@lliure.com.br" />

	<?php lliure::header();?>

</head>

<body>
<div id="tudo">
	<div id="topo">
		<div class="left">
			<a href="index.php" class="logoSistema"><img src="usr/img/blank.gif"/></a>
			<?php
			if(!empty($_GET) &&  ll_tsecuryt()){
				$keyGet = array_keys($_GET);
				if($keyGet['0'] == 'app' && !empty($_GET['app'])){
					?>
					<a href="javascript: void(0);" class="addDesktop" title="Adicionar este local ao desktop"><i class="fa fa-share-square  fa-rotate-90"></i></a>
					<?php
				}
			}
			?>
		</div>

		<div class="right">
			<div class="menu">
				<ul>
					<?php
					echo '<li><a href="index.php">Home</a></li>'
						.'<li><a href="?opt=user&en=minhaconta">Minha conta</a></li>'
						.(ll_tsecuryt('admin') ? '<li><a href="?painel">Painel de controle</a></li>' : '')
						.'<li><a href="nli.php?r=logout">Sair</a></li>';
					?>
				</ul>
			</div>

		</div>
	</div>

	<div id="conteudo">
		<?php
		$carrega = 'opt/stirpanelo/ne_trovi.php';

		if(file_exists($_ll[$get[0]]['pagina']))
			$carrega = $_ll[$get[0]]['pagina'];

		require_once($carrega);
		?>
		<div class="both"></div>
	</div>

	<div id="ll_rodape">
		<a href="http://www.lliure.com.br"><?php echo 'lliure '.$_ll['conf']->versao;?></a>
	</div>
</div>

<?php lliure::footer(); ?>

</body>

<head>
	<title><?php echo $_ll['titulo']?></title>

	<script type="text/javascript">
		$(function(){
			<?php
			ll_alert();
			?>

			$('.addDesktop').click(function(){
				ll_addDesk();
			});

			ll_load('load');
			ll_sessionFix();

			$('#topo .right div.start').mouseenter(function(){
				var size = $("#appRapido").find("li").size()*52;
				$("#appRapido").css({width: size});

				$(this).stop().animate({width: size+20}, 500, 'easeInOutQuart');
			}).mouseleave(function(){
			  $(this).stop().animate({width: '20'}, 500, 'easeInOutQuart');
			});
		});
	</script>
</head>

</html>
