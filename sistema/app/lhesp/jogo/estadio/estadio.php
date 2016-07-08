
<?php

switch(isset($_GET['ac']) ? $_GET['ac'] : ''){

/////////////////////////////////		DEFAULT
default:
	?>
	<h2>Ginásios cadastrados</h2>
	<?php
		
	$navigi = new navigi();
	$navigi->tabela =  appTabela.'estadios';
	$navigi->query = 'select * from '.$navigi->tabela.' order by nome asc' ;
	$navigi->delete = true;
	$navigi->rename = true;
	$navigi->config = array(
		'fa' => 'fa-home',
		'link' => $this->sapm->home . '&p=step&gin=',
		'id' => 'Id',
		'coluna' => 'Nome'
		);								
	$navigi->monta();
	

	break;

/////////////////////////////////		NOVO
case 'novo': 
	?>
	<h2>Cadastrar</h2>
	<?php
	if((isset($_POST['nome'])) and (isset($_POST['endereco']))){
		$nome = $_POST['nome'];
		$endereco = $_POST['endereco'];
		$mapa = $_POST['mapa'];
		$fone = $_POST['fone'];
		$cidade = $_POST['cidade'];
		
		$cadastro = mysql_query("INSERT INTO estadios (Nome, Endereco, Cidade, Mapa, Fone) values ('$nome', '$endereco', '$cidade', '$mapa', '$fone')") or die(mysql_error());
		?>
		<span class="mensagem">Ginásio cadastrado com sucesso!</span>
		<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=estadios&amp;estadios">
		<?php
	} else {
		?>
		<form method="post">
			<?php
			$pagina_tipo = "estadios";
			require_once('forms.php');
			?>
		<button type="submit">Cadastrar</button>
		</form>
		<?php
	}
	break;

/////////////////////////////////		ALTERAR
case 'alterar':
	?>
	<h2>Alterar</h2>
	<?php
	if((isset($_POST['nome'])) and (isset($_POST['endereco']))){
		$nome = $_POST['nome'];
		$endereco = $_POST['endereco'];
		$mapa = $_POST['mapa'];
		$fone = $_POST['fone'];
		$cidade = $_POST['cidade'];

		$sql = "UPDATE  estadios SET Nome='$nome', Endereco='$endereco', Cidade = '$cidade', Mapa='$mapa', Fone='$fone' where Id like '$_GET[estadios]'"; 
		$resultado = mysql_query($sql) 	or die (mysql_error()); 
		?>
		<span class="mensagem">Ginásio alterado com sucesso!</span>
		<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=estadios&amp;estadios">
		<?php
	} else {
		$estadio = mysql_fetch_array(mysql_query("select * from estadios where Id like '$_GET[estadios]'"));
		?>
		<form method="post">
			<?php
			$pagina_tipo = "estadios";
			require_once('forms.php');
			?>
			<button type="submit">Alterar</button>
		</form>
		<?php
	}
	break;
}
?>