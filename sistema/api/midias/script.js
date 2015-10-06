;if(typeof api === 'undefined')api={};

api.Midias = {};
api.Midias.sendFileToServer = function(settings){
	if(settings && api.Midias.sendFiles >= 5){
		api.Midias.sendFilesBuffer.push(settings);
	}else{
		if(!settings && api.Midias.sendFilesBuffer.length > 0)
			settings = api.Midias.sendFilesBuffer.shift();
		if(settings){
			api.Midias.sendFiles += 1;
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
				url: 'api/midias/upload.php?m=' + api.Midias.contesto.attr('data-action'),
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
		else api.Midias.sendFileToServer();
	}
};
api.Midias.dezenhaInput = function(files){
	var length = Object.keys(files).length;
	var ordem = Object.keys(files).sort();
	api.Midias.contesto.attr('data-total-parcial', length);
	var content = api.Midias.contesto.find('.api-midias-content');
	var multfiles = api.Midias.contesto.find('.api-midias-multfiles');
	content.html('');
	multfiles.html(length + ' Arquivos');
	for(var id in ordem)(function(id, file){
		content.append(api.Midias.iconeInput(file));
	})(id, files[ordem[id]]);
	if(api.Midias.contesto.hasClass('api-midias-grande') || length == 1)content.show(); else content.hide();
	if(api.Midias.contesto.hasClass('api-midias-pequeno') && length != 1)multfiles.show(); else multfiles.hide();
};
api.Midias.iconeInput = function(file){
	var img = file.img;
	var path = img.split('/');
	img = path.pop();
	path = path.join('/');
	delete file.img;
	file.class = 'api-midias-file';
	return $('<div>', file).append([
		$('<div>', {class: 'api-midias-icone'}).append([
			$('<div>', {class: 'api-midias-img', css: {'display': 'none'}}).append([
				$('<img>', {'src': path + '/' + (file['data-corte']? file['data-corte'] + '/': '') + img}),
			]),
			$('<div>', {class: 'api-midias-generico'}).append([
				$('<i>', {'class': 'fa fa-file'}),
				$('<div>', {class: 'api-midias-etc'}).html(file['data-etc'])
			]),
			$('<div>', {class: 'api-midias-barra-load'})
		]),
		$('<span>', {class: 'api-midias-name'}).html(file['data-nome']),$('<br>',   {class: 'api-midias-br-nome'}),
		$('<span>', {class: 'api-midias-dados'}).html((file['data-size'] !== undefined? 'Tamanho: '+ api.Midias.dezenhaTamando(file['data-size']): ''))
	]);
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
api.Midias.inputsProcessa = function(contesto){
	contesto = contesto || api.Midias.contesto;
	var fotos = {};
	contesto.find('.api-midias-file').each(function(index, val){
		val = $(val);
		fotos[(index+ ':'+ (!!val.attr('data-corte')? val.attr('data-corte') + '/': '') + val.attr('data-nome'))] = {
			'data-ordem': index,
			'data-data':  val.attr('data-data'),
			'data-size':  val.attr('data-size'),
			'data-etc':   val.attr('data-etc'),
			'data-corte': val.attr('data-corte'),
			'data-nome':  val.attr('data-nome'),
			'img':		  val.find('.img-ico').attr('src')
		};
	});
	var inp = decodeURI(contesto.attr('data-dados')).split(';'); inp.pop();
	api.Midias.atualizaInputs(api.Midias.difernciar(fotos, inp, contesto), contesto);
};
api.Midias.difernciar = function(itens, lista, contesto){
	contesto = contesto || api.Midias.contesto;
	var i, inseridos = [], removidos = [], name = contesto.attr('data-api-midias');
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
api.Midias.redimencionar = function(dimensoesUm, dimensoesDois, tipo){ 

	tipo = (tipo === undefined? 'p': tipo);
	dimensoesUm.proporcao = (dimensoesUm.proporcao === undefined? dimensoesUm.width / dimensoesUm.height : dimensoesUm.proporcao);
	dimensoesDois.proporcao = (dimensoesDois.proporcao === undefined? dimensoesDois.width / dimensoesDois.height : dimensoesDois.proporcao);
	var f = {};
	var proprocao = 0;

	switch (tipo){

		case 'c':

			if(dimensoesUm.proporcao > dimensoesDois.proporcao){
				proprocao = dimensoesDois.height / dimensoesUm.height;
			}else{
				proprocao = dimensoesDois.width / dimensoesUm.width;
			}

			f.width =		Math.round(dimensoesUm.width * proprocao);
			f.height =		Math.round(dimensoesUm.height * proprocao);
			f.proporcao =	f.width / f.height;
			f.top =			Math.round(-((f.height - dimensoesDois.height) / 2));
			f.left =		Math.round(-((f.width - dimensoesDois.width) / 2));

			break;

		case 'a':

			f.width =		dimensoesDois.width;
			f.height =		dimensoesDois.height;
			f.proporcao =	dimensoesDois.proporcao;
			f.top =			0;
			f.left =		0;

			break;

		default:
		case 'r':
		case 'p':
		case 'o':

			if(dimensoesUm.proporcao < dimensoesDois.proporcao){
				proprocao = dimensoesDois.height / dimensoesUm.height;
			}else{
				proprocao = dimensoesDois.width / dimensoesUm.width;
			}

			f.width =		Math.round(dimensoesUm.width * proprocao);
			f.height =		Math.round(dimensoesUm.height * proprocao);
			f.proporcao =	f.width / f.height;
			f.top =			Math.round(-((f.height - dimensoesDois.height) / 2));
			f.left =		Math.round(-((f.width - dimensoesDois.width) / 2));

			break;
	}
	return f;
};
api.Midias.contesto = null;
api.Midias.sendFiles = 0;
api.Midias.sendFilesBuffer = [];

(function($, d, w){
	
	$(function(){

		$('.api-midias .api-midias-botoes .api-midias-upload').prop('disabled', false).click(function(){
			var contesto = $(this).closest('.api-midias');
			api.Midias.contesto = contesto;
			contesto.find('.api-midias-input-file').click();
		});

		$('.api-midias .api-midias-input-file').change(function(event){

			var contesto = api.Midias.contesto;
			var total = parseInt(contesto.attr('data-quant-total'));
			var tipos = (api.Midias.contesto.attr('data-tipos').length == 0? null: api.Midias.contesto.attr('data-tipos').split(' '));

			var eu = $(this);
			eu.prop('disable', true);
			api.Midias.contesto.find('.api-midias-botoes .api-midias-upload').prop('disable', true);

			api.Midias.contesto.attr('data-total-parcial', this.files.length);
			var content = api.Midias.contesto.find('.api-midias-content');
			content.html('');
			var multfiles = api.Midias.contesto.find('.api-midias-multfiles');
			multfiles.html(this.files.length+ ' Arquivos');

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

		$('.api-midias .api-midias-botoes .api-midias-servidor').prop('disabled', false).click(function(){
			var contesto = $(this).closest('.api-midias');
			api.Midias.contesto = contesto;
			var carrega = 'api/midias/midias.php?m='+ contesto.attr('data-action');
			$('input[ref="inseridos"]', contesto).each(function(){
				carrega += '&inseridos[]=' + escape($(this).val()).replace('/', '%2F');
			});
			$('input[ref="removidos"]', contesto).each(function(){
				carrega += '&removidos[]=' + escape($(this).val()).replace('/', '%2F');
			});
			$().jfbox({
				carrega: carrega,
				position: 'maximized'
			});
		});

		$('[data-api-midias]:not(.api-midias)').prepend(
			$('<input>', {type: 'file'}).css({
				display: 'none'
			})
		).click(function(){
			api.Midias.contesto = $(this);
			api.Midias.contesto.find('input[type="file"]').click();
		});

		$('[data-api-midias]:not(.api-midias) input[type="file"]').click(function(event){
			event.stopPropagation();
		}).change(function(event){
			var contesto = api.Midias.contesto;
			var total = parseInt(contesto.attr('data-quant-total'));
			var action = contesto.attr('data-action');
			var files = {};
			var corte  = (contesto.attr('data-corte') && contesto.attr('data-cortes')? (contesto.attr('data-corte')+ '-'+ contesto.attr('data-cortes').split('-').shift()) : null);
			$.each(this.files, function(index, file){
				if (index >= total)return false;
				api.Midias.sendFileToServer({
					'file': file,
					'progress': function (percent){},
					'success': function (data){
						if (!data.erro){
							var img    = index+ ':'+ ((corte? corte + '/': '')+ decodeURI(data['nome']));
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
						var inp = decodeURI(contesto.attr('data-dados')).split(';'); inp.pop();
						var result = api.Midias.difernciar(files, inp, contesto);
						if(corte) {
							var i = '';
							$.each(result.inseridos, function (id, dados) {
								i += '&inseridos[]=' + dados.value;
							});
							$.each(result.removidos, function (id, dados) {
								i += '&removidos[]=' + dados.value;
							});
							$().jfbox({
								carrega: 'api/midias/cortar.php?m=' + action + '&socorte' + i,
								position: 'maximized',
								fermi: function (result) {
									console.log(result);
									$(contesto).trigger('end.midias.api', result);
								}
							});
						}else{
							$(contesto).trigger('end.midias.api', result);
						}
					}
				});
			});
			event.stopPropagation();
			event.preventDefault();
			return false;
		});

	});
	
	$.fn.midias = function(){
	
		return this.each(function(){
			
			var self = this;
			var area = $('#api_midias_files', this);
			var msg = $('.solte-aqui', area);
			var totalCele = $('.file[data-ordem]', self).length;
			var action = $(this).attr('data-action');
			var c = $(this).attr('data-corte').split('-');
			var corte = {};
			corte.width = parseInt(c[0]);
			corte.height = parseInt(c[1]);
			corte.proporcao = corte.width / corte.height;
			var icone = api.Midias.redimencionar(corte, {width: 100, height: 100}, 'p');
			var crop = null;
			var quantStart = parseInt($(self).attr('data-quant-start'));
			var quantLength = parseInt($(self).attr('data-quant-length'));
			var maxCele = (quantLength == 0? 0 :(quantStart + quantLength));
			var tipos = ($(self).attr('data-tipos').length == 0? null: $(self).attr('data-tipos').split(' '));
			var pagina = $(self).attr('data-pagina');
			var deletar = null;
			var name = $(self).attr('data-name');
			var cortes = $(self).attr('data-cortes').split('-');
			
			//adiciona o evento de arartar arquivos na area
			area.on('dragenter', function (e){
				e.stopPropagation();
				e.preventDefault();
				msg.fadeIn(250);
			}).on('dragover', function (e){
				e.stopPropagation();
				e.preventDefault();
			}).on('drop', function (e){
				msg.fadeOut(250);
				e.preventDefault();
				handleFileUpload(e.originalEvent.dataTransfer.files);
			});

			//caso o arastar volte no documento some a mensagem
			$(d).on('dragenter', function (e){
				e.stopPropagation();
				e.preventDefault();
			}).on('dragover', function (e){
				e.stopPropagation();
				e.preventDefault();
				msg.fadeOut(250);
			}).on('drop', function (e){
				e.stopPropagation();
				e.preventDefault();
			});
			
			msg.click(function(){
				msg.fadeOut(250);
			});
			
			/************* clicar no botao uploa abra a tela de upload *********/
			$('.upload button', this).click(function(){
				$('#upload-input', self).click();
				return false;
			});
			
			/************* clicar no botao uploa abra a tela de upload *********/
			$('#upload-input', self).change(function(event){
				//console.log(typeof this.files);
				handleFileUpload(this.files);
				event.stopPropagation();
				event.preventDefault();
				return false;
			});
			
			$('#midias-form-topo', this).submit(function(e){
				e.stopPropagation();
				e.preventDefault();
				return false;
			});
			
			/**************** celeciona um ou muitos arquivos ***********************/
			$(area).on('click', '.file .mark', function(e){
				if(!$(this).hasClass('erro')){
					var file = $(this).closest('.file');
					if(!$(this).hasClass('celec')){
						if(maxCele <= 0 || totalCele < maxCele){
							totalCele++;
						}else{
							$('.file[data-ordem]', self).each(function(index, element){
								var novo = parseInt($(element).attr('data-ordem'));
								novo--;
								if(novo <= 0){
									$('.mark', element).removeClass('celec');
									$(element).removeAttr('data-ordem');
								}else{
									$(element).attr({'data-ordem': novo});
								}
							});
						}
						$(file).attr({'data-ordem': totalCele});
						$(this).addClass('celec');
					}else{
						totalCele--;
						var celeRemo = parseInt($(file).attr('data-ordem'));
						$(file).removeAttr('data-ordem');
						$(this).removeClass('celec');
						$('.file[data-ordem]', self).each(function(index, element){
							var novo = parseInt($(element).attr('data-ordem'));
							if(novo >= celeRemo){
								novo--;
								$(element).attr({'data-ordem': novo});
							}
						});
					}
					liberaBotao();
					preencheAmostra();
				}
			});
			
			/**************** celeciona um ou muitos arquivos ***********************/
			$(area).on('click', '.file .mark .deletar', function(e){
				e.stopPropagation();
				e.preventDefault();
				$('#midias-msg-del', self).show();
				deletar = $(this).closest('.file');
			});
			
			$('#midias-msg-del button.canselar', self).click(function(e){
				deletar = null;
				$('#midias-msg-del', self).hide();
			});
			
			$('#midias-msg-del button.apagar', self).click(function(e){
				console.log($.getJSON('api/midias/deletar.php?m='+ action + '&ap=' + escape(deletar.attr('data-nome'))));
				$(deletar).remove();
				deletar = null;
				$('#midias-msg-del', self).hide();
				liberaBotao();
				preencheAmostra();
			});
			
			/******************** filtra os aquivos ********************/
			$('#midias-pesquisa').keyup(function(){
				var pesq = $(this).val().toLowerCase();


				if(pesq.search(/^\*/) >= 0){

					$('#api_midias_files .file', self).hide();

					/**************** reordenar por data **************/
					if (pesq == '*data=asc' || pesq == '*data=desc') {
						var datas = [];
						$('#api_midias_files .file', self).each(function (index, file) {
							var data_nova = parseInt($(file).attr('data-data'));
							if (datas.length <= 0) {
								datas.splice(0, 0, {'data': data_nova, 'element': file});
							} else {
								for (var i = (datas.length - 1); i >= 0; i--) {
									if (data_nova >= datas[i].data){
										datas.splice((i + 1), 0, {'data': data_nova, 'element': file}); break;
									} else if (i === 0) {
										datas.splice(0, 0, {'data': data_nova, 'element': file});
									}
								}
							}
						});

						if (pesq == '*data=asc')
							reordenarUp(datas);

						else if (pesq == '*data=desc')
							reordenarDown(datas);


						/**************** reordenar por nome **************/
					} else if (pesq == '*nome=asc' || pesq == '*nome=desc') {
						var nomes = [];
						$('#api_midias_files .file', self).each(function (index, file) {
							var nome_novo = $(file).attr('data-nome').toLowerCase();
							if (nomes.length <= 0) {
								nomes.splice(0, 0, {'nome': nome_novo, 'element': file});
							} else {
								for (var i = (nomes.length - 1); i >= 0; i--) {
									if (nome_novo >= nomes[i].nome) {
										nomes.splice((i + 1), 0, {'nome': nome_novo, 'element': file}); break;
									} else if (i === 0) {
										nomes.splice(0, 0, {'nome': nome_novo, 'element': file});
									}
								}
							}
						});

						if (pesq == '*nome=asc')
							reordenarUp(nomes);

						else if (pesq == '*nome=desc')
							reordenarDown(nomes);

					}

				/************ filtra por nome ************/
				}else if (pesq.length) {
					$('#api_midias_files .file', self).each(function (index, file) {
						if (!$(file).attr('data-nome').toLowerCase().match(pesq)) {
							$(file).hide();
						} else {
							$(file).show();
						}
					});


					/***************** mostrar tudo novamente ************/
				} else
					$('#api_midias_files .file', self).show();
			
			});

			function reordenarUp(lista){
				lista.reverse();
				$(lista).each(function(indes, cele){
					$('#api_midias_files .files', self).prepend($(cele.element).show());
				});
			}
			
			function reordenarDown(lista){
				$(lista).each(function(indes, cele){
					$('#api_midias_files .files', self).prepend($(cele.element).show());
				});
			}

			$('#midias-botao-anterior', self).click(function(){
				if(!$(this).prop('disabled')){
					$().jfbox({
						carrega: 'api/midias/midias.php?m='+ action + (function(){
							var i = '';
							$('#api-midias-icosCortardos .file', self).each(function(){
								i += '&corte[' + encodeURI($(this).attr('data-nome')) + ']=' + encodeURI($(this).attr('data-corte'));
							});
							return i;
						})(),
						position: 'maximized'
					});
				}
			});

			$('#midias-botao-encerrar, #midias-botao-proximo', self).click(function(){
				if(!$(this).prop('disabled')){
					var botao = this;
					var fotos = {};
					var arfot = [];
					$(($(botao).hasClass('arquivos')? '#api_midias_files .file[data-ordem]' :(
					$(botao).hasClass('cortes')? '#api-midias-icosCortardos .file' : '')),
					self).each(function(index, val){
						val = $(val);
						var value = (($(botao).hasClass('comCortes') && val.attr('data-corte') != undefined)? val.attr('data-corte') + '/': '')+ val.attr('data-nome');
						arfot[(parseInt(val.attr('data-ordem')) - 1)] = {
							'data-ordem': val.attr('data-ordem'),
							'data-data':  val.attr('data-data'),
							'data-size':  val.attr('data-size'),
							'data-etc':   val.attr('data-etc'),
							'data-corte': val.attr('data-corte'),
							'data-nome':  val.attr('data-nome'),
							'img':		  val.find('.img-ico').attr('src'),
							'value':	  value
						};
					});
					$.each(arfot, function(id, valeu){var i = (id+ ':'+ valeu.value); fotos[i] = valeu; delete fotos[i].value;});
					var inp = decodeURI($(self).attr('data-dados')).split(';'); inp.pop();
					result = api.Midias.difernciar(fotos, inp);
					if($(this).hasClass('fim')) {
						api.Midias.dezenhaInput(fotos);
						api.Midias.atualizaInputs(result);
						fechaJfbox();
					}else if($(this).hasClass('socorte')){
						fechaJfbox(true, result);
					}else{
						var i = '';
						$.each(result.inseridos, function(id, dados){
							i += '&inseridos[]=' + dados.value;
						});
						$.each(result.removidos, function(id, dados){
							i += '&removidos[]=' + dados.value;
						});
						console.log(i);
						$().jfbox({
							carrega: 'api/midias/cortar.php?m='+ action + i,
							position: 'maximized'
						});
					}
				}
			});
			
			$('#midias-botao-cancelar', self).click(function(){
				fechaJfbox();
			});
			
			$('#api-midias-icosCortardos, #api-midias-icosToCortar', self).scroll(function(){
				$('#api-midias-icosCortardos, #api-midias-icosToCortar', self).scrollTop(
					$(this).scrollTop()
				);
			});
			
			$('#api-midias-icosToCortar, #api-midias-icosCortardos', self).on('click', '.file .mark', function(e){
				var nome = $(this).closest('.file').attr('data-nome');
				setImgToCorte(nome);
			});
			
			
			/**
			 * Configura uma imagem para cote
			 * @param {string} nome Nome da imagen a ser cortada
			 */
			function setImgToCorte(nome){
				setTimeout(function(){
					var box = $('#api-midias-areaCorte .area-de-corte', self);
					box.width = $(box).width();
					box.height = $(box).height();
					console.log($(box).width(), $(box).height());

					if($('img[data-nome]', box).attr('data-nome') == nome)
						return false;

					var eu = $('#api-midias-icosCortardos .file[data-nome="' + nome + '"]', self);
					var euImg = $('.img-ico', eu);
					var src = $(euImg).attr('src');
					var img = new Image();
					img.onload = function(){
						var fim = api.Midias.redimencionar(img, box, 'p');
						var nova = $('<img>', {id: 'midias-imgToCorte', 'data-nome': nome, src: src});
						box.find('.imgToCorte').css({width: fim.width, height: fim.height}).html(nova);
						setCorteToImg();
					};
					img.src = src;
				}, 2);
			}
			setImgToCorte($('#api-midias-icosToCortar .file').eq(0).attr('data-nome'));
			
			$('#midias-selec-corte', self).change(function(){
				setCorteToImg($(this).val());
			});
			
			$('#midias-auxilio-relacionado', self).change(function(){
				if(self.crop !== null){
					if($(this).prop("checked")){
						self.crop.setOptions({
							aspectRatio: icone.proporcao
						});
					}else{
						self.crop.setOptions({
							aspectRatio: 0
						});
					}
				}
			});
			
			/**
			 * configura um corte na imagem selecionada e acerta a miniatura dela.
			 * corte = ['c', 'o', 'm', 'p', 'r', 'a'];
			 * 
			 * @param {string} corteTipo O corte a ser configura, caso nao passe ele carrega o configurado para imagem
			 * @returns {Boolean} flase se nao ouver img configurada.
			 */
			function setCorteToImg(corteTipo){
				
				var imgRef = $('#api-midias-areaCorte .area-de-corte img[data-nome]', self);
				if(imgRef.length <= 0) return false;
				
				imgRef.width = imgRef.width();
				imgRef.height = imgRef.height();
				
				var src = $(imgRef).attr('src');
				
				var img = new Image();
				img.onload = function(){

					var nome = imgRef.attr('data-nome');
					var eu = $('#api-midias-icosCortardos .file[data-nome="' + nome + '"]', self);
					var ico = $('.img-ico', eu);
					var base = $('.base-corte', eu);

					if((['c', 'o', 'm', 'p', 'r', 'a'].indexOf(corteTipo)) < 0)
						corteTipo = eu.attr('data-corte').split('-').pop();

					if(corteTipo === undefined)
						corteTipo = cortes[0];
					
					$('#midias-selec-corte', self).val(corteTipo).prop('disabled', false);
					$('.midias-comandos-axiliar', self).hide();
					$('.midias-comandos-axiliar[data-axilio="' + corteTipo + '"]', self).show().find('input[type="checkbox"]').each(function(){
						$(this).prop('checked', ($(this).attr('data-defalt') == 'true'? true: false));
					});

					switch (corteTipo){
						default :
						case 'c':
						case 'r':
						case 'a':
						case 'p':
						case 'o':

							var f = api.Midias.redimencionar(img, icone, corteTipo);
							
							if(corteTipo == 'p' || corteTipo == 'r'){
								f.top = 0;
								f.left = 0;
								$(base).css({width: f.width, height: f.height});
							}else{
								$(base).css({width: icone.width, height: icone.height});
							}
							$(eu).attr({'data-corte': corte.width + '-' + corte.height + '-' + corteTipo});
							$(ico).css(f);

							if(self.crop != null){
								self.crop.destroy();
								self.crop = null;
							}

						break;

						case 'm':

							if(crop === null){
								
								function showPreview(coords){
									
									var rx = icone.width / coords.w;
									var ry = icone.height / coords.h;

									eu.attr({
										'data-corte': (
											corte.width + '-' + 
											corte.height + '-' + 
											Math.round((coords.x / imgRef.width) * img.width) + '-' + 
											Math.round((coords.y / imgRef.height) * img.height) + '-' + 
											Math.round((coords.w / imgRef.width) * img.width)  + '-' + 
											Math.round((coords.h / imgRef.height) * img.height) + '-' + 
											'm'
										)
									});

									$(ico).css({
										width: Math.round(rx * imgRef.width),
										height: Math.round(ry * imgRef.height),
										left: -(Math.round(rx * coords.x)),
										top: -(Math.round(ry * coords.y))
									});
									
								}

								self.crop = $.Jcrop('#midias-imgToCorte');
								self.crop.setOptions({
									onChange: showPreview,
									onSelect: showPreview,
									bgColor: 'transparent',
									boxWidth: imgRef.width,
									boxHeight: imgRef.height
								});
								self.crop.release();

								if(eu.attr('data-corte').split('-').pop() == 'm'){
									var c = eu.attr('data-corte').split('-');
									c[2] = parseInt(c[2]);
									c[3] = parseInt(c[3]);
									c[4] = parseInt(c[4]);
									c[5] = parseInt(c[5]);
									var p = [];
									p[0] = Math.round((c[2] / img.width) * imgRef.width);
									p[1] = Math.round((c[3] / img.height) * imgRef.height);
									p[2] = Math.round(((c[2] + c[4]) / img.width) * imgRef.width);
									p[3] = Math.round(((c[3] + c[5]) / img.height) * imgRef.height);
									self.crop.setSelect(p);
								}else{
									var f = api.Midias.redimencionar(img, icone, 'a');
									$(eu).attr({'data-corte': corte.width + '-' + corte.height + '-' + 'a'});
									$(ico).css(f);
								}
								$(base).css({width: icone.width, height: icone.height});

							}

						break;
					}
				};
				img.src = src;
				
			}

			$('#api-midias-icosCortardos .file').each(function(index, file){
				var ico = $('.img-ico', file);
				var src = $(ico).attr('src');
				var mark = $('.mark', file);
				var cor = 'c';
				var img = new Image();
				img.onload = function(){

					var cte = $(file).attr('data-corte');
					if(cte != undefined){
						var c = $(file).attr('data-corte').split('-');
						cte = c.pop();
						if(cte == 'c' || cte == 'p' || cte == 'o' || cte == 'r' || cte == 'a'){
							var f = api.Midias.redimencionar(img, icone, cte);
						}else{
							c[2] = parseInt(c[2]);
							c[3] = parseInt(c[3]);
							c[4] = parseInt(c[4]);
							c[5] = parseInt(c[5]);
							var rx = icone.width / c[4];
							var ry = icone.height / c[5];
							var f = {
								width:	  Math.round(rx * img.width),
								height:   Math.round(ry * img.height),
								left:	-(Math.round(rx * c[2])),
								top:	-(Math.round(ry * c[3]))
							};
						}
					}else{
						var f = api.Midias.redimencionar(img, icone, cor);
						$(file).attr({'data-corte': corte.width + '-' + corte.height + '-' + cor});
					}
					
					var width, height;
					
					if(cte == 'p' || cte == 'r'){
						f.top = 0;
						f.left = 0;
						width = f.width;
						height = f.height;
					}else{
						width = icone.width;
						height = icone.height;
					}
					
					$(mark).prepend([
						$('<div>', {class: 'base-corte'}).css({width: width, height: height}).html(
							$(ico).css(f)
						)
					]);
					$('.load', mark).hide();
				};
				img.src = src;
			});

			function liberaBotao(){
				var length = $('.file[data-ordem]', self).length;
				if((pagina == 'midias' && (length >= quantStart && length <= (quantStart + quantLength)))
				|| (pagina == 'corte')
				){
					$('#midias-botao-proximo', self).prop('disabled', false);
					$('#midias-botao-encerrar', self).prop('disabled', false);
				}else{
					$('#midias-botao-proximo', self).prop('disabled', true);
					$('#midias-botao-encerrar', self).prop('disabled', true);
				}
			}
			liberaBotao();
			
			function preencheAmostra(){
				var fotos = [];
				$('#api_midias_files .file[data-ordem]', self).each(function(index, val){
					var id = (parseInt($(val).attr('data-ordem')) - 1);
					var img = $('<img>', {class: $('img.img-ico, img.img-sem', val).attr('class'), src:  $('img.img-ico, img.img-sem', val).attr('src')});
					fotos[id] = img;
				});
				var ref = [];
				for(var i = 0, t = (fotos.length > 3? 3: fotos.length); i < t; i++){
					ref.push(
						$('<div>', {class: 'file p-' + i}).append([
							$('<div>', {class: 'ico'}).append([
								$('<div>', {class: 'pos'}).append([
									$('<span>', {class: 'mark'}).append([
										fotos[i]
									])
								])
							])
						])
					);
					//console.log(ref, fotos[i]);
				}
				$('#midias-arquivos-selecionadas .icones').html('').append(ref);
				$('#midias-arquivos-selecionadas .total').html((fotos.length <= 0? '': (fotos.length == 1? '1 arquivo': fotos.length + ' arquivos')));
			}
			preencheAmostra();

			function handleFileUpload(files){

				$(files).each(function(index, file){
					
					var etc  = file.name.split('.').pop().toLowerCase();
					var data = Math.round((new Date()).getTime() / 1000);
					
					if(tipos == null || tipos.indexOf(etc) >= 0){
						var ref = 
						$('<div>', {class: 'file', 'data-data': data, 'data-size': file.size, 'data-etc': etc, 'data-nome': file.name}).append([
							$('<div>', {class: 'ico'}).append([
								$('<div>', {class: 'pos'}).append([
									$('<span>', {class: 'mark'}).append([
										(file.type.match('image.*')?
											$('<img>', {class: 'img-ico', src: 'api/navigi/img/ico.png'})
										:
											$('<img>', {class: 'img-sem', src: 'api/navigi/img/ico.png'})),
										$('<span>', {class: 'api-midias-barra-load'}),
										$('<span>', {class: 'checkbox'}),
										$('<span>', {class: 'erro'})
									])
								])
							]),
							$('<div>', {class: 'nome'}).append([
								file.name
							])
						]);

						$('#api_midias_files .files', self).prepend(ref);

						if(file.type.match('image.*'))
							desenharIcone(file, $('.img-ico', ref));

						api.Midias.sendFileToServer({
							'file': file,
							'progress': function (percent) {
								$('.api-midias-barra-load', ref).css({width: percent + '%'});
							},
							'success': function (data) {
								if (!data.erro) {
									$('.barra-load', ref).hide();
									$(ref).attr({
										'data-data': decodeURI(data.data),
										'data-size': decodeURI(data.size),
										'data-etc': decodeURI(data.etc),
										'data-nome': decodeURI(data.nome)
									});
									$('.nome', ref).html(decodeURI(data.nome));
								} else {
									console.log(data);
									$(ref).addClass('erro').attr({
										'data-erro': decodeURI(data.msg),
									});
									$('.nome', ref).html(decodeURI(data.msg));
								}
							},
							'error': function (data) {
								console.log(data);
								$('.nome', ref).html('ERROR');
							},
							'end': liberaBotao
						});
					}
				});
			}
			
			function desenharIcone(file, ico){
				
				var desIco = this;
				desIco.fila = (desIco.fila == undefined? []: desIco.fila);
				
				//console.log(desIco.ocupado, this.fila);
				
				if(desIco.ocupado == true){
					desIco.fila.push({file: file, ico: ico});
				}else{
					desIco.ocupado = true;
					if(file == undefined && desIco.fila.length > 0){
						var a = desIco.fila.shift();
						file = a.file;
						ico  = a.ico;
					}
					if(file != undefined){
						var reader = new FileReader();
						reader.onload = function(f){
							$(ico).attr({src: f.target.result});
							desIco.ocupado = undefined;
							desenharIcone();
						};
						reader.onerror = function(f){
							$(ico).attr({src: 'imagens/icones/doc_delete.png'});
							desIco.ocupado = undefined;
							desenharIcone();
						};
						reader.readAsDataURL(file);
					}else{
						desIco.ocupado = undefined;
					}
				}
			}

		});
		
	};
	
})(jQuery, document, window);