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

    this.init = function() {
       $('input:button').click(function() { 
           var obj = this;

           var act = $(obj).attr('name');
           var url = adminurl + act + '/';
           var data = {};
           var method = 'GET';

           if(/^del/.test(act)) {
               var id = $(obj).parent().siblings().first().attr('title');
               url += id;
           }
           if(/^add/.test(act)) {
               $('.modeldata').each(function() {
                   //here this stands for dom rather than jquery object
                   data[this.id] = this.value;
               });
               method = 'POST';
           }
           if(/^up/.test(act)) {
               var id = $(obj).parent().siblings().first().attr('title');
               url += id;
               f.ajax_cb(obj, act, url, me.cb_up_open);
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
        f.pop(ret);
    };
}

var adm = new admin(window, document, $);
adm.init();
