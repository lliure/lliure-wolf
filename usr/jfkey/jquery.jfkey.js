 
/**
*
* lliure WAP
*
* @Versão 6.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

jQuery.fn.extend({ 
	jfkey: function (keyCombo,options,callback){
		
		// Save the key codes to JSON object
		var keyCodes = { 
			// start the a-z keys 
			'a' : 65, 
			'b' : 66,
			'c' : 67,
			'd' : 68,
			'e' : 69,
			'f' : 70,
			'g' : 71,
			'h' : 72,
			'i' : 73,
			'j' : 74,
			'k' : 75,
			'l' : 76,
			'm' : 77,
			'n' : 78,
			'o' : 79,
			'p' : 80,
			'q' : 81,
			'r' : 82,
			's' : 83,
			't' : 84,
			'u' : 85,
			'v' : 86,
			'w' : 87,
			'x' : 88,
			'y' : 89,
			'z' : 90,
			// start number keys 
			'0' : 48,
			'1' : 49,
			'2' : 50,
			'3' : 51,
			'4' : 52,
			'5' : 53,
			'6' : 54,
			'7' : 55,
			'8' : 56,
			'9' : 57,
			// start the f keys 
			'f1' : 112,
			'f2' : 113,
			'f3' : 114,
			'f4' : 115,
			'f5' : 116,
			'f6' : 117,
			'f7' : 118,
			'f8' : 119,
			'f9' : 120,
			'f10': 121,
			'f11': 122,
			'f12': 123,
			// start the modifier keys 
			'shift' : 16,
			'ctrl' : 17,
			'control' : 17,
			'alt' : 18,
			'option' : 18, //Mac OS key
			'opt' : 18, //Mac OS key
			'cmd' : 224, //Mac OS key
			'command' : 224, //Mac OS key
			'fn' : 255, //tested on Lenovo ThinkPad
			'function' : 255, //tested on Lenovo ThinkPad
			// Misc. Keys 
			'backspace' : 8,
			'osxdelete' : 8, //Mac OS version of backspace
			'enter' : 13,
			'return' : 13, //Mac OS version of "enter"
			'space':32,
			'spacebar':32,
			'esc':27,
			'escape':27,
			'tab':9,
			'capslock':20,
			'capslk':20,
			'super':91,
			'windows':91,
			'insert':45,
			'delete':46, //NOT THE OS X DELETE KEY!
			'home':36,
			'end':35,
			'pgup':33,
			'pageup':33,
			'pgdn':34,
			'pagedown':34,
			// Arrow keys 
			'left' : 37,
			'up'   : 38,
			'right': 39,
			'down' : 40,
			// Special char keys 
			'`':96,
			'~':96,
			'-':45,
			'_':45,
			'=':187,
			'+':187,
			'[':219,
			'{':219,
			']':221,
			'}':221,
			'\\':220, //it's actually a \ but there's two to escape the original
			'|':220,
			';':59,
			':':59,
			"'":222,
			'"':222,
			',':188,
			'<':188,
			'.':190,
			'>':190,
			'/':191,
			'?':191
		};

		
		var y = '';
		
		if(typeof options == 'function' && typeof callback == 'undefined'){
			callback = options;
			options = false;
		}
		
		if(keyCombo.toString().indexOf(',') > -1){ //Se for selecionada mais de uma tecla
			var keySplit = keyCombo.match(/[a-zA-Z0-9]+/gi); // limpara para pegar só letras e números
		} else { // caso contrario se for só uma 
			var keySplit = [keyCombo];
		}
		
		var i = '';
		for(i in keySplit){ //transformando em array ...
			if(!keySplit.hasOwnProperty(i)) { 
				continue; 
			}
			//Same as above for the toString() and IE
			if(keySplit[i].toString().indexOf('+') > -1){
				//Key selection by user is a key combo
				// Create a combo array and split the key combo
				var combo = [];
				var comboSplit = keySplit[i].split('+');
				// Save the key codes for each element in the key combo
				for(y in comboSplit){
					combo[y] = keyCodes[ comboSplit[y] ];
				}
				keySplit[i] = combo;
			}
			else {
				//Otherwise, it's just a normal, single key command
				keySplit[i] = keyCodes[ keySplit[i] ];
			}
		}
		
	
		this.keydown(function(e) {
			if($.inArray(e.keyCode, keySplit) > -1){ // Verifica se a tecla prcionada é a mesma que foi selecionada
				if(typeof callback == 'function'){ //checa se o retorno é uma função
					if(callback.call(this, e.keyCode, options) != true) // executa a função de retorno
						e.preventDefault();
				} else {
					if(options === false){ // caso queria que a tecla sejá anulada 
						e.preventDefault();
					}
				}				
			}
		});
	}
});