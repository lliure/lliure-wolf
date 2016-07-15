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
		
		$user = @mysql_query('select id from '.PREFIXO.'lliure_autenticacao where login = "'.$login.'" limit 1');
		if(@mysql_num_rows($user) > 0){
			$user = mysql_fetch_array($user);
			$user = $user['id'];
		} else{
			@mysql_query('INSERT INTO '.PREFIXO.'lliure_autenticacao (login, nome, cadastro, grupo, tema) VALUES ("'.$login.'", "'.$nome.'", "'.time().'", "'.$grupo.'", "'.$tema.'")');
			$user = mysql_insert_id();
		}
			
		if(isset($user) && !empty($user)) {		
			$_SESSION['ll']['user'] = array(
				'id' => $user,
				'login' => $login,
				'nome' => $nome,
				'grupo' => $grupo,
				'tema' => $tema,
				'token' => self::token('novo')
				);
			
			mysql_query('UPDATE '.PREFIXO.'lliure_autenticacao SET ultimoacesso="'.time().'" WHERE  id="'.$user.'";');
			
			return true;
		} else {
			return false;
		}
	}
	
	/* Revoga a autenticação do usuário no sistema */
	public static function desautentica(){
		unset($_SESSION['ll']['user']);
		return true;
	}
	
	/********************************************************** 	Tratamento de cabeçalho 					*/
	/**
	 * @param null $css
	 * @param bool|true $ecoa
	 * @deprecated
	 */
	public static function loadCss($css = null, $ecoa=true){
		if(!empty($css))
			self::add($css, 'css');

		else
			self::footer();
	}

	/**
	 * @param null $js
	 * @param bool|true $ecoa
	 * @deprecated
	 */
	public static function loadJs($js = null, $ecoa = true){
		if(!empty($js))
			self::add($js, 'js');
		else
			self::header();
	}


	/**
	 * carrera scripts, estilos e ou componentes para o sistema
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
	 * lliure::add('app/teste/estilo.css.php', 'css', 5);
	 *
	 * @OBS.: as prioridades serven para determinar quando seu arquivo aparecera. a prioridade padrão é 10,
	 * e quanrto menor este numero, mais para o inicio do documento seu arquivo aparecera.
	 *
	 * carregando scripts e estilos, marcando o tipo, mudando a prioridade e define uma dependencia.
	 * lliure::add('app/teste/estilo.css.php', 'css', 5, 'jquery');
	 *
	 * @OBS.: Dependencia de um script é oque ele precisa para funcionar. toda dependencia é sempre uma outra api
	 *
	 * carregando um arquivo .php.
	 * lliure::add('app/teste/teste.php'); //faz um require no arquivo no começo do documento (requere)
	 *
	 * carregando uma call (chamado a uma funcao ou metodo estatico).
	 * lliure::add('func_teste', 'call'); //carrega uma funcao especifica
	 * lliure::add('class_teste::func_teste', 'call'); //carrega um metodo especifica
	 * lliure::add(array('class_teste::func_teste', $var1, $var2), 'call'); //carrega um metodo especifica passando parametros para ele
	 *
	 * @param string|array $file
	 * @param null $type
	 * @param int $priorit
	 * @param string $dependency
	 */
	public static function add($file, $type = null, $priorit = 10, $dependency = ''){

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

		if(is_array($file))
			$type = 'call';

		if($type === null){
			$f = parse_url($file);
			$e = explode(".", $f['path']);
			$ext = strtolower(array_pop($e));
			$type = $ext;}

		if($type == 'call' && is_array($file) && isset($file[0]) && strpos($file[0], '::') !== false)
			$file[0] = explode('::', $file[0], 2);

		if(isset($_ll['docs']))
		foreach($_ll['docs'] as $l => $ps)
		foreach($ps as $p => $is)
		foreach($is as $i => $ts)
		foreach($ts as $t => $f)
		if($f == $file) return;

		$reord = !(isset($_ll['docs'][$loc]));
		$_ll['docs'][$loc][$priorit][][$type] = $file;
		if($reord) ksort($_ll['docs'][$loc]);

		if($dependency != '' && self::loadedComponent($dependency) === null)
			ll::api($dependency);
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
		$bl = "\r\n";

		foreach($ds as $p => $is)
		foreach($is as $i => $ts)
		foreach($ts as $t => $f){
			if ($t == 'css')
				echo '<link type="text/css" rel="stylesheet" href="' . $f . '" />'. $bl. $tab;

			elseif ($t == 'ico')
				echo '<link type="image/x-icon" rel="SHORTCUT ICON" href="' . $f . '" />'. $bl. $tab;

			elseif ($t == 'js')
				echo '<script type="text/javascript" src="' . $f . '"></script>'. $bl. $tab;

			elseif ($t == 'php')
				require $f;

			elseif ($t == 'call'){
				if(is_array($f))
					@call_user_func_array(array_shift($f), $f);

				else
					@call_user_func(f);
			}

		}
	}

	public static function api($name){
		return self::loadComponent('api', $name);
	}

	public static function app($name){
		return self::loadComponent('app', $name);
	}

	private static function loadComponent($type, $name){
		global $_ll;
		if (file_exists($f = ($type. '/'. $name. '/inicio.php'))
		|| (file_exists($f = ($type. '/'. $name. '/'. $name. '.php')))){
			$_ll['components'][$type][$name] = true;
			return require_once $f;

		} else
			$_ll['components'][$type][$name] = false;
	}

	private static function loadedComponent($name){
		global $_ll;
		foreach($_ll['components'] as $type => $names)
		foreach($names as $n => $status)
			if($name == $n) return $status;
		return null;
	}

	/********************************************************** 	Gerenciamento de API	 					*/
	/**
	 * @param $api
	 * @return bool
	 * @deprecated
	 */
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

	/**
	 * @param $api
	 * @return bool
	 * @deprecated
	 */
	public static function inicia($api){
		return self::iniciaApi($api);
	}

	/**
	 * @param $api
	 * @deprecated
	 */
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

/**
 * Class ll
 * Apelido para a função lliure
 */
class ll extends lliure{}

/**
 * Class ll_app
 * @deprecated
 */
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