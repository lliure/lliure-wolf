<?php

function xxx($a = 'defaultA', $b = 'defaultB'){
	echo 'A: ', $a, '<br/>B: ',$b;
}

call_user_func('xxx', 'oi');
?>
