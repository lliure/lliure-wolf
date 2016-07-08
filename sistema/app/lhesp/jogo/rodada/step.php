<?php
$rodada = array();
$equipe = array();
global $backReal;

if(isset($_GET['id'])){
	$rodada = mysql_fetch_assoc(mysql_query('select * from '.appTabela.'rodadas  where Id = '.$_GET['id'].' limit 1'));
	$categoria = $rodada['Categoria'];
	
	$rodada['Data'] = date('d/m/Y H:i', $rodada['Data']);
} else {
	$categoria = $_GET['cat'];
}
	
$equipeQuery = mysql_query('SELECT * FROM '.appTabela.'equipes where Categoria = "'.$categoria.'" order by Nome Asc');
while($equipeWhile = mysql_fetch_assoc($equipeQuery))
	$equipe[$equipeWhile['Id']] = $equipeWhile['Nome'];

$ginQuery = mysql_query('SELECT * FROM '.appTabela.'estadios  order by Nome Asc');
while($ginWhile = mysql_fetch_assoc($ginQuery))
	$ginasio[$ginWhile['Id']] = $ginWhile['Nome'];


	?>
<form action="<?php echo $this->sapm->onserver . '&ac=rodada'?>" method="post" class="form">
	<fieldset>	
		<div class="column">
			<div class="width" style="width: 133px;">
				<label>Numero:</label>
				<?php echo jf_input('Numero', $rodada); ?>
			</div>
			
			<div class="width" style="width: 175px;">
				<label>Data:</label>
				<?php echo jf_input('Data', $rodada, 'data'); ?>
			</div>	
			
			<div>
				<label>Ginasio:</label>
				<?php echo jf_select('Ginasio', $rodada, $ginasio); ?>				
			</div>
		</div>	
		
		<div class="column">
			<div>
				<label>Time A:</label>
				<?php echo jf_select('Time1', $rodada, $equipe); ?>
			</div>
			
			<div>
				<label>Time B:</label>
				<?php echo jf_select('Time2', $rodada, $equipe); ?>
			</div>
		</div>
		
		
		
			
	</fieldset>
	
	<div class="botoes">
		<button type="submit" class="confirm">Gravar</button>
		<a href="<?php echo $backReal; ?>">Cancelar</a>
	</div>
</form>


<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">		
	$(document).ready(function(){
		$(".data").mask("99/99/9999 99:99");
	});
</script>