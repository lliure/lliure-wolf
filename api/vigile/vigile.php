<?php

//lliure::add('api/vigile/script.js');
//lliure::add('vigile_footer', 'footer');

function vigile($texto, $modo = 'top', $local = null){
	$_SESSION['vigile']['texto'] = $texto;

	$_SESSION['vigile']['texto'] = $texto;
	
	switch($modo){
	case 'local':
		$_SESSION['vigile']['local'] = $local;
		break;
	}
}

//vg_alert('presta atenção cara', '#div1');

function vigile_footer(){
	if (isset($_SESSION['vigile']) && !empty($_SESSION['vigile'])) {
		?>
		<script>
			$('<?php echo $_SESSION['vigile']['local']?>').append('<div id="vigile-alert" class="alert alert-success alert-dismissible" role="alert">'
																		+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
																		+'<?php echo $_SESSION['vigile']['texto']; ?>'
																	+'</div>');
			<?php
			echo 'setTimeout(function(){';
			echo '  $("#vigile-alert").fadeTo(2000, 500).slideUp(500, function(){';
			echo '     $("#vigile-alert").alert(\'close\');';
			echo '  });';
			echo '}, 5000);';
			
			unset($_SESSION['vigile']);
		echo '</script>';
	}
}
?>