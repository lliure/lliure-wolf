<?php
/**
*
* lliure WAP
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
header("Content-Type: text/html; charset=ISO-8859-1",true);

require_once('../../includes/functions.php');
$llconf = simplexml_load_file('../../etc/llconf.ll');
$arrayConf = xml2array($llconf);


switch(isset($_GET['ac']) ? $_GET['ac'] : 'home' ){
case 'home':
	$pg_idiomas = '<h1>Controle de Idiomas</h1>';
	if(isset($arrayConf['idiomas']) && !empty($arrayConf['idiomas'])) {
		$pg_idiomas .= '<table class="table">'
			.'<tr> <th>Idiomas</th> <th width="16px"></th> <th width="16px"></th> </tr>';
			
			foreach($arrayConf['idiomas'] as $chave => $valor){
				$pg_idiomas .= '<tr>'
									.'<td>'.$ll_lista_idiomas[$valor].'</td>'
									
									.'<td><a href="opt/idiomas/idiomas.php?ac=natv&amp;idi='.$valor.'" class="jfbox"><img src="imagens/icones/'.($chave == 'nativo' ? 'round_checkmark' : 'round').'.png"></a></td>'
									
									.'<td><a href="opt/idiomas/idiomas.php?ac=del&amp;idi='.$valor.'" class="jfbox"><img src="imagens/icones/trash.png"></a></td>'
							  .'</tr>';

				unset($ll_lista_idiomas[$valor]);
			}
		$pg_idiomas .= '</table>';
	} else {
		$pg_idiomas .= 'A Ferramenta de multi-idiomas não está ativada';
	}

	$pg_idiomas .=
		 '<form class="form jfbox" action="opt/idiomas/idiomas.php?ac=write">'
			.'<fieldset>'
				.'<div>'
					.'<label>Adicionar linguagem</label>'
					.'<select name="idioma">';

				foreach($ll_lista_idiomas as $chave => $valor){
					$pg_idiomas .= '<option value="'.$chave.'">'.$valor.'</option>';
				}
					
	$pg_idiomas .=	'</select>'
				.'</div>'
			.'</fieldset>'
			.'<div class="botoes"><button type="submit">Adicionar</button></div>'
		.'</form>';

	echo $pg_idiomas;
break;

case 'del':
	$idiomaSet = array();
	
	foreach($arrayConf['idiomas'] as $chave => $valor){
		if($_GET['idi'] != $valor){
			$idiomaSet[$chave] = $valor;
		}
	}

	if(!isset($idiomaSet['nativo']) && count($idiomaSet) > 0){
		$idiomaSet['nativo'] = array_shift($idiomaSet);
	}
	
	$arrayConf['idiomas'] = $idiomaSet;

	$write = new jf_xml('<?xml version="1.0" encoding="iso-8859-1"?><configuracoes/>');
	$write->jf_array2xml($arrayConf);

	file_put_contents('../../etc/llconf.ll', $write->jf_pretty_xml());
	header('location: idiomas.php');
break;

case 'natv':
	$i = 1;
	$idiomaSet = array();
	foreach($arrayConf['idiomas'] as $chave => $valor){
		if($_GET['idi'] == $valor){
			$idiomaSet['nativo'] = $valor;
		} else {
			$idiomaSet['a'.$i] = $valor;
			$i++;
		}
	}
	$arrayConf['idiomas'] = $idiomaSet;

	$write = new jf_xml('<?xml version="1.0" encoding="iso-8859-1"?><configuracoes/>');
	$write->jf_array2xml($arrayConf);

	file_put_contents('../../etc/llconf.ll', $write->jf_pretty_xml());
	header('location: idiomas.php');
break;

case 'write':
	
	if(isset($arrayConf['idiomas']) && !empty($arrayConf['idiomas'])){
		$a = count($arrayConf['idiomas']);
		$arrayConf['idiomas']['a'.$a] = $_POST['idioma'];
	} else {
		$arrayConf['idiomas']['nativo'] = $_POST['idioma'];
	}
	
	// criamos um elento xml
	$write = new jf_xml('<?xml version="1.0" encoding="iso-8859-1"?><configuracoes/>');
	$write->jf_array2xml($arrayConf);

	// converte para string adicionar tabulação e salva como arquivo
	file_put_contents('../../etc/llconf.ll', $write->jf_pretty_xml());

	header('location: idiomas.php');
break;
}
?>
