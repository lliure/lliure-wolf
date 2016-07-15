<?php
/**
*
* Exibição de informações basicas do lliure
*
* @Versão do lliure 8.0
* @Pacote lliure
* @Sub-pacote stirpanelo
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

header("Content-Type: text/html; charset=ISO-8859-1",true);
require_once '../../includes/functions.php';

if(($_ll = lltoObject('../../etc/llconf.ll')) == false)
	$_ll->versao = 'erro ao ler llconf';
?>

<div id="llSobre">
	<h1><img src="opt/mensagens/img/logo_sobre.png" alt="lliure" /></h1>
	<span class="sigla">Web Application Platform</span>
	<span class="versao">Versão <?php echo  $_ll->versao;?></span>
	
	<h2>Obrigado por escolher o lliure</h2>	
		
	<div class="rodape">
		<div class="container">
			<span><a href="http://www.lliure.com.br/aplicativos">Aplicativos</a></span>
			<span><a href="http://www.lliure.com.br/hub">Fórum</a></span>
			<span><a href="http://newsmade.lliure.com.br/lliure">Tutoriais</a></span>
			<span><a href="http://www.lliure.com.br/hospedagem">Hosted by lliureHost</a></span>
		</div>
	</div>
</div>
