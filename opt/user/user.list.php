<?php global $backReal, $_ll;

$botoes[] =	array('href' => $backReal, 'fa' => 'fa-chevron-left', 'title' => $backNome);
$botoes[] = array('href' => $_ll['opt']['onclient'].'&ac=new',  'fa' => 'fa-user-plus ', 'title' => 'Criar usuário', 'attr' => array('class' => 'criar'));
echo app_bar('Painel de usuários', $botoes); ?>
<div class="container-fluid">
    <?php $navegador = new navigi();
    $navegador->tabela = PREFIXO.'lliure_admin';
    $navegador->query = 'select * from '.$navegador->tabela.' where login is null || login != "'.$_ll['user']['login'].'"'.(ll::valida() ? '' : ' and grupo != "dev"').' order by nome ASC';
    $navegador->delete = true;
    $navegador->config = array(
        'link' => $_ll['opt']['home'].'&user=',
        'fa' => 'fa-user' );
    $navegador->monta(); ?>
</div>
<script type="text/javascript">
    $(function(){
        $(".criar").click(function(){
            ll_load($(this).attr('href'), function(){
                Vigile().success('Novo usuário criado com sucesso!');
                navigi_start();
            });
            return false;
        });
    });
</script>