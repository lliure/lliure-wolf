<?php/**** API Fileup - lliure WAP** @Vers�o 8.0* @Pacote lliure* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/* @Licen�a http://opensource.org/licenses/gpl-license.php GNU Public License**//*	no formulario use assim:	<form  enctype="multipart/form-data" ...	$file = new fileup; 					//inicia a classe	$file->titulo = 'Imagem'; 				//titulo da Label	$file->rotulo = 'Selecionar imagem'; 	// texto do bot�o	$file->registro = $dados['imagem'];	$file->campo = 'imagem'; 				//campo do banco de dados (no retorno no formulario ele ir� retornar um $_POST com essa chave, no caso do exemplo $_POST['imagem'])	$file->extencao = 'png jpg'; 			//exten��es permitidas para o upload, se deixar em branco ser� aceita todas	$file->form(); 				 			// executa a classe	OU	$file = new fileup(array(		'label' => 'Imagem',		'name' => 'imagem',		'value' => $dados['imagem'],		'accept' => 'png jpg',		'button' => 'Selecionar imagem'	));	echo $file;	No retorno no formulario use assim: 	$file = new fileup; 											// incia a classe	$file->diretorio = '../../../uploads/porta_niquel/ofertas/';	// pasta para o upload (lembre-se que o caminho � apartir do arquivo onde estiver sedo execultado)	$file->up(); // executa a classe*/class fileup{		public $campo,		$name;	public $titulo,		$label = null;	public $registro,	$value;	public $extencao,	$accept = '*';	public $rotulo,		$button = 'Upload';	public $diretorio, 	$directory;	public function __construct(array $attrs = array()){		$this->campo		=& $this->name;		$this->titulo		=& $this->label;		$this->registro		=& $this->value;		$this->extencao		=& $this->accept;		$this->rotulo		=& $this->button;		$this->diretorio	=& $this->directory;		list(			$this->name,			$this->label,			$this->value,			$this->accept,			$this->button		) = array_values(array_merge(array(			'name' => 'file[]',			'label' => null,			'value' => '',			'accept' => '*',			'button' => 'Upload'		), $attrs));	}	static public function make(array $attrs = array()){		return new self($attrs);	}	public function __toString(){		if(!is_array($this->name))			$this->name = array($this->name);		$total_names = count($this->name);		if(!is_array($this->label))			$this->label = array_fill(0, $total_names, $this->label);		if(!is_array($this->value))			$this->value = array_fill(0, $total_names, $this->value);		if(!is_array($this->button))			$this->button = array_fill(0, $total_names, $this->button);		if(!empty($this->accept)){			if((!is_array($this->accept)))				$this->accept = array_fill(0, $total_names, $this->accept);			foreach($this->accept as $k => $r){				$f = array();				foreach(($a = preg_split('/[, ]/sim', $r)) as $t){					if (empty($t) || $t == '*')						continue;					if (preg_match('/\\//sim', $t))						$f[] = $t;					elseif (!preg_match('/^[.]/sim', $t))						$f[] = '.' . $t;					else						$f[] = $t;				}				$this->accept[$k] = implode(',', $f);			}		}		$ret = ''; foreach($this->name as $chave => $name) $ret .=		(!empty($this->label[$chave])? '<div class="form-group"><label>'.$this->label[$chave].'</label>' : '').			'<div class="fileUpBloco input-group">'.				'<span class="input-group-btn">'.					'<button class="btn btn-default fileUpBloco-btn-up" type="button" style="border-right: none;">'.						'<i class="fa fa-upload"></i> <span class="hidden-xs"> ' . $this->button[$chave]. '</span>'.					'</button>'.				'</span>'.				'<input type="file" name="fileUp[file][]"'. (empty($this->accept[$chave])? '': ' accept="'. $this->accept[$chave] . '"'). '>'.				'<input type="hidden" name="fileUp[del][]" value="0">'.				'<input type="hidden" name="fileUp[name][]" value="'. $this->name[$chave]. '" />'.				'<input type="hidden" name="fileUp[regant][]" value="'.$this->value[$chave].'" />'.				'<input type="text" class="form-control fileUpBloco-input" value="'. $this->value[$chave]. '" readonly="readonly">'.				'<span class="input-group-btn">'.					'<button class="btn btn-default fileUpBloco-btn-del" type="button" title="Deletar arquivo">'.						'<i class="fa fa-trash-o"><i class="fa fa-check"></i></i>'.					'</button>'.				'</span>'.			'</div>'.		(!empty($this->label[$chave])? '</div>' : ''); return $ret;	}	function form(){		echo $this;	}	function up(){		if(!isset($_FILES['fileUp']['name'])){			echo 'Arquivo n�o enviado. verifique se o formulario de origem est� setado como <strong>enctype="multipart/form-data"</strong> <br/>';			unset($_POST['fileUp']); return false;}		$campos = [];				foreach($_POST['fileUp']['name'] as $chave => $name){			$campoName = $_POST['fileUp']['name'][$chave];			$key = (isset($campos[$campoName])? count($campos[$campoName]): 0);			$campos[$campoName][$key] = $_POST['fileUp']['regant'][$chave];			if($_POST['fileUp']['del'][$chave] == '1'){				@unlink($this->directory. $_POST['fileUp']['regant'][$chave]);				$campos[$campoName][$key] = null;}			if($_FILES['fileUp']['error']['file'][$chave] == 0){				$imagemNome = self::NomeUnico($_FILES['fileUp']['name']['file'][$chave]);								if(!empty($_POST['fileUp']['regant'][$chave]))					@unlink($this->directory. $_POST['fileUp']['regant'][$chave]);				if($_POST['fileUp']['del'][$chave] != '1'){					move_uploaded_file($_FILES['fileUp']['tmp_name']['file'][$chave], $this->directory . $imagemNome);					$campos[$campoName][$key] = $imagemNome;				}else $campos[$campoName][$key] = null;}		}unset($_POST['fileUp']);		foreach($campos as $campo => $values) foreach($values as $k => $file){			parse_str(($campo. '='. rawurlencode($file)), $unidade);			$_POST = array_merge_recursive($_POST, $unidade);}	}	private static function NomeUnico($arquivo){		$imagemNome = explode('.', $arquivo);		$extenc = array_pop($imagemNome);		$imagemNome = join(".", $imagemNome);		$imagemNome = jf_urlformat($imagemNome);		$imagemNome = $imagemNome.'_'.substr(md5(time()), rand(0, 20), 8).'.'.$extenc;		$imagemNome = strtolower($imagemNome);		return $imagemNome;	}}