<?php $carrega = 'opt/stirpanelo/ne_trovi.php';

if(is_string($_ll[$_ll['operation_type']]['pagina'])){
    if(file_exists($f = $_ll[$_ll['operation_type']]['pagina']))
        $carrega = ($f);

}elseif (is_array($_ll[$_ll['operation_type']]['pagina']))
    foreach ($_ll[$_ll['operation_type']]['pagina'] as $f)
        if (file_exists($f)) $carrega = ($f);

require_once($carrega);