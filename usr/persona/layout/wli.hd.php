<?php

ll::add(__DIR__. '/wli.css.php?cor=94324b', 'css', 5);
ll::add(__DIR__. '/script.js', 'js', 5);


class personaLayout{

    public static function montaMenu(array &$menu){ ?>
        <ul class="nav navbar-nav navbar-right">
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

    private static function montaMenuGrupo($item){
        self::montaMenuSubGrupo($item);
    }

    private static function montaMenuSubGrupo($item, $pfLink = '', $nivel = 1, $context = 'lliure-topo-menu'){
        $c = ((!empty($context))? $context. '-': ''). $item['pasta'];
        $l = ((!empty($pfLink))? $pfLink. '>': ''). $item['pasta'];
        list($nome, $attrs) = self::montaMenuSeparaNome($item['attrs']);
        $attrs['id'] = ((isset($attrs['id']))? $attrs['id']: $c);
        $attrs['class'] = "dropdown". ((isset($attrs['class']))? " {$attrs['class']}": ''); ?>
        <li <?php echo ll::implodeMeta($attrs); ?>>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php self::montaMeneNome($nome); ?> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <?php foreach($item['itens'] as $i){
                    switch($i['type']){
                        case 'grupo': case 'subGrupo':
                            self::montaMenuSubGrupo($i, $l, ($nivel + 1), $c);
                        break;
                        case 'item':
                            self::montaMenuItem($i, $l, ($nivel + 1), $c);
                        break;
                    }
                } ?>
            </ul>
        </li>
    <?php }

    private static function montaMenuItem($item, $pfLink = '', $nivel = 1, $context = 'lliure-topo-menu'){
        global $_ll;
        $c = ((!empty($context))? $context. '-': ''). ((isset($item['pasta']))? $item['pasta']: '');
        list($nome, $attrs) = self::montaMenuSeparaNome($item['attrs']);
        $attrs = array_merge(['id' => $c, 'href' => $item['url']], $attrs);
        $attrs['class'] = "lliure-topo-menu-nivel-{$nivel}". ((isset($attrs['class']))? " {$attrs['class']}": '');
        $l = ((!empty($attrs['href']))? $attrs['href']:
            $_ll['app']['home']. '&p='. ((!empty($pfLink))? $pfLink. '>': '').
            ((isset($attrs['pasta']))? $attrs['pasta']: $item['pasta']));
        unset($attrs['pasta']); ?>
        <li <?php echo ll::implodeMeta($attrs); ?>>
            <a class="ll_background-400-hover" href="<?php echo $l; ?>"><?php self::montaMeneNome($nome); ?></a>
        </li>
    <?php }

    private static function montaMenuSeparaNome(array $attrs){
        $nome = '';
        if(isset($attrs['fa'], $attrs['nome'])){
            $nome['fa']   = $attrs['fa'];
            $nome['nome'] = $attrs['nome'];
        }elseif(isset($attrs['nome']))
            $nome = $attrs['nome'];
        unset($attrs['fa'], $attrs['nome']);
        return array($nome, $attrs);
    }

    private static function montaMeneNome($nome){
        echo ((is_array($nome))? "<i class=\"fa fa-{$nome['fa']}\"></i> {$nome['nome']}": $nome);}

}