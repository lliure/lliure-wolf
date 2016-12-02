<?php $carrega = false;

if(is_string($_ll[$_ll['operation_type']]['pagina'])){
    if(file_exists($f = $_ll[$_ll['operation_type']]['pagina']))
        $carrega = ($f);

}elseif (is_array($_ll[$_ll['operation_type']]['pagina']))
    foreach ($_ll[$_ll['operation_type']]['pagina'] as $f)
        if (file_exists($f)) $carrega = ($f);


if(!!$carrega)
    require_once($carrega);

else{
    $_ll['mensagens'] = 'nao_encontrada';
    require_once realpath(__DIR__ .'/../../opt/mensagens/mensagens.x.php');}