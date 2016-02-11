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
		global $_ll;
		
		if(!empty($css)){
			if(!in_array($css, $_ll['css']))
				$_ll['css'][] = $css;				
			
		}else{
			$ret = '';
			foreach($_ll['css'] as $css){
				$ret.= '<link rel="stylesheet" href="'.$css.'"/>'."\r\n\t";
			}
			
			if($ecoa)
				echo $ret;
			else
				return $ret;
		}
	}
	
	public static function loadJs($js = null, $ecoa = true){
		global $_ll;
		
		if(!empty($js)){
			if(!in_array($js, $_ll['js']))
				$_ll['js'][] = $js;
		}else{
			$ret = '';
			foreach($_ll['js'] as $js){
				$ret.= '<script type="text/javascript" src="'.$js.'"></script>'."\r\n\t";
			}
			
			if($ecoa)
				echo $ret;
			else
				return $ret;
		}
	}
	
		
	/********************************************************** 	Gerenciamento de API	 					*/
	public static function iniciaApi($api){
		$api = strtolower($api);
		$loaded = true;
		
		$load = self::$apis[$api];
		if(empty($load['carregado']) || $load['carregado']==false)
			$loaded=false;
		
		if(!$loaded ){
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

?>