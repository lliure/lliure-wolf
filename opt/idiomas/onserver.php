<?php
/**
 * lliure WAP
 *
 * @Versão 8.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 */

echo '<pre>'. print_r($_POST, 1). '</pre>';
echo '<pre>'. print_r($_GET, 1). '</pre>';

switch(isset($_GET['ac']) ? $_GET['ac'] : 'home' ){

case 'del':
	$i = 1; $idiomaSet = array();
	
	foreach($_ll['conf']->idiomas as $chave => $valor)
		if($_GET['idi'] != $valor) $idiomaSet['a'. ($i++)] = $valor;

	if(!isset($idiomaSet['nativo']) && !empty($idiomaSet))
		$idiomaSet = array_merge(array('nativo' => array_shift($idiomaSet)), $idiomaSet);

	$_ll['conf']->idiomas = $idiomaSet;

	ll::complila_conf($_ll['conf']);

break;

case 'natv':

	$i = 1; $idiomaSet = array();

	foreach($_ll['conf']->idiomas as $chave => $valor)
		if($_GET['idi'] == $valor)
			$idiomaSet['nativo'] = $valor;

		else
			$idiomaSet['a'. ($i++)] = $valor;

	$_ll['conf']->idiomas = $idiomaSet;

	ll::complila_conf($_ll['conf']);

break;

case 'write':

	$_ll['conf']->idiomas = ll::ota($_ll['conf']->idiomas);

	if(isset($_ll['conf']->idiomas) && !empty($_ll['conf']->idiomas))
		$_ll['conf']->idiomas[('a'. (count($_ll['conf']->idiomas) + 1))] = $_POST['idioma'];
		
	else
		$_ll['conf']->idiomas->nativo = $_POST['idioma'];
	
	ll::complila_conf($_ll['conf']);

break;
}

header('location: '. $_ll['opt']['onclient']);