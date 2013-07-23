commandsQueue = new Array();
currentCommand = 0;
searchAttempts = 0;
searchSubject = '';
commandPart = '';
commandName = '';

// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};

function textWidth(text){
	var calc = '<span style="display:none" class="color2">' + text + '</span>';
	$('body').append(calc);
	var width = $('body').find('span:last').width();
	$('body').find('span:last').remove();
	return width;
};

setCommandFocus = function(){
	$('#command').focus();
}

$('.wrap').mousemove(setCommandFocus);

$('#command').keydown(function(e){
    $('html,body').stop(1,1).animate({scrollTop: $(this).offset().top - 40}, 200);
    if( e.which == 9
        || e.which == 38
        || e.which == 40)
        e.preventDefault();
});

$('#command').keyup(function(e){
	var isRunningCommand = (commandPart.length > 0 && commandName.length > 0);
	
	var comInput = $(this);
    var comArrow = $('.command-box.incomming .cmd-arrow');
	
    /* tab - 9 */
    if(e.which == 9 && !isRunningCommand){
        if(searchAttempts == 0)
            searchSubject = comInput.val();
        $.post('core/comsearch.php',
			{filename: searchSubject ,attempts: searchAttempts},
			function(data){
				if(data.length > 0)
                    comInput.val(data);
                else
                    searchAttempts = -1;
			});
        searchAttempts++;
    }else{
        searchAttempts = 0;
    }
    
    /* up - 38 */
	if(e.which == 38){
		if((currentCommand) >= 0){
            if(currentCommand != 0)
                currentCommand--;
			comInput.val(commandsQueue[currentCommand]);
		}
	}
	
    /* down - 40 */
	if(e.which == 40){
		if((currentCommand+1) <= commandsQueue.length){
			currentCommand++;
			comInput.val(commandsQueue[currentCommand]);
		}
	}
	
    /* return - 13 */
	if(e.which == 13){
        if(comInput.val().length > 0 || isRunningCommand){
            if(commandsQueue[commandsQueue.length-1] != comInput.val() && !isRunningCommand){
                commandsQueue[commandsQueue.length] = comInput.val();
                currentCommand = commandsQueue.length;
                if(currentCommand == 51){
                    currentCommand = 50;
                    commandsQueue.remove(0);
                }
            }
			
			var tcomText = (comInput.attr('type').toLowerCase() != 'password' ? comInput.val(): '******');
			var tcomArrowWidth = textWidth(comArrow.html());
			if(tcomArrowWidth < 20) tcomArrowWidth = 20;
			var tcom = $('<table class="command-box">'+
				'<tr>'+
					'<td class="cmd-arrow color2" style="width: '+tcomArrowWidth+'px">'+comArrow.html()+'</td>'+
					'<td class="cmd-inner-box">'+tcomText+'</td>'+
				'</tr>'+
				'<tr>'+
					'<td colspan="2"><div class="return">loading...</div></td>'+
				'</tr>'+
			'</table>');
            tcom.appendTo('.log');

            comInput.hide();
			comArrow.html('>').css('width', '20px');
			
            $.post('core/comexec.php',{
					command: comInput.val(),
					commandPart: commandPart,
					commandName: commandName
				},
                function(data){
                    tcom.find('.return').html(data);
                    comInput.val('').show();
                    setCommandFocus();
                });
        }
	}
});

$(document).ready(setCommandFocus);

clearTerminalLog = function(){
    $('.log').html('')
}
