<?php
$aplicativo = new ll_app();
$aplicativo->setNome('fileup')
			->setCaminho('api/fileup/inicio.php')
			->css('api/fileup/estilo.css')
			->addApi();
			
$aplicativo->setNome('navigi')
			->setCaminho('api/navigi/inicio.php')
			->css('api/navigi/estilo.css')
			->js('api/navigi/script.js')
			->addApi();
			
$aplicativo->setNome('jfnav')
			->setCaminho('api/jfnav/inicio.php')
			->css('api/jfnav/estilo.css')
			->addApi();
			
$aplicativo->setNome('appbar')
			->setCaminho('api/appbar/inicio.php')
			->css('api/appbar/estilo.css')
			->addApi();
			
$aplicativo->setNome('aplimo')
			->setCaminho('api/aplimo/inicio.php')
			->css('api/aplimo/estilo.css')
			->js('api/aplimo/script.js')
			->addApi();
			
$aplicativo->setNome('tags')
			->setCaminho('api/tags/inicio.php')
			->css('api/tags/estilo.css')
			->js('api/tags/script.js')
			->addApi();
			
$aplicativo->setNome('parsedown')
			->setCaminho('api/parsedown/parsedown.php')
			->addApi();
			
$aplicativo->setNome('jfbox')
			->js('api/jfbox/jquery.jfbox.js')
			->css('api/jfbox/jfbox.css')
			->addApi();

$aplicativo->setNome('Midias')
			->setCaminho('api/midias/inicio.php')
			->css('api/midias/estilo.css')
			->css('api/midias/jquery.Jcrop.min.css')
			->css('opt/font-awesome/css/font-awesome.min.css')
			->js('api/midias/jquery.Jcrop.js')
			->js('api/midias/jquery.color.js')
			->js('api/midias/script.js')
			->addApi();
			
class api extends lliure{}
?>
