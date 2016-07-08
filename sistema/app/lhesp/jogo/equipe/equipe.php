<h1>Equipes</h1>
<div class="mesquerda">
	<ul>
		<li><a href="?plugin=lhesp&amp;acoes=equipes&amp;equipes">Listar</a></li>
		<li><a href="?plugin=lhesp&amp;acoes=equipes&amp;equipes&amp;novo">Cadastar</a></li>
	</ul>
</div>

<div class="Pcentro">

<?php
if(isset($_GET['novo'])){
	$caseequipe = 'novo';
} elseif(isset($_GET['deletar'])){
	$caseequipe = 'deletar';
} elseif(ctype_digit($_GET['equipes'])){
	$caseequipe = 'alterar';
}

switch($caseequipe){

/////////////////////////////////		DEFAULT
default:
	?>
	<h2>Equipes cadastradas</h2>
	<?php
	if(isset($_GET['busca_alf'])){
		$busca = $_GET['busca_alf'];
		$busca = "where Nome like '$busca%'";
		?>
		<h4>Consulta com a letra <?=$_GET['busca_alf']?></h4>
		<?php
	}

	$busca = "SELECT * FROM equipes  $busca"; 
	$total_reg = "10";

	if (!isset($_GET['pagina'])) {
		$pc = "1";
	} else {
		$pc = $_GET['pagina'];
		$filtro_pag = "&pagina=$pc";
	} 

	$inicio = $pc - 1;
	$inicio = $inicio * $total_reg; 
	
	$limite = mysql_query("$busca order by Nome Asc, Categoria Asc LIMIT $inicio,$total_reg");
	$todos = mysql_query("$busca");

	$tr = mysql_num_rows($todos); 
	$tp = $tr / $total_reg; 

	?>
	<table>
		<tr>
			<th width="30px"></th>
			<th width="400px" style="text-align: left;">Equipes</th>

			<th width="50px">Categoria</th>
			
			<th width="50px">Apagar</th>

			<th width="50px">Editar</th>
		</tr>
	<?php
	while ($estadio = mysql_fetch_array($limite)) {
		$nome = $estadio["Nome"];
		$id = $estadio["Id"];
		$categoria = $estadio["Categoria"];
		$categoria = mysql_fetch_array(mysql_query("SELECT * FROM categoria where Id like '$categoria'"));
		$categoria = $categoria['Abrev']
	?>
		<tr>
			<td><?=$id?></td>
			<td><?=$nome?></td>
			<td class="edtdel"><?=$categoria?></td>
			<td class="edtdel"><a href="?plugin=lhesp&amp;acoes=equipes&amp;equipes=<?=$id?>&amp;deletar"><img src="<?=$pluginPasta?>/img/lixo_mini.gif" alt="apagar"/></a></td>
			<td class="edtdel"><a href="?plugin=lhesp&amp;acoes=equipes&amp;equipes=<?=$id?><?=$filtro_pag?>"><img src="<?=$pluginPasta?>/img/edit_mini.gif" alt="editar"/></a></td>
		</tr>

	<?php
	}
	?>
	</table>
	<br/>
	<?php
	$pagGet = "plugin=lhesp&amp;acoes=equipes&amp;equipes";
	$separador = " | ";
	$getLink = "busca_alf";
	require_once("alfabeto.php");
	?>
	<br/>
	<?php
	$anterior = $pc -1;
	$proximo = $pc +1;

	if ($pc>1) {
		echo " <a href='?plugin=lhesp&amp;acoes=equipes&amp;equipes&amp;pagina=$anterior'><img src='imagens/icones/back.png'></a> ";
	}
	if ($pc>1 and $pc<$tp) {	
		echo "<img src='imagens/icones/divd.png'>";
	}
	if ($pc<$tp) {
		echo " <a href='?plugin=lhesp&amp;acoes=equipes&amp;equipes&amp;pagina=$proximo'><img src='imagens/icones/next.png'> </a>";
	} 
	break;

/////////////////////////////////		NOVO
case 'novo': ?>
	<h2>Cadastrar</h2>
	<?php
	if((isset($_POST['nome'])) and (isset($_POST['resumo']))){

		$nome = $_POST['nome'];
		$resumo = $_POST['resumo'];
		$sede = $_POST['sede'];
		$ginasio = $_POST['ginasio'];
		$fone1 = $_POST['fone1'];
		$fone2 = $_POST['fone2'];
		$mail = $_POST['mail'];
		
		$categoria_busca = mysql_query("SELECT * FROM categoria order by Nome Asc");
		while($categoria = mysql_fetch_array($categoria_busca)){
			$nomecat = $categoria['Nome'];
			$idcat = $categoria['Id'];
			if($_POST['cat'.$idcat] == 'ok'){
			$cadastro = mysql_query("INSERT INTO equipes (Nome, Resumo, Sede, Ginasio, Fone1, Fone2, Mail, Categoria) values ('$nome', '$resumo', '$sede', '$ginasio', '$fone1', '$fone2', '$mail', '$idcat')") or die(mysql_error());
			?>
			Cadastrado em <?=$nomecat?> <br/>
			<?php
			}
		}

		
		?>
		<br/>
		<span class="mensagem">Equipe cadastrada com sucesso!</span>
		<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=equipes">
		<?php
	} else {
	?>
	<form method="post" id="formula">
		<?php
			$pagina_tipo = "equipes";
			require_once('forms.php');
		?>
	<div class="both"></div>
	<button  class="link" onclick="sForm('formula',''<?=$endComun?>')"><img src="imagens/icones/save.png" alt="salvar"/></button>
	</form>
	<?
	}
	break;

/////////////////////////////////		ALTERAR
case 'alterar':
	if(isset($_GET['pagina'])){
		$filtro_pag = "&pagina=".$_GET['pagina'];
	}
	?>
	<h2>Alterar</h2>
	<?php
	if((isset($_POST['nome'])) and (isset($_POST['resumo']))){
		$nome = $_POST['nome'];
		$resumo = $_POST['resumo'];
		$sede = $_POST['sede'];
		$ginasio = $_POST['ginasio'];
		$fone1 = $_POST['fone1'];
		$fone2 = $_POST['fone2'];
		$mail = $_POST['mail'];
		
		$sql = "UPDATE  equipes SET Nome='$nome', Resumo='$resumo', Sede='$sede', Ginasio='$ginasio', Fone1='$fone1', Fone2='$fone2', Mail='$mail' where Id like '$_GET[equipes]'"; 
		$resultada = mysql_query($sql) 	or die (mysql_error()); 
		?>
		<span class="mensagem">Equipe alterada com sucesso!</span>
		<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=equipes&amp;equipes<?=$filtro_pag?>">
		<?php
	} else {
		$estadio = mysql_fetch_array(mysql_query("select * from equipes where Id like '$_GET[equipes]'"));
	?>
	<form method="post">
		<?php
			$pagina_tipo = "equipes";
			require_once('forms.php');
		?>
	<button type="submit">Alterar</button>
	</form>
	<?
	}
	break;

/////////////////////////////////		DELETAR
case 'deletar':
	$sql = "DELETE FROM equipes WHERE Id='$_GET[equipes]'"; 
	$resultado = mysql_query($sql) 
	or die (mysql_error()); 
	
	?>
	<span class="mensagem">Equipe Apagada com sucesso!</span>
	<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=equipes&amp;equipes">
	<?php
break;
}
?>

</div>
