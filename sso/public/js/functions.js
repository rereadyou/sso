function functions(win, dom, $) {
    
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

    this.post = function(url, args) {
        
    };

    this.execute_script = function(url) {

    };



}
