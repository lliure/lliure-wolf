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
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once("../etc/bdconf.php"); 
require_once("functions.php"); 


$url = array_reverse($_SESSION['historicoNav']);
$url = $url[0];

$pasta = explode("&", $url);
$pasta = explode("=", $pasta['0']);
$pasta = $pasta['1'];

$tabela = PREFIXO."lliure_desktop";
$dados['nome'] = jf_iconv2($_POST['nome']);
$dados['link'] = $url;
$dados['imagem'] = "app/".$pasta."/sys/ico.png";

if(!file_exists('../app/'.$pasta.'/sys/ico.png'))
	$dados['imagem'] = "plugins/".$pasta."/sys/ico.png";

jf_insert($tabela, $dados);
?>

<script type="text/javascript">
	jfAlert('<?php echo 'A página <strong>'.$dados['nome'].'</strong> foi adicionada com sucesso ao seu desktop!'; ?>', 0.7);
</script>
