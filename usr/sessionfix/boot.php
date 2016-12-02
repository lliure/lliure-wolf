<?php


$link = realpath(__DIR__. '/../../'). DIRECTORY_SEPARATOR. 'nli.os.php?opt=session';
$link = parse_url($link);

$base = explode(DIRECTORY_SEPARATOR, realpath(dirname($_SERVER['DOCUMENT_ROOT']. $_SERVER['PHP_SELF'])));
$arfl = explode(DIRECTORY_SEPARATOR, (realpath($link['path'])));

foreach ($base as $k => $v) if($base[$k] == $arfl[$k]) unset($base[$k], $arfl[$k]); else break;
$url = str_repeat('../', count($base)). implode('/', $arfl). ((isset($link['query']))? '?'. $link['query']: '');

ll::add(array('sessionfix::script', $url), 'call:footer', 0);