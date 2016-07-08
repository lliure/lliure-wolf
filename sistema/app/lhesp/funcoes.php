<?php
function alt_classific($time, $pontos, $marcados, $tomados, $vitoria, $derrota, $empate){
	if(mysql_num_rows(mysql_query("SELECT * FROM classificacao WHERE Time like $time")) != false){
		$sql = "UPDATE classificacao SET Pontos = Pontos + '$pontos', Gols = Gols + '$marcados', Gols_tomados = Gols_tomados + '$tomados', Vitoria = Vitoria + '$vitoria', Derrota = Derrota + '$derrota', Empate = Empate + '$empate' WHERE Time=$time"; 
		$resultado = mysql_query($sql) or die (mysql_error()); 
	} else {
		$equipe = mysql_fetch_array(mysql_query("SELECT * FROM equipes WHERE Id like $time"));
		$categ = $equipe['Categoria'];
		
		$sql = "INSERT INTO classificacao (Time, Pontos, Gols, Gols_tomados, Categoria, Vitoria, Derrota, Empate) VALUES ('$time', '$pontos', $marcados, '$tomados', '$categ','$vitoria', '$derrota', '$empate')";
		$resultado = mysql_query($sql) or die (mysql_error()); 
	}
}
?>