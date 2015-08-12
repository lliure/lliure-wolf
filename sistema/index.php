<?php 
/**
*
* lliure WAP
*
* @Vers�o 7.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licen�a http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if(!file_exists("etc/bdconf.php"))
	header('location: install/index.php');

require_once("etc/bdconf.php"); 
require_once("includes/functions.php");

/* Identifica o diret�rio atual do sistema */
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
$_ll['app']['pagina'] = "opt/mensagens/permissao.php";


$get = array_keys($_GET);
switch(isset($get[0]) ? $get[0] : 'desk' ){
	case 'app':
		if(!empty($_GET['app'])
			&& (file_exists('app/'.$_GET['app']))){
			
			$_ll['app']['home'] = 'index.php?app='.$_GET['app'];
			$_ll['app']['onserver'] = 'onserver.php?app='.$_GET['app'];
			$_ll['app']['onclient'] = 'onclient.php?app='.$_GET['app'];			
			$_ll['app']['pasta'] = 'app/'.$_GET['app'].'/';
				

			$_ll['app']['sen_html'] = $_ll['app']['onclient'];
			$llAppHome = $_ll['app']['home'];
			$llAppOnServer = $_ll['app']['onserver'];
			$llAppSenHtml = $_ll['app']['onclient'];			
			$llAppPasta = $_ll['app']['pasta'];
			
			/**		Controle de abertura de p�ginas		**/			
			switch($_ll['mode_operacion']){
				
			case 'onserver':
				$_ll['app']['pagina'] = $_ll['app']['pasta'].'onserver.php';
				$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				break;
				
			case 'sen_html':
				$_ll['app']['pagina'] = $_ll['app']['pasta'].'sen_html.php';
				
				if(!file_exists($_ll['app']['pagina']))
					$_ll['app']['pagina'] = $_ll['app']['pasta'].'onclient.php';
					
				$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				break;
				
			case 'onclient':
				$_ll['app']['pagina'] = $_ll['app']['pasta'].'onclient.php';
				$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';
				break;
			
			case 'kun_html':
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
					
					if(file_exists($_ll['app']['pasta'].'header.php'))
						$_ll['app']['header'] = $_ll['app']['pasta'].'header.php';			
				}	
				
				break;			
			}
			
		} elseif(ll_tsecuryt('admin')) {
			$_ll['app']['pagina'] = "opt/stirpanelo/ne_trovi.php";
		}
		break;
		
	case 'opt':
		if(!empty($_GET['opt'])
			&& (file_exists('opt/'.$_GET['opt']))){
			
			$_ll['opt']['home'] = 'index.php?opt='.$_GET['opt'];
			$_ll['opt']['onserver'] = 'onserver.php?opt='.$_GET['opt'];
			$_ll['opt']['onclient'] = 'onclient.php?opt='.$_GET['opt'];			
			$_ll['opt']['pasta'] = 'opt/'.$_GET['opt'].'/';
			
			/**		Controle de abertura de p�ginas		**/			
			switch($_ll['mode_operacion']){
				
			case 'onserver':
				$_ll['opt']['pagina'] = $_ll['opt']['pasta'].'onserver.php';
				$_ll['opt']['header'] = $_ll['opt']['pasta'].'header.php';
				break;
				
			case 'onclient':
				$_ll['opt']['pagina'] = $_ll['opt']['pasta'].'onclient.php';
				$_ll['opt']['header'] = $_ll['opt']['pasta'].'header.php';
				break;
			
			case 'kun_html':
				$ll_segok = true;				
				
				if($ll_segok){
					$_ll['opt']['pagina'] = $_ll['opt']['pasta'].'start.php';
					
					if(file_exists($_ll['opt']['pasta'].'header.php'))
						$_ll['opt']['header'] = $_ll['opt']['pasta'].'header.php';			
				}					
				break;			
			}
		} else {
			$_ll['opt']['pagina'] = "opt/stirpanelo/ne_trovi.php";
		}
		
		
		break;

	case 'painel':
		$get[0] = 'app';
		if(ll_tsecuryt('admin')){
			$_ll['app']['header'] = 'opt/stirpanelo/header.php';
			$_ll['app']['pagina'] = 'opt/stirpanelo/index.php';
			$_ll['app']['home'] = '?painel';
		}

		break;

	case 'desk':	
		$get[0] = 'app';		
		
		if(isset($_ll['conf']->desktop->$_ll['user']['grupo']))
			header('location: '.$_ll['conf']->desktop->$_ll['user']['grupo']);
			
		$_ll['app']['pagina'] = "opt/desktop/desktop.php";
		$_ll['app']['header'] = 'opt/desktop/desktop.header.php';
		break;

	default:
		$get[0] = 'app';
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
if($_ll[$get[0]]['header'] != null)
	require_once($_ll[$get[0]]['header']);


/*******************************		On Server		*/
if($_ll['mode_operacion'] == 'onserver'){	
	require_once($_ll[$get[0]]['pagina']);	
	die();
}



/*******************************		Sen HTML		*/
if($_ll['mode_operacion'] == 'onclient'){
	require_once($_ll[$get[0]]['pagina']);	
	die();
}
	
//Inicia o hist�rico
ll_historico('inicia');
//Inicia o Tema atual 	

if(($ll_tema = lltoObject('temas/'.$_ll['user']['tema'].'/dados.ll')) != false){
	$_ll['tema'] = (array) $ll_tema;
	$ll_icones = $_ll['tema']['icones'];
	$plgIcones = $ll_icones;
	
}
// 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="SHORTCUT ICON" href="imagens/layout/favicon.ico" type="image/x-icon" />
	<meta name="author" content="Jeison Frasson" />
	<meta name="DC.creator.address" content="jomadee@lliure.com.br" />
	<meta name="DC.creator " content="Jeison Frasson" />

	<?php
	lliure::loadCss();	
	echo (isset($_GET['app']) && !empty($_GET['app'])  && file_exists($_ll['app']['pasta'].'estilo.css') ?
		'<link rel="stylesheet" type="text/css" href="'.$_ll['app']['pasta'].'estilo.css">'
		: '' )
		
	.'<link rel="stylesheet" type="text/css" href="temas/'.$ll_tema->id.'/estilo.css">';
	
	
	lliure::loadJs();
	
	?>
	<title>lliure WAP</title>
</head>

<body>
<div id="tudo">
	<div id="topo">
		<span class="borda-esquerda"></span>
		<span class="borda-direita"></span>
		<div class="left">
			<a href="index.php" class="logoSistema"><img src="imagens/layout/blank.gif"/></a>
			<?php
			if(!empty($_GET) &&  ll_tsecuryt()){
				$keyGet = array_keys($_GET);
				if($keyGet['0'] == 'app' && !empty($_GET['app'])){
					?>
					<a href="javascript: void(0);" class="addDesktop" title="Adicionar essa p?na ao desktop"><img src="imagens/layout/add_desktop.png" alt="" /></a>
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
						.'<li><a href="?opt=user&minhaconta">Minha conta</a></li>'
						.(ll_tsecuryt('admin') ? '<li><a href="?painel">Painel de controle</a></li>' : '')						
						.'<li><a href="nli.php?r=logout">Sair</a></li>';
					?>					
				</ul>
			</div>
			<?php 
			
			if(ll_tsecuryt('admin')){
				$consulta = "select b.* from 
							".PREFIXO."lliure_start as a
							
							left join ".PREFIXO."lliure_apps as b
							on a.idPlug = b.id	";
				$query = mysql_query($consulta);
				
				?>
				<div class="start" id="menu_rapido"  <?php echo (mysql_num_rows($query) == 0 ? 'style="display: none;"' : '' );?>>
					<div class="width">
						<span class="icone"></span>
						<ul id="appRapido">
							<?php
							while($dados = mysql_fetch_array($query)){
								?>
								<li id="appR-<?php echo $dados['id']?>">
									<a href="?app=<?php echo $dados['pasta']?>" title="<?php echo $dados['nome']?>">
										<img src="<?php echo 'app/'.$dados['pasta'].'/sys/ico.png'; ?>" alt="" />
									</a>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
				<?php				
			} 
			?>
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
</body>

<head>
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
