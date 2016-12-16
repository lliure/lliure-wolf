<?php

$navigi = unserialize(jf_decode($_SESSION['ll']['user']['token'], $_POST['token']));

function navigi_tratamento($dados){
    global $navigi;


    $configSel = 0;
    if($navigi['configSel'] != false)
        $configSel = $dados[$navigi['configSel']];

    /** Configura a coluna e o id que serao exibidos	*/
    $dados['coluna'] = $dados[$navigi['config'][$configSel]['coluna']];
    $dados['id'] = $dados[$navigi['config'][$configSel]['id']];


    /**********		DEFINICAO DO CLICK	 					**/
    $dados['click'] = null;

    if(isset($navigi['config'][$configSel]['link_col']))
        $dados['click'] = $dados[$navigi['config'][$configSel]['link_col']];
    elseif(isset($navigi['config'][$configSel]['link']))
        $dados['click'] = $navigi['config'][$configSel]['link'].$dados['id'];

    $dados['click']	.= (isset($navigi['paginacao']['pAtual']) ? '&nvg_pg='. $navigi['paginacao']['pAtual'] : '' ); /* acrecenta paginação*/
    /**/

    /**********		DEFINIÇÃO DO ICONE							**/
    if(!isset($navigi['config'][$configSel]['fa'])){
        $dados['ico'] = 'api/navigi/img/ico.png';

        if(isset($navigi['config'][$configSel]['ico']))
            $dados['ico'] = $navigi['config'][$configSel]['ico'];

        if(isset($navigi['config'][$configSel]['ico_col']) && !empty($navigi['config'][$configSel]['ico_col']))
            $dados['ico'] = $dados[$navigi['config'][$configSel]['ico_col']];
    } else {
        $dados['fa'] = $navigi['config'][$configSel]['fa'];
    }
    /**/

    $dados['as_id'] = $dados[$navigi['config'][$configSel]['as_id']]; // alias para o id

    /**********		DEFINIÇÃO DAS FUNÇÕES DE RENOMEAR E DELETAR	**/
    /** Também realiza a codificação de permições, usadas no js	**/

    $dados['rename'] = (($dados['rename'] === null)?
        ((isset($navigi['config'][$configSel]['rename']))?
            !!$navigi['config'][$configSel]['rename']:
            !!$navigi['rename']): !!$dados['rename']);

    $dados['delete'] = (($dados['delete'] === null)?
        ((isset($navigi['config'][$configSel]['delete']))?
            !!$navigi['config'][$configSel]['delete']:
            !!$navigi['delete']): !!$dados['delete']);

    $per_ren = (($navigi['rename'])? '1': '0');
    $per_del = (($navigi['delete'])? '1': '0');

    $dados['permicao'] = $per_ren.$per_del;


    if(isset($navigi['config'][$configSel]['botao']))
        $dados['botao'] = $navigi['config'][$configSel]['botao'];

    return $dados;
}


$query = mysql_query($navigi['query']);

if(mysql_error() != false)
    die('Erro na consulta mysql: <strong>'.$navigi['query'].'</strong>');

$navigi['rename'] = ($navigi['rename'] ? 1 : 0);
$navigi['delete'] = ($navigi['delete'] ? 1 : 0);

$lista = '';

if($navigi['exibicao'] == 'icone'){ 	//// exibindo como icones
    while($dados = mysql_fetch_assoc($query)){
        $dados = navigi_tratamento(array_merge(array(
            'rename' => null,
            'delete' => null,
        ), $dados));

        $lista .=
        '<div class="navigi_item" '
            .'id="item_'.$dados['id'].'" '
            .'as_id="'.$dados['as_id'].'" '
            .($navigi['configSel'] != false ? 'seletor="'.$dados[$navigi['configSel']].'" ' : '')
            .'dclick="'.$dados['click'].'" '
            .'permicao="'.$dados['permicao'].'" '
            .'nome="'.$dados['coluna'].'">'
            .'<div class="navigi_item_main">'
                .'<div class="navigi_item_padding">'
                    .'<div class="navigi_item_padding_main">'
                        .'<div class="navigi_item_content">'
                            .'<div class="navigi_ico">'
                                .(isset($dados['fa']) ?
                                    '<i class="navigi_fa fa '.$dados['fa'].'"></i>' :
                                    '<div class="navig_thunb" style="background-image: url('.$dados['ico'].'); "></div>')
                            .'</div>'
                            .'<div id="nome_'.$dados['id'].'" class="navigi_nome">'
                                .htmlspecialchars($dados['coluna'], ENT_COMPAT, 'ISO-8859-1', true)
                            .'</div>'
                        .'</div>'
                        .'<i class="navigi_menuContextoOpen fa fa-exclamation-circle"></i>'
                        .'<div class="navigi_contextoMenu">'
                            .'<div class="btn-group-vertical">'
                                .'<button type="button" class="btn btn-default btn-sm navigi_icone_open">'
                                    .'Abrir'
                                .'</button>'
                                .'<button type="button" class="btn btn-default btn-sm navigi_icone_rename">'
                                    .'Renomear'
                                .'</button>'
                                .'<button type="button" class="btn btn-default btn-sm navigi_icone_delete">'
                                    .'Deletar'
                                .'</button>'
                            .'</div>'
                        .'</div>'
                    .'</div>'
                .'</div>'
            .'</div>'
        .'</div>';
    }

} else {	/*/// exibindo como lista ********************************************************/
    $ico = false;

    if($navigi['configSel'] != false){
        $ico = $navigi['config'];
        $ico = array_pop($ico);
        $ico = (isset($ico['ico']) || isset($ico['fa']) ? true : false);
    }

    $linhas = array();
    while($dados = mysql_fetch_array($query)){

        $dados = navigi_tratamento(array_merge(array(
            'rename' => null,
            'delete' => null,
            '__bots' => [],
            '__cell' => '',
        ), $dados));

        /** ICONE */
        if($ico == true) $dados['__cell'] .=(
            '<td class="navigi_ico">'
                .(isset($dados['fa'])
                    ? '<i class="fa '.$dados['fa'].'"></i>'
                    : '<img src="'.$dados['ico'].'" alt="'.$dados['coluna'].'" style="max-width: 16px;"/>' )
            .'</td>'
        );

        /** ID / COD. */
        $dados['__cell'] .= '<td class="navigi_cod">'. str_pad($dados['as_id'], 7, 0, STR_PAD_LEFT). '</td>';

        /** NOME */
        $dados['__cell'] .= '<td><div class="navigi_nome">'. $dados['coluna']. '</div></td>';


        /** ETIQUETAS | puxando os campos que foram setados nas etiquetas	***/
        if(!empty($navigi['cell']))
            foreach($navigi['cell'] as $key => $valor)
                $dados['__cell'] .= '<td>'. $dados[$key]. '</td>';

        /** BOTOES */
        if(isset($dados['botao'])) foreach($dados['botao'] as $key => $valor){
            $valor['link'] = str_replace('#ID', $dados['id'], $valor['link']);
            $dados['__bots'][] =
            '<a href="'.$valor['link'].'" '.(isset($valor['modal']) ? 'class="navigi_bmod btn btn-default" rel="'.$valor['modal'].'"' : '').'>'.
                (isset($valor['fa']) ? '<i class="fa '.$valor['fa'].'"></i>' : '<img src="'.$valor['ico'].'">').
            '</a>';}
        if(!!$dados['rename']) $dados['__bots'][] = '<button type="button" class="navigi_ren btn btn-default"><i class="fa fa-pencil-square-o"></i></button>';
        if(!!$dados['delete']) $dados['__bots'][] = '<button type="button" class="navigi_del btn btn-default"><i class="fa fa-trash-o"></i></button>';

        $dados['__cell'] .= '<td class="navigi_botoes text-right" style="white-space: nowrap;"><div class="btn-group">';
            foreach($dados['__bots'] as $bot)
                $dados['__cell'] .= $bot;
        $dados['__cell'] .= '</td></div>';


        //$linhas[] = $dados;

        $lista .=
        '<tr class="navigi_tr" '
            .'id="item_'.$dados['id'].'" '
            .'as_id = "'.$dados['as_id'].'" '
            .'dclick="'.$dados['click'].'" '
            .($navigi['configSel'] != false ? 'seletor="'.$dados[$navigi['configSel']].'" ' : '')
            .'permicao="'.$dados['permicao'].'" '
            .'nome="'.$dados['coluna'].'">'
            .$dados['__cell']
        .'</tr>';

    }
}


$pagi = '<nav><ul class="pagination">';
$anterior = $navigi['paginacao']['pAtual'] -1;
$proximo = $navigi['paginacao']['pAtual'] +1;

if($navigi['paginacao']['tPaginas'] > 1){
    $navigi['paginacao']['tReg'] = 3;

    $ini = $navigi['paginacao']['pAtual']-$navigi['paginacao']['tReg'];
    if($ini < 1){
        $ini = 1;
    }

    $ult = $navigi['paginacao']['pAtual']+$navigi['paginacao']['tReg'];
    if($ult > $navigi['paginacao']['tPaginas']){
        $ult = $navigi['paginacao']['tPaginas'];
    }

    for($i = $ini; $i <= $ult; $i++)
        $pagi .= '<li' . ($i == $navigi['paginacao']['pAtual']? " class='active'": "") . '><a' . ($i == $navigi['paginacao']['pAtual']? " class='ll_background-500 ll_border-color-600'": "") . ' href="' . (empty($navigi['paginacao']['url'])? '': $navigi['paginacao']['url'] . '&') . "nvg_pg=" . $i . '">' . $i . '</a></li>';

} $pagi .= '</ul></nav>';

echo ll::json_encode(array('list' => $lista, 'pagi' => $pagi));