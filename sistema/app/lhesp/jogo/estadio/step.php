<?php
$estadio = array();

if(isset($_GET['gin']))
	$estadio = mysql_fetch_array(mysql_query('select * from estadios where Id = "'.$_GET['gin'].'" limit 1'));
?>

<h2>Alterar Ginásio</h2>
<form method="post" class="form" action="<?php echo $this->sapm->onserver . (isset($_GET['gin']) ? '&gin=' . $_GET['gin'] : ''); ?>">
	<fieldset>	
		<div>
			<label>Nome:</label>
			<?php echo jf_input('Nome', $estadio); ?>
		</div>
		
		<div>
			<label>Endereço:</label>
			<?php echo jf_input('Endereco', $estadio); ?>
		</div>
			
		<div>
			<label>Cidade:</label>
			<?php echo jf_input('Cidade', $estadio); ?>
		</div>
		
		<div>
			<label>Fone:</label>
			<?php echo jf_input('Fone', $estadio); ?>
		</div>
		
		<div>
			<label>Mapa:</label>
			<?php echo jf_textarea('Mapa', $estadio); ?>
		</div>
	</fieldset>
	<div class="botoes">
		<button type="submit" class="confirm">Alterar</button>
	</div>
</form>
	