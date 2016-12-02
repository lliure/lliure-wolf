<?php /*

<div id="tudo">
    <div id="topo">
        <div class="left">
            <a href="<?php echo $_ll['url']['endereco'];?>" class="logoSistema"><img src="usr/img/blank.gif"/></a>
            <?php if(!$_ll['desktop'] && $_ll['operation_type'] == 'app' && ll_tsecuryt()){ ?>
                <a href="javascript: void(0);" class="addDesktop" title="Adicionar este local ao desktop"><i class="fa fa-share-square  fa-rotate-90"></i></a>
            <?php }?>
        </div>
        <div class="right">
            <div class="menu">
                <ul>
                    <li><a href="<?php echo $_ll['url']['endereco']; ?>">Home</a></li>
                    <li><a href="?opt=user&en=minhaconta">Minha conta</a></li>
                    <?php echo (ll_tsecuryt('admin') ? '<li><a href="?opt=stirpanelo">Painel de controle</a></li>' : ''); ?>
                    <li><a href="onserver.php?opt=singin&ac=logout">Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</div> */ ?>

<header id="ll_topo">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button id="persona-navbar-collapse-button" type="button" class="navbar-toggle collapsed text-left" data-toggle="collapse" data-target="#persona-navbar-collapse" aria-expanded="false">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
                <div id="lliurelogoMargen" class="hidden-md hidden-sm hidden-lg"></div>
                <?php if(ll::valida() && $_ll['operation_type'] == 'app'){ ?>
                    <button type="button" class="btn btn-sm btn-add-desktop btn-lliure" style="padding: 5px; float: left; margin: 5px 0; position: relative; ">
                        <i class="fa fa-desktop" aria-hidden="true"></i>
                    </button>
                <?php } ?>
                <a href="<?php echo $_ll['url']['endereco'];?>" class="navbar-brand logoSistema navbar-brand">
                    <div id="lliurelogo" class="color-white" style="width: 60px; max-width: 100%;"><?php require $_ll['tema']['path']. 'layout/logo.svg'; ?></div>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="persona-navbar-collapse">
                <?php personaLayout::montaMenu($_ll['mainMenu']); ?>
            </div>
        </div>
    </nav>
</header>

<?php require_once ll::content(); ?>

<div id="ll_rodape_widht"></div>
<footer id="ll_rodape">
    <div class="container-fluid text-right">
        <a href="http://www.lliure.com.br" class="ll_color-100 ll_color-100-hover"><?php echo 'lliure '.$_ll['conf']->versao;?></a>
    </div>
</footer>

<script type="text/javascript">
    (function($){$(function(){
        $('.btn-add-desktop').click(function(){
            //ll_addDesk();

            jfBox('onclient.php?opt=desktop&ac=addDesktop').open();
        });
    })})(jQuery);
</script>