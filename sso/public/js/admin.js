function admin(win, doc, $) {
    var f = new functions(win, doc, $);
    var me = this;

    var admainurl = 'http://sso.allyes.me/admin/';
    this.userclass = 'user';
    this.infoclass = 'info';
    this.historyclass = 'history';
    this.activeuserclass = 'activeuser';
    this.fn = new functions;

    this.init = function() {
       $('input:button').click(function() { 
           var action = $(this).val();
           var cls = $(this).attr('class');
           var id = $(this).parent().siblings().first().attr('title');
           $.get(admainurl+'del'+cls+'/'+id, {}, this.cb_action_done); 
       }); 
    };

    this.cb_action_done = function(dom, action) {
       var _dom = $(dom).parent().find('div').last();
       $(action+' done!').insertAfter(_dom);
    }
}

var adm = new admin(window, document, $);
adm.init();
