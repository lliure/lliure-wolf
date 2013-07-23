<?php
function about(){
	ob_start();
	include (LLPATH.'paginas/sobre.php');
	ob_clean();
?>
	<div class="color2">
		lliure WAP<br/>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	</div>
	<span class="color2">lliure version: </span><?php echo $versao; ?><br/>
	<span class="color2">lliure home: </span><a class="color1" href="http://www.lliure.com.br" target="_blank">www.lliure.com.br</a><br/>
	<span class="color2">Author: </span>Jeison Frasson [jnomade2@gmail.com]<br/>
	<div class="color2">
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=<br/><br/>
		Shell info<br/>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	</div>
	<span class="color2">Version: </span>1.0<br/>
	<span class="color2">License: </span>LGPL - GNU Lesser General Public License version 3 or above - <a class="color1" target="blank" href="http://www.gnu.org/copyleft/lesser.html">read</a><br/>
	<span class="color2">Author: </span>Carlos Alberto Bertholdo Carucce [carloscarucce@gmail.com]<br/>
	<span class="color2">Host IP: </span><?php echo gethostbyname($_SERVER['SERVER_NAME']); ?><br/>
	<span class="color2">Your IP: </span><?php echo $_SERVER['REMOTE_ADDR']; ?><br/>
	<span class="color2">Server port: </span><?php echo $_SERVER['SERVER_PORT']; ?><br/>
	<span class="color2">Date: </span><?php echo date('r'); ?><br/>
	<div class="color2">
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	</div>
<?php
}
?>
