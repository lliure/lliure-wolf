/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.IcodePlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceIcode', function() {
				ed.windowManager.open({
					file : url + '/icode.htm',
					width : 500 + parseInt(ed.getLang('icode.delta_width', 0)),
					height : 300 + parseInt(ed.getLang('icode.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});
			
			ed.addButton('icode', {
				title : 'I-Code',
				cmd : 'mceIcode',
				image : url + '/img/logo.gif'
			});

			
		},

		getInfo : function() {
			return {
				longname : 'Icode',
				author : 'Jeison Frasson',
				authorurl : 'http://www.lliure.com.br',
				infourl : 'http://www.lliure.com.br/pagina/icode.html',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('icode', tinymce.plugins.IcodePlugin);
})();