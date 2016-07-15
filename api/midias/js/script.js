;if(typeof api === 'undefined')api={};

api.Midias = {};
api.Midias.urlUpload = 'onserver.php?api=midias&p=upload';		//	link base de uploads dos arquivos
api.Midias.urlCorte = 'onclient.php?api=midias&p=cortar';		//	link base da tela de cortes das imagens
api.Midias.urlRepositorio = 'onclient.php?api=midias&p=midias'; //	link base da tela do repositorio online
api.Midias.contexto = null;										//	contexto atual de dados para uploads
api.Midias.sendFiles = 0;										//	arquivos subindo atualmente
api.Midias.sendFilesBuffer = [];								//	buff de armasemanto de arquivos para upload
api.Midias.sendFilesMax = 5;									//	maximo de arquivos upados simultaniamente.
api.Midias.sendFileToServer = function(settings){
    if(settings && api.Midias.sendFiles >= api.Midias.sendFilesMax){
        settings.contexto = api.Midias.contexto;
        api.Midias.sendFilesBuffer.push(settings);
    }else{
        if(!settings && api.Midias.sendFilesBuffer.length > 0)
            settings = api.Midias.sendFilesBuffer.shift();
        if(settings){
            api.Midias.sendFiles += 1;
            var contexto = settings.contexto || api.Midias.contexto;
            var formData = new FormData();
            formData.append('file', settings.file);
            $.ajax({
                xhr: function(){
                    var xhrobj = $.ajaxSettings.xhr();
                    if (xhrobj.upload){xhrobj.upload.addEventListener('progress', function(event){
                        settings.progress((!event.lengthComputable? 0: Math.ceil((event.loaded || event.position) / (event.total) * 100)));
                    }, false);}
                    return xhrobj;
                },
                url: api.Midias.urlUpload + '&m=' + contexto.attr('data-action'),
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                success: function(data){
                    settings.success(data);
                    api.Midias.sendFiles -= 1;
                    ifEnd(settings.end);
                },
                error: function(data){
                    settings.error(data);
                    ifEnd(settings.end);
                }
            });
        }
    }
    function ifEnd(end){
        if(api.Midias.sendFiles <= 0)end();
        else api.Midias.sendFileToServer();}
};
api.Midias.difernciar = function(itens, lista, contexto){
    contexto = contexto || api.Midias.contexto;
    var i, inseridos = [], removidos = [], name = contexto.attr('data-api-midias');
    $.each(itens, function(index, dados){
    if((i = lista.indexOf(index)) < 0)
        inseridos.push({name: name + '[inseridos][' + (index.split(':').shift()) + ']', value: index.split(':').pop()});
    else
        lista.splice(i, 1);
    });
    $.each(lista, function(index, valeu){
        removidos.push({name: name + '[removidos][' + (valeu.split(':').shift()) + ']', value: valeu.split(':').pop()});
    });
    return {'inseridos': inseridos, 'removidos': removidos};
};
api.Midias.atualizaInputs = function(opcoes, contesto){
    contesto = contesto || api.Midias.contesto;
    $('input[type="hidden"][ref="inseridos"], input[type="hidden"][ref="removidos"]', contesto).remove();
    if(opcoes.removidos)
        $.each(opcoes.removidos, function(id, dados){
            $(contesto).append([
                $('<input>', {type: 'hidden', ref: 'removidos', name: dados.name, value: dados.value})
            ]);
        });
    if(opcoes.inseridos)
        $.each(opcoes.inseridos, function(id, dados){
            $(contesto).append([
                $('<input>', {type: 'hidden', ref: 'inseridos', name: dados.name, value: dados.value})
            ]);
        });
};
api.Midias.dezenhaTamando = function (tamanho){
    tamanho = parseFloat(tamanho);
    if(tamanho < 1024)
        return Math.floor(tamanho)+ 'b';
    tamanho /= 1024;
    if(tamanho < 1024)
        return Math.floor(tamanho)+ 'KB';
    tamanho /= 1024;
    if(tamanho < 1024)
        return Math.floor(tamanho)+ 'MB';
    tamanho /= 1024;
    if(tamanho < 1024)
        return Math.floor(tamanho)+ 'GB';
    tamanho /= 1024;
    if(tamanho < 1024)
        return Math.floor(tamanho)+ 'TB';
    tamanho /= 1024;
    return Math.floor(tamanho)+ 'PB';
};



(function($, d, w){

    function loadOptions(target){
        return {
            'contexto': (target.data('contexto')? target.closest(target.data('contexto')): target),
            'nome': target.data('api-midias'),
            'modo': target.data('api-modo'),
            'pasta': target.data('pasta'),
            'pastaRef': target.data('pastaRef'),
            'quant-start':  parseInt(target.data('quant-start')),
            'quant-length': parseInt(target.data('quant-length')),
            'quant-total':  parseInt(target.data('quant-total')),
            'quant-max': (parseInt(target.data('quant-length')) == 0? 0 :(parseInt(target.data('quant-start')) + parseInt(target.data('quant-length')))),
            'corte': target.data('corte'),
            'cortes': target.data('cortes'),
            'tipos': target.data('tipos').split(' '),
            'dados': target.data('dados'),
            'action': target.data('action')
        }
    }



    $(function(){
        $('[data-api-midias][data-modo="upload"]').each(function(i, a){
            var self = $(a);
            var o = loadOptions(self);
            var input = (
                $('<input>', { type: 'file'}).css({
                    display: 'none'
                }).data('datas', o)
                .click(function(event){
                    //event.preventDefault();
                    event.stopPropagation();
                })
                .change(function(event){
                    var o = $(this).data('datas');
                    event.preventDefault();
                    event.stopPropagation();
                    simplesLoad(this.files, o);
                    return false;
                })
            );
            self.data('myInputTarget', input);
            o['contexto'].prepend(input);

        }).click(function(e){

            var self = $(this);
            var o = loadOptions(self);
            var myInputTarget = $(self.data('myInputTarget'));

            api.Midias.contexto = o['contexto'];
            console.log(myInputTarget);
            myInputTarget.focus().click();

            e.stopPropagation();
            return false;

        });
    });

    function simplesLoad(fs, o){
        var contexto = o['contexto'] || api.Midias.contesto;
        var total = parseInt(o['quant-total']);
        var action = o['action'];
        var files = {};
        var corte  = (o['corte'] && o['cortes']? (o['corte']+ '-'+ (o['cortes'].split('-').shift())) : null);
        $(contexto).trigger('star.midias.api');
        $.each(fs, function(index, file){
            if (index >= total)return false;
            api.Midias.sendFileToServer({
                'file': file,
                'progress': function (percent){
                    //$(contexto).trigger('progress.midias.api', [percent]);
                },
                'success': function (data){
                    if (!data.erro){
                        var img    = index + ':' + ((corte? corte + '/': '') + decodeURI(data['nome']));
                        files[img] = {
                            'ordem': index,
                            'data':  decodeURI(data['data']),
                            'size':  decodeURI(data['size']),
                            'etc':   decodeURI(data['etc']),
                            'corte': corte,
                            'nome':  decodeURI(data['nome']),
                            'img':   img
                        };

                    }else{
                        console.log(data);
                    }
                },
                'error': function (data) {
                    console.log(data);
                },
                'end': function(){
                    var inp = decodeURI(o['dados']).split(';'); inp.pop();
                    var result = api.Midias.difernciar(files, inp, contexto);
                    if(corte) {
                        var i = '';
                        $.each(result.inseridos, function (id, dados) {
                            i += '&inseridos[]=' + dados.value;
                        });
                        $.each(result.removidos, function (id, dados) {
                            i += '&removidos[]=' + dados.value;
                        });
                        $().jfbox({
                            carrega: api.Midias.urlCorte + '&m=' + action + '&socorte' + i,
                            position: 'maximized',
                            fermi: function (result){
                                console.log(result);
                                $(contexto).trigger('end.midias.api', [result]);
                            }
                        });
                    }else{
                        $(contesto).trigger('end.midias.api', [result]);
                    }
                }
            });
        });
    }



    $(function(){
        $('[data-api-midias][data-modo="repositorio"]').click(function(event){

            var self = $(this);
            var o = loadOptions(self);

            var contexto = o['contexto'];
            api.Midias.contesto = contexto;
            var carrega = api.Midias.urlRepositorio + '&m=' + o['action'];

            $(contexto).trigger('star.midias.api');

            $('input[ref="inseridos"]', contexto).each(function(){
                carrega += '&inseridos[]=' + escape($(this).val()).replace('/', '%2F');
            });
            $('input[ref="removidos"]', contexto).each(function(){
                carrega += '&removidos[]=' + escape($(this).val()).replace('/', '%2F');
            });
            $().jfbox({
                carrega: carrega,
                position: 'maximized',
                fermi: function (result){
                    console.log(result);
                    $(contexto).trigger('end.midias.api', [result]);
                }
            });

            event.preventDefault();
            event.stopPropagation();
        });
    });



    api.Midias._repositorio = function(){

        var self = $('#api_midias_repositorio');
        var contexto = api.Midias.contesto;
        var o = loadOptions(contexto);

        var area = $('#api_midias_files', self);
        o['selecionados'] = selecionados();


        /**************** celeciona um ou muitos arquivos ***********************/
        $(area).on('click', '.file', function(e){
            if(!$(this).hasClass('erro')){
                var file = $(this);
                if(!file.hasClass('celec')){
                    if(o['quant-max'] > 0 && o['selecionados'].length >= o['quant-max']){
                        o['selecionados'].each(function(index, element){
                            var novo = parseInt($(element).attr('data-ordem')) - 1;
                            if(novo <= 0) {
                                $(element).removeClass('celec').removeAttr('data-ordem');
                                o['selecionados'] = selecionados();
                            }else
                                $(element).attr({'data-ordem': novo});
                        });
                    }
                    file.attr({'data-ordem': o['selecionados'].length + 1}).addClass('celec');
                    o['selecionados'] = selecionados();
                }else{
                    var celeRemo = parseInt(file.attr('data-ordem'));
                    file.removeAttr('data-ordem').removeClass('celec');
                    o['selecionados'] = selecionados().each(function(index, element){
                        var novo = parseInt($(element).attr('data-ordem'));
                        $(element).attr({'data-ordem': (novo >= celeRemo? (--novo): novo)});
                    });
                }
                liberaBotao();
                preencheAmostra($(this));
            }
        });

        function selecionados(){
            return $('.file.celec', area);
        }

        function preencheAmostra(hover){
            var icone = $('#midias-arquivos-selecionadas .icones');
            var dados = $('#midias-arquivos-selecionadas .dados');
            dados.html('');
            icone.html('');
            var mostar = null;

            if(!hover)
                o['selecionados'].each(function (i, e){
                    if (mostar === null || parseInt(mostar.attr('data-ordem')) < parseInt($(e).attr('data-ordem')))
                        mostar = $(e);
                });
            else
                mostar = hover;

            if(mostar === null) return;

            mostar = mostar.clone().removeClass('celec');
            if(mostar !== null) icone.append(mostar);

            var tabela = $('<table>');

            tabela.append($('<tr>').append([
                $('<td>').html('Nome'),
                $('<td>').html(mostar.attr('data-nome'))
            ]));

            tabela.append($('<tr>').append([
                $('<td>').html('Tamanho'),
                $('<td>').html(api.Midias.dezenhaTamando(mostar.attr('data-size')))
            ]));

            var date = new Date();

            date.setTime(parseInt(mostar.attr('data-cria'))* 1000);
            tabela.append($('<tr>').append([
                $('<td>').html('Criado'),
                $('<td>').html(date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear())
            ]));

            date.setTime(parseInt(mostar.attr('data-data'))* 1000);
            tabela.append($('<tr>').append([
                $('<td>').html('Modificado'),
                $('<td>').html(date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear())
            ]));

            var dimencao = mostar.attr('data-dimencoes');
            if(dimencao)
            tabela.append($('<tr>').append([
                $('<td>').html('Dimenções'),
                $('<td>').html(dimencao)
            ]));

            dados.append(tabela);

        } preencheAmostra();


        function liberaBotao(){
            var length = o['selecionados'].length;
            if(length >= o['quant-start'] && length <= o['quant-total']){
                $('#midias-botao-proximo', self).prop('disabled', false);
                $('#midias-botao-encerrar', self).prop('disabled', false);
            }else{
                $('#midias-botao-proximo', self).prop('disabled', true);
                $('#midias-botao-encerrar', self).prop('disabled', true);
            }
        } liberaBotao();






        $('#api-midias-repositorio-upload').prop('disabled', false).click(function(){
            contexto.find('#api-midias-upload-input').click();
        });

        $('#api-midias-upload-input').change(function(event){

            var total = o['quant-max'];
            var tipos = o['tipos'];

            var eu = $(this); eu.prop('disable', true);
            $('#api-midias-repositorio-upload').prop('disable', true);

            contexto.attr('data-total-parcial', this.files.length);
            var content = contexto.find('.api-midias-content');
            content.html('');
            var multfiles = api.Midias.contesto.find('.api-midias-multfiles');
            multfiles.html(this.files.length + ' Arquivos');

            $.each(this.files, function(index, file){
                if(index >= total)return false;

                var etc  = file.name.split('.').pop().toLowerCase();
                var data = Math.round((new Date()).getTime() / 1000);

                if(tipos == null || tipos.indexOf(etc) >= 0){

                    var ref = api.Midias.iconeInput({
                        'data-data':  	data,
                        'data-size':  	0,
                        'data-etc':   	etc,
                        'data-corte': 	null,
                        'data-nome':  	file.name,
                        'img':		  	''
                    });

                    content.append(ref);

                    if(file.type.match('image.*')){
                        contesto.find('.api-midias-generico').hide();
                        contesto.find('.api-midias-img').show();
                        var reader = new FileReader();
                        reader.onload = function(f){
                            ref.find('.api-midias-img img').attr({src: f.target.result});
                        };
                        reader.onerror = function(){
                            ref.find('.api-midias-img img').attr({src: 'imagens/icones/doc_delete.png'});
                        };
                        reader.readAsDataURL(file);
                    }

                    api.Midias.sendFileToServer({
                        'file': file,
                        'progress': function (percent) {
                            ref.find('.api-midias-img .api-midias-barra-load').css({width: percent + '%'});
                        },
                        'success': function (data) {
                            if (!data.erro) {
                                ref.find('.api-midias-img .api-midias-barra-load').hide();
                                $(ref).attr({
                                    'data-ordem': index,
                                    'data-data':  decodeURI(data['data']),
                                    'data-size':  decodeURI(data['size']),
                                    'data-etc':   decodeURI(data['etc']),
                                    'data-nome':  decodeURI(data['nome'])
                                });
                                ref.find('.api-midias-name').html(decodeURI(data['nome']));
                                ref.find('.api-midias-dados').html('Tamanho: '+ api.Midias.dezenhaTamando(parseInt(decodeURI(data['size']))));
                            } else {
                                console.log(data);
                                $(ref).addClass('erro').attr({
                                    'data-erro': decodeURI(data.msg),
                                });
                                ref.find('.api-midias-name').html(decodeURI(data.msg));
                            }
                        },
                        'error': function (data) {
                            console.log(data);
                            ref.find('.api-midias-name').html('error');
                        },
                        'end': function(){
                            api.Midias.inputsProcessa(contesto);
                            eu.prop('disable', false);
                            contesto.find('.api-midias-botoes .api-midias-upload').prop('disable', false);
                        }
                    });
                }
            });
            event.stopPropagation();
            event.preventDefault();
            return false;
        });


    };

})(jQuery, document, window);
