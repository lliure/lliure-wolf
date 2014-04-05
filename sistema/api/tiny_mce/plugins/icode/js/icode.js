tinyMCEPopup.requireLangPack();

var IcodeDialog = {
	
	init : function() {
		var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		//f.somearg.value = tinyMCEPopup.getWindowArg('some_custom_arg');
	},
	
	insert : function() {
		// Insert the contents from the input into the document
		var entrada = "<pre style='background-color: #ccc;' class=\"brush: c-sharp;\">"+htmlTrade(document.forms[0].someval.value)+"</pre>";
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, entrada);
		tinyMCEPopup.close();
	}
};



tinyMCEPopup.onInit.add(IcodeDialog.init, IcodeDialog);


function htmlTrade(entrada){
	return entrada.replace(/(<|<\/)/g, '<span>&lt;</span>');
}