<?php
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
header("Content-Type: text/html; charset=ISO-8859-1",true);
require_once("../../etc/bdconf.php"); 
require_once("../../includes/jf.funcoes.php");

switch(isset($_GET['ac']) ? $_GET['ac'] : 'erro' ){
case 'addDesktop':
	$url = array_reverse($_SESSION['historicoNav']);
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
		
	jf_insert($tabela, $dados);
	?>

	<script type="text/javascript">
		jfAlert('<?php echo 'A página <strong>'.$dados['nome'].'</strong> foi adicionada com sucesso ao seu desktop!'; ?>', 0.7);
	</script>
	<?php
	break;
	
default:
case 'erro':	
	break;
}
?>
