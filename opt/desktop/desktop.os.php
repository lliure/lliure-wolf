<?php global $_ll, $desktop;

switch(isset($_GET['ac']) ? $_GET['ac'] : 'erro'){ case 'addDesktop':

	echo '<pre>'. print_r($_SESSION['historicoNav'], 1). '</pre>';

	$url = array_reverse($_SESSION['historicoNav']);
	$link = array_shift($url);
	$url = parse_url($link);
	parse_str($url['query'], $url);

	echo '<pre>'. print_r($url, 1). '</pre>';
	$kys = array_keys($url);

	if(!isset($kys[0])){
		header('Location: '. $_ll[$_ll['operation_type']]['onclient']. '&ac=addDesktopError&error=1'); break;}

	$confgs = ll::confg_app($kys[0], $url[$kys[0]]);

	if(!isset($_POST['nome']) || empty($_POST['nome'])) $_POST['nome'] = $confgs->nome;
	$_POST['link'] = $link;
	$_POST['imagem'] = $confgs->ico;

	$desktop->set($_POST);

	header('Location: '. $_ll[$_ll['operation_type']]['onclient']. '&ac=addDesktopSuccess');

	/*$url = array_reverse($_SESSION['historicoNav']);
	$url = $url[0];

	$pasta = explode("&", $url);
	$pasta = explode("=", $pasta['0']);
	$pasta = $pasta['1'];

	$tabela = PREFIXO."lliure_desktop";
	$dados['nome'] = jf_iconv2($_POST['nome']);
	$dados['link'] = $url;
	$dados['imagem'] = "app/".$pasta."/sys/ico.png";

	if(!file_exists('../../'.$dados['imagem']))
		$dados['imagem'] = "opt/stirpanelo/icon_defaulto.png";

	jf_insert($tabela, $dados); ?>
	<script type="text/javascript">
		jfAlert('<?php echo 'A página <strong>'.$dados['nome'].'</strong> foi adicionada com sucesso ao seu desktop!'; ?>', 0.7);
	</script>
	<?php break;*/

break; default: case 'erro':

break;}
