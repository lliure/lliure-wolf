<?php
$caseestadio = '';

if(isset($_GET['novo'])){
	$caseestadio = 'novo';
} elseif(isset($_GET['deletar'])){
	$caseestadio = 'deletar';
}  elseif(isset($_GET['result'])){
	$caseestadio = 'result';
}  elseif(isset($_GET['classif'])){
	$caseestadio = 'classif';
} elseif(isset($_GET['rodadas']) && ctype_digit($_GET['rodadas'])){
	$caseestadio = 'alterar';
}

switch($caseestadio){

/////////////////////////////////		DEFAULT
default:
	?>
	<h2>Rodadas cadastradas </h2>
	<?php
	
	$navigi = new navigi();
	$navigi->tabela = appTabela.'rodadas';
	$navigi->query = 'select *, 	
						(SELECT Nome FROM '.appTabela.'equipes where Id = Time1) as timeA,
						(SELECT Nome FROM '.appTabela.'equipes where Id = Time2) as timeB,
						(SELECT Nome FROM '.appTabela.'categoria where Id = categoria) as cat
						
						from '.$navigi->tabela.'  where tipo = "0" order by Numero desc' ;
	$navigi->delete = true;
	$navigi->exibicao = 'lista';
	$navigi->pesquisa = 'Numero:int,timeA,timeB';
	$navigi->paginacao = 15;
	$navigi->config = array(
		'id' => 'Id',
		'coluna' => 'Numero',
		'link' => $this->sapm->home . '&p=step&id=',
		'botao' => array(
				array(
					'fa' => 'fa-bullhorn', 
					'link' => $this->sapm->home . '&id=#ID', 
					'modal' => '300xauto'
				)
			)
		);	
	
	$navigi->etiqueta = array(
		'id' => 'Cod',		
		'timeA' => 'Time A',
		'timeB' => 'Time B',
		'coluna' => array('Nº', '50px'),
		'cat' => array('Categoria', '150px'),
		);
			
	$navigi->monta();
	
	break;

/////////////////////////////////		NOVO
case 'novo': 
?>
<h2>Cadastrar rodada</h2>
<?php
if((isset($_POST['numero']))){
	$categoria = $_GET['cat'];
	
	$numero = $_POST['numero'];
	$time1 = $_POST['time1'];
	$time2 = $_POST['time2'];
	$estadio = $_POST['ginasio'];
	$data = mktime($_POST['hora'], $_POST['minutos'], '0', $_POST['mes'], $_POST['dia'], $_POST['ano']);
	
	$cadastro = mysql_query("INSERT INTO rodadas (Numero, Time1, Time2, Categoria, Data, Estadio, tipo) values ('$numero', '$time1', '$time2', '$categoria', '$data', '$estadio','0')") or die(mysql_error());
	
	?>
	<span class="mensagem">Rodada castrada com sucesso!</span>
	<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=rodadas&amp;rodadas&novo&cat=<?=$categoria?>">
	<?php
} elseif (isset($_GET['cat']) || isset($_POST['categoria'])) {
?>
<form method="post"  ENCTYPE="multipart/form-data">
	<?php
		$pagina_tipo = "rodadas";
		require_once('forms.php');
	?>
<button type="submit">Cadastrar</button>
</form>
<?php
} else {
?>
<form method="get"  ENCTYPE="multipart/form-data">
<input name="rodadas"  type="hidden"/>
<input name="novo" type="hidden"/>
<label>
	<span>Selecione a categoria:</span>
		<select name="cat">
		<?php
			$categoria_busca = mysql_query("SELECT * FROM categoria order by Nome Asc");
			while($categoria = mysql_fetch_array($categoria_busca)){
			$nome = $categoria['Nome'];
			$id = $categoria['Id'];
			?>
			<option value="<?=$id?>" ><?=$nome?></option>
			<?php
			}
		?>
			
		</select>
</label>
<button type="submit">Continuar</button>
</form>
<?php
}
break;

/////////////////////////////////		ALTERAR
case 'alterar':?>
<h2>Alterar</h2>
<?php
		if(isset($_GET['pagina'])){
			$filtro_pag = "&pagina=".$_GET['pagina'];
		}
		
if((isset($_POST['numero'])) and (isset($_POST['time1']))){
	
	$numero = $_POST['numero'];
	$time1 = $_POST['time1'];
	$time2 = $_POST['time2'];
	$estadio = $_POST['ginasio'];
	
	$data = mktime($_POST['hora'], $_POST['minutos'], '0', $_POST['mes'], $_POST['dia'], $_POST['ano']);
		
	$sql = "UPDATE  rodadas SET Numero='$numero', Time1='$time1', Time2='$time2', Data='$data', Estadio='$estadio' where Id like '$_GET[rodadas]'"; 
	$resultado = mysql_query($sql) 	or die (mysql_error()); 
	?>
	<span class="mensagem">Rodada alterada com sucesso!</span>
	<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=rodadas&amp;rodadas<?=$filtro_pag?>">
	<?php
} else {
	$rodada = mysql_fetch_array(mysql_query("select * from rodadas where Id like '$_GET[rodadas]'"));
?>
<form method="post">
	<?php
		$pagina_tipo = "rodadas";
		require_once('forms.php');
	?>
<button type="submit">Alterar</button>
</form>
<?php
}
break;

/////////////////////////////////		ALTERAR RESULTADO
case 'result':
?>
<h2>Resultado</h2>
<?php
if((isset($_POST['gols1'])) and (isset($_POST['gols2']))){
	
	$gols1 = $_POST['gols1'];
	$gols2 = $_POST['gols2'];
	$Wo = $_POST['Wo'];
	
	$time = mysql_query("SELECT * FROM rodadas where Id like '$_GET[rodadas]'");
	$result = mysql_fetch_array($time);
	
		if($Wo == 1){
			if($gols2 == 0){
				alt_classific($result['Time1'], '3', $gols1, $gols2, 1, 0, 0);
				alt_classific($result['Time2'], '0', $gols2, $gols1, 0, 1, 0 );
			} else {
				alt_classific($result['Time1'], '0', $gols1, $gols2, 0, 1, 0);
				alt_classific($result['Time2'], '3', $gols2, $gols1, 1, 0, 0 );
			}
		} elseif($gols1 > $gols2){
			alt_classific($result['Time1'], '3', $gols1, $gols2, 1, 0, 0);
			alt_classific($result['Time2'], '1', $gols2, $gols1, 0, 1, 0 );
		} elseif($gols1 == $gols2){
			alt_classific($result['Time1'], '2', $gols1, $gols2, 0, 0, 1);
			alt_classific($result['Time2'], '2', $gols2, $gols1, 0, 0, 1);
		} else {
			alt_classific($result['Time2'], '3', $gols2, $gols1, 1, 0, 0 );
			alt_classific($result['Time1'], '1', $gols1, $gols2, 0, 1, 0);
		}
		
	$sql = "UPDATE  rodadas SET Gols1='$gols1', Gols2='$gols2', Wo='$Wo' where Id like '$_GET[rodadas]'"; 
	$resultado = mysql_query($sql) 	or die (mysql_error()); 
	?>
	<span class="mensagem">Resultado atribuido com sucesso!</span>
	<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=rodadas&amp;rodadas">
	<?php
} else {
	$rodada = mysql_fetch_array(mysql_query("select * from rodadas where Id like '$_GET[rodadas]'"));
?>
<form method="post">
	<?php
		$pagina_tipo = "rodadasresult";
		require_once('forms.php');
	?>
<button type="submit">Atribuir</button>
</form>
<?php
}
break;

/////////////////////////////////		GERA CLASSIFICAÇÃO
case 'classif': 
	$time = mysql_query("SELECT * FROM rodadas where Gols1 != 0 or Gols2 != 0");
	$apagatudo = mysql_query("TRUNCATE TABLE classificacao");
	while($result = mysql_fetch_array($time)){
		$gols1 = $result['Gols1'];
		$gols2 = $result['Gols2'];
		$Wo = $result['Wo'];
		
		if($Wo == 1){
			if($gols2 == 0){
				alt_classific($result['Time1'], '3', $gols1, $gols2, 1, 0, 0);
				alt_classific($result['Time2'], '0', $gols2, $gols1, 0, 1, 0 );
			} else {
				alt_classific($result['Time1'], '0', $gols1, $gols2, 0, 1, 0);
				alt_classific($result['Time2'], '3', $gols2, $gols1, 1, 0, 0 );
			}
		} elseif($gols1 > $gols2){
			alt_classific($result['Time1'], '3', $gols1, $gols2, 1, 0, 0);
			alt_classific($result['Time2'], '1', $gols2, $gols1, 0, 1, 0 );
		} elseif($gols1 == $gols2){
			alt_classific($result['Time1'], '2', $gols1, $gols2, 0, 0, 1);
			alt_classific($result['Time2'], '2', $gols2, $gols1, 0, 0, 1);
		} else {
			alt_classific($result['Time2'], '3', $gols2, $gols1, 1, 0, 0 );
			alt_classific($result['Time1'], '1', $gols1, $gols2, 0, 1, 0);
		}
	}

	?>
	<span class="mensagem">Tabela de classificação atualizada com sucesso!</span>
	<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=rodadas&amp;rodadas">
	<?php
break;

/////////////////////////////////		DELETAR
case 'deletar':

	$sql = "DELETE FROM rodadas WHERE Id='$_GET[rodadas]'"; 
	$resultado = mysql_query($sql) 
	or die (mysql_error()); 
	
	?>
	<span class="mensagem">Rodada apagada com sucesso!</span>
	<meta http-equiv="refresh" content="1; URL=?plugin=lhesp&amp;acoes=rodadas&amp;rodadas">
	<?php
break;
}
?>

