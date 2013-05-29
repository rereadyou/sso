function authentication(win, doc, $) {
    var f = new functions(win, doc, $);
    var me = this;
    var login = 'http://sso.allyes.me/admin/login';

    this.email = '';
    this.password = '';

    var login_div = 'authen_login';
    var submit = 'btnLoginSubmit';

    this.init = function() {
       f.addEventListener(f.$$(submit), 'click', me.login); 
    };

    this.login = function() {
        me.email = f.$$('authen_email').value;
        me.psw = f.$$('authen_psw').value;
        
        $.post(login, {'email':me.email, 'password': me.psw}, this.cb_login_done ); 
        
    };

    this.cb_login_done = function() {
        console.info('login done.');
    };
}

var authen = new authentication(window, document, $);
authen.init();
