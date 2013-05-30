function SSOController(window, document) {
    "use strict";
    var me = this;

    var ssoSubmitId = 'submitSSOLogin'; //login div id
    var ssoLogoutId = 'submitSSOLogout'; //logout div id
    var ssoPreloginURL = "http://sso.allyes.me/authentication/prelogin/";//.php";
    var ssoLoginURL = "http://sso.allyes.me/authentication/login/";//.php"; //login process page
    var ssoLogoutURL = "http://sso.allyes.me/authentication/logout/";//.php";
    var ssoLoginChkURL = "http://sso.allyes.me/authentication/login_chk/";//.php";

    this.name = 'ssocontroller';
    this.version = '0.0.1';
    this.scriptId = 'ssocontroller';
    this.flush = 'loginFlush'; //登录结果提醒div
    this.hints = '';
    this.isCheckLoginState = true; //是否先检查已经登录过
    this.loginType = 'rsa';
    this.ssoticketName = 'allyes_ticket';
    this.service = 'sso';
    this.nonce = null;
    this.isNonceReady = false;
    this.servertime = null;
    this.ssopubkeyN = null;
    this.ssopubkeyE = null;
    this.email = null;
    this.up = null; //user password
    this.state = null;
    this.retcode = null;

    this.init = function() {
        me.flush = $$(me.flush);

        if (me.isCheckLoginState) {
            addEventListener(window, "load", me.login_chk);
        };

        addEventListener($$(ssoSubmitId), 'click', me.login);
        addEventListener($$(ssoLogoutId), 'click', me.logout);
    };
    
    this.encrypt_string = function(src) {
        if (!me.ssopubkeyN || !me.ssopubkeyE) {
            return false;
        }
        var rsa = new RSAKey();
        rsa.setPublic(me.ssopubkeyN, me.ssopubkeyE); 
        return rsa.encrypt(src);
    };

    //deprecated
    this.decrypt_string = function(ciphertext) {
        var rsa = new RSAKey();
        rsa.setPrivate(me.ssopubkeyN, me.ssopubkeyE, me.ssopubkeyD);
        var src = rsa.decrypt(ciphertext);
        return src;
    };

    this.cookie = function(cname, cval) {
        var cookie = document.cookie;
        if (arguments.length === 1) {
            var beg = cookie.indexOf(cname + '=');
            if (beg === -1) {
                return null;
            }
            beg = beg + cname.length + 1;
            var end = cookie.indexOf(';', beg);
            if (end === -1) {
                return cookie.substring(beg);
            }
            return cookie.substring(beg, end);
            /*
            var Res = eval('/' + cname + '=([^;]+)/').exec(cookie);
            return Res == null ? null : Res[1];
            */
        }
        if (cval === '') {
            var expires = new Date();
            expires.setTime(expires.getTime() - 1);
            expires = '; expires=' + expires.toGMTString();
            document.cookie = cname + '=' + cval + expires;
        } else {
            document.cookie = cname + '=' + cval;
        }
        return true;
    };

    this.login_chk = function() {
        if (me.get_querystring('action') !== 'unlogined') {
            var url = ssoLoginChkURL;
            var query = {'service': me.service, 'action': 'login_chk'};
            query.callback = me.name + '.cb_login_chk_handler';
            url = make_url(url, query);
            execute_script(me.scriptId, url);
        }
    };

    this.cb_login_chk_handler = function(json) {
        if (json.retcode === 5) {
            me.hints += ssoEncoder.Base64.decode(json.uname);
            me.hints += "  " + ssoEncoder.Base64.decode(json.reason);
            me.cookie(me.ssoticketName, json.ticket);
        }
        me.flush.innerHTML = me.hints;
    };

    this.get_querystring = function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r !== null) {
            return window.unescape(r[2]);
        }
        return null;
    };

    this.logout = function() {
        var url = ssoLogoutURL;
        var query = {'service': me.service, 'action': 'logout',
                'ticket': me.cookie(me.ssoticketName)};
        query.callback = me.name + '.cb_logout_handler';
        url = make_url(url, query);
        execute_script(me.scriptId, url);
    };

    this.cb_logout_handler = function(json) {
        if(json.retcode == 7) {
            me.cookie(me.ssoticketName, '');
        } else {
        }
        me.flush.innerHTML = ssoEncoder.Base64.decode(json.reason);
    };

    //以jsonp方式跨域进行登录, 可考虑升级为form post提交
    this.login = function() {
        me.email = document.getElementById('email').value;
        me.up = document.getElementById('upsw').value;

        if (me.email === '') {
            me.flush.innerHTML = 'Empty username!';
            return false;
        }
        if (me.up === '') {
            me.flush.innerHTML = 'Empty password!';
            return false;
        }
        //had to send login request after fetch nonce
        if (!me.nonce) {
            me.get_nonce();
        }
    };

    this.get_nonce = function(callback) {
        if(me.servertime && me.nonce) {
            return true;
        }
        var url = ssoPreloginURL; 
        var query = {service: me.service};
        query.callback = me.name + '.cb_expose_nonce';
        url = make_url(url, query);
        execute_script(me.scriptId, url);
    }
    
    //由于jsonp的异步性，需要确保提交之前取得正确的nonce
    this.cb_expose_nonce = function(json) {
        me.nonce = json.nonce;
        me.servertime = json.servertime;
        me.ssopubkeyN = json.rsan;
        me.ssopubkeyE = json.rsae;
        //send login request
        me.send_login_request();
    };

    this.send_login_request = function() {
        me.email = ssoEncoder.Base64.encode(me.email);
        var upsw = [me.servertime, me.nonce].join('\t');
        upsw = [upsw, me.up].join('\n');
        me.up = me.encrypt_string(upsw);

        var url = ssoLoginURL;
        var query = {'email': me.email, 'upsw': me.up, 'nonce': me.nonce,
                    'service': me.service, 'servertime': me.servertime};
        query.callback = me.name + '.cb_login_handler';
        url = make_url(url, query);
        execute_script(me.scriptId, url);

    };

    this.cb_login_handler = function(json) {
        if (json.retcode === 1) {
            me.cookie(me.ssoticketName, json.ticket);
        }
        me.flush.innerHTML = ssoEncoder.Base64.decode(json.reason);
        me.nonce = null;
        me.servertime = null;
    };

    var addEventListener = function(dom, event, fn) {
        if (dom.addEventListener) {
            dom.addEventListener(event, fn);
        } else if (dom.attachEvent) {
            dom.attachEvent("on", event, fn);
        } else {
            dom["on" + event] = fn;
        }
    };

    var execute_script = function(id, url) {
        remove_node(id);
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script'); 
        script.id = id;
        script.type = 'text/javascript';
        script.src = url;
        append_node(head, script);
    }

    var make_url = function(url, queryObj) {
        var joinChar = /\?/.test(url) ? '&' : '?';
        return url + joinChar +  build_query(queryObj);
    }

    var build_query = function(obj) {
        if(typeof(obj) != "object" ) {
            return "";
        }
        var vals = new Array();
        for(var key in obj) {
            if(typeof(obj[key]) == 'function') {
                continue;
            }
            vals.push(key + '=' + obj[key]);
        }
        return vals.join('&');
    }

    var remove_node = function(id) {
        if(typeof(id) === 'string') {
            var dom = $$(id);
            if(dom) {
                dom.parentNode.removeChild(dom);
            }
        }
    }

    var append_node = function(par, chi) {
        par.appendChild(chi);
    }

    var $$ = function(id) {
        return document.getElementById(id);
    }

    var make_nonce = function(len) {
        var _keys = "ABCDEFGHIGHLMNOPQRSTUVWXYZ0123456789";
        var nonce = '';
        var keyLen = _keys.length;
        for(var i = 0; i < len; i++) {
            nonce += _keys.charAt((Math.ceil(Math.random()*100000))%keyLen);
        }
        return nonce;
    }

} //end of SSOController

//all encoder strategies
var ssoEncoder = ssoEncoder || {};

(function() {
    'use strict';
    this.Base64 = {
        _keys: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',

        encode: function(input) {
            input = "" + input;
            if (input === "") {
                return "";
            }
            input = this._utf8_encode(input);
            var output = '';
            var chr1, chr2, chr3 = '';
            var enc1, enc2, enc3, enc4 = '';
            var i = 0;
            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
                output += this._keys.charAt(enc1);
                output += this._keys.charAt(enc2);
                output += this._keys.charAt(enc3);
                output += this._keys.charAt(enc4);
                chr1 = chr2 = chr3 = '';
                enc1 = enc2 = enc3 = enc4 = '';
            } while (i < input.length);
            return output;
        },

        decode: function(input) {
            var output = "";
            var chr1, chr2, chr3;
            var enc1, enc2, enc3, enc4;
            var i = 0;

            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

            while (i < input.length) {
                enc1 = this._keys.indexOf(input.charAt(i++));
                enc2 = this._keys.indexOf(input.charAt(i++));
                enc3 = this._keys.indexOf(input.charAt(i++));
                enc4 = this._keys.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }
            }

            output = this._utf8_decode(output);
            return output;
        },

        _utf8_encode : function (string) {
            string = string.replace(/\r\n/g,"\n");
            var utftext = "";

            for (var n = 0; n < string.length; n++) {
                var c = string.charCodeAt(n);

                if (c < 128) {
                    utftext += String.fromCharCode(c);
                } else if ((c > 127) && (c < 2048)) {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                } else {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }
            }
            return utftext;
        },

        _utf8_decode : function (utftext) {
            var string = "";
            var i = 0;
            var c = 0;
            var c1 = 0;
            var c2 = 0;

            while ( i < utftext.length ) {
                c = utftext.charCodeAt(i);

                if (c < 128) {
                    string += String.fromCharCode(c);
                    i++;
                } else if ((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i+1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                } else {
                    c2 = utftext.charCodeAt(i+1);
                    c3 = utftext.charCodeAt(i+2);
                    string += String.fromCharCode(((c&15) << 12) | ((c2&63) << 6) | (c3&63));
                    i += 3;
                }
            }
            return string;
        },
    };
}).call(ssoEncoder);


var ssocontroller = new SSOController(window, document);
ssocontroller.init();


