<?php
/**
*
* lliure WAP
*
* @Versão 8.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

//ll_historico('reinicia');

$botoes[] = array('href' => $backReal, 'fa' => 'fa-chevron-left', 'title' => $backNome);
echo app_bar('Painel de controle', $botoes); ?>

<div class="container-fluid">
	<div class="painelCtrl">
		<div class="bloco">
			<h2 style="margin-top: 0;">Configurações</h2>

			<div class="listp">
				<div class="inter">
					<a href="?opt=user"><img src="opt/stirpanelo/img/users.png" alt="" /></a>
					<a href="?opt=user"><span>Usuários</span></a>
				</div>
			</div>

			<?php if(ll::valida()) { ?>
				<div class="listp">
					<div class="inter link_idioma">
						<a href="onclient.php?opt=idiomas"><img src="opt/stirpanelo/img/language.png" alt="" /></a>
						<a href="onclient.php?opt=idiomas"><span>Idiomas</span></a>
					</div>
				</div>
			<?php } ?>

			<div class="listp">
				<div class="inter">
					<a href="nli.oc.php?opt=sobre" class="llSobre"><img src="opt/stirpanelo/img/info.png" alt="" /></a>
					<a href="nli.oc.php?opt=sobre" class="llSobre"><span>Sobre</span></a>
				</div>
			</div>

		</div>

		<div class="bloco">
			<h2>Aplicativos</h2>

			<?php $aplicativos = $appFolder = array();
			$erroAbrirDir = false;

			$intalilo = new Instalilo();
			foreach ($intalilo->get(null, array('nome' => "ASC")) as $dados)
				$aplicativos[$dados['pasta']] = $dados['nome'];

			if (!($erroAbrirDir = !($handle = opendir("app")))){
				while (false !== ($file = readdir($handle)))
					if (strstr($file, '.') == false)
						$appFolder[] = $file;

				closedir($handle);}

			natcasesort($appFolder);

			if(!$erroAbrirDir){

				foreach($appFolder as $chave => $file){
					if(isset($aplicativos[$file])){
						$confs = array_merge(array(
							'ico' => 'opt/stirpanelo/icon_defaulto.png',
							'nome' => $file,
						), ll::ota(ll::confg_app('app', $file))); ?>

						<div class="listp">
							<div class="inter">
								<a href="?app=<?php echo $file; ?>"><img src="<?php echo $confs['ico']; ?>" alt="<?php echo $confs['nome']; ?>" /></a>
								<a href="?app=<?php echo $file; ?>"><span><?php echo $confs['nome']; ?></span></a>
							</div>
						</div>
					<?php unset($appFolder[$chave]); } ?>
				<?php } ?>

				<?php foreach($appFolder as $chave => $file) { ?>
					<?php if (ll::valida()){
						$confs = array_merge(array(
							'ico' => 'opt/instalilo/ico.png',
							'nome' => $file,
						), ll::ota(ll::confg_app('app', $file))); ?>
						<div class="listp">
							<div class="inter">
								<a href="onclient.php?opt=instalilo&app=<?php echo $file ?>" class="install install-icone">
									<i class="fa fa-archive"></i><img src="<?php echo $confs['ico']; ?>" alt="<?php echo $confs['nome']; ?>"/>
								</a>
								<a href="onclient.php?opt=instalilo&app=<?php echo $file ?>" class="install"><span><?php echo $confs['nome']; ?></span></a>
							</div>
						</div>
					<?php } ?>
				<?php } ?>

			<?php } else { ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title">ERRO</h3>
					</div>
					<div class="panel-body">
						<p>Houve um erro ao tentar abrir o diretório de aplicativos</p>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function (){
		$('.install').jfbox({width: 420});
		$('.link_idioma a').jfbox({width: 420});
		$('.llSobre').jfbox({width: 420, addClass: 'llSobre_box'});
	});
</script>
