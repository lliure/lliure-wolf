<?php

header("Content-type: text/css; charset: UTF-8");

$rgb = ((isset($_GET['cor']))? $_GET['cor']: '888888');
$rgb = ((strlen($rgb) == 3)? preg_replace('/(.)(.)(.)/', '$1$1$2$2$3$3', $rgb): $rgb);

echo '/* $rgb = ' .$rgb .  ' */'. "\n";

$r = (hexdec($rgb) >> 16) & 0xFF;
echo '/* $r = ' .$r .  ' */'. "\n";

$g = (hexdec($rgb) >> 8) & 0xFF;
echo '/* $g = ' .$g .  ' */'. "\n";

$b = hexdec($rgb) & 0xFF;
echo '/* $b = ' .$b .  ' */'. "\n";

$f = (1 / 5);

$rgb100 = 'rgb('. ((int)(((256 - $r) * ($f * 4)) + $r)) .', '. ((int)(((256 - $g) * ($f * 4)) + $g)) .', '. ((int)(((256 - $b) * ($f * 4)) + $b)). ')';
$rgb200 = 'rgb('. ((int)(((256 - $r) * ($f * 3)) + $r)) .', '. ((int)(((256 - $g) * ($f * 3)) + $g)) .', '. ((int)(((256 - $b) * ($f * 3)) + $b)). ')';
$rgb300 = 'rgb('. ((int)(((256 - $r) * ($f * 2)) + $r)) .', '. ((int)(((256 - $g) * ($f * 2)) + $g)) .', '. ((int)(((256 - $b) * ($f * 2)) + $b)). ')';
$rgb400 = 'rgb('. ((int)(((256 - $r) * ($f * 1)) + $r)) .', '. ((int)(((256 - $g) * ($f * 1)) + $g)) .', '. ((int)(((256 - $b) * ($f * 1)) + $b)). ')';
$rgb500 = 'rgb('. $r .', '. $g .', '. $b. ')';
$rgb600 = 'rgb('. ((int)(($r) * ($f * 4))) .', '. ((int)(($g) * ($f * 4))) .', '. ((int)(($b) * ($f * 4))). ')';
$rgb700 = 'rgb('. ((int)(($r) * ($f * 3))) .', '. ((int)(($g) * ($f * 3))) .', '. ((int)(($b) * ($f * 3))). ')';
$rgb800 = 'rgb('. ((int)(($r) * ($f * 2))) .', '. ((int)(($g) * ($f * 2))) .', '. ((int)(($b) * ($f * 2))). ')';
$rgb900 = 'rgb('. ((int)(($r) * ($f * 1))) .', '. ((int)(($g) * ($f * 1))) .', '. ((int)(($b) * ($f * 1))). ')';

ob_start(); ?><style>
    
.ll_color{ color: <?php echo $rgb500; ?> !important; }
.ll_color-100{ color: <?php echo $rgb100; ?> !important; }
.ll_color-200{ color: <?php echo $rgb200; ?> !important; }
.ll_color-300{ color: <?php echo $rgb300; ?> !important; }
.ll_color-400{ color: <?php echo $rgb400; ?> !important; }
.ll_color-500{ color: <?php echo $rgb500; ?> !important; }
.ll_color-600{ color: <?php echo $rgb600; ?> !important; }
.ll_color-700{ color: <?php echo $rgb700; ?> !important; }
.ll_color-800{ color: <?php echo $rgb800; ?> !important; }
.ll_color-900{ color: <?php echo $rgb900; ?> !important; }

.ll_color-hover:hover{ color: <?php echo $rgb500; ?> !important; }
.ll_color-100-hover:hover{ color: <?php echo $rgb100; ?> !important; }
.ll_color-200-hover:hover{ color: <?php echo $rgb200; ?> !important; }
.ll_color-300-hover:hover{ color: <?php echo $rgb300; ?> !important; }
.ll_color-400-hover:hover{ color: <?php echo $rgb400; ?> !important; }
.ll_color-500-hover:hover{ color: <?php echo $rgb500; ?> !important; }
.ll_color-600-hover:hover{ color: <?php echo $rgb600; ?> !important; }
.ll_color-700-hover:hover{ color: <?php echo $rgb700; ?> !important; }
.ll_color-800-hover:hover{ color: <?php echo $rgb800; ?> !important; }
.ll_color-900-hover:hover{ color: <?php echo $rgb900; ?> !important; }
			
.ll_border-color{ border-color: <?php echo $rgb500; ?> !important; }
.ll_border-color-100{ border-color: <?php echo $rgb100; ?> !important; }
.ll_border-color-200{ border-color: <?php echo $rgb200; ?> !important; }
.ll_border-color-300{ border-color: <?php echo $rgb300; ?> !important; }
.ll_border-color-400{ border-color: <?php echo $rgb400; ?> !important; }
.ll_border-color-500{ border-color: <?php echo $rgb500; ?> !important; }
.ll_border-color-600{ border-color: <?php echo $rgb600; ?> !important; }
.ll_border-color-700{ border-color: <?php echo $rgb700; ?> !important; }
.ll_border-color-800{ border-color: <?php echo $rgb800; ?> !important; }
.ll_border-color-900{ border-color: <?php echo $rgb900; ?> !important; }
			
.ll_border-color-hover:hover{ border-color: <?php echo $rgb500; ?> !important; }
.ll_border-color-100-hover:hover{ border-color: <?php echo $rgb100; ?> !important; }
.ll_border-color-200-hover:hover{ border-color: <?php echo $rgb200; ?> !important; }
.ll_border-color-300-hover:hover{ border-color: <?php echo $rgb300; ?> !important; }
.ll_border-color-400-hover:hover{ border-color: <?php echo $rgb400; ?> !important; }
.ll_border-color-500-hover:hover{ border-color: <?php echo $rgb500; ?> !important; }
.ll_border-color-600-hover:hover{ border-color: <?php echo $rgb600; ?> !important; }
.ll_border-color-700-hover:hover{ border-color: <?php echo $rgb700; ?> !important; }
.ll_border-color-800-hover:hover{ border-color: <?php echo $rgb800; ?> !important; }
.ll_border-color-900-hover:hover{ border-color: <?php echo $rgb900; ?> !important; }
		
.ll_background{ background-color: <?php echo $rgb500; ?> !important; }
.ll_background-100{ background-color: <?php echo $rgb100; ?> !important; }
.ll_background-200{ background-color: <?php echo $rgb200; ?> !important; }
.ll_background-300{ background-color: <?php echo $rgb300; ?> !important; }
.ll_background-400{ background-color: <?php echo $rgb400; ?> !important; }
.ll_background-500{ background-color: <?php echo $rgb500; ?> !important; }
.ll_background-600{ background-color: <?php echo $rgb600; ?> !important; }
.ll_background-700{ background-color: <?php echo $rgb700; ?> !important; }
.ll_background-800{ background-color: <?php echo $rgb800; ?> !important; }
.ll_background-900{ background-color: <?php echo $rgb900; ?> !important; }
		
.ll_background-hover:hover{ background-color: <?php echo $rgb500; ?> !important; }
.ll_background-100-hover:hover{ background-color: <?php echo $rgb100; ?> !important; }
.ll_background-200-hover:hover{ background-color: <?php echo $rgb200; ?> !important; }
.ll_background-300-hover:hover{ background-color: <?php echo $rgb300; ?> !important; }
.ll_background-400-hover:hover{ background-color: <?php echo $rgb400; ?> !important; }
.ll_background-500-hover:hover{ background-color: <?php echo $rgb500; ?> !important; }
.ll_background-600-hover:hover{ background-color: <?php echo $rgb600; ?> !important; }
.ll_background-700-hover:hover{ background-color: <?php echo $rgb700; ?> !important; }
.ll_background-800-hover:hover{ background-color: <?php echo $rgb800; ?> !important; }
.ll_background-900-hover:hover{ background-color: <?php echo $rgb900; ?> !important; }


.btn-lliure {
    color: #fff;
    background-color: <?php echo $rgb500; ?>;
    border-color: <?php echo $rgb600; ?>;
}

.btn-lliure:focus,
.btn-lliure.focus {
    color: #fff;
    background-color: <?php echo $rgb600; ?>;
    border-color: <?php echo $rgb700; ?>;
}

.btn-lliure:hover {
    color: #fff;
    background-color: <?php echo $rgb600; ?>;
    border-color: <?php echo $rgb700; ?>;
}

.btn-lliure:active,
.btn-lliure.active,
.open > .dropdown-toggle.btn-lliure {
    color: #fff;
    background-color: <?php echo $rgb600; ?>;
    border-color: <?php echo $rgb700; ?>;
}

.btn-lliure:active:hover,
.btn-lliure.active:hover,
.open > .dropdown-toggle.btn-lliure:hover,
.btn-lliure:active:focus,
.btn-lliure.active:focus,
.open > .dropdown-toggle.btn-lliure:focus,
.btn-lliure:active.focus,
.btn-lliure.active.focus,
.open > .dropdown-toggle.btn-lliure.focus {
    color: #fff;
    background-color: <?php echo $rgb700; ?>;
    border-color: <?php echo $rgb800; ?>;
}

.btn-lliure:active,
.btn-lliure.active,
.open > .dropdown-toggle.btn-lliure {
    background-image: none;
}

.btn-lliure.disabled:hover,
.btn-lliure[disabled]:hover,
fieldset[disabled] .btn-lliure:hover,
.btn-lliure.disabled:focus,
.btn-lliure[disabled]:focus,
fieldset[disabled] .btn-lliure:focus,
.btn-lliure.disabled.focus,
.btn-lliure[disabled].focus,
fieldset[disabled] .btn-lliure.focus {
    background-color: <?php echo $rgb500; ?>;
    border-color: <?php echo $rgb600; ?>;
}

.btn-lliure .badge {
    color: <?php echo $rgb100; ?>;
    background-color: <?php echo $rgb800; ?>;
}


html{
    display: block;
    height: 100%;
    width: 100%;
}

body{
    position: relative;
    display: block;
    min-height: 100%;
    width: 100%;
}

#lliurelogo{
    display: inline-block;
}

#lliurelogo g{ fill: <?php echo $rgb500; ?>; }
#lliurelogo.color-white g{ fill: #fff; }
#lliurelogo.color-black g{ fill: #000; }

#ll_topo {

    background:
        radial-gradient(100% 100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        radial-gradient(  0  100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        linear-gradient(to bottom, <?php echo $rgb500; ?> 0%,<?php echo $rgb500; ?> 100%);

    background:
        -ms-radial-gradient(100% 100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        -ms-radial-gradient(  0  100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        linear-gradient(to bottom, <?php echo $rgb500; ?> 0%,<?php echo $rgb500; ?> 100%);

    background:
        -moz-radial-gradient(100% 100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        -moz-radial-gradient(  0  100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        linear-gradient(to bottom, <?php echo $rgb500; ?> 0%,<?php echo $rgb500; ?> 100%);

    background:
        -o-radial-gradient(100% 100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        -o-radial-gradient(  0  100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        linear-gradient(to bottom, <?php echo $rgb500; ?> 0%,<?php echo $rgb500; ?> 100%);

    background:
        -webkit-radial-gradient(100% 100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        -webkit-radial-gradient(  0  100%, rgba(0,0,0,0) 68%, <?php echo $rgb500; ?> 73%),
        linear-gradient(to bottom, <?php echo $rgb500; ?> 0%,<?php echo $rgb500; ?> 100%);
}

#ll_topo {
    position: relative;
    z-index: 500;

    background-position:        left bottom, right bottom, left top;
    -webkit--background-size:   10px 6px, 10px 6px, 100% calc(100% - 6px);
    -moz-background-size:       10px 6px, 10px 6px, 100% calc(100% - 6px);
    -ms-background-size:        10px 6px, 10px 6px, 100% calc(100% - 6px);
    -o-background-size:         10px 6px, 10px 6px, 100% calc(100% - 6px);
    background-size:            10px 6px, 10px 6px, 100% calc(100% - 6px);
    background-repeat:          no-repeat;
}


#ll_topo > .navbar.navbar-default{
    border: none;
    border-radius: 0;
    background: none;
    min-height: auto;
    padding-bottom: 6px;
    margin-bottom: -6px;
}

#ll_topo > .navbar.navbar-default > .container-fluid{}

#ll_topo > .navbar.navbar-default > .container-fluid > .navbar-header{
    position: relative;
}

#ll_topo > .navbar.navbar-default > .container-fluid > .navbar-header > .navbar-brand{
    height: 34px;
    line-height: 34px;
    padding: 8px 10px;
    position: relative;
    margin-left: 34px;
}

@media (min-width: 768px) {
    #ll_topo > .navbar.navbar-default > .container-fluid > .navbar-header > .navbar-brand {
        padding-left: 0;
        margin-left: 0;
    }
}


#ll_topo > .navbar.navbar-default > .container-fluid > .navbar-header > .navbar-toggle{
    margin: 0;
    padding: 0 15px;
    position: absolute;
    top: 0; bottom: 0;
    left: 0; right: 0;

    background: none !important;
    border: none !important;
    width: 100%;
    color: #fff;
}

#ll_topo > .navbar.navbar-default > .container-fluid > .navbar-collapse{
    border: none;
    -webkit-box-shadow: none;
    box-shadow: none;
}

#ll_topo > .navbar.navbar-default > .container-fluid > .navbar-collapse > .navbar-nav{
    margin-top: 0;
}

#ll_topo > .navbar.navbar-default > .container-fluid > .navbar-collapse > .navbar-nav > li{}

#ll_topo > .navbar.navbar-default > .container-fluid > .navbar-collapse > .navbar-nav > li > a{
    color: #fff;
    font-size: 0.915em;
    line-height: 34px;
    padding: 0 15px;
}

@media (min-width: 768px) {
    #ll_topo > .navbar.navbar-default > .container-fluid > .navbar-collapse > .navbar-nav > li > a{
        padding: 0 10px;
    }
}


#ll_rodape_widht{
    display: none;
}

#ll_rodape{
    height: 25px;
    display: block;
    z-index: 400;
}

@media (min-width: 768px) {
    #ll_rodape_widht{
        display: block;
        width: 100%;
        height: 25px;
        opacity: 0;
        z-index: -1;
    }
    #ll_rodape{
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }
}

#ll_rodape > div > a{
    line-height: 25px;
}


</style><?php echo substr(ob_get_clean(), 7, -8);