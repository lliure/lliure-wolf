<?php

switch($pagina_tipo){

// ANEXOS
case "anexos": ?>
	
	<label>
		<span>Nome:</span>
		<input type="text" value="<?=$estadio['Nome']?>" name="nome"/>
	</label>
	
	<label>
		<span>Descrição:</span>
		<textarea name="descricao"><?=$estadio['Descricao']?></textarea>
	</label>
	<?php
	if(empty($_GET['anexos'])){ ?>
	<label>
		<span>Arquivo:</span>
		<input type="file" name="anexo" />
	</label>
	<?php
	}
break;

// ANEXOS
case "regras": ?>
	
	<label>
	<span>Nome:</span>
	<input type="text" value="<?=$estadio['Nome']?>" name="nome"/>
	</label>
	
	<label>
	<span>Descrição:</span>
	<textarea name="descricao"><?=$estadio['Descricao']?></textarea>
	</label>
	<?php
	if(empty($_GET['regras'])){ ?>
	<label>
	<span>Arquivo:</span>
	<input type="file" name="regra" />
	</label>
	<?php
	}
break;

// 	ESTADIOS
case "estadios": ?>
	
<?php
break;

//	EQUIPES
case "equipes":?>
	<label>
	<span>Nome:</span>
	<input type="text" value="<?=$estadio['Nome']?>" name="nome"/>
	</label>
	
	<label>
	<span>Resumo:</span>
	<textarea name="resumo"><?=$estadio['Resumo']?></textarea>
	</label>
	
	<label>
	<span>Sede:</span>
	<input type="text" value="<?=$estadio['Sede']?>" name="sede"/>
	</label>
	
	<label>
	<span>Fone:</span>
	<input type="text" value="<?=$estadio['Fone1']?>" name="fone1"/>
	</label>	
	
	<label>
	<span>Fone2:</span>
	<input type="text" value="<?=$estadio['Fone2']?>" name="fone2"/>
	</label>
	
	<label>
	<span>E-mail:</span> 
	<input type="text" value="<?=$estadio['Mail']?>" name="mail"/>
	</label>
	
	<label>
	<span>Ginasio:</span>
		<select name="ginasio">
		<?php
			$ginasio_busca = mysql_query("SELECT * FROM estadios order by Nome Asc");
			while($ginasio = mysql_fetch_array($ginasio_busca)){
			$nome = $ginasio['Nome'];
			$id = $ginasio['Id'];
			?>
			<option value="<?=$id?>" <?php if($estadio['Ginasio'] == $id){echo ' selected';} ?>><?=$nome?></option>
			<?php
			}
		?>
			
		</select>
	</label>
	
	<?php
	if(empty($_GET['equipes'])){ ?>
	<h5>Categoria</h5>
	
	<?php
			$categoria_busca = mysql_query("SELECT * FROM categoria order by Nome Asc");
			while($categoria = mysql_fetch_array($categoria_busca)){
			$nome = $categoria['Nome'];
			$id = $categoria['Id'];
			?>
			<label class="catbox">
			<input type="checkbox" name="cat<?=$id?>" value="ok"/> <span><?=$nome?></span>
			</label>
			<?php
			}
	}
break;

// 	NOTICIAS
case "noticias": ?>
	<label>
	<span>Titulo:</span>
	<input type="text" value="<?=$noticia['Titulo']?>" name="titulo"/>
	</label>
	
	<label>
	<span>Texto:</span>
	<textarea name="texto"><?=$noticia['Texto']?></textarea>
	</label>	
	
	<?php
	if(empty($_GET['noticias'])){ ?>
	<label>
	<span>Anexar foto:</span>
	<input type="file" name="anexo" />
	</label>
	
	<?php
	}
break;

// 	RODADAS
case "rodadas": 
$categoria = $_GET['cat'];
?>
	<label>
	<span>Numero:</span>
	<input type="text" value="<?=$rodada['Numero']?>" name="numero"/>
	</label>
	<label>
	<span>Time A:</span>
		<select name="time1">
		<?php
			if(isset($rodada['Time1'])){
				$where = "Id like $rodada[Time1]";
			} else{
				$where = "Categoria like $categoria";
			}
			$equipe_busca = mysql_query("SELECT * FROM equipes where $where order by Nome Asc");
			while($equipe = mysql_fetch_array($equipe_busca)){
			$nome = $equipe['Nome'];
			$id = $equipe['Id'];
			?>
			<option value="<?=$id?>" <?php if($rodadas['Time1'] == $id){echo ' selected';} ?>><?=$nome?></option>
			<?php
			}
		?>
			
		</select>
	</label>
	
	<label>
	<span>Time B:</span>
		<select name="time2">
		<?php
			if(isset($rodada['Time2'])){
				$where = "Id like $rodada[Time2]";
			} else{
				$where = "Categoria like $categoria";
			}
			
			
			$equipe_busca = mysql_query("SELECT * FROM equipes where $where order by Nome Asc");
			while($equipe = mysql_fetch_array($equipe_busca)){
			$nome = $equipe['Nome'];
			$id = $equipe['Id'];
			?>
			<option value="<?=$id?>" <?php if($rodadas['Time2'] == $id){echo ' selected';} ?>><?=$nome?></option>
			<?php
			}
		?>
			
		</select>
	</label>
	
	<label>
		<span>Ginasio:</span>
		<select name="ginasio">
		<?php
			$ginasio_busca = mysql_query("SELECT * FROM estadios order by Nome Asc");
			while($ginasio = mysql_fetch_array($ginasio_busca)){
			$nome = $ginasio['Nome'];
			$id = $ginasio['Id'];
			?>
			<option value="<?=$id?>" <?php if($rodadas['Ginasio'] == $id){echo ' selected';} ?>><?=$nome?></option>
			<?php
			}
		?>
			
		</select>
	</label>
	
	<label>
	<h5>Data:</h5> 
	<?php
		
	if(isset($rodada['Data'])){
	
		$ano = date('y', $rodada['Data']);
		$mes = date('m', $rodada['Data']);
		$dia = date('d', $rodada['Data']);
		
		$hor = date('G', $rodada['Data']);
		$min = date('i', $rodada['Data']);
		
		
	}
	?>
	<select name="dia" class="data">
		<?php
	for ($i = 1; $i <= 31; $i++) {
		?>
			<option value="<?=$i?>" <?php if($dia == $i){echo ' selected';} ?>><?=$i?></option>		
		<? }?>
	</select>
	/
	<select name="mes" class="data">
		<?php
	for ($i = 1; $i <= 12; $i++) {
		?>
			<option value="<?=$i?>" <?php if($mes == $i){echo ' selected';} ?>><?=$i?></option>		
		<? }?>
	</select>	
	/
	<select name="ano" class="datag">
		<?php
	for ($i = 9; $i <= 20; $i++) {
		if($i == 9){$i = "09";}
		?>
			<option value="20<?=$i?>" <?php if($ano == $i){echo ' selected';} ?>>20<?=$i?></option>		
		<? }?>
	</select>
	</label>
	
	<label>
	<h5>Horario:</h5> 
	<select name="hora" class="data">
		<?php
	for ($i = 1; $i <= 23; $i++) {
		?>
			<option value="<?=$i?>" <?php if($hor == $i){echo ' selected';} ?>><?=$i?></option>		
		<? }?>
			<option value="<?=$i?>" <?php if($hor == '00'){echo ' selected';} ?>>24</option>		
	</select>
	:
	<select name="minutos" class="data">
			<option value="0" <?php if($min == '00'){echo ' selected';} ?>>00</option>
		<?php
	for ($i = 1; $i <= 59; $i++) {
		?>
			<option value="<?=$i?>" <?php if($min == $i){echo ' selected';} ?>><?=$i?></option>		
		<? }?>
	</select>	
	</label>
	<br/>
	<br/>
<?php
break;

// 	RESULTADO DA EQUIPE
case 'rodadasresult':
			$equipe_busca = mysql_query("SELECT * FROM equipes where Id like $rodada[Time1]");
			while($equipe = mysql_fetch_array($equipe_busca))
			$nome1 = $equipe['Nome'];
			
			$equipe_busca = mysql_query("SELECT * FROM equipes where Id like $rodada[Time2]");
			while($equipe = mysql_fetch_array($equipe_busca))
			$nome2 = $equipe['Nome'];
?>
	<table width="600px">
		<tr>
			<th width="30px">Nº</th>
			<th width="250px" style="text-align: left;">Time A</th>
			<th width="50px" style="text-align: left;">Gols A</th>
			<th width="10px"></th>
			<th width="50px" style="text-align: left;">Gols B</th>
			<th width="250px" style="text-align: left;">Time B</th>
		</tr>
		<tr>
			<td><?=$rodada['Numero']?></td>
			<td><?=$nome1?></td>
			<th><input type="text" value="<?=$rodada['Gols1']?>" name="gols1" style="width: 50px;" name="gols1"/></th>
			<th>X</th>
			<th><input type="text" value="<?=$rodada['Gols2']?>" name="gols2" style="width: 50px;"/></th>
			<td><?=$nome2?></td>
		</tr>
	</table>
	
	<label class="catbox">
	<input type="checkbox" name="Wo" value="1" <? if($rodada['Wo'] == 1){ ?> checked <?php }?>/> <span>Wo</span>
	</label>
	<br/>
	<br/>
<?php
break;

}
?>