<?php

if(!defined('MIDIAS_BASEPATH')) define('MIDIAS_BASEPATH', realpath(dirname(__FILE__). '/../../'));
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

require_once MIDIAS_BASEPATH. '/etc/bdconf.php';
require_once MIDIAS_BASEPATH. '/includes/functions.php';

class Midias{

	const
		CORTES = array('c', 'o', 'm', 'p', 'r', 'a'),

		//Modelos
		ModeloPequeno = 'pequeno',
		ModeloGrande = 'grande',

		//tipos
		TiposImagens = 0;

	private static $tiposPreDefinidos = array(
		'png jpg gif'			// Midias::TiposImagens
	);

	private
		$titulo = 'Selecione os arquivos',
		$dica = '',
		$name = '',
		$modelo = null,
		$tipos = null,
		$corte = '',
		$cortes = self::CORTES,
		$quantidadeStar = 0,
		$quantidadeLength = 1,
		$quantidadeTotal = 1,
		$rais = '',
		$diretorio = '',
		$pasta = '',
		$pastaRef = '',
		$dados = array(),
		$inseridos = array(),
		$removidos = array();


	public function tipos($tipos = null) {
		if($tipos !== NULL)
			$this->tipos = is_numeric($tipos)? self::$tiposPreDefinidos[$tipos]: $tipos;

		else
			return is_array($this->tipos)? implode(' ', $this->tipos): $this->tipos;

		return $this;
	}

	public function corte($width = null, $height = null){
		if($width !== NULL && $height !== NULL)
			$this->corte = $width. '-'. $height;

		else
			return $this->corte;

		return $this;
	}

	public function cortes(array $cortes = NULL){
		if($cortes !== NULL)
			$this->cortes = $cortes;

		else
			return $this->cortes;

		return $this;
	}

	public function quantidade($star = NULL, $length = NULL) {
		if($star !== NULL && $length !== NULL ){
			$this->quantidadeStar = ($star >= 0? $star: 0);
			$this->quantidadeLength = ($length >= 1? $length: 1);

		}elseif($star !== NULL){
			$this->quantidadeStar = 0;
			$this->quantidadeLength = ($star >= 0? $star: 0);

		}else
			return $this->quantidadeTotal;

		$this->quantidadeTotal = $this->quantidadeStar + $this->quantidadeLength;
		return $this;
	}

	function quantidadeStar() {
		return $this->quantidadeStar;
	}

	function quantidadeLength() {
		return $this->quantidadeLength;
	}

	public function rais($rais = NULL){
		if($rais !== NULL)
			$this->rais = $rais;

		else
			return $this->rais;

		$this->dirExtar();
		return $this;
	}

	public function diretorio($diretorio = NULL){
		if($diretorio !== NULL)
			$this->diretorio = $diretorio;

		else
			return $this->diretorio;

		$this->dirExtar();
		return $this;
	}

	private function dirExtar(){
		$this->pasta = realpath($this->rais(). DS . ($dir = $this->diretorio() && !empty($dir)? $dir. DS: ''));
		$this->pastaRef = str_repeat('../', preg_match_all('/\\|\//', SISTEMA) + 1). str_replace('\\', '/', substr($this->pasta, (strlen(MIDIAS_BASEPATH) - strlen(SISTEMA))));
	}

	public function pasta(){
		return $this->pasta;
	}

	public function pastaRef(){
		return $this->pastaRef;
	}

	public function name($name = NULL){
		if($name !== NULL)
			$this->name = $name;

		else
			return $this->name;

		return $this;
	}

	public function modelo($modelo = NULL){
		if($modelo === NULL)
			return ($this->modelo === null? ($this->quantidadeTotal <= 1? self::ModeloPequeno: self::ModeloGrande): $this->modelo);

		$this->modelo = ($modelo == self::ModeloPequeno? $modelo: ($modelo == self::ModeloGrande? $modelo: null));
		return $this;
	}

	private function addFiles(&$var, $files = null){
		if($files === NULL)
			return array_keys($var);

		$var = array();
		foreach($files as $file){
			$file = explode('/', $file);
			$var[array_pop($file)] = array_pop($file);
		}
		return $this;
	}

	public function dados(array $arquivos = NULL){
		return $this->addFiles($this->dados, $arquivos);
	}

	public function inseridos(array $arquivos = NULL){
		return $this->addFiles($this->inseridos, $arquivos);
	}

	public function removidos(array $arquivos = NULL){
		return $this->addFiles($this->removidos, $arquivos);
	}

	public function setCortes(array $cortes){
		foreach ($cortes as $arq => $corte){
			if(isset($this->dados[$arq])) $this->dados[$arq] = $corte;
			if(isset($this->inseridos[$arq])) $this->inseridos[$arq] = $corte;
		}
	}

	public function getCorte($arquivo){
		return
			(isset($this->inseridos[$arquivo])? $this->inseridos[$arquivo]:
				(isset($this->dados[$arquivo])? $this->dados[$arquivo]:
					(NULL)));
	}

	public function listaDeArquivos(){
		$arquivos = $this->dados;
		foreach($this->removidos as $arquivo => $corte)
			unset($arquivos[$arquivo]);
		foreach($this->inseridos as $arquivo => $corte)
			$arquivos[$arquivo] = $corte;
		return array_keys($arquivos);
	}

	public function dica($dica = NULL){
		if($dica !== NULL)
			$this->dica = $dica;

		else
			return $this->dica;

		return $this;
	}

	public function titulo($titulo = NULL){
		if($titulo !== NULL)
			$this->titulo = $titulo;

		else
			return $this->titulo;

		return $this;
	}


	public function __toString(){
		$t = (($t = count($this->dados())) <= $this->quantidadeTotal? $t: $this->quantidadeTotal);
		return '
			<div class="input api-midias api-midias-'. $this->modelo(). '" data-total-parcial="'. count($this->dados()). '"'. $this->enbled(). '>
				<input class="api-midias-input-file" type="file"'. ($this->quantidadeTotal > 1? ' multiple': ''). '/>
				<div class="api-midias-botoes">
					<button class="api-midias-upload" type="button" disabled="disabled" title="Upload"><i class="fa fa-upload"></i></i></button><br/>
					<button class="api-midias-servidor" type="button" disabled="disabled" title="Servidor"><i class="fa fa-server"></i></button>
				</div>
				<div class="api-midias-content"'. ($this->modelo() == self::ModeloGrande || $t == 1? '': ' style="display: none"'). '>
					'. $this->icones(). '
				</div>
				<div class="api-midias-multfiles"'. ($this->modelo() == self::ModeloPequeno && $t != 1? '': ' style="display: none"'). '>
					'. count($this->dados()). ' Arquivos
				</div>
			</div>
		';
	}

	protected function icones(){
		$r = ''; $i = 0;
		foreach($this->dados() as $file) {
			if($this->quantidadeTotal <= $i++) break;
			$nome = substr($file, (($p = strripos('/', $file)) !== false ? $p : 0));
			$ext = array_pop(explode('.', $nome));
			$tamanho = filesize($this->pasta() . '/' . $file);
			$corte = self::getCorte($file);
			$r .= '
					<div class="api-midias-file" data-name="'. $nome. '" data-tamanho="'. $tamanho. ($corte !== null? ' data-corte="' . $corte. '"': '') . '">
						<div class="api-midias-icone">
							<div class="api-midias-img"'. (in_array($ext, explode(' ', self::$tiposPreDefinidos[self::TiposImagens]))? '': ' style="display: none;"'). '>
								<img src="'. $this->pastaRef(). '/'. ($corte !== null? $corte. '/': '') . $file . '" alt=""/>
							</div>
							<div class="api-midias-generico"'. (in_array($ext, explode(' ', self::$tiposPreDefinidos[self::TiposImagens]))? ' style="display: none;"': ''). '>
								<i class="fa fa-file"></i>
								<div class="api-midias-etc">'. $ext. '</div>
							</div>
							<div class="api-midias-barra-load"></div>
						</div>
						<span class="api-midias-name">'. $nome. '</span><br class="api-midias-br-nome"/>
						<span class="api-midias-dados">Tamanho: '. self::tamanhoDoArquivo($tamanho). '</span>
					</div>
			';
		}return $r;
	}

	private static function tamanhoDoArquivo($tamanho){
		if($tamanho < 1024)
			return floor($tamanho).'b';

		$tamanho /= 1024;
		if($tamanho < 1024)
			return floor($tamanho).'kB';

		$tamanho /= 1024;
		if($tamanho < 1024)
			return floor($tamanho).'MB';

		$tamanho /= 1024;
		if($tamanho < 1024)
			return floor($tamanho).'GB';

		$tamanho /= 1024;
		if($tamanho < 1024)
			return floor($tamanho).'TB';

		$tamanho /= 1024;
		return floor($tamanho).'PB';

	}

	/*public function construirSelecionados(){
		$r  = '<div id="midias-dados-antetiores" style="display: none; visibility: hidden;">';
		foreach ($this->dados() as $id => $arquivo){
			$r .= '<input type="hidden" name="dados['. ($id + 1). ']" data-id="'. ($id + 1). '" value="'. ((!empty($this->dados[$arquivo])? $this->dados[$arquivo]. '/': ''). $arquivo). '"/>';
		}
		$r .= '</div>';
		return $r;
	}*/

	final static function preparaParaJson($array){
		if(is_array($array)){
			foreach ($array as $key => $value){
				$array[self::preparaParaJson($key)] = self::preparaParaJson($value);
			}
		}else{
			return rawurlencode($array);
		}
		return $array;
	}

	public function implode(){
		return rawurlencode(jf_encode($_SESSION['logado']['token'], serialize($this)));
	}

	public function enbled(){
		$r  = ' data-api-midias="' . $this->name(). '"';
		$r .= ' data-quant-start="'. $this->quantidadeStar(). '"';
		$r .= ' data-quant-length="'. $this->quantidadeLength(). '"';
		$r .= ' data-quant-total="'. $this->quantidade(). '"';
		$r .= ' data-corte="'. $this->corte(). '"';
		$r .= ' data-cortes="'. implode('-', $this->cortes()). '"';
		$r .= ' data-tipos="'. $this->tipos(). '"';
		$r .= ' data-dados="'. $this->dadosImplode($this->dados()). '"';
		$r .= ' data-action="'. $this->implode(). '"';
		return $r;
	}

	private function dadosImplode($dados){
		$r = array();
		foreach ($dados as $id => $arquivo){
			$r[] = $id. ':'. urldecode((!empty($this->dados[$arquivo])? $this->dados[$arquivo]. '/': ''). $arquivo);
		}
		return implode(';', $r). ';';
	}

	static public function atualizaBanco(&$_DADOS, $tabela, $idLigacao, $colunaLigacao = 'lig', $colunaArquivo = 'arquivo', $colunaOrden = null){

		if(isset($_DADOS['removidos']))
			foreach ($_DADOS['removidos'] as $removido)
				if($e = jf_delete($tabela, array($colunaLigacao => $idLigacao, $colunaArquivo => $removido)))
					echo $erro[] = $e;

		if(!empty($erro) && is_array($erro))
			echo '<pre>', print_r($erro, true), '</pre>';

		$insert = array();
		if(isset($_DADOS['inseridos'])){
			foreach ($_DADOS['inseridos'] as $pos => $inserir){
				if($colunaOrden !== NULL)
					$insert[] = array($colunaLigacao => $idLigacao, $colunaArquivo => $inserir, $colunaOrden => $pos);

				else
					$insert[] = array($colunaLigacao => $idLigacao, $colunaArquivo => $inserir);
			}
		}

		if(!empty($insert) && $erro = jf_insert($tabela, $insert))
			echo $erro;

	}

}