/**
 *
 * API navigi - lliure
 *
 * @Versão 6.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

Navigi = {};
function navigi_start(){
    Navigi.start();
}

(function($, Vigile, nvg){

    $(function(){
        var $navigi = $('#navigi');
        if($navigi.length > 0){
            $navigi.navigi();
            nvg.start();
        }
    });

    var navigi_token = '';
    nvg.start = function() {
        navigi_limpAllEvent();

        var $load = $('<div class="navigi_load"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>');
        var $navigi = $('#navigi');

        if($navigi.length > 0) {
            navigi_token = $navigi.attr('token');
            $navigi.addClass('navigi_loading').after($load);

            $.ajax({
                type: "POST",
                url: 'onclient.php?api=navigi',
                data: {token: navigi_token},
                success: function (r) {
                    r = JSON.parse(r);
                    $navigi.find('.navigi_areaIcones').html(r.list);
                    $navigi.find('.navigi_paginacao').html(r.pagi);
                    $navigi.removeClass('navigi_loading');
                    $load.remove();
                    $(window).resize();
                }
            });
        }
    };

    var navigi_selecionado = {length: 0};
    jQuery.fn.navigi = function(){

        return $(this).each(function(){

            var $contexto = $(this);

            /* ICONE */
            $(this).on('click', '.navigi_contextoMenu', function (e){

                e.stopPropagation();
                return false;

            /* ICONE */
            }).on('click', '.navigi_item', function (e) {

                var $id = $(this);

                if(!$id.hasClass('navigi_contextoMenuAberto')){
                    if ( !!e.originalEvent )
                        location = $id.attr('dclick');

                    else {
                        navigi_limpAllEvent();
                        navigi_selecionado = $id.addClass('navigi_selecionado');
                    }
                }

                taphold = false;
                e.stopPropagation();
                return false;

            /* ICONE */
            }).on('mouseup', '.navigi_item', function (e){

                var $id = $(this).closest('.navigi_item');

                if(e.which == 2){
                    navigi_limpAllEvent();
                    navigi_selecionado = $id.addClass('navigi_selecionado');}

            /* ICONE */
            }).on('contextmenu', '.navigi_item', function (e){

                navigi_limpAllEvent();
                var $id = $(this).closest('.navigi_item');
                navigi_selecionado = $id.addClass('navigi_selecionado navigi_contextoMenuAberto');

                e.stopPropagation();
                return false;

            /* ICONE */
            }).on("taphold", '.navigi_item', {duration: 1200}, function(e){

                navigi_limpAllEvent();

                var $id = $(this).closest('.navigi_item');
                navigi_selecionado = $id.addClass('navigi_selecionado navigi_contextoMenuAberto');
                taphold = true;

                e.stopPropagation();
                return false;

            /* ICONE */
            }).on('click', '.navigi_menuContextoOpen', function (e){

                var $id = $(this).closest('.navigi_item');
                $id.addClass('navigi_contextoMenuAberto');

                e.stopPropagation();
                return false;

            /* ICONE */
            }).on('click', '.navigi_icone_rename', function (e){
                if(!$(this).is(':disabled')) {
                    var $id = $(this).closest('.navigi_item');
                    $id.removeClass('navigi_contextoMenuAberto');
                    navigi_edit();
                }
                e.stopPropagation();
                return false;

            /* ICONE */
            }).on('click', '.navigi_icone_delete', function (e){
                if(!$(this).is(':disabled')) {
                    var $id = $(this).closest('.navigi_item');
                    $id.removeClass('navigi_contextoMenuAberto');
                    navigi_delete();
                }
                e.stopPropagation();
                return false;

            /* ICONE */
            }).on('click', '.navigi_icone_open', function (e){

                var $id = $(this).closest('.navigi_item');
                location = $id.attr('dclick');

                e.stopPropagation();
                return false;
            });

            /* ICONE */
            if($contexto.is('[data-exibicao="icone"]')){
                function windowResize() {
                    var width = $contexto.width();
                    var porcen = (100 / Math.ceil(width / 150));
                    $contexto.find('.navigi_item').css({'min-width': porcen + '%', 'max-width': porcen + '%'});
                }
                $(window).resize(windowResize);
                windowResize();
            }

            /* LISTA */
            $(this).on('click', '.navigi_tr', function (){
                if ($(this).attr('dclick') != '')
                    location = $(this).attr('dclick');

            /* LISTA */
            }).on('click', '.navigi_del', function (e){

                var del = $(this).closest('.navigi_tr');
                var id = del.attr('as_id');
                navigi_apaga(id).done(function(){
                    del.remove();
                });

                e.stopPropagation();
                return false;

            /* LISTA */
            }).on('click', '.navigi_bmod', function (e){

                var href = $(this).attr('href');
                var tamanho = $(this).attr('rel');
                tamanho = tamanho.split("x");

                jfBox({url: href, width: tamanho[0], height: tamanho[1]}).open();
                e.stopPropagation();
                return false;

            /* LISTA */
            }).on('click', '.navigi_ren', function (e){

                var $id = $(this).closest('.navigi_tr');
                var id = $id.attr('as_id');
                navigi_limpAllEvent();

                var inputName, btnRename, btnCansel;
                $id.addClass('navigi_editandoNome').find('.navigi_nome').after([
                    $('<div>', {'class': 'navigi_rename_form', click: function(e){ e.stopPropagation(); return false; }}).append([
                        $('<div>', {'class': 'input-group'}).append([
                            (inputName = $('<input>', {'class': 'form-control input-sm', 'type': 'text', 'value': $id.attr('nome')})),
                            $('<span>', {'class': 'input-group-btn'}).append([
                                (btnRename = $('<button>', {'class': 'btn btn-default btn-sm', 'type': 'button'}).append($('<i>', {'class': 'fa fa-check'}).css({'line-height': 'inherit'}))),
                                (btnCansel = $('<button>', {'class': 'btn btn-default btn-sm', 'type': 'button'}).append($('<i>', {'class': 'fa fa-times'}).css({'line-height': 'inherit'})))
                            ])
                        ])
                    ])
                ]);

                btnCansel.click(function () {
                    navigi_limpAllEvent();
                });

                btnRename.click(function(){
                    var texto = inputName.val();
                    var seletor = ((!!$id.attr('seletor'))? null: $id.attr('seletor'));

                    inputName.prop('disabled', true);
                    btnRename.prop('disabled', true);
                    btnCansel.prop('disabled', true);

                    navigi_rename(id, texto, seletor).done(function (texto){
                        $id.find('.navigi_nome').html(texto);
                        $id.attr({nome: texto});
                        navigi_limpAllEvent();

                    }).fail(function(){
                        inputName.prop('disabled', false);
                        btnRename.prop('disabled', false);
                        btnCansel.prop('disabled', false);
                    });
                });

                inputName.select();
                e.stopPropagation();
                return false;

            });

        });

    };

    /*************************************** EVENTOS ***************************************/
    var $html = $('html');

    $html.click(function (e) {
        if(e.which == 1) navigi_limpAllEvent();
    });

    $html.jfkey('left', function (e){
        if (navigi_selecionado.length > 0 && $('#navigi_inp_ren').length == 0){
            if(navigi_selecionado.prev().length > 0)
                navigi_selecionado.prev().click();
            else
                navigi_selecionado.siblings('.navigi_item:last-of-type').click();
        } else {
            return true;
        }
    });

    $html.jfkey('right', function (e) {
        if (navigi_selecionado.length > 0 && $('#navigi_inp_ren').length == 0){
            if(navigi_selecionado.next('.navigi_item').length > 0)
                navigi_selecionado.next('.navigi_item').click();
            else
                navigi_selecionado.siblings('.navigi_item:first-of-type').click();
        } else
            return true;
    });

    $html.jfkey('delete,osxdelete', function(){
        if ($('#navigi_inp_ren, #navigi_inp_lis_ren').length == 1) {
            return true;
        } else if (navigi_selecionado.length > 0 && (navigi_selecionado.attr('permicao') == '11' || navigi_selecionado.attr('permicao') == '01')){

            /* navigi_apaga(navigi_selecionado.attr('as_id')).done(function(){
                navigi_selecionado.remove();
            }); */

            navigi_delete();

        } else
            return true;
    });

    $html.jfkey('f2', function (){
        if (navigi_selecionado.length > 0 && (navigi_selecionado.attr('permicao') == '11' || navigi_selecionado.attr('permicao') == '10'))
            navigi_edit();
        else
            return true;
    });

    $html.jfkey('esc', function () {
        navigi_limpAllEvent();
    });


    /*************************************** FUNÇÕES ***************************************/
    function navigi_limpAllEvent() {
        if (navigi_selecionado.length > 0) {

            /* if ($('#navigi_inp_ren').length > 0){
                $('#' + navigi_selecionado + ' .navigi_nome').html($('#' + navigi_selecionado).attr('nome'));
            } */

            $('#navigi_inp_ren, #navigi_icone_form_deletando').remove();
            $('.navigi_item').removeClass('navigi_selecionado navigi_editandoNome navigi_contextoMenuAberto navigi_itemDeletando');
            navigi_selecionado = {length: 0};

        } else if ($('.navigi_tr .navigi_rename_form').length > 0) {

            $('.navigi_tr').removeClass('navigi_selecionado navigi_editandoNome');
            $('.navigi_tr .navigi_rename_form').remove();

        }
    }

    function navigi_delete(){

        var btnConfirm, btnCansel;
        var formEditName = $('<div>', {id: 'navigi_icone_form_deletando', class: 'text-left'}).append([
            $('<div>', {'class': 'alert alert-warning well-sm'}).css({'margin-bottom': 5}).html('<small><strong>Cuidado!</strong> Deseja realmente apagar este item.</small>'),
            $('<div>', {'class': 'text-right'}).append([
                $('<div>', {'class': 'btn-group'}).append([
                    (btnConfirm = $('<button>', {type: "button", class: "btn btn-default btn-sm"}).append($('<i>', {'class': 'fa fa-trash-o'}).css({'line-height': 'inherit'}))),
                    (btnCansel = $('<button>', {type: "button", class: "btn btn-default btn-sm"}).append($('<i>', {'class': 'fa fa-times'}).css({'line-height': 'inherit'})))
                ])
            ])
        ]);

        formEditName.on('contextmenu click', function(e){ e.stopPropagation(); return false; });

        navigi_selecionado.addClass('navigi_itemDeletando').find('.navigi_contextoMenu').after(formEditName);

        btnCansel.click(function(e){
            if(!$(this).is(':disabled')){
                navigi_selecionado.removeClass('navigi_itemDeletando');
                formEditName.remove();
            }
            e.stopPropagation(); return false;
        });

        btnConfirm.click(function(e){
            if(!$(this).is(':disabled')){

                btnConfirm.prop('disabled', true);
                btnCansel.prop('disabled', true);

                var as_id = navigi_selecionado.attr('as_id');

                navigi_apaga(as_id).done(function(){
                    navigi_selecionado.remove();
                    navigi_limpAllEvent();

                }).fail(function(){
                    btnConfirm.prop('disabled', false);
                    btnCansel.prop('disabled', false);
                });
            }
            e.stopPropagation(); return false;
        });
    }

    function navigi_apaga(id){
        var dfd = $.Deferred();
        $.post('onserver.php?api=navigi&ac=delete', {id: id, token: navigi_token}, function (e) {
            if(e == ''){
                Vigile().success('Registro excluido com sucesso!');  dfd.resolve();

            }else if (e == 403){
                Vigile().info('Você não tem permissão para excluir esse registro!'); dfd.reject();

            }else if (e == 412){
                Vigile().warning('Não foi possível excluir esse registro!'); dfd.reject();

            }else{
                Vigile().warning(e); dfd.reject()}
        });
        return dfd;
    }

    function navigi_edit() {

        var inputName, btnRename, btnCansel;
        var formEditName = $('<div>', {id: 'navigi_inp_ren', class: 'text-left'}).append([
            $('<div>', {'class': 'form-group'}).append([
                $('<label>', {}).html('Nome'),
                (inputName = $('<input>', {type: "email", class: "form-control text-left input-sm", 'value': navigi_selecionado.find('.navigi_nome').html()}))
            ]),
            $('<div>', {'class': 'text-right'}).append([
                $('<div>', {'class': 'btn-group'}).append([
                    (btnRename = $('<button>', {type: "button", class: "btn btn-default btn-sm"}).append($('<i>', {'class': 'fa fa-check'}).css({'line-height': 'inherit'}))),
                    (btnCansel = $('<button>', {type: "button", class: "btn btn-default btn-sm"}).append($('<i>', {'class': 'fa fa-times'}).css({'line-height': 'inherit'})))
                ])
            ])
        ]);

        formEditName.on('contextmenu click', function(e){ e.stopPropagation(); return false; });

        navigi_selecionado.addClass('navigi_editandoNome').find('.navigi_contextoMenu').after(formEditName);

        btnCansel.click(function(e){
            if(!$(this).is(':disabled')){
                navigi_selecionado.removeClass('navigi_editandoNome');
                formEditName.remove();
            }
            e.stopPropagation(); return false;
        });

        btnRename.click(function(e){
            if(!$(this).is(':disabled')){

                inputName.prop('disabled', true);
                btnRename.prop('disabled', true);
                btnCansel.prop('disabled', true);

                var seletor = ((!!navigi_selecionado.attr('seletor'))? null: navigi_selecionado.attr('seletor'));
                var as_id = navigi_selecionado.attr('as_id');

                navigi_rename(as_id, inputName.val(), seletor).done(function(nome){
                    navigi_selecionado.find('.navigi_nome').html(nome);
                    navigi_selecionado.removeClass('navigi_editandoNome');
                    formEditName.remove();

                }).fail(function(){
                    inputName.prop('disabled', false);
                    btnRename.prop('disabled', false);
                    btnCansel.prop('disabled', false);
                });
            }
            e.stopPropagation(); return false;
        });
    }

    function navigi_rename(as_id, texto, seletor){
        var dfd = $.Deferred();
        $.post('onserver.php?api=navigi&ac=rename', {id: as_id, texto: texto, seletor: seletor, token: navigi_token}, function(e){
            if (e == '') {
                Vigile().success('Nome alterado com sucesso!'); dfd.resolve(texto);

            } else if (e == 403) {
                Vigile().warning('Você não tem permissão para alterar esse registro!'); dfd.reject();

            }else{
                Vigile().warning(e); dfd.reject();
            }
        });
        return dfd.promise();
    }

})(jQuery, Vigile, Navigi);