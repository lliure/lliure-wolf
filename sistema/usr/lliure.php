<?php
/**
*
* Classe de implementação do lliure
*
* @Versão do lliure 8.0
* @Pacote lliure
*
* Entre em contato com o desenvolvedor <lliure@glliure.com.br> http://www.lliure.com.br/
* Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class lliure {
	public static $apis = array();
		
	/* Gerenciamento de token */
	public static function token($caso){
		switch($caso){
			default:
				if(isset($_SESSION['ll']['token']) && $_SESSION['ll']['token'] == $caso)
					return true;
				else
					return false;
				
			break;
			
			case 'exibe':
				if(isset($_SESSION['ll']['token']))
					return $_SESSION['ll']['token'];
				else
					return false;
			break;
			
			case 'novo':
				$token = uniqid(md5(rand()));
				$_SESSION['ll']['token'] = $token;
				return $token;
			break;
		}
	}
		
	/********************************************************** 	Autenticação 						*/
	/* Valida acesso pelo grupo */
	public static function valida($grupo = null){
		if(func_num_args() > 1){
			$grupo = func_get_args();
		} else {
			if(!is_array($grupo) && strpos($grupo, ','))
				$grupo = explode(',', $grupo);
		}
		
		$grupo_user = $_SESSION['ll']['user']['grupo'];
		switch($grupo_user){
			case 'dev':
				return true;
			break;
			
			default:
				if((is_array($grupo) && in_array($grupo_user, $grupo)) || $grupo == $grupo_user)
					return true;
			break;
		}
		
		self::denied('acesso');
	}
	
	/* Faz autenticação do usuário no sistema */
	public static function autentica($login = null, $nome = null, $grupo = 'user', $tema = 'default'){
		if($login === null){
			if(isset($_SESSION['ll']['user']))
				return true;
			else
				return false;
		}
		
		$user = mysql_query('select id from '.PREFIXO.'lliure_autenticacao where login = "'.$login.'" limit 1');
		if(mysql_num_rows($user) > 0){
			$user = mysql_fetch_array($user);
			$user = $user['id'];
		} else{
			mysql_query('INSERT INTO '.PREFIXO.'lliure_autenticacao (login, nome, cadastro, grupo, tema) VALUES ("'.$login.'", "'.$nome.'", "'.time().'", "'.$grupo.'", "'.$tema.'")');
			$user = mysql_insert_id();
		}
			
		
		$_SESSION['ll']['user'] = array(
			'id' => $user,
			'login' => $login,
			'nome' => $nome,
			'grupo' => $grupo,
			'tema' => $tema,
			'token' => self::token('novo')
			);
		
		mysql_query('UPDATE '.PREFIXO.'lliure_autenticacao SET ultimoacesso="'.time().'" WHERE  id="'.$user.'";');
	}
	
	/* Revoga a autenticação do usuário no sistema */
	public static function desautentica(){
		unset($_SESSION['ll']['user']);
		return true;
	}
	
	/********************************************************** 	Tratamento de cabeçalho 					*/
	public static function loadCss($css = null, $ecoa=true){

		if(!empty($css))
			self::add($css, 'css');

		else
			self::footer();

	}
	
	public static function loadJs($js = null, $ecoa = true){

		if(!empty($js))
			self::add($js, 'js');

		else
			self::header();

	}


	/** carrera scripts, estilos e ou componentes para o sistema
	 *
	 *  carregando scripts e estilos.
	 *  lliure::add('app/teste/estilo.css'); // carrega meu estilo
	 *  lliure::add('app/teste/script.js'); // carrega meu script
	 *
	 *  carregando scripts e estilos, marcando o tipo.
	 *  lliure::add('app/teste/estilo.css', 'css'); // carrega meu estilo
	 *  lliure::add('app/teste/script.js', 'js'); // carrega meu script
	 *  lliure::add('app/teste/estilo.css.php', 'css'); // carrega um arquivo php como um estilo
	 *
	 *  carregando scripts e estilos, mudando a prioridade.
	 *  lliure::add('app/teste/fonts.css', 5);
	 *
	 * 	as prioridades serven para determinar quando seu arquivo aparecera. a prioridade padrão é 10,
	 *  e quanrto menor este numero, mais para o inicio do documento seu arquivo aparecera.
	 *
	 *  carregando scripts e estilos, marcando o tipo e mudando a prioridade.
	 *  lliure::add('app/teste/estilo.css.php', 'css', 5);
	 *
	 *  carregando um arquivo .php.
	 * 	lliure::add('app/teste/teste.php'); //faz um require no arquivo no final do documento
	 *
	 *  carregando uma call (chamado a uma funcao ou metodo estatico).
	 * 	lliure::add('func_teste', 'call'); //carrega uma funcao especifica
	 * 	lliure::add('class_teste::func_teste', 'call'); //carrega um metodo especifica
	 */
	public static function add($file, $parm2 = null, $parm3 = null){

		global $_ll;
		$type = null;
		$priorit = 10;
		$file = trim($file);
		$loc = 'header';

		if(is_string($parm2)){

			$type = $parm2;

			if (is_numeric($parm3))
				$priorit = $parm3;

			if (strpos($type, ':') !== false) {
				$e = explode(':', $type, 2);
				if (($k = array_search('header', $e)) !== false || ($k = array_search('footer', $e)) !== false)
					$loc = $e[$k];
				$type = $e[(1 - $k)];
			}

			if($type == 'header' || $type == 'footer'){
				$loc = $type;
				$type = null;}

		}elseif (is_numeric($parm2))
			$priorit = $parm2;

		if($type !== null);
		elseif(substr($file, -2) == '()' && is_callable(substr($file, 0, -2)))
			$type = 'call';

		else{
			$f = parse_url($file);
			$e = explode(".", $f['path']);
			$ext = strtolower(array_pop($e));
			$type = $ext;
		}

		if(isset($_ll['docs']))
		foreach($_ll['docs'] as $l => $ps)
		foreach($ps as $p => $is)
		foreach($is as $i => $ts)
		foreach($ts as $t => $f)
		if($f == $file) return;

		$_ll['docs'][$loc][$priorit][][$type] = $file;

		ksort($_ll['docs'][$loc]);

	}

	/**
	 * require todos os documentos da lista no head
	 */
	public static function header(){
		global $_ll;
		if(isset($_ll['docs']['header']))
			self::getDocs($_ll['docs']['header']);
	}

	/**
	 * require todos os documentos da lista no footer
	 */
	public static function footer(){
		global $_ll;
		if(isset($_ll['docs']['footer']))
			self::getDocs($_ll['docs']['footer']);
	}

	private static function getDocs(array $ds, $loc = 'header'){

		//echo '<pre>';
		//print_r($ds);
		//echo '</pre>';

		//$tab = ll::EX('docs_tab', "\t");
		$tab = "\t";
		$bl = "\n\r";

		foreach($ds as $p => $is)
		foreach($is as $i => $ts)
		foreach($ts as $t => $f){

			if ($t == 'css') {
				echo '<link type="text/css" rel="stylesheet" href="' . $f . '" />'. $bl. $tab;
			} elseif ($t == 'ico') {
				echo '<link type="image/x-icon" rel="SHORTCUT ICON" href="' . $f . '" />'. $bl. $tab;

			} elseif ($t == 'js') {
				echo '<script type="text/javascript" src="' . $f . '"></script>'. $bl. $tab;
			} elseif ($t == 'php') {
				require $f;

			} elseif ($t == 'call') {
				call_user_func(substr($f, 0, -2));
			}

		}

	}

	public static function api($name){
		return self::loadComponente('api', $name);
	}

	public static function app($name){
		return self::loadComponente('app', $name);
	}

	private static function loadComponente($type, $name){
		if (file_exists($f = ($type. '/'. $name. '/inicio.php'))
		|| (file_exists($f = ($type. '/'. $name. '/'. $name. '.php'))))
			return require_once $f;
	}

	/********************************************************** 	Gerenciamento de API	 					*/
	public static function iniciaApi($api){
		$api = strtolower($api);
		$loaded = true;
		
		$load = self::$apis[$api];
		if(empty($load['carregado']) || $load['carregado']==false)
			$loaded=false;
		
		if(!$loaded ){
			if(!empty($load['caminho']) && !is_array($load['caminho']))
				require_once $load['caminho'];
			
			if(!empty($load['css']) && !is_array($load['css']))
				$load['css'] = explode(';', $load['css']);
			
			if(!empty($load['js']) && !is_array($load['js']))
				$load['js'] = explode(';', $load['js']);
			
			if(isset($load['css']) && is_array($load['css'])){
				foreach($load['css'] as $css)
					self::loadCss($css);
			}
			
			if(isset($load['js']) && is_array($load['js'])){
				foreach($load['js'] as $js)
					self::loadJs($js);
			}
			
			$loaded = true;
			self::$apis[$api]['carregado'] = $loaded;
			
		}

		return $loaded;
	}
	
	public static function inicia($api){
		return self::iniciaApi($api);
	}
	
	public static function addApi($api){
		$css = $api->css();
		$js = $api->js();
		$caminho = $api->caminho;
		$nome = $api->nome;
		
		self::$apis[$nome] = array(
					'js' => $js,
					'css' => $css,
					'caminho' => $caminho
				);
	}
	
	
	/********************************************************** 	Bloqueio				 					*/	
	public function denied($mod){
		switch ($mod){
		default:
			
			break;			
		}

		echo 'Você não tem permissão para acessar está página! <br/>';
		echo '<a href="index.php">Retornar a área de trabalho</a>';
		
		die();
	}
}

class ll extends lliure{}


class ll_app{
	private $css;
	private $js;
	public $nome;
	public $caminho;
	
	public function _construct($nome = ''){
		$this->setNome(strtolower($nome));
	}
	
	public function css($cssUrl = null){
		if(!empty($cssUrl)){
			$this->css[] = $cssUrl;
			return $this;
		}else{
			return $this->css;
		}
	}
	
	public function js($jsUrl = null){
		if(!empty($jsUrl)){
			$this->js[] = $jsUrl;
			return $this;
		}else{
			return $this->js;
		}
	}
	
	public function setNome($nome){
		$this->nome = strtolower($nome);
		$this->caminho = '';
		$this->css = array();
		$this->js = array();
		return $this;
	}
	
	public function setCaminho($caminho){
		$this->caminho = $caminho;
		return $this;
	}
	
	public function addApi(){
		lliure::addApi($this);
		return $this;
	}
}


/*function meus_tabs($tab){
	return "\t\t";
}
ll::ON('docs_tab', 'meus_tabs');

ll::EX('hook');*/


?>