
function ll_load(url, data, callback){ $('#ll_load').load(url, data, callback); }
$(function(){ $('body').prepend('<div id="ll_load"></div>'); });