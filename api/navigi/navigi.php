<?php
/**
*
* API navigi - lliure
*
* @Versão 8.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*	***	Documentação da função ***
	
	Para iniciar a classe
	$navegador = new navigi();
	
	Define a tabela da consulta
	$navegador->tabela = PREFIXO.'tabela ';

	Definendo a query para consulta
	$navegador->query = 'select * from '.$navegador->tabela;

	Define como será a exibição. por ser "lista" ou "icone"
	$navegador->exibicao = 'icone';

	Define as cofigurações da navegação
	$navegador->config = (array) $config;

	Para rodar a classe
	$navegador->monta();

	###
	Opção "config", essa é a mais complicada pois é nela que difinimos como vai ser o icone o clique duplo e a divercidade da busca...

	Assim ficaria uma configuração mais simples, no caso apenas vamos direcionar o duplo clique  para uma página (visualizar) que tenha o id "X" (por padrão o jfnav pesquisa o campo "id" e adiciona no final da url)
	$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id='
				);

#	Para alterar o icone padrão adicione 'ico' => 'img/meuico.png' onde o endereço começa a contar apartir da raiz do aplicativo
	exemplo da utilização
	$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id=',
				'ico' => 'img/meuico.png'
				);	

#	Alterando a coluna de exibição (por padrão se chama nome), desta forma vamos definir que a coluna que vamos consultar será "cor" ao invés de "nome"
		$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id=',
				'coluna' => 'cor'
				);

#	Exemplo de montagem do config com tabelas anexadas, isso é usado para quando a coluna principal estiver em outra tabela (um exemplo é quando utilizamos multidiomas), o "as_id" nada mais é que o id da FK para quando for fazer o rename realizar na tabela correta e com o id correto
	$navegador->config = array(
			'link' => '?app=meuapp&pagina=visualizar&id=',
			'ico' => 'img/meuico.png',

			'tabela' => PREFIXO.'meuapp_dados'
			'as_id' => 'campo_id'
			);
	
#	Consultando mais de um tipo de registro
	para este fim você tera que usar da mesma forma que a de cima porem dentro de arrays, e usar o parametro 'configSel' para definir o campo que diferencia um do outro, e os indices dos array de configuração será a diferença

	$navegador->configSel = 'tipo';
	$navegador->config['produto'] =  array (
	 			'link' => '?app=meuapp&pagina=visualizar&id=',
				'ico' => 'img/meuico.png'
				);
	
	$navegador->config['categoria'] =  array (
				'link' => '?app=meuapp&p=categoria&id=',
				'ico' => 'img/outroico.png'
				);
	
#	Para habilitar a função "apagar" passe como "true" o paramentro 'delete'
	$navegador->delete = true;
	
#	Para habilitar a função "renomear" passe como "true" o paramentro 'rename'
	$navigi->rename = true;
	
#	Trabalhando com botões auxiliares
	use 'ico' para definir o icone do botao
		'link' para definir o link ao clicar
		'modal' em caso de abertura de modal, sendo "Largura X Altura" ex: 250x100, para que fique automatico use a palavra "auto"
	
	ex:
	'botao' => array(
				array('ico' => $_ll['app']['pasta'].'img/box.png', 
				'link' => $_ll['app']['sen_html'].'&apm=produtos&sapm=produto&ac=estoque&id=#ID', 
				'modal' => '300xauto')
			)

#	Alterando os nomes das etiquetas
	$navigi->etiqueta = array(
								'id' => 'Pedido',								
								'coluna' => 'Data'
							);
	//1 lembrando que essas são as duas padrões utilizadas pelo sistema, caso adicione mais, as mesmo serão carregadas no modo lista com seus respectivos conteudos
	
	//2 utilize um array com o arg 0 com o nome e o arg 1 com a medida da coluna caso necessário
		ex: 'usuario' => array('nome','50px');

# Pesquisa
	para instanciar uma pesquisa utilize
	$navigi->pesquisa = 'Id:int,Numero:str';
	
	por padrao todos são strings
	$navigi->pesquisa = 'Id,Numero';
		
		
#	Exemplo de utilização simples *************
	
	$navigi = new navigi();
	$navigi->tabela = PREFIXO.'app';
	$navigi->query = 'select * from '.$navigi->tabela.' order by nome asc' ;
	$navigi->delete = true;
	$navigi->rename = true;
	$navigi->config = array(
		'ico' => $_ll['app']['pasta'].'imagens/sys/app.png',
		'link' => '?app=meuapp&ac=editar&id='           
		);								
	$navigi->monta();
	
*/

class navigi{

	/** query em mysql */
	var $query;
	
	/** pesquisa query em mysql */
	var $pesquisa = false;	
	
	/** tabela da consulta */
	var $tabela;
	
	/*apagar var $objetos; */
	
	/** configurações: ico; link; tabela; as_id, botao: array()*/
	var $config;
	
	/** true,false */
	var $debug = false;
	
	/** icone,lista */
	var $exibicao = 'icone';

	/** true,false */
	var $delete = false;	

	/** true,false */
	var $rename = false;	

	/** string // nome do campo que faz a diferenciação dos dados listados */
	var $configSel = false; 	

	/** false,1,n */
	var $paginacao = false;

	/** array(); ex: array('id' => 'codigo' [, 'coluna' => 'valor']) */	
	var $etiqueta = null;
	var $cell = null;

	private $colunas = array();
	private $campos = array();
	private $ordens = array();
	private static $filtros = array();

	public function monta($echo = false){
		global $_ll;

		/** Retro compatibilidade para verções antigas*/
		if(isset($this->config['campo'])){
			$this->configSel = $this->config['campo'];
			unset($this->config['campo']);
		}

        /** AJUSTA O PADRAO PARA CONFIGURACAO DE BOTOES */
        if(isset($this->config['botao']) && !isset($this->config['botao'][0]) && isset($this->config['botao']['link']))
            $this->config['botao'] = array(0 => $this->config['botao']);

        if($this->configSel === false)
            $this->config = array($this->config);

        foreach($this->config as $chave => $valor){
            $this->config[$chave]['coluna'] = 	(isset($this->config[$chave]['coluna']) ? 	$this->config[$chave]['coluna'] : 	'nome');
            $this->config[$chave]['as_id'] = 	(isset($this->config[$chave]['as_id']) ? 	$this->config[$chave]['as_id'] : 	'id');
            $this->config[$chave]['id'] = 		(isset($this->config[$chave]['id']) ? 		$this->config[$chave]['id'] : 		$this->config[$chave]['as_id']);
            $this->config[$chave]['tabela'] = 	(isset($this->config[$chave]['tabela']) ? 	$this->config[$chave]['tabela'] : 	$this->tabela);
        }

        /** CONFIGURA A ETIQUETA*/
        $this->colunas = array('id' => [], 'coluna' => []);

        if(!isset($this->etiqueta['id']))
            $this->etiqueta['id'] = 'Cod.';

        if(!isset($this->etiqueta['coluna']))
            $this->etiqueta['coluna'] = '';

        foreach($this->etiqueta as $chave => $valor){
            if(!is_array($valor))
                $this->etiqueta[$chave] = $valor = array('label' => $valor, 'width' => 'auto');

            elseif(is_array($valor) && isset($valor[0], $valor[1]))
                $this->etiqueta[$chave] = $valor = array_merge(array('label' => $valor[0], 'width' => $valor[1]), $valor);

            if($chave != 'id' && $chave != 'coluna')
                $this->cell[$chave] = $valor;

            $this->colunas[$chave] = $valor;
        }

		
		if($this->pesquisa != false && isset($_GET['pesquisa']) && !empty($_GET['pesquisa'])){
			$termos = explode(' ', $_GET['pesquisa']);
			
			// fitra os valores vazios
			foreach($termos as $chave => $valor)
				if(!empty($valor))
					$busca[] = $valor;
			
			$pesCam = explode(',', $this->pesquisa);
			foreach($pesCam as $key => $value){
				$pesCam[$key] = explode(':', $value);
				
				if(!isset($pesCam[$key][1]))
					$pesCam[$key][1] = 'str';
			}

			$query = '(';
			foreach($busca as $chave => $valor){
				$query .= ($chave != 0? ' AND ': '') . '(';

				foreach($pesCam as $key => $campo){
					$query .= ($key != 0? ' OR ': '');

					switch($campo[1]){
						case 'str':
							$query .= ' ' . $campo[0] . ' like "%' . $valor . '%"';
							break;
						case 'int':
							$query .= ' ' . $campo[0] . ' = "' . $valor . '"';
							break;
					}
				}
				$query .= ')';
			}
			$query .= ')';

			//if(strpos($this->query, 'where') !== false)
			$this->query = 'select * from ('.$this->query.') as qry where '.$query;
		}

		if(isset($_GET['s'])){
            $query = '';
            foreach($this->colunas as $key => $valor){
                if($key == 'coluna') $key = ((isset($this->config[$key]['coluna']))? $this->config[$key]['coluna']: ((isset($this->config[0]['coluna']))? $this->config[0]['coluna']: 'coluna'));
                $query .= (((($q = $this->filterFirter($key, $valor)) && !empty($q)) && empty($query))? '('. $q. ')': ((!empty($q))? ' AND ('. $q. ')': '')); }
            if(!empty($query)) $this->query = 'select * from ('.$this->query.') as qry where '.$query;
        }

		if(isset($_GET['o'])){
            $query = '';
            foreach($this->colunas as $key => $valor){
                if($key == 'coluna') $key = ((isset($this->config[$key]['coluna']))? $this->config[$key]['coluna']: ((isset($this->config[0]['coluna']))? $this->config[0]['coluna']: 'coluna'));
                $query .= (((($q = $this->filterOrder($key, $valor)) && !empty($q)) && empty($query))? $q: ((!empty($q))? ', '. $q: '')); }
            if(!empty($query)) $this->query .= ' ORDER BY '. $query;
        }

		if($this->paginacao != false){
			$pAtual = 1;
			if (isset($_GET['nvg_pg']) && !empty($_GET['nvg_pg'])) 
				$pAtual = $_GET['nvg_pg'];
				
			$inicio = $pAtual - 1;
			$inicio = $inicio * $this->paginacao;
				
			$tReg = mysql_query($this->query);
			if($error = mysql_error())
				echo '<pre>Erro na consulta SQL: <strong>'.$error.'</strong></pre>';
			
			$tReg = @mysql_num_rows($tReg);
				
			$tPaginas = ceil($tReg / $this->paginacao);
				
			$this->query = $this->query . ' limit ' . $inicio . ',' . $this->paginacao;

            $get = $_GET;
            unset($get['nvg_pg']);

			$url = $_ll['app']['home']. ((!empty($get))? '&'. http_build_query($get): '');
			$this->paginacao = array('pAtual' => $pAtual,'tPaginas' => $tPaginas, 'tReg' => $tReg, 'url' => $url);
		}
		
		/** caso tenha botoes na configuracao muda a exibicao para lista*/
		if(isset($this->config['botao']))
			$this->exibicao = 'lista';


        $navigi = array(
            'tabela'    => $this->tabela,
            'query'     => $this->query,
            'debug'     => $this->debug,
            'delete'    => $this->delete,
            'rename'    => $this->rename,
            'configSel' => $this->configSel,
            'paginacao' => $this->paginacao,
            'exibicao'  => $this->exibicao,
            'config'    => $this->config,
            'etiqueta'  => $this->etiqueta,
            'cell'      => $this->cell,
		);


        $ico = false;
        if($this->configSel != false){
            $ico = $this->config;
            $ico = array_pop($ico);
            $ico = (isset($ico['ico']) || isset($ico['fa']) ? true : false);}
		if($ico) array_unshift($this->colunas, array('class' => 'navigi_ico', 'order' => false));
		if($this->debug == true) echo '<pre>'.print_r($navigi ,true).'</pre>';
		$encriptado = jf_encode($_ll['user']['token'], serialize($navigi));


        $inpts = $buttons = $hidens = []; $i = 0;
        foreach(((isset($_GET['s']))? $_GET['s']: array()) as $k => $v) $inpts["s[{$k}]"] = $v;
        foreach(((isset($_GET['o']))? $_GET['o']: array()) as $k => $v) $buttons["o[{$k}]"] = $v;
        foreach(((isset($_GET['s']))? $_GET['s']: array()) as $k => $v) $hidens["s[{$k}]"] = $v;
        foreach(((isset($_GET['o']))? $_GET['o']: array()) as $k => $v) $hidens["o[{$k}]"] = $i++ . ":$v";


		$r = '<div id="navigi" class="navigi_loading" data-exibicao="' . $this->exibicao . '" token="'. $encriptado. '">';
        if($this->exibicao == 'lista'){
            $c = ''; $f = true;
            foreach($this->colunas as $key => $valor){
                if($key == 'coluna') $key = ((isset($this->config[$key]['coluna']))? $this->config[$key]['coluna']: ((isset($this->config[0]['coluna']))? $this->config[0]['coluna']: 'coluna'));

                if($f){if(isset($valor['class'])) $valor['class'] .= ' table-th-filter'; else $valor['class'] = 'table-th-filter';}
                $c .= '<th'.
                    ((isset($valor['class']))? ' class="' . $valor['class'] . '"': '').
                    ((isset($valor['width']) || isset($valor[1]))? ' style="width: ' . ((isset($valor['width']))? $valor['width']: $valor[1]) . ';"': ''). '>'.
                    $this->filters($key, $valor, $inpts, $buttons).
                '</th>';
            }

            if(!$f) $c .= '<th class="navigi_botoes"></th>'; else
                $c .= '<th class="navigi_botoes">'. $this->filterForm($hidens). '</th>';

            $r .=
            '<table class="table navigi_list">'
                .'<thead'. (($f)? ' class="id-form-filter"': ''). '>'.
                    '<tr>'. $c. '</tr>'.
                '</thead>'.
                '<tbody class="navigi_areaIcones"></tbody>'.
            '</table>';
        }elseif($this->exibicao == 'icone'){
            $r .= '<div class="navigi_areaIcones"></div>';
        }
        $r .= '<div class="navigi_paginacao"></div>';
        $r .= '</div>';


		if(!$echo){

            /* if($this->pesquisa != false) echo
            '<div>'.
                '<form class="form-inline" action="onserver.php?api=navigi&ac=pesquisa" method="post">'.
                    '<input name="url" value="'. $_ll['app']['home']. '&' . substr(jf_monta_link($_GET, array('nvg_pg', 'pesquisa')), 1).'" type="hidden">'.
                    '<div class="form-group">'.
                        '<label class="hidden-sm hidden-md hidden-lg">Pesquisar</label>'.
                        '<input class="form-control" placeholder="Pesquisar" name="pesquisa" value="'.(isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '').'" >'.
                    '</div>'.
                    '<div class="form-group text-right" style="margin-bottom: 0;">'.
                        '&nbsp;<button type="submit" class="btn btn-default">Buscar</button>'.
                    '</div>'.
                '</form><br>'.
            '</div>'; */

            echo $r;

        }else return $r;

        return '';
	}


	private function filters($campo, $coluna, $inpts, $buttons){

	    $filter = ((isset($coluna['filter']))? $coluna['filter']: array()); $r = '';
	    if(!isset($coluna['order'])) $coluna['order'] = true;

	    if(isset($filter['type']) || isset($coluna['order'])){
            $r .= '<div class="input-group" style="width: 100%;">';

            if(isset($filter['type'])){
                self::$filtros[$filter['type']] = $filter['type'];
                switch($filter['type']){
                    case 'text':
                    case 'number':
                        $this->campos[$campo] = $campo;
                        $r .=
                            '<input '. self::montaAttr($filter). ' class="form-control" '. self::montaInput('s['.$campo.']', $inpts). '>'.
                            '<label>'. ((isset($coluna['label']))? $coluna['label']: ((isset($coluna[0]))? $coluna[0]: '')). '</label>';
                    break;
                    case 'telefone':
                        $this->campos[$campo] = $campo;
                        $r .=
                            '<input '. self::montaAttr($filter). ' class="form-control" data-mask="telefone" '. self::montaInput('s['.$campo.']', $inpts). '>'.
                            '<label>'. ((isset($coluna['label']))? $coluna['label']: ((isset($coluna[0]))? $coluna[0]: '')). '</label>';
                    break;
                    case 'select':
                        $this->campos[$campo] = $campo;
                        $r .=
                            '<select class="form-control"'. self::montaInput('s['.$campo.']', $inpts). '>'.
                                self::montaSelect('s['.$campo.']', $inpts, ((isset($filter['options']))? $filter['options']: array()), true).
                            '</select>'.
                            '<label>'. ((isset($coluna['label']))? $coluna['label']: ((isset($coluna[0]))? $coluna[0]: '')). '</label>';
                    break;
                    case 'dateRange':
                        $this->campos[$campo.'-S'] = $campo.'-S';
                        $this->campos[$campo.'-E'] = $campo.'-E';
                        $r = '<div class="input-group input-daterange">';
                        $r .=
                        '<input type="text" class="form-control input-daterange-start" '. self::montaInput('s['.$campo.'-S]', $inpts) .' placeholder="De:">'.
                        '<span class="input-group-addon" style="width: 0 !important; min-width: 0;"></span>'.
                        '<input type="text" class="form-control input-daterange-end" '. self::montaInput('s['.$campo.'-E]', $inpts) .' placeholder="Ate:">'.
                        '<label>'. ((isset($coluna['label']))? $coluna['label']: ((isset($coluna[0]))? $coluna[0]: '')). '</label>';
                    break;
                }
            }else{
                $r .= '<label>'. ((isset($coluna['label']))? $coluna['label']: ((isset($coluna[0]))? $coluna[0]: '')). '</label>';
            }

            if($coluna['order']){
                $this->ordens[$campo] = $campo; $r .=
                '<span class="input-group-btn">'.
                    '<button class="btn btn-default" type="button" '. montaInput('o['.$campo.']', $buttons). '>'.
                        '<i class="fa fa-sort"></i>'.
                        '<i class="fa fa-sort-asc"></i>'.
                        '<i class="fa fa-sort-desc"></i>'.
                    '</button>'.
                '</span>';}

            $r .= '</div>';
	    }

	    return $r;
    }

    private function filterForm($hidens){
	    global $_ll;

	    $get = $_GET;
	    unset($get['nvg_pg'], $get['s'], $get['o']);

        $r =
        '<form action="onserver.php?api=navigi&ac=pesquisa" method="post" style="margin: 0;">'.
            '<input name="url" value="'. $_ll['app']['home']. '&' . http_build_query($get) .'" type="hidden">';

            foreach($this->campos as $campo) $r .=
                '<input type="hidden" class="form-control"'. montaInput('s['. $campo. ']', $hidens). '>';

            foreach($this->ordens as $campo) $r .=
                '<input type="hidden" class="form-control"'. montaInput('o['. $campo. ']', $hidens). '>';

            $r .=
            '<div class="btn-group">'.
                '<button type="submit" class="btn btn-default btn-form-filter-submit" name="filter"><i class="fa fa-search" aria-hidden="true"></i></button>'.
                '<button type="submit" class="btn btn-default btn-form-filter-submit"><i class="fa fa-eraser" aria-hidden="true"></i></button>'.
            '</div>'.
        '</form>';

        return $r;
    }

    private function filterFirter($campo, $coluna){
        $filter = ((isset($coluna['filter']))? $coluna['filter']: array()); $r = '';

        switch($filter['type']){
            default;
                if(isset($_GET['s'][$campo]))
                    $r .= $campo . ' LIKE "%'. ($_GET['s'][$campo]). '%"';

            break;
            case 'select':
                if(isset($_GET['s'][$campo]))
                    $r .= $campo . ' = "'. ($_GET['s'][$campo]). '"';

            break;
            case 'dateRange':
                if(isset($_GET['s'][$campo. '-S']))
                    $d[] = '"'. date_create_from_format('d/m/Y H:i:s', $_GET['s'][$campo. '-S']. ' 00:00:00')->getTimestamp(). '" <= `'. ((isset($filter['col']))? $filter['col']: $campo). '`';

                if(isset($_GET['s'][$campo. '-E']))
                    $d[] = '"'. date_create_from_format('d/m/Y H:i:s', $_GET['s'][$campo. '-E']. ' 23:59:59')->getTimestamp(). '" >= `'. ((isset($filter['col']))? $filter['col']: $campo). '`';

                if(isset($d)) $r .= implode(' AND ', $d);
            break;
        }

        return $r;
    }

    private function filterOrder($campo, $coluna){
        $filter = ((isset($coluna['filter']))? $coluna['filter']: array()); $r = '';
        switch($filter['type']){ default;
            if(isset($_GET['o'][$campo])) $r .= $campo . ' '. ($_GET['o'][$campo]);
        break;}
        return $r;
    }

    public static function filterScripts(){ ?>
        <script type="text/javascript">
            ;(function ($){

                $('.id-form-filter').on('keyup change', 'input, select', function(){
                    var value = '', $self = $(this);
                    $self.attr('value', (value = $self.val()));
                    $self.closest('.id-form-filter').find('input[type="hidden"][name="' + this.name + '"]').val(value);
                });

                $('.id-form-filter button[name]').click(function(){
                    var value = '', $self = $(this), pos = [], atu = 0;
                    $self.val(value = (($self.val() == '')? 'ASC': (($self.val() == 'ASC')? 'DESC': '')));

                    var $o = $('input[type="hidden"][name^="o"').each(function (i, e){
                        var v = [], $this = $(e);
                        if($this.val() != "" && (v = $this.val().split(':')).length > 1) pos[v[0]] = e.name;
                    });
                    if(!(pos.indexOf(this.name) + 1)) pos.push(this.name);
                    if(value == '' && !!((atu = pos.indexOf(this.name)) + 1)) pos = (pos.slice(0, atu).concat(pos.slice(atu + 1)));
                    $o.each(function (i, e){
                        if(!(pos.indexOf(e.name) + 1)) $(this).val(''); else{
                            var v = $(this).val().split(':');
                            $(this).val(pos.indexOf(e.name) + ':' + ((!!v[1])? v[1]: value));
                        }
                    });
                    if(value != '' && !!(pos.indexOf(this.name) + 1))
                        $self.closest('.id-form-filter').find('input[type="hidden"][name="' + this.name + '"]').val(pos.indexOf(this.name) + ':' + value);
                });

                <?php foreach(self::$filtros as $filtro) switch($filtro) {case 'dateRange': ?>

                    $('.input-daterange').each(function (i, e){
                        var $dateStart = $(e).find('.input-daterange-start');
                        var $dateEnd   = $(e).find('.input-daterange-end');
                        $(e).datepicker({
                            inputs: $('.input-daterange-start, .input-daterange-end'),
                            clearBtn: true,
                            beforeShowDay: function (e){
                                var oDatS = $dateStart.data('datepicker'); oDatS = ((!!oDatS)? oDatS.o: false);
                                var oDatE = $dateEnd.data('datepicker'); oDatE = ((!!oDatE)? oDatE.o: false);
                                if(!oDatS || !oDatE) return;
                                var dateS = $dateStart.datepicker('getDate');
                                var dateE = $dateEnd.datepicker('getDate');
                                if(dateS == null && dateE == null) return;
                                return [
                                    (((dateE == null || e.getTime() < dateE.getTime()) && (dateS == null || e.getTime() > dateS.getTime()))? 'range ll_background-100 ll_border-color-100': ''),
                                    (((dateE != null && e.getTime() == dateE.getTime()) || (dateS != null && e.getTime() == dateS.getTime()))? 'll_background-400 ll_border-color-400': ''),
                                    (((oDatE == this && dateE != null && e.getTime() == dateE.getTime()) || (oDatS == this && dateS != null && e.getTime() == dateS.getTime()))? 'll_background-500 ll_border-color-500': '')
                                ].join(' ');
                            }
                        });
                    });

                <?php break; case 'telefone': ?>

                    var SPMaskBehavior = function(val){
                        return (val.replace(/\D/g, '').length === 11 ? '(00) 0.0000-0000' : '(00) 0000-00009');};

                    $('[data-mask="telefone"]').mask(SPMaskBehavior, { onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }});

                <?php break; } ?>

            })(jQuery);
        </script>
    <?php }



    private static function montaSelect($name, $valeu, array $options, $first = false){
        $r = '';
        foreach ($options as $val => $opt){
            $data = ShortTag::Search($val);
            $data = (($data && isset($data[0]))? $data[0]: array());
            $key = (($data)? substr($val, 0, $data['start']): $val);

            $attr = array();
            if(!empty($data))
                foreach ($data['shortTag'] as $k => $v)
                    $attr[((is_numeric($k))? $v: $k)] = $v;

            if (is_array($opt)) {
                $attr = montaAttr(array_merge(array(
                    'label' => $key
                ), $attr));
                $r .= "<optgroup{$attr}>" . montaSelect($name, $valeu, $opt, $first) . '</optgroup>';
            } else {
                $attr = montaAttr(array_merge(
                    array('value' => $key),
                    (((isset($valeu[$name]) && (
                        (!is_array($valeu[$name]) && $key == $valeu[$name]) ||
                        ( is_array($valeu[$name]) && array_key_exists($key, $valeu[$name]))
                    ) && $first = true) || ($first != true && $first = true))? array('selected' => 'selected') : array()), $attr
                ));
                $r .= "<option{$attr}>{$opt}</option>";
            }
        }
        return $r;
    }

    private static function montaInput($name, array $dados, $chackbox = false){
        return 'name="' . $name . '"' . ($chackbox === false ? (isset($dados[$name]) ? ' value="' . ((string) $dados[$name]) . '"' : '') : (' value="' . (is_null($chackbox) ? 'null' : $chackbox) . '"' . (array_key_exists($name, $dados) && ($dados[$name] == $chackbox)? ' checked="checked"' : '')));
    }

    private static function montaAttr(array $attrs){
        $r=''; foreach ($attrs as $k => $v)
            $r.=  ' '.((is_string($k))? $k: $v) . '="'. ((is_string($v) || is_numeric($v))? $v: ((is_null($v))? $k: ((is_bool($v))? 'true': 'false'))). '"';
        return ($r);
    }


}






