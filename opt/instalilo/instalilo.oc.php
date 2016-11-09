<?php /**
 *
 * lliure WAP
 *
 * @Versão 6.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */ ?>

<div class="container-fluid">
    <br>
    <h1 class="h3 ll_color" style="margin-top: 0;">Instalando aplicativo</h1>
    <div class="install-box">
        <h2 class="h4 ll_color">Resumo de processo</h2>
        <div class="log">
            <div class="padding">
                <?php switch (isset($_GET['ac']) ? $_GET['ac'] : '') { default: ?>

                    <form action="<?php echo $_ll['opt']['onclient']; ?>&ac=instalar&app=<?php echo $_GET['app'] ?>" class="jfbox" method="post">

                        <?php if (file_exists(($sql = 'app/' . $_GET['app'] . '/sys/config.ll')) || file_exists(($config = 'app/' . $_GET['app'] . '/sys/config.plg'))) { ?>
                            <div class="alert well-sm alert-success">
                                Arquivo de configuração interna: <strong>OK</strong>
                            </div>
                        <?php } else { ?>
                            <div class="panel panel-info">
                                <div class="panel-heading well-sm">
                                    Arquivo de configuração interna: <strong>INFO</strong>
                                </div>
                                <div class="panel-body well-sm">
                                    <p>
                                        Arquivo de configuração interna não encontrado <br>
                                        Por favor adicione manualmente o nome do aplicativo.
                                    </p>
                                    <div class="form-group" style="margin-bottom: 0">
                                        <label for="FormInstalInputNome">Nome</label>
                                        <input type="text" name="nome" class="form-control" id="FormInstalInputNome" placeholder="<?php echo $_GET['app']; ?>">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (file_exists(($sql = 'app/' . $_GET['app'] . '/sys/bd.sql'))) { ?>
                            <div class="alert well-sm alert-success">
                                Arquivo de configuração do Banco de dados: <strong>OK</strong>
                            </div>
                        <?php } else { ?>
                            <div class="alert well-sm alert-info">
                                Este aplicativo não possui um arquivo de Banco de dados: <strong>INFO</strong>
                            </div>
                        <?php } ?>

                        <div class="text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fexar</button>
                            <button type="submit" class="btn btn-lliure">Instalar Aplicativo</button>
                        </div>

                    </form>

                <?php break; case 'instalar':

                    $msgs = array();

                    $bd = 'app/' . $_GET['app'] . '/sys/bd.sql';
                    $tp = new leitor_sql($bd, 'll_', PREFIXO);
                    foreach ($tp->getMsgs() as $msg) $msgs[] = $msg;

                    // cria pastas necessarias
                    if (file_exists($f = 'app/' . $_GET['app'] . '/sys/.folder')) {
                        $dirbase = '../uploads/';
                        $folders = file($f);

                        foreach ($folders as $key => $folder) {
                            if (@mkdir(($p = ($dirbase . ltrim(trim($folder), '/'))), 0777, true)) {
                                $msgs[] = array('success' => 'Add pasta: <strong>' . $p . '</strong>: <strong>OK</strong>');
                            } else {
                                $msgs[] = array('danger' => 'Add pasta: <strong>' . $p . '</strong>: <strong>ERRO</strong>');
                            }
                        }
                    }
                
                    //procura o nome
                    if (!! ($appConfig = ll::ota(ll::confg_app('app', $_GET['app'])))){
                        $aplicativo_nome = $appConfig['nome'];

                    } elseif (isset($_POST['nome']) && !empty(isset($_POST['nome']))) {
                        $aplicativo_nome = $_POST['nome'];

                    } else
                        $aplicativo_nome = $_GET['app'];

                    $instalilo = new Instalilo();
                    $instalilo->set(array('nome' => $aplicativo_nome, 'pasta' => $_GET['app'])); ?>

                    <div class="install-log well well-sm">
                        <?php foreach ($msgs as $k => $msg) {
                            list($status, $msg) = each($msg);
                            echo '<div class="alert well-sm alert-' . $status . '">' . $msg . '</div>';
                        } ?>
                    </div>

                    <p>
                        <strong>Instalação realizada com sucesso!</strong>
                    </p>
                    <p>
                        Esta instalação foi referente apenas ao banco de dados e pastas,
                        não foram arquivos de configuração, leia com atenção o arquivo
                        sobre a instalação deste aplicativo para seu pleno funcionamento
                    </p>

                    <div class="text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fexar</button>
                    </div>

                    <script>
                        jfBox.ready(function(modal){
                            modal.always(function(){
                                window.location.reload();
                            });
                        });
                    </script>

                <?php break; } ?>
            </div>
        </div>
    </div>
    <br>
</div>