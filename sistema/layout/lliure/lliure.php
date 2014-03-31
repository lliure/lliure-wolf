<?php layout::metas(layout::contentType, 'text/html; charset=iso-8859-1');?>
<?php layout::addDocHead('imagens/layout/favicon.ico');?>
<?php layout::metas('author', 'Jeison Frasson');?>
<?php layout::metas('DC.creator.address', 'jomadee@lliure.com.br');?>
<?php layout::metas('DC.creator', 'Jeison Frasson');?>
<?php layout::metas('collaboration', 'Rodrigo Dechen');?>
<?php layout::metas('collaboration.address', 'mestri.rodrigo@gmail.com');?>
<?php layout::header();?>
        
    <div id="tudo">
        <div id="topo">
            <span class="borda-esquerda"></span>
            <span class="borda-direita"></span>
            <div class="left">
                <a href="index.php" class="logoSistema"><img src="imagens/layout/blank.gif"/></a>
                <?php
                if(!empty($_GET) &&  ll_tsecuryt()){
                    $keyGet = array_keys($_GET);
                    if($keyGet['0'] == 'app' && !empty($_GET['app'])){
                        ?>
                        <a href="javascript: void(0);" class="addDesktop" title="Adicionar essa página ao desktop"><img src="imagens/layout/add_desktop.png" alt="" /></a>
                        <?php 
                    }
                } 
                ?>
            </div>


            <div class="right">			
                <div class="menu">
                    <ul>
                        <?php
                        echo '<li><a href="index.php">Home</a></li>'
                            .'<li><a href="?minhaconta">Minha conta</a></li>'
                            .(ll_tsecuryt('admin') ? '<li><a href="?opt=painel">Painel de controle</a></li>' : '')						
                            .'<li><a href="nli.php?r=logout">Sair</a></li>';
                        ?>					
                    </ul>
                </div>
                <?php 

                if(ll_tsecuryt('admin')){
                    $consulta = "select b.* from 
                                ".PREFIXO."lliure_start as a

                                left join ".PREFIXO."lliure_apps as b
                                on a.idPlug = b.id	";
                    $query = mysql_query($consulta);

                    ?>
                    <div class="start" id="menu_rapido"  <?php echo (mysql_num_rows($query) == 0 ? 'style="display: none;"' : '' );?>>
                        <div class="width">
                            <span class="icone"></span>
                            <ul id="appRapido">
                                <?php
                                while($dados = mysql_fetch_array($query)){								
                                    $icone = 'app/'.$dados['pasta'].'/sys/ico.png';

                                    ?>
                                    <li id="appR-<?php echo $dados['id']?>">
                                        <a href="?app=<?php echo $dados['pasta']?>" title="<?php echo $dados['nome']?>">
                                            <img src="<?php echo $icone; ?>" alt="" />
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php				
                } 
                ?>
            </div>
        </div>

        <div id="conteudo">
            <?php echo layout::content();?>
            <div class="both"></div>
        </div>


        <div id="rodape">
            <a href="http://www.lliure.com.br"><img src="imagens/layout/logo_inf.png" alt="" /></a>
        </div> 
    </div>
    
	<script type="text/javascript">
		$(function(){
			<?php ll_alert();?>	
		});
	</script>
    
<?php layout::footer();?>

