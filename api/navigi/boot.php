<?php

ll::usr('jquery');
ll::usr('jfkey');
ll::usr('jquery-taphold');
ll::usr('font-awesome');
ll::usr('jquery-maskplugin');
ll::api('vigile');
ll::api('datepicker');

ll::add('api/navigi/estilo.css', 'css', 2);
ll::add('api/navigi/script.js', 'js', 2);
ll::add('navigi::filterScripts', 'call:footer', 2);