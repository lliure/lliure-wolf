<?php global $_ll, $backReal;

if(isset($_GET['user']) && !empty($_GET['user']))
	require_once __DIR__. '/user.form.php';

else
	require_once __DIR__. '/user.list.php';