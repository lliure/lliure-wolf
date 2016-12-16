;(function($){

    var topRigth =      $('<div>', {class: 'api-vigili-rigth'});
    var topCenter =     $('<div>', {class: 'api-vigili-center'});
    var topLeft =       $('<div>', {class: 'api-vigili-left'});
    var botRigth =      $('<div>', {class: 'api-vigili-rigth'});
    var botCenter =     $('<div>', {class: 'api-vigili-center'});
    var botLeft =       $('<div>', {class: 'api-vigili-left'});

    $(function(){
        var $body = $('body');

        $body.append([
            $('<div>', {id: 'api-vigile-header'}).append([
                topLeft, topCenter, topRigth
            ]),
            $('<div>', {id: 'api-vigile-footer'}).append([
                botLeft, botCenter, botRigth
            ])
        ]);

        $body.on('click', '[data-dismiss="callout"]', function(){
            if($(this).not(':disabled')){
                var $target = $(this).closest('.callout');
                if ($target.hasClass('callout-fade')) $target.fadeOut(function(){ $(this).remove(); });
                else $target.remove();
            }
        });
    });

    var main = [], unloading = false;

    function popupContent(alert){
        var popUp = $('<div>', {'class': 'alert alert-' + alert.type + ' alert-dismissible alert-animation'}).append([
            $('<button>', {'type': 'button', 'class': 'close', 'data-dismiss': 'alert'}).append([
                $('<span>').html('&times;')
            ]),
            alert.msg
        ]);
        if(alert.o.time > 0) setTimeout(function(){
            popUp.fadeOut(function(){ $(this).remove(); });
        }, alert.o.time);
        return popUp;
    }

    function inArea(target, type, msg, o){
        $(target).append( popupContent({
            'type': type,
            'msg': msg,
            'o': o
        }));
    }

    function inMain(type, msg, local, o){
        unload({
            'local': local,
            'type': type,
            'msg': msg,
            'o': o
        });
    }

    function unload(msg){
        if(!!msg){
            main.push(msg); unload();

        }else if(!unloading){
            unloading = true;

            msg = main.shift();
            $(((msg.local[1]) <= 0?
                ((msg.local[0] == 0)? topCenter: ((msg.local[0] >= 0)? topRigth: topLeft)):
                ((msg.local[0] == 0)? botCenter: ((msg.local[0] >= 0)? botRigth: botLeft)))
            ).append( popupContent({
                'type': msg.type,
                'msg': msg.msg,
                'o': msg.o
            }));

            setTimeout(function(){
                unloading = false;
                if(main.length > 0) unload();
            }, 100);
        }
    }

    $.fn.vigile = function(options){
        options = $.extend({
            'time': 7500
        }, options);

        var self = this;
        this.alert = function(msg, local){
            local = local || [1, 0];
            if(this.__proto__ == $.fn)
                inArea(this, 'alert', msg, options);
            else
                inMain('alert', msg, local, options);
            return self;
        };
        this.success = function(msg, local){
            local = local || [1, 0];
            if(this.__proto__ == $.fn)
                inArea(this, 'success', msg, options);
            else
                inMain('success', msg, local, options);
            return self;
        };
        this.info = function(msg, local){
            local = local || [1, 0];
            if(this.__proto__ == $.fn)
                inArea(this, 'info', msg, options);
            else
                inMain('info', msg, local, options);
            return self;
        };
        this.warning = function(msg, local){
            local = local || [1, 0];
            if(this.__proto__ == $.fn)
                inArea(this, 'warning', msg, options);
            else
                inMain('warning', msg, local, options);
            return self;
        };
        this.danger = function(msg, local){
            local = local || [1, 0];
            if(this.__proto__ == $.fn)
                inArea(this, 'danger', msg, options);
            else
                inMain('danger', msg, local, options);
            return self;
        };
        return this;
    };

})(jQuery); Vigile = jQuery.fn.vigile;