<?php


/**
 *
 * Classe de implementa��o do lliure
 *
 * @Vers�o do lliure 8.0
 * @Pacote lliure
 *
 * Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * Licen�a http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

class lliure {
	public static $apis = array();
	
	/**
	 * @return stdClass
	 */
	public static function carrega_conf(){
		global $_ll;

		if(isset($_ll['conf']) && !empty($_ll['conf']))
			return $_ll['conf'];

		if (file_exists($f = (realpath(dirname(__FILE__). '/../etc/llconf.ll'))))
			//return ($_ll['conf'] = self::xmlToObject($f));
			return json_decode(file_get_contents($f));

		return new stdClass();
	}

	public static function complila_conf($newConfs = null){
		//echo '<pre>'; var_dump($newConfs); echo '</pre>';

		if($newConfs === null && isset($GLOBALS['_ll']['conf']))
			$newConfs = $GLOBALS['_ll']['conf'];

		if(file_put_contents((realpath(dirname(__FILE__). '/../etc'). '/llconf.ll'), self::arrayToJsonRecursive($newConfs)) === false)
			return false;

		return self::carrega_conf();
	}

	/**
	 * json_encode alternativo com op��o de compactar 
	 *
	 * @param $array
	 * @param bool $compacto
	 * @return string
	 */
	static public function json_encode($array, $compacto = true){
		return self::arrayToJsonRecursive($array, $compacto);
	}

	/**
	 * @param $array array parar convercao
	 * @param bool $c [true: Compacto, false: descompactado (identado)]
	 * @param string $t Tabula��o
	 * @return string string json
	 */
	private static function arrayToJsonRecursive($array, $c = false, $t = ''){
		$aS = array(); $oS = array(); $tA = true; $kR = 0;
		foreach ($array as $kA => $v){
			$k = self::escapeJsonString($kA);
			switch (gettype($v)){
				default;
				case "object": 	$v = (array) $v;
				case "array":	$oS[] = '"'. $k. '":'. (!$c? ' ': ''). ($aS[] = (self::arrayToJsonRecursive($v, $c, ("\t". $t)))); break;
				case "boolean": $oS[] = '"'. $k. '":'. (!$c? ' ': ''). ($aS[] = ($v? 'true': 'false')); break;
				case "integer":
				case "double":	$oS[] = '"'. $k. '":'. (!$c? ' ': ''). ($aS[] = ($v)); break;
				case "string":	$oS[] = '"'. $k. '":'. (!$c? ' ': ''). ($aS[] = ('"'. self::escapeJsonString($v). '"')); break;
				case "NULL":	$oS[] = '"'. $k. '":'. (!$c? ' ': ''). ($aS[] = ('null'));
			} $tA = (is_numeric($kA) && $kA == $kR? $tA: false); $kR++;
		} return (empty($aS)? '{}': (!$tA? "{": "["). (!$c? "\n\t". $t: '') . implode(",". (!$c? "\n\t". $t: ''), (!$tA? $oS: $aS)). (!$c? "\n". $t: ''). (!$tA? "}": "]"));
	}

	/**
	 * @param string $value texto para tratar
	 * @return string texto tratado
	 */
	private static function escapeJsonString($value){
		return str_replace(
			array(  "\\",   "\"",   "/",  "\n",  "\r",  "\t", "\x08", "\x0c"),
			array("\\\\", "\\\"", "\\/", "\\n", "\\r", "\\t", "\\f",  "\\b" ),
			$value
		);
	}

	/**
	 * Carrega as configura��es do modulo.
	 * lliure 9.0 tomara como pad�o confg.ll como nome padr�o para o arquivo
	 * e seu conteuto seja um json.
	 *
	 * ainda � compativel com xml (mas n�o � mais recomendado)
	 *
	 * @param string $operation_type tipo do modulo
	 * @param string $operation_load modulo
	 * @param bool $load_confs [true: registra, false: n�o registra] no $_ll
	 * @return array|stdClass as congura��es do modulo
	 */
	public static function confg_app(
		$operation_type,
    	$operation_load,
		$load_confs = false
	){
		global $_ll;

		if ( isset($_ll[$operation_type]['conf'])
		&& (!empty($_ll[$operation_type]['conf'])))
			return($_ll[$operation_type]['conf']);

		if (file_exists($f = (realpath(dirname(__FILE__). "/../$operation_type/$operation_load/sys/config.ll")))
		|| (file_exists($f = (realpath(dirname(__FILE__). "/../$operation_type/$operation_load/sys/config.plg"))))) {
			$confs = file_get_contents($f);

			if (preg_match('/^\\s*<\\?xml/sim', $confs)) {
				if(($confs = @simplexml_load_string($confs, 'SimpleXMLElement', LIBXML_NOCDATA)) != false){
					$confs = self::ota($confs);
					array_walk_recursive($confs, function(&$item){
						$item = utf8_decode($item);
					});
				}else
					$confs = array();
			}else
				$confs = json_decode($confs, TRUE);

			$confs = array_merge(array(
				'nome' => $operation_load,
				'ico' => (
					(file_exists(realpath(dirname(__FILE__). "/../". ($f = "$operation_type/$operation_load/sys/ico.svg"))))? $f:(
					(file_exists(realpath(dirname(__FILE__). "/../". ($f = "$operation_type/$operation_load/sys/ico.png"))))? $f:
					('usr/img/icon_defaulto.png')))
			), $confs);

			$confs = json_decode(self::json_encode($confs));

			if($load_confs) $_ll[$operation_type]['conf'] = $confs;
			return $confs;
		}

		return new stdClass();
	}

	/**
	 * Valida se o usuario � de um grupo
	 * @param string|array|null $grupo
	 * @return bool
	 */
	public static function valida($grupo = null){

		if(func_num_args() > 1)   $grupo = func_get_args();
		elseif(!is_array($grupo)) $grupo = explode(',', ((string) $grupo));
		$grupo_user = $_SESSION['ll']['user']['grupo'];

		if(($grupo_user == 'dev') || (in_array($grupo_user, $grupo))) return true;
		return false;
	}

	/**
	 * Faz autentic�o do us�io no sistema
	 *
	 * @param null $login
	 * @param null $nome
	 * @param string $grupo
	 * @param string $tema @deprecated
	 * @return bool
	 */
	public static function autentica($login = null, $nome = null, $grupo = 'user', $tema = 'default'){
		if($login === null){
			if(isset($_SESSION['ll']['user']) && !empty($_SESSION['ll']['user']['login']))
				return true;
			else
				return false;
		}
		
		$usQu = @mysql_query('SELECT id from '.PREFIXO.'lliure_autenticacao where login = "'.$login.'" limit 1');
		if(@mysql_num_rows($usQu) > 0){
			$user = mysql_fetch_array($usQu);
			$user = $user['id'];
		} else{
			@mysql_query('INSERT INTO '.PREFIXO.'lliure_autenticacao (login, nome, cadastro, grupo) VALUES ("'.$login.'", "'.$nome.'", "'.time().'", "'.$grupo.'")');
			$user = mysql_insert_id();}

		if(!isset($user) || empty($user))
			return false;

		self::usr('token');
		$_SESSION['ll']['user'] = array(
			'id' => $user,
			'login' => $login,
			'nome' => $nome,
			'grupo' => $grupo,
			//'tema' => $tema,
			'token' => Token::create()
		);

		mysql_query('UPDATE '.PREFIXO.'lliure_autenticacao SET ultimoacesso="'.time().'" WHERE  id="'.$user.'";');
		return true;

	}
	
	/* Revoga a autentica��o do us�rio no sistema */
	public static function desautentica(){
		unset($_SESSION['ll']['user']);
		return true;
	}

	/**
	 * carrera scripts, estilos, metas tags, chamadas ou componentes para o sistema
	 *
	 * carregando scripts e estilos.
	 * lliure::add('app/teste/estilo.css'); // carrega meu estilo
	 * lliure::add('app/teste/script.js'); // carrega meu script
	 *
	 * carregando scripts e estilos, marcando o tipo.
	 * lliure::add('app/teste/estilo.css', 'css'); // carrega meu estilo
	 * lliure::add('app/teste/script.js', 'js'); // carrega meu script
	 * lliure::add('app/teste/estilo.css.php', 'css'); // carrega um arquivo php como um estilo
	 *
	 * carregando scripts e estilos, marcando o tipo e mudando a prioridade.
	 * lliure::add('app/teste/estilo.css.php', 'css', 15);
	 *
	 * @OBS.: as prioridades serven para determinar quando seu arquivo aparecera. a prioridade padr�o � 10,
	 * e quanrto menor este numero, mais para o inicio do documento seu arquivo aparecera. Procure sempre
	 * usar prioridades de valor maior que 10 pos abaixo disto � reservado para o sistema.
	 *
	 * fixando tags personalisados no heder.
	 * lliure::add(array('http-equiv' => 'Content-Type', 'content' => 'text/html; charset=iso-8859-1'), 'meta');
	 *
	 * carregando um arquivo .php.
	 * lliure::add('app/teste/teste.php'); //faz um require no arquivo no come�o do documento (requere)
	 *
	 * carregando uma call (chamado a uma funcao ou metodo estatico).
	 * lliure::add('func_teste', 'call'); //carrega uma funcao especifica
	 * lliure::add('class_teste::func_teste', 'call'); //carrega um metodo especifica
	 * lliure::add(array('class_teste::func_teste', $var1, $var2), 'call'); //carrega um metodo especifica passando parametros para ele
	 *
	 * @param string|array $file
	 * @param string|null $type
	 * @param int $priorit
	 * @return bool
	 */
	public static function add($file, $type = null, $priorit = 10){

		global $_ll;
		$loc = 'header';

		if(strpos($type, ':') !== false){
			$e = explode(':', $type, 2);
			if (($k = array_search('header', $e)) !== false || ($k = array_search('footer', $e)) !== false)
				$loc = $e[$k];
			$type = $e[($k == 1? 0: 1)];}

		if($type == 'header' || $type == 'footer'){
			$loc = $type;
			$type = null;}

		if($type === null && is_array($file))
			$type = 'meta';

		if($type === null){
			$f = parse_url($file);
			$e = explode(".", $f['path']);
			$ext = strtolower(array_pop($e));
			$type = $ext;}

		if(null === array_search($type, array(
			'js',
			'css',
			'php',
			'meta',
			'bese',
			'link',
			'call',
			'integral',
		))) return false;

		if(is_string($file) && ($type == 'js' || $type == 'css')){

			$f = parse_url($file);
			$base = explode(DIRECTORY_SEPARATOR, realpath(dirname($_SERVER['DOCUMENT_ROOT']. $_SERVER['PHP_SELF'])));
			$arfl = explode(DIRECTORY_SEPARATOR, ($a = realpath($f['path'])));

			if(empty($arfl)) return false;

			foreach ($base as $k => $v)
				if($base[$k] == $arfl[$k])
					unset($base[$k], $arfl[$k]);
				else
					break;
			$file = str_repeat('../', count($base)). implode('/', $arfl). ((isset($f['query']))? '?'. $f['query']: '');}


		if($type == 'call' && is_array($file) && isset($file[0]) && strpos($file[0], '::') !== false)
			$file[0] = explode('::', $file[0]);

		if($type == 'css'){
			$type = 'link';
			$file = (!is_array($file)? array('type'=> 'text/css', 'rel' => 'stylesheet', 'href' => $file): $file);}


		if(isset($_ll['docs']))
		foreach($_ll['docs'] as $l => $ps)
		foreach($ps as $p => $is)
		foreach($is as $i => $ts)
		foreach($ts as $t => $f)
		if($f == $file) return false;

		$reord = !(isset($_ll['docs'][$loc][$priorit]));
		$_ll['docs'][$loc][$priorit][][$type] = $file;
		if($reord) ksort($_ll['docs'][$loc]);

		return true;
	}

	/**
	 * Devolve o caminho do arquivo que processa os scrits do cabe�alho
	 * Usado na conatru��o de layouts para o sistema.
	 *
	 * Modo de usar:
	 * <?php require_once ll::header(); ?>
	 * 
	 * @return string
	 */
	public static function header(){
		return 'usr/lliure/header.php';
	}

	/**
	 * Devolve o caminho do arquivo que processa os scrits do conteudo
	 * Usado na conatru��o de layouts para o sistema.
	 * 
	 * Modo de usar:
	 * <?php require_once ll::content(); ?>
	 * 
	 * @return string
	 */
	public static function content(){
		return 'usr/lliure/content.php';
	}

	/**
	 * Devolve o caminho do arquivo que processa os scrits do rodap�
	 * Usado na conatru��o de layouts para o sistema.
	 *
	 * Modo de usar:
	 * <?php require_once ll::footer(); ?>
	 * 
	 * @return string
	 */
	public static function footer(){
		return 'usr/lliure/footer.php';
	}

	/**
	 * rederiza todos os documentos da lista no head
	 */
	public static function renderHeader(){
		global $_ll;
		if(isset($_ll['docs']['header']))
			self::getDocs($_ll['docs']['header']);
	}

	/**
	 * rederiza todos os documentos da lista no footer, isto �,
	 * escreve todos os style, script e ou
	 */
	public static function renderFooter(){
		global $_ll;
		if(isset($_ll['docs']['footer']))
			self::getDocs($_ll['docs']['footer']);
	}

	private static function getDocs(array $ds){

		//global $_ll;

		//echo '<pre>';
		//print_r($ds);
		//echo '</pre>';

		//$tab = ll::EX('docs_tab', "\t");
		$tab = "\t";
		$bl = "\r\n";
		$l = array();

		foreach($ds as $p => $is) // Documentos, posi��o, �ndices
		foreach($is as $i => $ts) // �ndices, �ndice, Tipos
		foreach($ts as $t => $f){ // Tipos, Tipos, fun��o
			//if(!($t == 'php' || $t == 'call')) $f = (preg_match('/^http/', trim($f)) > 0? '': $_ll['url']['real']). $f;

			switch ($t){
				case 'meta':
				case 'base':
				case 'link':
					$l[] = '<'.$t.' ' . self::implodeMeta($f) . '/>';

				break;
				case 'css':
					$l[] = '<link type="text/css" href="' . $f . '">';

				break;
				case 'js':
					$l[] = '<script type="text/javascript" src="' . $f . '"></script>';

					break;
				case 'integral':
					if(is_array($f)) foreach ($f as $i)
						$l[] = (string) $i;
					else
						$l[] = $f;

				break;
				case 'php':
					require $f;

				break;
				case 'call':
					if(is_array($f))
						@call_user_func_array(array_shift($f), $f);
					else
						@call_user_func($f);

				break;
			}
		}

		echo implode($bl. $tab, $l);
	}

	public static function implodeMeta(array $array){
		$r = array();
		foreach ($array as $k => $v)
			$r[] = $k. '="'. $v . '"';
		return implode(' ', $r);
	}

	public static function api($name){
		return self::loadComponent('api', $name);
	}

	public static function app($name){
		return self::loadComponent('app', $name);
	}

	public static function usr($name){
		return self::loadComponent('usr', $name);
	}

	public static function opt($name){
		return self::loadComponent('opt', $name);
	}

	private static function loadComponent(
		$type,
		$name
	){
		global $_ll;
		$name = strtolower($name);
		if (file_exists($f = ($_ll['dir']. $type. '/'. $name. '/' .($a =  'boot' ). '.php'))
		|| (file_exists($f = ($_ll['dir']. $type. '/'. $name. '/' .($a = 'inicio'). '.php')))
		|| (file_exists($f = ($_ll['dir']. $type. '/'. $name. '/' .($a =  $name  ). '.php')))){

			$_ll['components'][$type][$name] = true;
			if($a === 'boot')
				require_once $f;

			if ((!isset($_ll['install']) || !$_ll['install'])
			&& (file_exists($f = ($_ll['dir']. $type. '/'. $name. '/' .($a = 'inicio'). '.php'))
			|| (file_exists($f = ($_ll['dir']. $type. '/'. $name. '/' .($a =  $name  ). '.php')))))
				require_once $f;

			return true;

		} else {
			$_ll['components'][$type][$name] = false;
			return false;
		}
	}

	private static function loadedComponent($name){
		global $_ll;
		foreach($_ll['components'] as $type => $names)
		foreach($names as $n => $status)
			if($name == $n) return $status;
		return null;
	}




	/**
	 *  lliure::menu(array(
	 *
	 *      lliure::menuGrupo('paginas', 'Menu', array(
	 *          lliure::menuSubGrupo('jogo', ['trophy', 'Jogos'], array(
	 *              lliure::menuItem('estadio', 'Gin�sios'),
	 *              lliure::menuItem('equipe', 'Equipes'),
	 *              lliure::menuItem('rodada', 'Rodadas'),
	 *              lliure::menuItem('categorias', 'Categorias'),
	 *          )),
	 *          lliure::menuItem('artilharia', ['futbol-o', 'Artilharia']),
	 *      )),
	 *
	 *      lliure::menuGrupo('anexo', ['paperclip', 'Anexos'], array(
	 *          lliure::menuItem('regras', 'Regras', ['modo' => 'onserver', 'url' => ['teste' => 'teste']]),
	 *          lliure::menuItem('outros', 'Outros'),
	 *      )),
	 *
	 *      lliure::menuGrupo('teste', 'Teste', array(
	 *          lliure::menuItem('teste1', 'Teste 1'),
	 *          lliure::menuSubGrupo('teste2', 'Teste 2', array(
	 *              lliure::menuItem('teste2-1', 'Teste 2.1'),
	 *              lliure::menuItem('teste2-2', 'Teste 2.2'),
	 *              lliure::menuSubGrupo('teste2-3',   'Teste 2.3', array(
	 *                  lliure::menuItem('teste2-3-1', 'Teste 2.3.1'),
	 *                  lliure::menuItem('teste2-3-2', 'Teste 2.3.2'),
	 *                  lliure::menuItem('teste2-3-3', 'Teste 2.3.3'),
	 *                  lliure::menuItem('teste2-3-4', 'Teste 2.3.4'),
	 *              )),
	 *              lliure::menuSubGrupo('teste2-4',   'Teste 2.4', array(
	 *                  lliure::menuItem('teste2-4-1', 'Teste 2.4.1'),
	 *                  lliure::menuItem('teste2-4-2', 'Teste 2.4.2'),
	 *                  lliure::menuItem('teste2-4-3', 'Teste 2.4.3'),
	 *                  lliure::menuItem('teste2-4-4', 'Teste 2.4.4'),
	 *              )),
	 *          )),
	 *          lliure::menuItem('teste3', 'Teste 3'),
	 *          lliure::menuItem('teste4', 'Teste 4'),
	 *      )),
	 *
	 *  ));
	 *
	 * @param array $itens Lista de itens para o menu lateral
	 */
	public static function menu(array $itens){
		global $_ll; self::menuPulular($_ll['mainMenu'], $itens);}

	private static function menuPulular(&$lista, $itens){
		foreach($itens as &$item){
			switch(isset($item['type'])? $item['type']: null){
				case 'grupo': case 'subGrupo':
					$lista[$item['pasta']]['type'] = $item['type'];
					$lista[$item['pasta']]['nome'] = ((!!$item['nome'])? $item['nome']: $lista[$item['pasta']]['nome']);
					$lista[$item['pasta']]['pasta'] = $item['pasta'];
					$lista[$item['pasta']]['active'] = $item['active'];
					if(isset($lista[$item['pasta']]))
						self::menuPulular($lista[$item['pasta']]['itens'], $item['itens']);
					else
						$lista[$item['pasta']]['itens'] = $item['pasta'];
				break;
				case 'item':
					$lista[] = $item;
				break;
			}
		}
	}

	public static function menuSubGrupo($pasta, $nome = null, array $itens = array()){
		if((!$itens && isset($nome[0], $nome[0]['type']))){
			$itens = $nome;
			$nome = '';}
		if(is_array($nome) && isset($nome[0], $nome[1])){
			$nome['fa'] = $nome[0];
			$nome['nome'] = $nome[1];
			unset($nome[0], $nome[1]);}
		return array(
			'type'   => 'subGrupo',
			'active' => false,
			'nome'   => $nome,
			'pasta'  => $pasta,
			'itens'  => $itens,
		);
	}

	public static function menuGrupo($pasta, $nome = null, array $itens = array()){
		$item = self::menuSubGrupo($pasta, $nome, $itens);
		$item['type'] = 'grupo';
		return $item;
	}

	public static function menuItem($url, $nome, array $attrs = array()){
		if(is_array($nome) && isset($nome[0], $nome[1])){
			$nome['fa'] = $nome[0];
			$nome['nome'] = $nome[1];
			unset($nome[0], $nome[1]);}
		return array(
			'type'   => 'item',
			'active' => false,
			'item'   => array(
				'url'  => $url,
				'nome'  => $nome,
				'attrs' => $attrs,
			),
		);
	}




	public static function xmlToObject($file){
		if(($file = @simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA)) != false){
			$file = self::ota($file);
			array_walk_recursive($file, function(&$item, $key){
				$item = utf8_decode($item);
			});
		}

		$file = self::ato($file);

		//var_dump($file); die();
		return $file;
	}

	// converte um array para Objeto
	public static function ato($array){
		if(!is_array($array))
			return $array;

		foreach ($array as $k => $v)
			$array[$k] = self::ato($v);
		return (object) $array;
	}

	// converte objeto para array
	public static function ota($obj){
		if(!is_object($obj))
			return $obj;

		$obj = (array) $obj;
		foreach ($obj as $k => $v)
			$obj[$k] = self::ota($v);
		return $obj;
	}
	
	
	/**
	 * Bloqueio
	 */
	public function denied($mod){
		global $_ll;
		echo 'Voc� n�o tem permiss�o para acessar est� p�gina! <br/>';
		echo '<a href="' . $_ll['url']['real'] . '">Retornar a �rea de trabalho</a>';
		die();
	}

}



/**
 * Class ll
 * Apelido para a classe lliure
 */
class ll extends lliure{}



/* Identifica o diret�rio atual do sistema */
ll_dir();