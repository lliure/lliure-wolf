<?php

global $_ll;

/** Define o tema do sistema */
$tema_loca = 'default';
$tema_name = 'persona';
$tema_path = $_ll['dir']. 'usr/persona/';
$tema_exec = URL_NORMAL;
$tema_homW = 'opt=desktop';
$tema_homN = 'opt=singin';

if(isset($_ll['conf']->grupo->default)){
	$_ll['tema']['local'] = $tema_loca = $_ll['conf']->grupo->default->local;
	if(isset($_ll['conf']->temas->{$_ll['conf']->grupo->default->template})){
		$_ll['tema']['name'] = $tema_name = (string) $_ll['conf']->grupo->default->local;
		$_ll['tema']['path'] = $tema_path = (string) $_ll['conf']->temas->{$_ll['conf']->grupo->default->template};
	}else{
		$_ll['tema']['name'] = $tema_name = (string) $_ll['conf']->grupo->default->local;
		$_ll['tema']['path'] = $tema_path = (string) $_ll['conf']->grupo->default->template;}
	$_ll['tema']['exec'] = $tema_exec = (string) (isset($_ll['conf']->grupo->default->execucao)? $_ll['conf']->grupo->default->execucao: $tema_exec);
	$_ll['tema']['home_wli'] = $tema_homW = (string) (isset($_ll['conf']->grupo->default->home_wli)? $_ll['conf']->grupo->default->home_wli: $tema_homW);
	$_ll['tema']['home_nli'] = $tema_homN = (string) (isset($_ll['conf']->grupo->default->home_nli)? $_ll['conf']->grupo->default->home_nli: $tema_homN);
}else{
	$_ll['tema']['name'] = $tema_name;
	$_ll['tema']['path'] = $tema_path;
	$_ll['tema']['exec'] = $tema_exec;
	$_ll['tema']['home_wli'] = $tema_homW;
	$_ll['tema']['home_nli'] = $tema_homN;}

if(isset($_ll['conf']->grupo))
	foreach ($_ll['conf']->grupo as $g => $d){
		if($d->local == $_ll['url']['local']){
			$_ll['tema']['local'] = $d->local;
			if(isset($_ll['conf']->temas->{$d->template})){
				$_ll['tema']['name'] = (string) $d->template;
				$_ll['tema']['path'] = (string) $_ll['conf']->temas->{$d->template};
			}else{
				$_ll['tema']['name'] = (string) $g;
				$_ll['tema']['path'] = (string) $d->template;}
			$_ll['tema']['exec'] = (string) (isset($d->execucao)? $d->execucao: $tema_exec);
			$_ll['tema']['home_wli'] = (string) (isset($d->home_wli)? $d->home_wli: $tema_homW);
			$_ll['tema']['home_nli'] = (string) (isset($d->home_nli)? $d->home_nli: $tema_homN);
			break;}}

$_ll['tema']['path'] = $_ll['dir']. $_ll['tema']['path'];
$_ll['tema']['wli']['x'] = $tema_path. 'layout/wli.x.php';
$_ll['tema']['nli']['x'] = $tema_path. 'layout/nli.x.php';
$_ll['tema']['wli']['hd'] = (file_exists($f = ($tema_path. 'layout/wli.hd.php'))? $f: '');
$_ll['tema']['nli']['hd'] = (file_exists($f = ($tema_path. 'layout/nli.hd.php'))? $f: '');
$_ll['tema']['wli']['css'] = (file_exists($f = ($tema_path. 'layout/wli.css'))? $f: '');
$_ll['tema']['nli']['css'] = (file_exists($f = ($tema_path. 'layout/nli.css'))? $f: '');

if(file_exists($f = ($_ll['tema']['path']. 'layout/wli.x.php'))){
	$_ll['tema']['wli']['x'] = $f;
	if(file_exists($f = ($_ll['tema']['path']. 'layout/wli.hd.php'))) $_ll['tema']['wli']['hd'] = $f;
	if(file_exists($f = ($_ll['tema']['path']. 'layout/wli.css')))    $_ll['tema']['wli']['css'] = $f;}

if(file_exists($f = ($_ll['tema']['path']. 'layout/nli.x.php'))){
	$_ll['tema']['nli']['x'] = $f;
	if(file_exists($f = ($_ll['tema']['path']. 'layout/nli.hd.php'))) $_ll['tema']['nli']['hd'] = $f;  else $_ll['tema']['nli']['hd'] = '';
	if(file_exists($f = ($_ll['tema']['path']. 'layout/nli.css')))    $_ll['tema']['nli']['css'] = $f; else $_ll['tema']['nli']['css'] = '';}