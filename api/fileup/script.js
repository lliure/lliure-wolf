;(function ($, undefined){
    $(function(){

        function quote(str) {
            return (str + '').replace(new RegExp('[.\\\\+?\\[\\^\\]$(){}=!<>|:\\-*]', 'g'), '\\$&');}

        var body = $('body');
        body.find('.fileUpBloco-btn-up').click(function(){
            $(this).closest('.fileUpBloco').find(':input[type="file"]').click();
        });
        body.find('.fileUpBloco :input[type="file"]').change(function(){
            var bas = $(this).closest('.fileUpBloco');
            var ext = '.' + $(this).val().split('.').pop();
            var mit = (this.files.length >= 1? this.files[0].type: '');
            var exs = $(this).attr('accept');
            exs = (!!exs? exs.split(','): false);
            if(!exs || exs.find(function(v){
                var reg = new RegExp(quote(v).replace(/\\\*$/, '.*'));
                console.log(ext, mit, reg, reg.test(ext) || reg.test(mit));
                return reg.test(ext) || reg.test(mit);
            }) != undefined){
                $(bas).find('.fileUpBloco-input').val($(this).val().split(/[\\/]/im).pop());
                bas.removeClass('fileUpBlocoDel');
                bas.find(':input[name="fileUp[del][]"]').val(0);
            }else{
                if(this.length >= 1) jfAlert('Tipo de arquivo não permitido');
                $(bas).find('.fileUpBloco-input').val('');
                $(this).val();
            }
        });
        body.find('.fileUpBloco-btn-del').click(function(){
            var bas = $(this).closest('.fileUpBloco');
            var del = !parseInt(bas.find(':input[name="fileUp[del][]"]').val());
            bas.toggleClass('fileUpBlocoDel', del);
            bas.find(':input[name="fileUp[del][]"]').val(del? 1: 0);
        });
    });
})(jQuery);