<?php
$aplicativo = new ll_app();
$aplicativo->setNome('fileup')
			->setCaminho('fileup/inicio.php')
			->css('api/fileup/estilo.css')
			->addApp();
			
$aplicativo->setNome('navigi')
			->setCaminho('api/navigi/inicio.php')
			->css('api/navigi/estilo.css')
			->js('api/navigi/script.js')
			->addApp();
			
$aplicativo->setNome('jfnav')
			->setCaminho('api/jfnav/inicio.php')
			->css('api/jfnav/estilo.css')
			->addApp();
			
$aplicativo->setNome('appbar')
			->setCaminho('api/appbar/inicio.php')
			->css('api/appbar/estilo.css')
			->addApp();
			
$aplicativo->setNome('aplimo')
			->setCaminho('api/aplimo/inicio.php')
			->css('api/aplimo/estilo.css')
			->css('api/aplimo/script.js')
			->addApp();
?>
