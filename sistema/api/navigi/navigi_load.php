<?php
/**
*
* API navigi - lliure
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once('../../etc/bdconf.php');
require_once('../../includes/functions.php');

$navigi = unserialize(jf_decode($_SESSION['logado']['token'], $_POST['token']));

function navigi_tratamento($dados){
	global $navigi;

	$configSel = 0;
	
	if($navigi['configSel']  != false)
		$configSel = $dados[$navigi['configSel']];
		
	$dados['coluna'] = $dados[$navigi['config'][$configSel]['coluna']];

	$dados['click'] = null;
	
	if(isset($navigi['config'][$configSel]['link_col']))
		$dados['click'] = $dados[$navigi['config'][$configSel]['link_col']];
	elseif(isset($navigi['config'][$configSel]['link']))
		$dados['click'] = $navigi['config'][$configSel]['link'].$dados['id'];
		
					  
	$dados['ico'] = 'api/navigi/img/ico.png';
	
	if(isset($navigi['config'][$configSel]['ico']))
		$dados['ico'] = $navigi['config'][$configSel]['ico'];
	
	if(isset($navigi['config'][$configSel]['ico_col']) && !empty($navigi['config'][$configSel]['ico_col']))
		$dados['ico'] = $dados[$navigi['config'][$configSel]['ico_col']];
	
	$dados['as_id'] = $dados[$navigi['config'][$configSel]['as_id']];
	
	$dados['rename'] = false;
	$dados['delete'] = false;
	
	$per_ren = $navigi['rename'];
	$per_del = $navigi['delete'];
	
	if(isset($navigi['config'][$configSel]['rename']) && $navigi['config'][$configSel]['rename']){
		$dados['rename'] = true; 
		$per_ren = 1;
	}

	if(isset($navigi['config'][$configSel]['delete']) && $navigi['config'][$configSel]['delete']){
		$dados['delete'] = true; 
		$per_del = 1;
	}
	$dados['permicao'] = $per_ren.$per_del;
	
	return $dados;	
}


$query = mysql_query($navigi['query']);

if(mysql_error() != false)
	die('Erro na consulta mysql: <strong>'.$navigi['query'].'</strong>');
	
$navigi['rename'] = ($navigi['rename'] ? 1 : 0);
$navigi['delete'] = ($navigi['delete'] ? 1 : 0);

if($navigi['exibicao'] == 'icone'){ 	//// exibindo como icones
	while($dados = mysql_fetch_assoc($query)){
		$dados = navigi_tratamento($dados);
		
	
		echo '<div 	class="navigi_item" '
					.'id="item_'.$dados['id'].'" ' 
					.'as_id="'.$dados['as_id'].'" ' 
					.($navigi['configSel'] != false ? 'seletor="'.$dados[$navigi['configSel']].'" ' : '')
					.'dclick="'.$dados['click'].'" '
					.'permicao="'.$dados['permicao'].'" '
					.'nome="'.$dados['coluna'].'"> '
					 
				.'<span class="navigi_ico"><span><img src="'.$dados['ico'].'" alt="'.$dados['coluna'].'" /></span></span>'
				.'<span id="nome_'.$dados['id'].'" class="navigi_nome">'.$dados['coluna'].'</span>'
			.'</div>';

	}
} else {	//// exibindo como lista
	$ico = false;
	
	if($navigi['configSel'] != false){
		$ico = $navigi['config'];
		$ico = array_pop($ico);
		$ico = (isset($ico['ico']) ? true : false);
	}
	
	echo '<table class="table navigi_list">'
			.'<tr>'
				.($ico == true ? '<th class="ico"></th>' : '' )
				.'<th class="cod">Cod.</th>'
				.'<th></th>'
				.'<th class="ico"></th>'
				.'<th class="ico"></th>';
		
		while($dados = mysql_fetch_array($query)){
			$dados = navigi_tratamento($dados);
			
			$colspan = 3;
			$rename = null;
			$delete = null;
			
			if($navigi['rename'] || $dados['rename']){
				$rename = '<td class="navigi_ren"><img src="api/navigi/img/rename.png"></td>';
				$colspan--;
			}
			
			if($navigi['delete'] || $dados['delete']){
				$delete = '<td class="navigi_del"><img src="api/navigi/img/trash.png"></td>';
				$colspan--;
			}
			
			echo '<tr class="navigi_tr" '
					.'id="item_'.$dados['id'].'" ' 
					.'as_id = "'.$dados['as_id'].'" ' 
					.'dclick="'.$dados['click'].'" '
					.($navigi['configSel'] != false ? 'seletor="'.$dados[$navigi['configSel']].'" ' : '')
					.'permicao="'.$dados['permicao'].'" '
					.'nome="'.$dados['coluna'].'">'
			
					.($ico == true ? '<td><img src="'.$dados['ico'].'" alt="'.$dados['coluna'].'" /></td>' : '' )
					
					.'<td>'.str_pad($dados['as_id'], 7, 0, STR_PAD_LEFT).'</td>'
					.'<td colspan="'.$colspan.'"><div class="navigi_nome">'.$dados['coluna'].'</div></td>'
			
					.$rename
					.$delete

				.'</tr>';
		}
		
		echo '</table>';
		
}
?>
