<?php

$botoes[] =	array('href' => $backReal, 'fa' => 'fa-chevron-left', 'title' => $backNome);
echo app_bar('Painel de usuários', $botoes);

$dados = DB::first($user->get(array('id' => $_GET['user'])));
extract($dados); ?>

<form method="post" action="<?php echo $_ll['opt']['onserver']. '&ac=grava&id='. $_GET['user']. (isset($_GET['en']) && $_GET['en'] == 'minhaconta' ? '&en=minhaconta' : '' );?>"  enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="container">
        <fieldset>
            <legend>Dados pessoais</legend>

            <div class="form-group">
                <label for="FormUserInputNome">Nome <span>*</span></label>
                <input type="text" class="form-control" id="FormUserInputNome" value="<?php echo $nome; ?>" name="nome">
            </div>

            <div class="form-group">
                <label for="FormUserInputEmail">E-mail</label>
                <input type="email" class="form-control" id="FormUserInputEmail" value="<?php echo $email; ?>" name="email">
            </div>

            <?php /* removido no 9.0
				<div class="form-group">
					<label for="FormUserInputTwitter">Twitter</label>
					<input type="text" class="form-control" id="FormUserInputTwitter" value="<?php echo $twitter?>" name="twitter">
				</div> */ ?>

            <div class="form-group">
                <label>Foto</label>
                <?php echo fileup::make(array(
                    'name' => 'foto',
                    'value' => $foto,
                    'accept' => 'image/*',
                    'button' => 'Selecionar imagem'
                )); ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>Dados de acesso</legend>

            <div class="form-group">
                <label for="FormUserInputLogin">Login <span>*</span></label>
                <input type="text" name="login" class="form-control"<?php echo !empty($login)? ' readonly value="'. $login. '"': ''; ?> id="FormUserInputLogin">
            </div>

            <div class="form-group">
                <label for="FormUserInputSenha">Senha</label>
                <input type="password" name="senha" class="form-control" id="FormUserInputSenha">
                <p class="help-block">Deixe em branco para manter a senha atual.</p>
            </div>

            <?php if(ll::valida('admin') && $login != $_ll['user']['login']){ ?>
                <div class="form-group">
                    <label for="FormUserInputGrupo">Grupo de usuário</label>
                    <select id="FormUserInputGrupo" class="form-control" name="grupo">
                        <?php

                        if(isset($_ll['conf']->grupo)){
                            //$grupos_add = jf_iconv("UTF-8", "ISO-8859-1", (array) $llconf->usua_grup);

                            if(!empty($_ll['conf']->grupo)){
                                $sub = null;
                                foreach($_ll['conf']->grupo as $ogrupo => $valor)
                                    if(isset($valor->nome))
                                        $sub .= '<option value="'.$ogrupo.'" '.($grupo == $ogrupo?'selected':'').'>'.$valor->nome.'</option>';

                                if($sub != null)
                                    echo '<optgroup label="Sub-grupos">'.$sub.'</optgroup>';
                            }
                        }

                        ll::valida() ?
                            $grupos['dev'] = 'Desenvolvedor': null;
                        $grupos['admin'] = 'Administrador';
                        $grupos['user'] = 'Usuário';

                        echo '<optgroup label="Grupos principais">';
                        foreach($grupos as $indice => $valor)
                            echo '<option value="'.$indice.'" '.($grupo == $indice?'selected':'').'>'.$valor.'</option>';
                        echo '</optgroup>'; ?>
                    </select>
                </div>
            <?php } ?>
        </fieldset>

        <?php if(ll::valida()){ ?>

            <fieldset>
                <legend>Liberações</legend>

                <?php $libberacao = new Liberacao(); $libs = true; ?>
                <?php if($grupo != 'dev'){ $libs = array(); foreach ($libberacao->get(array('login' => $login)) as $v){
                    $libs["{$v['operation_type']}/{$v['operation_load']}"] = true;
                }}; ?>

                <?php $locais = array(
                    'Sistema' => array(
                        'opt/user' => 'Usuarios',
                        'opt/desktop' => 'Desktop',
                        'opt/stirpanelo' => 'Painel de controle',
                        'opt/instalilo' => 'Instalações de apps',
                        'opt/idiomas' => 'Idiomas',
                    )
                ); ?>

                <?php $instalilo = new Instalilo(); ?>
                <?php foreach($instalilo->get() as $v) $locais['Apps']["app/{$v['pasta']}"] = $v['nome']; ?>

                <?php foreach($locais as $local => $apps){ ?>
                    <h4><?php echo $local; ?></h4>
                    <?php foreach($apps as $k => $v){ ?>
                        <div class="checkbox<?php echo (($libs === true)? ' disabled': ''); ?>">
                            <label>
                                <input type="checkbox" name="liberacoes[]" value="<?php echo $k; ?>"<?php echo (($libs === true || (isset($libs[$k]) && $libs[$k]))? ' checked="checked"': ''); ?><?php echo (($libs === true)? ' disabled="disabled"': ''); ?>>
                                <?php echo $v; ?>
                            </label>
                        </div>
                    <?php } ?>
                <?php } ?>

            </fieldset>

        <?php } ?>

        <br>
        <br>

        <a class="btn btn-default" href="<?php echo  $backReal;?>" role="button">Voltar</a>
        <button class="btn btn-lliure" type="submit">Gravar</button>
    </div>
</form>