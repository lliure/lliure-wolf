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
			->js('api/aplimo/script.js')
			->addApp();
			
$aplicativo->setNome('tags')
			->setCaminho('api/tags/inicio.php')
			->css('api/tags/estilo.css')
			->js('api/tags/script.js')
			->addApp();
			
			
$aplicativo->setNome('parsedown')
			->setCaminho('api/parsedown/parsedown.php')
			->addApp();
			
$aplicativo->setNome('midias')
			->setCaminho('api/midias/inicio.php')
			->css('api/midias/estilo.css')
			->css('api/midias/jquery.Jcrop.min.css')
			->js('api/midias/jquery.Jcrop.js')
			->js('api/midias/jquery.color.js')
			->js('api/midias/script.js')
			->addApp();
?>
