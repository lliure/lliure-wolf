<?php if(is_session_started() === true) echo json_decode(array('session' => 'ACITIVE')); else echo json_decode(array('session' => 'DESABLE'));