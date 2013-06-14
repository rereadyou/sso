function functions(win, dom, $) {
    "use strict";
    var me = this;
    
    this.name = 'functions';
    this.version = '0.0.1';
    this.scriptId = 'functions';

    this.popupId = '';
    this.coverBodyId = '';
    this.autoDimId = '';
    this.blinkId = '';

    this.$$ = function(id) {
        return dom.getElementById(id);
    };

    this.addEventListener = function(dom, event, fn) {
        if (dom.addEventListener) {
            dom.addEventListener(event, fn);
        } else if (dom.attachEvent) {
            dom.attachEvent('on', event, fn);
        } else {
            dom['on'+event] = fn;
        }
    };

    this.make_url = function(url, queryObj) {
        var joinChar = /\?/.test(url) ? '&' : '?';
        return url + joinChar +  me.build_query(queryObj);
    };

    this.build_query = function(obj) {
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
    };

    this.remove_node = function(id) {
        if(typeof(id) === 'string') {
            var dom = me.$$(id);
            if(dom) {
                dom.parentNode.removeChild(dom);
            }
        }
    };

    this.append_node = function(parent, child) {
        parent.appendChild(child);
    };

    this.execute_script = function(id, url) {
        me.remove_node(id);
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script'); 
        script.id = id;
        script.type = 'text/javascript';
        script.src = url;
        me.append_node(head, script);
    };

    this.get_querystring = function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r !== null) {
            return window.unescape(r[2]);
        }
        return null;
    };
this.cookie = function(cname, cval) { var cookie = document.cookie; if (arguments.length === 1) {
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

    this.ajax_cb = function(obj, act, url, cb, data={}, method='GET') {
        //wrapper for jquery ajax
        $.ajax({
            type: method,
            url: url,
            async: true,
            data: data,
            success: function(ret) { 
                cb(obj, act, ret); 
            },
        });
    };

    this.pop_confirm = function(id, hint, cb, wh=300, ht=100) {
        me.cover_body('body_cover', true);
        var body = document.body;
        //var body = document.querySelector('body');
        var h = body.clientHeight;
            h -= 150;
        var w = body.clientWidth;

        var plate = document.createElement('div');
            plate.id = id;
            plate.style.top = h/2 - ht + 'px' ;
            plate.style.left = w/2 - wh + 'px';
            plate.style.zIndex = '999';
            plate.style.position = 'absolute';
            plate.style.padding = '5px';
            plate.style.height = ht + 'px';
            plate.style.width = wh + 'px';
            plate.style.backgroundColor = '#ffffff';
            plate.style.border ='2px groove #ff4455';

        var flush = document.createElement('div');
            flush.id = id + '_flush';
            flush.innerHTML = hint;
            flush.style.padding = '5px 5px 0px 5px';
            flush.style.textAlign = 'justify';
            //flush.style.text
            flush.style.minHeight = ht - 30 + 'px';

        var btdiv = document.createElement('div');
            btdiv.id = id + '_bt';
            btdiv.style.cssFloat = 'right';

        var btok = document.createElement('input');
            btok.type = 'button';
            btok.id = id + 'ok';
            btok.value = 'OK';
            btok.style.width = '80px';
            btok.style.margin = '0px 5px';

        var btng = document.createElement('input');
            btng.type = 'button';
            btng.id = id + 'ng';
            btng.value = 'Cancel';
            btng.style.width = '80px';

        me.append_node(body, plate);
        me.append_node(plate, flush);
        me.append_node(btdiv, btok);
        me.append_node(btdiv, btng);
        me.append_node(plate, btdiv);

        me.addEventListener(btok, 'click', function() { me.remove_node(plate.id);
            me.cover_body('body_cover', false);
            cb();
        });
        me.addEventListener(btng, 'click', function() {
            me.cover_body('body_cover', false);
            me.remove_node(plate.id);
        });
    };

    this.cover_body = function(id, isShow = true) {
        var body = document.body;
        var h = body.clientHeight;
        var w = body.clientWidth;
        var id = '_body_cover_' + id;

        if(isShow) {
            var c = me.new_div(id);
                c.style.opacity = '0.8';
                c.style.height = h;
                c.style.width = w;
                c.style.left = '0px';
            } else {
                me.remove_node(id);
            }
    };

    this.auto_dim_flush = function(msg, dim=1500, type='info') {
        var id = '_function_auto_dim_flush_';

        var flush = me.new_div(id, type);
            flush.innerHTML = msg;
            flush.style.padding = '3px 10px';
            flush.style.top = '5px';
            flush.style.borderRadius = '5px 5px 5px 5px';

            setTimeout('var dim = document.getElementById("_function_auto_dim_flush_"); dim.parentNode.removeChild(dim); ', dim);
    };

    this.blink_flush = function(msg, loops=3, interval=700) {
        var i = 0;
        var lp = function() {
            me.auto_dim_flush(msg, interval, 'warn');
            if(++i >= loops) {
                clearInterval(bfid);
            }
        };
        var bfid = setInterval(lp, 1000);
    };

    this.pop = function(id, ctn) {
        //me.cover_body('_function_pop_', true);

        if(!id) {
            id = '_function_pop_div_' + id;
        }
            me.popupId = id;
        var ctner = me.new_div(id, 'plain');
            ctner.style.border = '1px groove #333333';
            ctner.style.padding = '10px';
            ctner.innerHTML = ctn;

            me.dom_to_center(id);
    };

    this.dom_to_center = function(id) {
        var dom = document.getElementById(id);
        var h = dom.offsetHeight;
        var w = dom.offsetWidth;

        dom.style.top = '50%';
        dom.style.marginTop = -h/2 + 'px';
    }

    this.page_size = function() {
        var body = document.body;
        var h = body.clientHeight;
        var w = body.clientWidth;
        return {'h':h, 'w':w};
    };

    this.new_div = function(id, type='plain') {
        var bgc = me.get_color_hex(type);

        var body = document.body;
        var h = body.clientHeight;
        var w = body.clientWidth;

        var flush = document.createElement('div');
            flush.id = id;
            flush.style.top = '0px';
            flush.style.color = '#ffffff';
            if(type == 'plain') {
                flush.style.color = '#000000';
            }
            flush.style.backgroundColor = bgc;
            flush.style.position = 'absolute';
            flush.style.margin = '0 auto';
            flush.style.padding = '3px 10px';
            //flush.style.top = '5px';
            flush.style.cssFloat = 'center';
            flush.style.textAlign = 'justify';
            flush.style.borderRadius = '5px 5px 5px 5px';
            flush.style.left = w / 2 - 200 + 'px';
            flush.style.zIndex = '998';

            me.append_node(body, flush);
            return flush;
    };

    this.get_color_hex = function(type) {
        var bgc = '';
        switch(type) {
            case 'info':
                bgc = '#458B00';
                break;
            case 'error':
                bgc = '#ff0000';
                break;
            case 'warn':
                bgc = '#C04848';
                break;
            case 'plain':
                bgc = '#ffffff';
                break;
            default:
                bgc = '#ffffff';
                break;
        }
        return bgc;
    };

    this.click_position = function(evt) {
        var pos = {x:0, y:0};
        var doc = document.documentElement;
        var body = document.body;
        evt = evt || window.event;

        if(window.pageYoffset) {
            pos.x = pageXoffset;
            pos.y = pageYoffset;
        } else {
            pos.x = (doc && doc.scrollLeft || body.scrollLeft) 
                - (doc && doc.clientLeft || body.clientLeft);
            pos.y = (doc && doc.scrollTop || body.scrollTop) 
                - (doc && doc.clientTop || body.clientTop);
        }
        
        pos.x += evt.clentX;
        pos.y += evt.clentY;

        return pos;
    };

}//end of functions
