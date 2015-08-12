;	apiMidias = {};
	apiMidias.contesto = null;

(function(jQuery, d, w){
	
	$(function(){
		$('.api-midias .botoes button, .api-midias input.div').click(function(){
			var contexto = $(this).closest('.api-midias');
			apiMidias.contesto = contexto;
			var carrega = contexto.attr('data-action');
			var a = '';
			$('input[ref="inseridos"]', contexto).each(function(){
				a += '&inseridos[]=' + escape($(this).val()).replace('/', '%2F');
			});
			$('input[ref="removidos"]', contexto).each(function(){
				a += '&removidos[]=' + escape($(this).val()).replace('/', '%2F');
			});
			$().jfbox({
				carrega: carrega + a,
				position: 'maximized'
			});
		});
	});
	
	$.fn.midias = function(){
	
		return this.each(function(){
			
			var self = this;
			var area = $('#api_midias_files', this);
			var msg = $('.solte-aqui', area);
			var totalCele = $('.file[data-cele-ord]', self).length;
			var action = $(this).attr('data-action');
			var c = $(this).attr('data-corte').split('-');
			var corte = {};
			corte.width = parseInt(c[0]);
			corte.height = parseInt(c[1]);
			corte.proporcao = corte.width / corte.height;
			var icone = redimencionar(corte, {width: 100, height: 100}, 'p');
			var crop = null;
			var quantStart = parseInt($(self).attr('data-quant-start'));
			var quantLength = parseInt($(self).attr('data-quant-length'));
			var maxCele = (quantLength == 0? 0 :(quantStart + quantLength));
			var tipos = $(self).attr('data-tipos').split(' ');
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
							$('.file[data-cele-ord]', self).each(function(index, element){
								var novo = parseInt($(element).attr('data-cele-ord'));
								novo--;
								if(novo <= 0){
									$('.mark', element).removeClass('celec');
									$(element).removeAttr('data-cele-ord');
								}else{
									$(element).attr({'data-cele-ord': novo});
								}
							});
						}
						$(file).attr({'data-cele-ord': totalCele});
						$(this).addClass('celec');
					}else{
						totalCele--;
						var celeRemo = parseInt($(file).attr('data-cele-ord'));
						$(file).removeAttr('data-cele-ord');
						$(this).removeClass('celec');
						$('.file[data-cele-ord]', self).each(function(index, element){
							var novo = parseInt($(element).attr('data-cele-ord'));
							if(novo >= celeRemo){
								novo--;
								$(element).attr({'data-cele-ord': novo});
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
				$.getJSON('api/midias/deletar.php?m='+ action + '&ap=' + escape(deletar.attr('data-nome')));
				$(deletar).remove();
				deletar = null;
				$('#midias-msg-del', self).hide();
				liberaBotao();
				preencheAmostra();
			});
			
			/******************** filtra os aquivos ********************/
			$('#midias-pesquisa').keyup(function(){
				var pesq = $(this).val().toLowerCase();



				/**************** reordenar por data **************/
				if(pesq == '*data=asc' || pesq == '*data=desc'){
					var datas = [];
					$('.file', self).each(function (index, file){
						var data_nova = parseInt($(file).attr('data-data'));
						if(datas.length <= 0){
							datas.splice(0, 0, {'data': data_nova, 'element': file});
						}else{
							for(var i = (datas.length - 1); i >= 0; i--){
								if(data_nova >= datas[i].data){
									datas.splice((i + 1), 0, {'data': data_nova, 'element': file});
									break;
								}else if(i === 0){
									datas.splice(0, 0, {'data': data_nova, 'element': file});
								}
							}
						}
					});
					
					if(pesq == '*data=asc')
						reordenarUp(datas);
						
					else if(pesq == '*data=desc')
						reordenarDown(datas);



				/**************** reordenar por nome **************/
				}else if(pesq == '*nome=asc' || pesq == '*nome=desc'){
					var nomes = [];
					$('.file', self).each(function (index, file){
						var nome_novo = $(file).attr('data-nome').toLowerCase();
						if(nomes.length <= 0){
							nomes.splice(0, 0, {'nome': nome_novo, 'element': file});
						}else{
							for(var i = (nomes.length - 1); i >= 0; i--){
								if(nome_novo >= nomes[i].nome){
									nomes.splice((i + 1), 0, {'nome': nome_novo, 'element': file});
									break;
								}else if(i === 0){
									nomes.splice(0, 0, {'nome': nome_novo, 'element': file});
								}
							}
						}
					});
					
					if(pesq == '*nome=asc')
						reordenarUp(nomes);
						
					else if(pesq == '*nome=desc')
						reordenarDown(nomes);



				/************ filtra por nome ************/
				}else if(pesq.length){
					$('.file', self).each(function (index, file){
						if(!$(file).attr('data-nome').toLowerCase().match(pesq)){
							$(file).hide();
						}else{
							$(file).show();
						}
					});



				/***************** mostrar tudo novamente ************/
				}else
					$('.file', self).show();
			
			});
			
			function reordenarUp(lista){
				$(lista).each(function(indes, cele){
					$('.files', self).append(cele.element);
				});
			}
			
			function reordenarDown(lista){
				$(lista).each(function(indes, cele){
					$('.files', self).prepend(cele.element);
				});
			}

			$('#midias-botao-anterior', self).click(function(){
				var i = '';
				$('#api-midias-icosCortardos .file', self).each(function(index){
					i += '&corte[' + escape($(this).attr('data-nome')) + ']=' + escape($(this).attr('data-corte'));
				});
				if(!$(this).prop('disabled')){
					$().jfbox({
						carrega: 'api/midias/midias.php?m='+ action + i,
						position: 'maximized'
					});
				}
			});

			$('#midias-botao-encerrar, #midias-botao-proximo', self).click(function(){
				if(!$(this).prop('disabled')){
					var botao = this;
					var fotos = [];
					$(
						($(botao).hasClass('arquivos')? '#api_midias_files .file[data-cele-ord]' : 
						($(botao).hasClass('cortes')? '#api-midias-icosCortardos .file' : ''))
					, self).each(function(index, val){
						var id = (parseInt($(val).attr('data-cele-ord')) - 1);
						var nome = $(val).attr('data-nome');
						var corteNome = nome;
						if($(botao).hasClass('comCortes') && $(val).attr('data-corte') != undefined)
							corteNome = $(val).attr('data-corte') + '/' + corteNome;
						fotos[id] = {simples: nome, value: corteNome};
					});
					var inseridos = [], removidos = [], inp, val = '';
					$(fotos).each(function(id, nome){
						val += (val == ''? '': '; ') + nome.simples;
						inp = $('#midias-dados-antetiores input[name="dados['+ (id + 1)+ ']"][value="'+ nome.value+ '"]', self);
						if(inp.length <= 0){
							nome.name = name + '[inseridos]['+ (id + 1)+ ']';
							inseridos.push(nome);
						}else{
							inp.remove();
						}
					});
					$('#midias-dados-antetiores input').each(function(){
						removidos.push({name: name + '[removidos][]', value: $(this).val()});
					});

					if($(this).hasClass('fim')){
						$('input[type="hidden"]', apiMidias.contesto).remove();
						$('input.div', apiMidias.contesto).val(val);
						$(inseridos).each(function(id, dados){
							$(apiMidias.contesto).append([
								$('<input>', {type: 'hidden', ref: 'inseridos', name: dados.name, value: dados.value})
							]);
						});
						$(removidos).each(function(id, dados){
							$(apiMidias.contesto).append([
								$('<input>', {type: 'hidden', ref: 'removidos', name: dados.name, value: dados.value})
							]);
						});
						fechaJfbox();
					}else{
						var i = '';
						$(inseridos).each(function(id, dados){
							i += '&inseridos[]=' + dados.value;
						});
						$(removidos).each(function(id, dados){
							i += '&removidos[]=' + dados.value;
						});
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
				
				var box = $('#api-midias-areaCorte .area-de-corte', self);
				box.width = $(box).width();
				box.height = $(box).height();
				
				if($('img[data-nome]', box).attr('data-nome') == nome)
					return false;
				
				var eu = $('#api-midias-icosCortardos .file[data-nome="' + nome + '"]', self);
				var euImg = $('.img-ico', eu);
				var src = $(euImg).attr('src');
				var img = new Image();
				img.onload = function(){
					var fim = redimencionar(img, box, 'p');
					var nova = $('<img>', {id: 'midias-imgToCorte', 'data-nome': nome, src: src});
					box.find('.imgToCorte').css({width: fim.width, height: fim.height}).html(nova);
					setCorteToImg();
				};
				img.src = src;
				
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
			 * corte = ['c', 'p', 'o', 'm'];
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

					if((['c', 'p', 'o', 'r', 'a', 'm'].indexOf(corteTipo)) < 0)
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

							var f = redimencionar(img, icone, corteTipo);
							
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
									var f = redimencionar(img, icone, 'a');
									$(eu).attr({'data-corte': corte.width + '-' + corte.height + '-' + 'a'});
									$(ico).css(f);
								}
								$(base).css({width: icone.width, height: icone.height});
								
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

							}

						break;
					}
				};
				img.src = src;
				
			}
			
			function redimencionar(dimensoesUm, dimensoesDois, tipo){
				
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
							var f = redimencionar(img, icone, cte);
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
						var f = redimencionar(img, icone, cor);
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
				var length = $('.file[data-cele-ord]', self).length;
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
				$('#api_midias_files .file[data-cele-ord]', self).each(function(index, val){
					var id = (parseInt($(val).attr('data-cele-ord')) - 1);
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
					
					if(tipos.indexOf(etc) >= 0){
						var ref = 
						$('<div>', {class: 'file', 'data-time': data, 'data-zise': file.size, 'data-etc': etc, 'data-nome': file.name}).append([
							$('<div>', {class: 'ico'}).append([
								$('<div>', {class: 'pos'}).append([
									$('<span>', {class: 'mark'}).append([
										(file.type.match('image.*')?
											$('<img>', {class: 'img-ico', src: 'api/navigi/img/ico.png'})
										:
											$('<img>', {class: 'img-sem', src: 'api/navigi/img/ico.png'})),
										$('<span>', {class: 'barra-load'}),
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

						if(file.type.match('image.*')){
							desenharIcone(file, $('.img-ico', ref));
						}

						var formData = new FormData();
						formData.append('file', file);
						sendFileToServer(formData, ref);
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
			
			function sendFileToServer(formData, ref){
				
				var sfts = this;
				this.abcdef = (isNaN(this.abcdef)? 0: this.abcdef);
				this.abcdefg = (this.abcdefg == undefined? []: this.abcdefg);
				
				//console.log(sfts.abcdef, this.abcdefg);
				
				if(sfts.abcdef >= 5){
					sfts.abcdefg.push({formData: formData, ref: ref});
					
				}else{
					if(formData == undefined && sfts.abcdefg.length > 0){
						var a		= sfts.abcdefg.shift();
						formData	= a.formData;
						ref			= a.ref;
					}
					if(formData != undefined){
						sfts.abcdef += 1;
						$.ajax({
							xhr: function(){
								var xhrobj = $.ajaxSettings.xhr();
								if (xhrobj.upload){
										xhrobj.upload.addEventListener('progress', function(event) {
											var percent = 0;
											var position = event.loaded || event.position;
											var total = event.total;
											if (event.lengthComputable) {
												percent = Math.ceil(position / total * 100);
											}
											//Set progress
											$('.barra-load', ref).css({width: percent + '%'});
										}, false);
									}
								return xhrobj;
							},
							url: 'api/midias/upload.php?m=' + $(self).attr('data-action'),
							type: "POST",
							dataType: 'json',
							contentType: false,
							processData: false,
							cache: false,
							data: formData,
							context: ref,
							success: function(data){
								$('.barra-load', ref).hide();
								if(data.erro != undefined){
									console.log(data);
									$(ref).addClass('erro').attr({
										'data-erro': unescape(data.msg),
									});
									$('.nome', ref).html(unescape(data.msg));
								}else{
									$(ref).attr({
										'data-data': unescape(data.data),
										'data-size': unescape(data.size),
										'data-etc':  unescape(data.etc),
										'data-nome': unescape(data.nome)
									});
									$('.nome', ref).html(unescape(data.nome));
								}
								sfts.abcdef -= 1;
								sendFileToServer();
							},
							error: function (){
								$(ref).addClass('erro');
								sfts.abcdef -= 1;
								sendFileToServer();
							}
						});
					}
				}
			}
			
		});
		
	};
	
})(jQuery, document, window);