<?php

define("appTabela", '');


$apigem = new api; 
$apigem->iniciaApi('aplimo');
$apigem->iniciaApi('navigi');


$aplikajo = new aplimo();
$aplikajo->nome = 'LHESP';

$aplikajo->sub_menu('Jogos', 'jogo', 'fa-trophy ');
$aplikajo->sub_menu_item('Ginásios', 'estadio');
$aplikajo->sub_menu_item('Equipes', 'equipe');
$aplikajo->sub_menu_item('Rodadas', 'rodada');

$aplikajo->menu('Anexos', 'anexo', 'fa-paperclip');


$aplikajo->header();	
