<?php
$productName = 'LLIURE';
$yourLink = 'http://lliure.com.br';
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $productName; ?> | Terminal</title>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'/>
		<link href="core/terminal.css" rel="stylesheet"/>
		<meta name="charset" content="UTF8"/>
        <meta name="robots" content="index, nofollow">
        <meta name="author" content="Carlos Alberto Bertholdo Carucce"/>
	</head>
	<body>
		<div class="wrap color1">
			<div class="terminal-width">
				<div class="log"></div>
				<!--<div class="clearfix command-box">
					<span class="cmd-arrow color2">></span><input class="color1" id="command" type="text" placeholder="Type command here..."/>
				</div>-->

				<table class="command-box incomming">
					<tr>
						<td class="cmd-arrow color2" style="width: 20px;">></td>
						<td class="cmd-inner-box"><div><input class="color1" id="command" type="text" placeholder=""/></div></td>
					</tr>
				</table>

				<div class="credits color2">
					Commands by: <a class="color2" target="_blank" href="<?php echo $yourLink; ?>"><?php echo $productName; ?></a> | 
					Console by Carlos A. B. Carucce (carloscarucce@gmail.com)
				</div>
			</div>
		</div>
		
		<script src="core/jquery-1.9.1.min.js"></script>
		<script src="core/terminal.js"></script>
	</body>
</html>
