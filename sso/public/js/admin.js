function admin(win, doc, $) {
    "use strict";

    var f = new functions(win, doc, $);
    var me = this;
    

    var adminurl = 'http://sso.allyes.me/admin/';

    this.name = 'adminjs';
    this.version = '0.0.1';
    this.scriptId = 'adminjs';

    this.userclass = 'user';
    this.infoclass = 'info';
    this.historyclass = 'history';
    this.activeuserclass = 'activeuser';

    this.coverId = '';
    this.popId = '';

    this.init = function() {
       $('input:button').click(function() { 
           var obj = this;

           var act = $(obj).attr('name');
           var url = adminurl + act + '/';
           var data = {};
           var method = 'GET';

           //delete action
           if(/^del/.test(act)) {
               var id = $(obj).parent().siblings().first().attr('title');
               url += id;
           }
           //add action
           if(/^add/.test(act)) {
               $('.modeldata').each(function() {
                   //here this stands for dom rather than jquery object
                   data[this.id] = this.value;
               });
               method = 'POST';
           }
           //update action
           if(/^up/.test(act)) {
               var id = $(obj).parent().siblings().first().attr('title');
               url += id;
               f.ajax_cb(obj, act, url, me.cb_up_open);
               return ;
           }

           var tip = 'Are you sure to do ' + act + '?';

           f.pop_confirm('actionid', tip, function() {
               f.ajax_cb(obj, act, url, me.cb_action_done, data, method);
           });
       }); 
    };

    this.cb_action_done = function(dom, act, ret) {
        var tip = act + ' done!';
        if(ret == 'done!') {
            if(/^del/.test(act)) {
                $(dom).parent().parent().fadeOut();
            }
            f.auto_dim_flush(tip, 2000, 'info');
        }
        else {
            //tip = 'Warning: ' + act + ' unsuccessfully!';
            tip = 'Warning: ' + act + ' 操作失败！';
            tip += '<br />' + ret;
            f.blink_flush(tip);
        }
    };

    this.cb_up_open = function(dom, act, ret) {
        var obj = this;
        var coverId = 'popup_body_cover';
        me.coverId = coverId;
        var popId = 'pop';
        me.popId = popId;

        f.cover_body(coverId, true);
        f.pop(popId, ret);

        var btnsub = document.getElementById('btnsubmit');
        var btnfin = document.getElementById('btncancel');
        var model = btnsub.getAttribute('model');

        f.addEventListener(btnsub, 'click', function() {
            var url = adminurl;
                url += 'up' + model.replace(/_/, '/');
                console.info(url);
            var data = {};
            $('.'+model).each(function() {
                //var idx = this.id.indexOf('_');
                //var cls = this.id.substring(0, idx);
                //var attr = this.id.substr(model.length + 1);

                data[this.name] = this.value;
            });

            f.ajax_cb(obj, act, url, me.cb_up_done, data, 'post'); 
        });

        f.addEventListener(btnfin, 'click', function() {
            f.cover_body(coverId, false);
            console.info(popId);
            f.remove_node(popId);
        });

    };

    this.cb_up_done = function(dom, act, ret) {
        if(ret === 'done!') {
            f.cover_body(me.coverId, false);
            f.remove_node(me.popId);
            f.auto_dim_flush('Update done!');
        }

    };
}

var adm = new admin(window, document, $);
adm.init();
