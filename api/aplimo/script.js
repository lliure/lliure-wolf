;(function($){
    $(function(){
        var $body = $('body');
        var $pnvc =  $('#persona-navbar-collapse');
        $('.apm-menu-botao-menu-left').off('click').click(function(){
            $body.toggleClass('apm-menu-left-show');
            $pnvc.collapse('toggle');
        });
        $pnvc.on('show.bs.collapse', function(){
            $body.addClass('apm-menu-left-show');
        }).on('hide.bs.collapse', function(){
            $body.removeClass('apm-menu-left-show');
        });
    });
})(jQuery);