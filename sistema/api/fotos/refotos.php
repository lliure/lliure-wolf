<?php
/**
*
* lliure WAP
*
* @Versão 6.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
header("Content-Type: text/html; charset=ISO-8859-1",true);
require_once("../../etc/bdconf.php"); 
require_once("../../includes/jf.funcoes.php"); 

$tabelaAtu = PREFIXO.$_GET['tabela'];

if(!empty($_POST)){
	$_POST =  jf_iconv('UTF-8', 'ISO-8859-1', $_POST);

	$campos['descricao'] = $_POST['descricao'];
	$alter['id'] = $_GET['foto'];
	
	mLupdate($tabelaAtu, $campos, $alter);
	?>
	<div style="text-align: center; font-size: 11px; margin-top: 120px;">Descrição alterada com sucesso!</div>
	<script type="text/javascript">
		$(document).ready(function() {
			setTimeout("fechaJfbox()", 1000);
			
		});
	</script>
	<?php
} else {
	$sql = "SELECT * FROM ".$tabelaAtu." WHERE id='".$_GET['foto']."'";
	$dados = mysql_fetch_array(mysql_query($sql));
	$foto = $dados['foto'];
	$nome = (!empty($dados['descricao'])?$dados['descricao']:"");

	$dir = $_GET['dir']."/";
	?>	
	
	<div class="ediFoto">
		<img src="../uploads/<?php echo $dir.'284-180-c/'.$foto?>" />
		
		<form method="post" class=" jfbox" action="api/fotos/refotos.php?tabela=<?php echo $_GET['tabela']?>&amp;foto=<?php echo $_GET['foto']?>">
			<input type="text" name="descricao" value="<?php echo $nome?>">
			<span class="botao"><button type="submit">Confirmar</button></span>
		</form>
	</div>
	<?php
}
?>
