<?php

ll::usr('jquery');

ll::add(__DIR__. '/css/bootstrap.min.css', 'css', 1);
ll::add(__DIR__. '/js/bootstrap.min.js',   'js',  1);

ll::add(array(
    '',
    '<!-- bootstrap -->',
    '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
    '<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">',
    '',
    '<!--[if lt IE 9]>',
    '<script type="text/javascript" src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>',
    '<script type="text/javascript" src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>',
    '<![endif]-->',
    '',
), 'integral',  100);
