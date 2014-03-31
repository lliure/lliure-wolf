<?php
/**
*
* lliure WAP
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

header("Content-Type: text/html; charset=ISO-8859-1",true);

switch(isset($_GET['ac']) ? $_GET['ac'] : ''){
default:
	?>
	<h1>Instalando aplicativo</h1>

	<div class="install-box">
		<h2>Resumo de processo</h2>
		
		<div class="log">
            
			<div class="padding">
                
				<?php if(file_exists(($bd = 'app/'.$_GET['app'].'/sys/bd.sql'))){?>
                
					<form action="<?php echo $_ll['app']['sen_html']. '&p=install&ac=instalar&app='. $_GET['app'];?>" class="jfbox">
                        
                        <div class="fase">
                            <div class="msm">Arquivo de configuração do Banco de dados: OK</div>
                        </div>
						
                        <?php if(file_exists(($config = 'app/'.$_GET['app'].'/sys/config.ll')) && (($appConfig = simplexml_load_file($config)) !== FALSE)){?>
							<div class="fase"><div class="msm">Arquivo de configuração interna: OK</div></div>
                            
                            <?php if(!isset($appConfig->seguranca) || $appConfig->seguranca == 'public'){?>
								<div class="fase">
                                    <div class="msm">As definições de segurança do aplicativo estão setadas como <strong>public</strong></div>
                                </div>
                            
							<?php }else{?>
                            
								<?php if(file_exists(($segur = 'app/'.$_GET['app'].'/sys/seguranca.ll'))){?>
                                    <div class="fase">
                                        <div class="msm">O aplicativo possui um arquivo de configuração de permições</div>
                                    </div>
									<input type="hidden" name="segur" value="<?php echo $segur;?>"/>
                                    
								<?php }else{?>
									<div class="fase">
                                        <div class="msm msmE">Não foi possivel encontrar o arquivo de com as configuraçõs de permições (<?php echo $segur;?>)</div>
                                    </div>
                                    
                                <?php }?>
                                    
							<?php }?>
                                    
						<?php }else{?>
							<div class="fase">
                                <div class="msm msmE">Arquivo de configuração interna: ERRO</div>
                                <span class="msmex">Por favor adicione manualmente o nome do aplicativo</span>
                                <div>
                                    <label>Nome</label>
                                    <input type="text" name="nome"/>
                                </div>
							</div>
                            
						<?php }?>
                            
						<span class="botao"><button type="submit">Instalar Aplicativo</button></span>
					</form>
                
                <?php }else{?>
                
					Este aplicativo não possui um arquivo de instalação. <br>Tente fazer a instalação manualmente através das instruções do criador. <br><br>
                    
				<?php }?>
                    
			</div>
		</div>
	</div>
    
	<?php
    
break;

case 'instalar':
    
	require_once("includes/class.leitor_sql.php"); 
	
	$_POST =  jf_iconv('UTF-8', 'ISO-8859-1', $_POST);
	
	$bd = 'app/'.$_GET['app'].'/sys/bd.sql';
	$tp = new leitor_sql($bd, 'll_', PREFIXO);	
	
	// cria pastas necessarias
	if(file_exists('app/'.$_GET['app'].'/sys/.folder')){
		$dirbase = '../uploads/';
		$folders = file('app/'.$_GET['app'].'/sys/.folder');
		
		foreach($folders as $key => $folder)
			@mkdir($dirbase.trim($folder), 0777);
	}
	
	//procura o nome
	if (file_exists(($f = 'app/'.$_GET['app'].'/sys/config.plg'))
    || (file_exists(($f = 'app/'.$_GET['app'].'/sys/config.ll')))){
		$appConfig = simplexml_load_file($f);
		$aplicativo_nome = $appConfig->nome;
        
	}else
		$aplicativo_nome = $_POST['nome'];
	
	if(isset($_POST['segur'])){
		@mkdir('etc/'.$_GET['app'], 0777);
		@copy($_POST['segur'], 'etc/'. $_GET['app'].' /seguranca.ll');
	}

	jf_insert(PREFIXO.'lliure_apps', array('nome' => $aplicativo_nome, 'pasta' => $_GET['app']));
    ?>
    <br>
    <br>
	<span><strong>Instalação realizada com sucesso!</strong></span><br>
    <br>
	<span style="font-size: 11px;">Esta instalação foi referente apenas ao banco de dados e pastas, não foram arquivos de configuração, leia com atenção o arquivo sobre a instalação deste aplicativo para seu pleno funcionamento</span><br>
    <br>
	<script>
		jfboxVars.fermi= function(){
			window.location.reload();
		}
	</script>
	<?php
break;
}