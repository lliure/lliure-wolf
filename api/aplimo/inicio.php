<?php
/**
 *
 * API Aplimo - lliure
 *
 * @Verção 7
 * @Pacote lliure
 * @Entre  em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
/* 

Documentação
// O nome Aplimo origina-se da junção das palavras aplikajó e temo


## Utilização

	$aplimo = new aplimo();
	$aplimo->nome = 'Aplimo';

	$aplimo->menu('Página incial', 'home');

	$aplimo->sub_menu('Configuração');
	$aplimo->sub_menu_item('Clientes', 'clientes');
	$aplimo->sub_menu_item('Contratos', 'contato');

	$aplimo->menu('Faturas', 'faturas');
	$aplimo->menu('Audiéncias', 'audiencias');
	$aplimo->menu('Consultas', 'consultas');

	$aplimo->monta();	
	
	
	**************    Explicação    **************
	
	Iniciando a classe
	$aplimo = new aplimo();
	
	Define o nome do aplicativo (que vai aparecer na barra superiror)
	$aplimo->nome = 'Teste';
	
	Define um link no menu
	$aplimo->menu('Página incial', 'home');
	
	Define inicio de um sub-menu
	$aplimo->sub_menu('Configuração');
	
	Define os links do sub-menu criado
	$aplimo->sub_menu_item('Clientes', 'clientes');

	
	
	
	***********	Exemplo HC Menu
	
	$this->hc_menu_item('a', array('url' => $_ll['app']['home'].'&apm=jogo&sapm=estadio', 'texto' => 'Listar todos'));
*/


class aplimo {

    private static $basePath = null;
    public $nome = '', $apm, $sapm;

    private $smalt = null;
    private $menuNovo = array();

    private $hdMenuLeft = array();
    private $hdMenuRigth = array();
    private $js = '';

    private $pagePath = false;
    private $pageFile = false;


    function __construct($botaoHome = true){
        global $_ll;
        self::basePath($_ll['app']['pasta']);

        self::menuNovo(array(
            self::menuGrupo('lliure', ['nome' => 'Sistema', 'class' => 'hidden-sm hidden-md hidden-lg'], [])
        ));

        foreach($_ll['mainMenu'] as $item){
            self::menuNovo(array(
                self::menuGrupo('lliure', array(
                    self::menuItem(array_merge_recursive($item, ['attrs' =>
                        ((isset($item['attrs']['class']))? ['class' => "{$item['item']['attrs']['class']} hidden-sm hidden-md hidden-lg"]: ['class' => 'hidden-sm hidden-md hidden-lg'])
                    ]))
                )),
            ));
        }

        if($botaoHome)
        self::menuNovo(array(
            self::menuItem('home', ['home', 'Home']),
        ));
    }

    static function basePath($pasta = null){
        if(!!$pasta) self::$basePath = $pasta;
        return self::$basePath;
    }


    /** @deprecated  */
    function menu($titulo, $url, $fa = null){

        /* $array = array(
            'titu'     => $titulo,
            'link'     => $url,
            'class'    => '',
            'basePath' => self::$basePath,
        );
        if($fa != null) $array['fa'] = $fa;
        $this->menu[] = $array; */

        self::menuNovo(array(
            self::menuItem($url, ((!!$fa)? [substr($fa, 3), $titulo]: $titulo)),
        ));
    }

    /** @deprecated  */
    function sub_menu($titulo, $folder, $fa = null){
        self::menuNovo(array(
            (self::menuSubGrupo($folder, ((!!$fa)? [substr($fa, 3), $titulo]: $titulo))),
        ));
        $this->smalt = $folder;
    }

    /** @deprecated  */
    function sub_menu_item($titulo, $url, $mark = null){
        self::menuNovo(array(
            self::menuSubGrupo($this->smalt, array(
                self::menuItem($url, $titulo, ((!!$mark)? array('mark' => ((is_string($mark))? explode(',', $mark): $mark)): array())),
            )),
        ));
    }



    /**
     * @deprecated
     */
    function hc_menu($texto, $mod, $tipo = 'a', $orientacao = null, $class = null, $compl = null){
        $name = null;

        switch($tipo){
            case 'a':
            case 'botao':
                $data['url'] = $mod;

            break; case 'input':
            $data['name'] = $class;
            $data['url'] = $compl;

            case 'botao_js':
                $data['js'] = $mod;

            break;
        }

        $data['texto'] = $texto;
        $data['align'] = $orientacao;
        $data['adjunct'] = $compl;
        $data['class'] = $class;

        //$data = json_encode($data, true);
        $this->hc_menu_item($tipo, $data);
    }

    /**
     * @deprecated
     * Exemplo de utlização
     * $this->hc_menu_item('a', '{"texto": "teste", "url": "http://google.com"}');
     *
     * $this->hc_menu_item('a', array("texto" => "teste", "url" => "http://google.com"));
     *
     * $type: passe o tipo do menu pode ser
     * 			   a: link comum
     * 		botao_js: para um botão com ação javascrip
     * 		   input: para criar um input
     */
    function hc_menu_item($type = 'a', $data = null){

        if(!is_array($data)){
            $data = utf8_encode($data);
            $data = json_decode($data, true);}

        $item = array(
            'tipo'    => $type,
            'texto'   => $data['texto'],
            'url'     => isset($data['url'])? $data['url']: null,
            'align'   => isset($data['align'])? $data['align']: 'right',
            'class'   => isset($data['class'])? $data['class']: null,
            'adjunct' => isset($data['adjunct'])? $data['adjunct']: '',
            'name'    => isset($data['name'])? $data['name']: null,
            'js'      => isset($data['js'])? $data['js']: null,
        );

        //$tmp_menu = array_keys($this->hc_menu);
        //return array_shift($tmp_menu);

        switch($item['tipo']){
            case 'a':

                if($item['align'] == 'right') self::hdMenuRigth(array(
                    self::hdMenuA($item['texto'], $item['url'], array('class' => $item['class']))
                )); else self::hdMenuLeft(array(
                    self::hdMenuA($item['texto'], $item['url'], array('class' => $item['class']))
                ));

            break;
            case 'botao':
            case 'botao_js':
                $this->js .= $item['js'];

                $attrs = ShortTag::Explode("[{$item['adjunct']}]");
                $attrs['class'] = ((isset($attrs['class']))? $attrs['class']. ' ': ''). $item['class'];

                if($item['align'] == 'right') self::hdMenuRigth(array(
                    self::hdMenuButton($item['texto'], $attrs)
                )); else self::hdMenuLeft(array(
                    self::hdMenuButton($item['texto'], $attrs)
                ));

            break;
            case 'input':
                $this->js .= $item['js'];
                $input = self::hdMenuForm($item['texto'], $item['url'], array(
                    'form' => array(
                        'class'  => $item['class'],
                    ),
                    'input' => array(
                        'name'  => $item['name'],
                        'value' => (isset($_GET[$item['name']])? $_GET[$item['name']]: ''),
                    ),
                ));
                if($item['align'] == 'right') self::hdMenuRigth(array($input));else self::hdMenuLeft(array($input));
            break;
        }
    }



    /**
     *  $aplimo = new aplimo();
     *  $aplimo->menuNovo(array(
     *
     *      $aplimo->menuGrupo('paginas', 'Menu', array(
     *          $aplimo->menuSubGrupo('jogo', ['trophy', 'Jogos'], array(
     *              $aplimo->menuItem('estadio', 'Ginásios'),
     *              $aplimo->menuItem('equipe', 'Equipes'),
     *              $aplimo->menuItem('rodada', 'Rodadas'),
     *              $aplimo->menuItem('categorias', 'Categorias'),
     *          )),
     *          $aplimo->menuItem('artilharia', ['futbol-o', 'Artilharia']),
     *      )),
     *
     *      $aplimo->menuGrupo('anexo', ['paperclip', 'Anexos'], array(
     *          $aplimo->menuItem('regras', 'Regras', ['modo' => 'onserver', 'url' => ['teste' => 'teste']]),
     *          $aplimo->menuItem('outros', 'Outros'),
     *      )),
     *
     *      $aplimo->menuGrupo('teste', 'Teste', array(
     *          $aplimo->menuItem('teste1', 'Teste 1'),
     *          $aplimo->menuSubGrupo('teste2', 'Teste 2', array(
     *              $aplimo->menuItem('teste2-1', 'Teste 2.1'),
     *              $aplimo->menuItem('teste2-2', 'Teste 2.2'),
     *              $aplimo->menuSubGrupo('teste2-3',   'Teste 2.3', array(
     *                  $aplimo->menuItem('teste2-3-1', 'Teste 2.3.1'),
     *                  $aplimo->menuItem('teste2-3-2', 'Teste 2.3.2'),
     *                  $aplimo->menuItem('teste2-3-3', 'Teste 2.3.3'),
     *                  $aplimo->menuItem('teste2-3-4', 'Teste 2.3.4'),
     *              )),
     *              $aplimo->menuSubGrupo('teste2-4',   'Teste 2.4', array(
     *                  $aplimo->menuItem('teste2-4-1', 'Teste 2.4.1'),
     *                  $aplimo->menuItem('teste2-4-2', 'Teste 2.4.2'),
     *                  $aplimo->menuItem('teste2-4-3', 'Teste 2.4.3'),
     *                  $aplimo->menuItem('teste2-4-4', 'Teste 2.4.4'),
     *              )),
     *          )),
     *          $aplimo->menuItem('teste3', 'Teste 3'),
     *          $aplimo->menuItem('teste4', 'Teste 4'),
     *      )),
     *
     *  ));
     *
     * @param array $itens Lista de itens para o menu lateral
     */
    public function menuNovo(array $itens){
        self::novoMenuPulular($this->menuNovo, $itens);
    }

    private static function novoMenuPulular(&$lista, $itens){
        foreach($itens as $k => $item){
            switch(isset($item['type'])? $item['type']: null){ case 'grupo': case 'subGrupo':
                $lista[$item['pasta']]['type']   = ((isset($lista[$item['pasta']]['type']))?   $lista[$item['pasta']]['type']:   $item['type']);
                $lista[$item['pasta']]['active'] = ((isset($lista[$item['pasta']]['active']))? $lista[$item['pasta']]['active']: $item['active']);
                $lista[$item['pasta']]['pasta']  = ((isset($lista[$item['pasta']]['pasta']))?  $lista[$item['pasta']]['pasta']:  $item['pasta']);
                $lista[$item['pasta']]['attrs']  = ((!isset($lista[$item['pasta']]['attrs']))? $item['attrs']: ((is_array($item['attrs']))? array_merge($lista[$item['pasta']]['attrs'], $item['attrs']): $lista[$item['pasta']]['attrs']));
                self::novoMenuPulular($lista[$item['pasta']]['itens'], ((!!$item['itens'])? $item['itens']: array()));
                
            break; case 'item':
                $lista[$item['pasta']]['type'] = ((isset($lista[$item['pasta']]['type']))? $lista[$item['pasta']]['type']: $item['type']);
                $lista[$item['pasta']]['active'] = ((isset($lista[$item['pasta']]['active']))? $lista[$item['pasta']]['active']: $item['active']);
                $lista[$item['pasta']]['pasta'] = ((isset($lista[$item['pasta']]['pasta']))? $lista[$item['pasta']]['pasta']: $item['pasta']);
                if(isset($item['url'])) $lista[$item['pasta']]['url'] = ((isset($lista[$item['pasta']]['url']))? $lista[$item['pasta']]['url']: $item['url']);
                $lista[$item['pasta']]['attrs'] = array_merge(((isset($lista[$item['pasta']]['attrs']))? $lista[$item['pasta']]['attrs']: array()), $item['attrs']);
            }
        }
    }

    public function menuSubGrupo($pasta, $attrs, array $itens = null){
        if($itens === null){
            $itens = $attrs;
            $attrs = [];
        }
        if(is_array($attrs) && isset($attrs[0], $attrs[1])){
            $attrs['fa'] = $attrs[0];
            $attrs['nome'] = $attrs[1];
            unset($attrs[0], $attrs[1]);
        }
        if(!is_array($attrs))
            $attrs = ['nome' => $attrs];

        return array(
            'type'   => 'subGrupo',
            'active' => false,
            'pasta'  => $pasta,
            'attrs'  => $attrs,
            'itens'  => $itens,
        );
    }

    public function menuGrupo($pasta, $attrs, array $itens = null){
        $item = self::menuSubGrupo($pasta, $attrs, $itens);
        $item['type'] = 'grupo';
        return $item;
    }

    public function menuItem($pasta, $attrs = null){

        if(is_array($attrs) && isset($attrs[0], $attrs[1])){
            $attrs['fa'] = $attrs[0];
            $attrs['nome'] = $attrs[1];
            unset($attrs[0], $attrs[1]);

        }if(!is_array($attrs) && !empty($attrs))
            $attrs = ['nome' => $attrs];

        elseif($attrs === null)
            return $pasta;

        return array(
            'type'   => 'item',
            'active' => false,
            'pasta'  => $pasta,
            'attrs'  => $attrs,
        );
    }



    /**
     * Acrescenta item no menu do header no app a direita.
     *
     * $this->hdMenuRigth(array(
     *     $this->hdMenuA('texto', 'url', array('class' => 'teste', 'data-teste' => 'teste')),
     *     $this->hdMenuButton('botao', array('id' => 'btm-teste-danger', 'class' => 'btn-danger')),
     *     $this->hdMenuForm('placeholder', 'url', array('button' => array('html' => 'texto')))
     * ));
     *
     * @param array $itens
     */
    public function hdMenuRigth(array $itens){
        $this->hdMenuRigth = array_merge($this->hdMenuRigth, $itens);
    }

    /**
     * Acrescenta item no menu do header no app a direita.
     *
     * $this->hdMenuLeft(array(
     *     $this->hdMenuA('texto', 'url', array('class' => 'teste', 'data-teste' => 'teste')),
     *     $this->hdMenuButton('botao', array('id' => 'btm-teste-danger', 'class' => 'btn-danger')),
     *     $this->hdMenuForm('placeholder', 'url', array('button' => array('html' => 'texto')))
     * ));
     *
     * @param array $itens
     */
    public function hdMenuLeft(array $itens){
        $this->hdMenuLeft = array_merge($this->hdMenuLeft, $itens);
    }

    public function hdMenuA($texto, $url, array $attrs = array()){
        $item['type'] = 'a';
        $attrs['class'] = ('btn btn-default navbar-btn'. ((isset($attrs['class']))? ' '. $attrs['class']: ''));
        $item['a'] = array_merge(array(
            'class' => '',
            'href'  => $url,
            'html'  => $texto,
        ), $attrs);
        return $item;
    }

    public function hdMenuButton($texto, array $attrs = array()){
        $item['type'] = 'button';
        $attrs['class'] = 'btn btn-default navbar-btn'. ((isset($attrs['class']))? ' '. $attrs['class']: '');
        $item['button'] = array_merge(array(
            'html' => $texto,
        ), $attrs);
        return $item;
    }

    public function hdMenuForm($placeholder, $url, array $attrs = array()){

        $item['type'] = 'form';

        $item['form'] = array_merge(array(
            'class' => '',
            'action' => $url,
            'method' => 'post',
        ), ((isset($attrs['form']))? $attrs['form']: array()));
        $item['form']['class'] = 'navbar-form'. (!(isset($item['form']['class']) && !empty($item['form']['class']))? '': ' '. $item['form']['class']);

        $item['input'] = array_merge(array(
            'type' => 'text',
            'class' => '',
            'placeholder' => $placeholder,
        ), ((isset($attrs['input']))? $attrs['input']: array()));
        $item['input']['class'] = 'form-control'. (!(isset($item['input']['class']) && !empty($item['input']['class']))? '': ' '. $item['input']['class']);

        $item['button'] = ((isset($attrs['button']))? $attrs['button']: array());
        $item['button']['class'] = 'btn btn-default'. (!(isset($attrs['button']['class']) && !empty($attrs['button']['class']))? '': ' '. $attrs['button']['class']);

        return $item;
    }



    private function menuDefineGetApm(&$menu, $prefUrl = false, $home = false){
        $page = false;
        foreach($menu as $item){
            switch($item['type']){
                case 'grupo': case 'subGrupo':
                    $page = ((!!$page)? $page: self::menuDefineGetApm($item['itens'], ((!!$prefUrl)? $prefUrl. '>': ''). $item['pasta']));
                    $home = ((!!$home)? $home: self::menuDefineGetApm($item['itens'], ((!!$prefUrl)? $prefUrl. '>': ''). $item['pasta'], $home));

                break;
                case 'item':
                    $page = ((!!$page)? $page: ((gettype($item['pasta']) == "string")? ((!!$prefUrl)? $prefUrl. '>': ''). $item['pasta']: false));
                    $home = ((!!$home)? $home: ((isset($item['attrs']['home']) && is_string($item['attrs']['home']))? $item['attrs']['home']: ((gettype($item['pasta']) == "string")? ((!!$prefUrl)? $prefUrl. '>': ''). $item['pasta']: false)));

                break;
            }
        } return ((!$home)? $page: $home);
    }

    function header(){

        global $_ll; $_ll['titulo'] = strip_tags($this->nome) . " | " . $_ll['titulo'];
        if(!isset($_GET['apm'])) $_GET['apm'] = self::menuDefineGetApm($this->menuNovo);

        $this->pagePath = explode('>', $_GET['apm']);
        $this->pageFile = array_pop($this->pagePath);
        $this->pagePath = implode('/', $this->pagePath);
        $this->pagePath .= ((!empty($this->pagePath))? '/': ''). $this->pageFile;

        $this->apm = new stdClass();
        $this->apm->home = $_ll['app']['home'] . '&apm=' . $_GET['apm'];
        $this->apm->onserver = $_ll['app']['onserver'] . '&apm=' . $_GET['apm'];
        $this->apm->onclient = $_ll['app']['onclient'] . '&apm=' . $_GET['apm'];

        $this->sapm = new stdClass();
        $this->sapm->home = $_ll['app']['home'] . '&apm=' . $_GET['apm'];
        $this->sapm->onserver = $_ll['app']['onserver'] . '&apm=' . $_GET['apm'];
        $this->sapm->onclient = $_ll['app']['onclient'] . '&apm=' . $_GET['apm'];

        if((file_exists($f = self::$basePath . "{$this->pagePath}/{$this->pageFile}.hd.php")||
            file_exists($f = self::$basePath . "{$this->pagePath}/header.php")))
            require_once($f);

    }

    function onserver(){
        global $_ll;

        if($this->pagePath === false)
            die(json_encode(array('erro' => 'Comando aplimo::header() ainda não foi executado')));

        if((file_exists($f = self::$basePath . "{$this->pagePath}/{$this->pageFile}.os.php")||
            file_exists($f = self::$basePath . "{$this->pagePath}/onserver.php"))) return require_once $f;

        die(json_encode(array('erro' => "Arquivo {$this->pageFile}.os.php não encontrado na pagina requisitada.")));
    }

    function onclient(){
        global $_ll;

        if($this->pagePath === false)
            die('Comando aplimo::header() ainda não foi executado');

        if((file_exists($f = self::$basePath . "{$this->pagePath}/{$this->pageFile}.oc.php")||
            file_exists($f = self::$basePath . "{$this->pagePath}/onclient.php"))) return require_once $f;

        die("Arquivo {$this->pageFile}.oc.php não encontrado na pagina requisitada.");
    }

    private function start(){
        global $_ll;

        if($this->pagePath === false){
            echo ('Comando aplimo::header() ainda não foi executado'); return null;}

        if((file_exists($f = self::$basePath . "{$this->pagePath}/{$this->pageFile}.x.php")||
            file_exists($f = self::$basePath . "{$this->pagePath}/{$this->pageFile}.php"))) return require_once $f;

        echo ("Arquivo {$this->pageFile}.x.php não encontrado para pagina requisitada.");

    }

    private function footer(){
        global $_ll;

        if($this->pagePath === false){
            echo ('Comando aplimo::header() ainda não foi executado'); return null;}

        if((file_exists($f = self::$basePath . "{$this->pagePath}/{$this->pageFile}.ft.php"))) return require_once $f;
    }

    function monta(){ global $_ll; ?>

        <div class="apm-container-position">
            <div class="apm-container corpo">

                <div class="apm-menu">
                    <button type="button" class="apm-menu-botao-menu-left apm-menu-left-botao-x btn btn-default hidden-sm hidden-md hidden-lg">
                        <i class="fa fa-times"></i>
                    </button>
                    <?php self::montaMenu($this->menuNovo); ?>
                </div>

                <div class="apm-centro">
                    <div id="apm-menu-left-background" class="apm-menu-botao-menu-left"></div>

                    <span id="apm-titulo" class="hidden-xs"><a class="ll_background-400-hover" href="<?php echo $_ll['app']['home']; ?>"><?php echo $this->nome; ?></a></span>

                    <?php $this->hdMenuMonta(); ?>

                    <?php $this->start(); ?>

                </div>
            </div>
        </div>

        <?php $this->footer(); if(!empty($this->js)) ll::add("<script id=\"apm-botos-js-footer\" type=\"text/javascript\">{$this->js}</script>", 'integral');
    }



    private function montaMenu(array &$menu){
        self::montaMenuAtivandoItens($menu); ?>
        <ul class="panel-group apm-menu-accordion" id="apm-menu-panel-group-main">
            <?php foreach($menu as $item){
                switch($item['type']){
                    case 'grupo':
                        self::montaMenuGrupo($item);
                    break;
                    case 'subGrupo':
                        self::montaMenuSubGrupo($item);
                    break;
                    case 'item':
                        self::montaMenuItem($item);
                    break;
                }
            } ?>
        </ul>
    <?php }

    private function montaMenuAtivandoItens(&$menu, $prefUrl = ''){
        $ret = false;
        foreach($menu as $pasta => $item){
            switch($item['type']){

                case 'grupo': case 'subGrupo':
                    if((self::montaMenuAtivandoItens($menu[$pasta]['itens'], ((!!$prefUrl)? $prefUrl. '/': ''). $item['pasta'])))
                        $ret = $menu[$pasta]['active'] = true;

                break;
                case 'item':
                    if(isset($item['pasta']) && (((!!$prefUrl)? $prefUrl. '/': ''). $item['pasta']) == $this->pagePath && ($item['pasta'] == $this->pageFile))
                        $ret = $menu[$pasta]['active'] = true;

                    if(!$ret && isset($item['attrs']['mark'])) if((is_array($item['attrs']['mark']) && !empty($marks = $item['attrs']['mark'])) || (is_string($item['attrs']['mark']) && !!($marks = explode(',', $item['attrs']['mark'])))) foreach($marks as $k => $m){
                        if(is_numeric($k) && $this->pageFile == $m)
                            $ret = $menu[$pasta]['active'] = true;
                        elseif(is_string($k) && isset($_GET[$k]) && ($_GET[$k] == $menu[$pasta]['pasta']))
                            $ret = $menu[$pasta]['active'] = true;
                    }

                break;
            }
        }
        return $ret;
    }

    private function montaMenuGrupo($item){
        list($nome, $attrs) = self::montaMenuSeparaNome($item['attrs']);
        $attrsToBot = $attrsToTop = $attrs;
        $attrsToTop['class'] = "apm-menu-grupo-header". (($item['active'])? ' apm-menu-item-active': ''). ((isset($attrs['class']))? " {$attrs['class']}": '');
        $attrsToBot['class'] = "apm-menu-grupo-footer". (($item['active'])? ' apm-menu-item-active': ''). ((isset($attrs['class']))? " {$attrs['class']}": ''); ?>
        <li <?php echo ll::implodeMeta($attrsToTop); ?>>
            <div><?php self::montaMeneNome($nome); ?></div>
        </li>
        <?php foreach($item['itens'] as $i){
            switch($i['type']){
                case 'grupo': case 'subGrupo':
                    self::montaMenuSubGrupo($i, $item['pasta']);
                break;
                case 'item':
                    self::montaMenuItem($i, $item['pasta']);
                break;
            }
        } ?>
        <li <?php echo ll::implodeMeta($attrsToBot); ?>></li>
    <?php }

    private function montaMenuSubGrupo($item, $pfLink = '', $nivel = 1, $context = 'main'){
        $c = ((!empty($context))? $context. '-': ''). $item['pasta'];
        $l = ((!empty($pfLink))? $pfLink. '>': ''). $item['pasta'];
        list($nome, $attrs) = self::montaMenuSeparaNome($item['attrs']);
        $attrs['class'] = "panel panel-default". (($item['active'])? ' apm-menu-item-active': ''). ((isset($attrs['class']))? " {$attrs['class']}": '') ?>
        <li <?php echo ll::implodeMeta($attrs); ?>>
            <a class="<?php echo (($item['active'])? 'apm-menu-item-active': 'collapsed'); ?>" data-toggle="collapse" data-parent="#apm-menu-panel-group-<?php echo $context; ?>" href="#apm-menu-accordion-<?php echo $c; ?>">
                <div class="panel-heading ll_background-hover<?php echo (($item['active'])? ' ll_background-500': ''); ?>" style="padding-left: <?php echo (15 * min($nivel, 3)); ?>px">
                    <div class="panel-title">
                        <?php self::montaMeneNome($nome); ?><i class="fa fa-angle-left apm-menu-sublist-icone"></i>
                    </div>
                </div>
            </a>
            <?php if(is_array($item['itens'])){ ?>
                <div id="apm-menu-accordion-<?php echo $c; ?>" class="panel-collapse collapse<?php echo (($item['active'])? ' in': ''); ?>">
                    <ul class="list-group panel-group" id="apm-menu-panel-group-<?php echo $c; ?>">
                        <?php foreach($item['itens'] as $i){
                            switch($i['type']){
                                case 'grupo': case 'subGrupo':
                                    self::montaMenuSubGrupo($i, $l, ($nivel + 1), $c);
                                break;
                                case 'item':
                                    self::montaMenuItem($i, $l, ($nivel + 1));
                                break;
                            }
                        } ?>
                    </ul>
                </div>
            <?php } ?>
        </li>
    <?php }

    private function montaMenuItem($item, $pfLink = '', $nivel = 1){
        global $_ll;
        $l = ((isset($item['url']))? $item['url']: ($_ll['app']['home'] . '&apm=' . ((!empty($pfLink))? $pfLink. '>': '') . $item['pasta']));
        list($nome, $attrs) = self::montaMenuSeparaNome($item['attrs']); unset($attrs['mark']); $attrs = array_merge(['href' => $l], $attrs);
        $attrs['class'] = "list-group-item". (($item['active'])? ' apm-menu-item-active': ''). ((isset($attrs['class']))? " {$attrs['class']}": '');
        $query = (($q = ((isset($attrs['query']))? ((is_string($attrs['query']))? $attrs['query']: ((is_array($attrs['query']))? http_build_query($attrs['query']): null)) : null)) && !empty($q)? '&'.$q: '');
        unset($attrs['query'], $attrs['home']); ?>
        <li <?php echo ll::implodeMeta($attrs); ?>>
            <a class="ll_background-hover<?php echo (($item['active'])? ' ll_background-500': ''); ?>" href="<?php echo $attrs['href']. $query; ?>" style="padding-left: <?php echo (15 * min($nivel, 3)); ?>px">
                <?php self::montaMeneNome($nome); ?>
            </a>
        </li>
    <?php }

    private function montaMenuSeparaNome(array $attrs){
        $nome = '';
        if(isset($attrs['fa'], $attrs['nome'])){
            $nome['fa']   = $attrs['fa'];
            $nome['nome'] = $attrs['nome'];
        }elseif(isset($attrs['nome']))
            $nome = $attrs['nome'];
        unset($attrs['fa'], $attrs['nome']);
        return array($nome, $attrs);
    }

    private function montaMeneNome($nome){
        echo ((is_array($nome))? "<i class=\"fa fa-{$nome['fa']}\"></i> {$nome['nome']}": $nome);}



    private function hdMenuMonta(){ global $_ll; ?>
        <nav id="apm-h-menu" class="navbar navbar-default<?php echo ((empty($this->hdMenuLeft) && empty($this->hdMenuRigth))? ' apm-h-menu-empty': ''); ?>">
            <div class="navbar-header">
                <a class="navbar-brand hidden-sm hidden-md hidden-lg" href="<?php echo $_ll['app']['home']; ?>"><?php echo $this->nome; ?></a>
            </div>
            <div class="apm-h-menu-itens-area">
                <ul class="nav navbar-nav">
                    <?php self::hdMenuMontaTypes($this->hdMenuLeft); ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php self::hdMenuMontaTypes($this->hdMenuRigth); ?>
                </ul>
            </div>
        </nav>
    <?php }

    private function hdMenuMontaTypes($lista){ ?>
        <?php foreach($lista as $k => $item){ ?>
            <li>
                <div>
                    <?php switch ($type = ((isset($item['type'])? $item['type']: 'a'))){
                        default: case 'a': case 'link':
                            $texto = $item[$type]['html']; unset($item[$type]['html']); ?>
                            <a <?php echo ll::implodeMeta($item[$type]); ?>><?php echo $texto; ?></a>
                            <?php break; case 'button': case 'botao':
                            $texto = $item[$type]['html']; unset($item[$type]['html']); ?>
                            <button <?php echo ll::implodeMeta($item[$type]); ?>><?php echo $texto; ?></button>
                            <?php break; case 'form';
                            $button = ((isset($item['button'], $item['button']['html']) && !empty($item['button']['html'])));
                            $texto = (($button)? $item['button']['html']: ''); unset($item['button']['html']); ?>
                            <form <?php echo ll::implodeMeta($item['form']); ?>>
                                <div class="form-group">
                                    <input <?php echo ll::implodeMeta($item['input']); ?>>
                                </div><?php if($button){ ?><div class="text-right"><button <?php echo ((isset($item['button']) && !empty($item['button']))? ll::implodeMeta($item['button']): ''); ?>><?php echo $texto; ?></button></div><?php } ?>
                            </form>
                            <?php break; /* case 'dropdown': ?>
                            <li class="dropdown">
                                <div class="navbar-btn">
                                    <div style="position: relative;">
                                        <a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Action</a></li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="#">One more separated link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php break; */ ?>
                        <?php } ?>
                </div>
            </li>
        <?php } ?>
    <?php }

}
