<?php
/**
 * lliure WAP
 *
 * @Versão 8.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 */

$pg_idiomas = '<div class="container-fluid"><h3>Controle de Idiomas</h3>';

if(isset($_ll['conf']->idiomas) && !empty($_ll['conf']->idiomas)) {
    $pg_idiomas .=
        '<table class="table table-hover">'
            .'<thead><tr><th>Idiomas</th><th width="16px"></th><th width="16px"></th></tr></thead>';

            foreach($_ll['conf']->idiomas as $chave => $valor){
                $pg_idiomas .=
                    '<tr>'
                        .'<td>'.$ll_lista_idiomas[$valor].'</td>'
                        .'<td><a href="'. $_ll['opt']['onserver']. '&ac=natv&idi='.$valor.'" class="jfbox"><i class="fa '.($chave == 'nativo' ? 'fa-check-circle-o' : 'fa-circle-o').'"></i></a></td>'
                        .'<td><a href="'. $_ll['opt']['onserver']. '&ac=del&idi='.$valor.'" class="jfbox"><i class="fa fa-trash"></i></a></td>'
                    .'</tr>';

                unset($ll_lista_idiomas[$valor]);
            }
            $pg_idiomas .=
        '</table>';

} else
    $pg_idiomas .= '<p>A Ferramenta de multi-idiomas não está ativada</p>';

$pg_idiomas .=
    "<form class=\"jfbox\" action=\"{$_ll['opt']['onserver']}&ac=write\" method=\"post\">"
        .'<fieldset>'
            .'<div class="form-group">'
                .'<label>Adicionar linguagem</label>'
                .'<select class="form-control" name="idioma">';

                    foreach($ll_lista_idiomas as $chave => $valor)
                        $pg_idiomas .= '<option value="'.$chave.'">'.$valor.'</option>';
                    $pg_idiomas .=

                '</select>'
            .'</div>'
        .'</fieldset>'
        .'<div class="text-right">'
            .'<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> '
            .'<button type="submit" class="btn btn-default btn-lliure">Adicionar</button>'
        .'</div>'
    .'</form>';
$pg_idiomas .= '<br></div>';

echo $pg_idiomas;
