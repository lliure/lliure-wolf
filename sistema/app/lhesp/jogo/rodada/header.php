<?php
$this->hc_menu_item('a', array('url' => $this->sapm->home, 'texto' => 'Listar todas'));

$this->hc_menu_item('a', array('url' => $this->sapm->home . '&p=step', 'texto' => 'Adicionar rodadas'));

$this->hc_menu_item('a', array('url' => $this->sapm->onserver . '&ac=classificacao', 'texto' => 'Gerar Classificação'));