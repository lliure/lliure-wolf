<?php
$tema_default = 'lliure';
$tema_path = 'opt/persona/lliure/';

if(isset($_ll['conf']->tema_default))
	if(file_exists($_ll['conf']->temas->{$_ll['conf']->tema_default})){
		$tema_default = (string) $_ll['conf']->tema_default;
		$tema_path = (string) $_ll['conf']->temas->{$tema_default};
	}
?>