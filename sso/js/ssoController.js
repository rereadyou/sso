/**
 * sso navigator
 *
 * 
 *
 */

 //define(function(){
//若要使用长时间不适用则自动登出功能则在app需要登录验证的页面中启用这个文件
	
	var ssoPreloginURL = "http://www.sso.com/core/common/prelogin.php";
	var ssoLoginURL = "http://www.sso.com/login.php";
	var nonce = '';
	var servertime = '';
	var entry = get_url_parameter('entry');
	var pf = parentFrame = window.parent;//iframe 跨域限制无权限
	//window.domain = "domain1.com";
	
	
	
	
//iframe 信使，用于同service js 通信，需要和service同域
function create_messenger(entry)
{
	var messenger = document.createElement('iframe');
		messenger.id = "sso_messenger";
		messenger.name = "sso_messenger";
		messenger.src = "http://www.domain1.com/messenger.html";
		messenger.height = 0;
		messenger.width = 0;
		messenger.frameBorder = 0;
		messenger.allowtransparency = 'true';
		messenger.scrooling = 'no';
	return messenger;
}
	
///////////////////////////////////help/////////////////////////////////////
function get_url_parameter(name)
{	
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;	
}
	
///////////////////////////////////////////////////////////////////////////////
	//页面就绪后获取 nonce servertime
	$(document).ready(function() {
		$('div#sso_messenger').append(create_messenger());
		get_nonce();
	});
		
	function expose_nonce(json)
	{
		nonce = json.nonce;
		servertime = json.servertime;
		//console.warn(hex_sha1(nonce));
		//console.info('nonce='+nonce);
		//console.info('servertime='+servertime);
	}
	
	function get_nonce()
	{
		$.ajax({
			url: ssoPreloginURL,
			type: "get",
			async: false,
			dataType: "jsonp",
			data: {'entry': entry},
			jsonp: "callback",
			jsonpCallback: "expose_nonce",			
			success: function(json) {
				//json;
				//console.info(json.nonce);
				//console.info(json.servertime);
			},
			error: function(json) {
				console.info(json);
			}
		});
	}
///////////////////////////////////////////////////////////////////////////////
	function login_chk()
	{
		//此处的ticket和phpsessid都是domain域下的
		//console.info($.cookie('ticket'));
		//console.info($.cookie('PHPSESSID'));
		$.ajax({
			url: "http://www.domain1.com/login.php",
			type: "get",
			data: {'action': 'login_chk', 'ticket': $.cookie('ticket') || 'noticket'},
			success: function(isLogined) {
				//若用户已经登录，则应执行
				if(isLogined == 'unlogined') {
					console.info(isLogined);
					if(!/^http:\/\/www\.domain1\.com\/login\.php.*$/.test(location.href)) {
						//window.location.href='http://www.domain1.com/login.php';
						//跳转至app home 页面
						//location.replace('http://www.domain1.com/app.php');
					}
						
				} else {
					//if(!/^http:\/\/www\.domain1\.com.*$/.test(location.href))
						//location.replace('http://www.domain1.com/app.php');
				}
					
			},
			error: function(err) {
				console.info(err);
			},
			timeout: function( ) {
				console.info('timeout');
			}
		});
	}
	
	//循环检测本域下的ticket是否有效
	$(document).ready(function() {
		//setInterval(login_chk, 5000);
	});
///////////////////////////////////////////////////////////////////////////////
	//注意ajax是不能够跨域的，如果要跨域操作要使用JSONP
	//但是JSONP只能以GET方式进行
	$(document).ready(function() {		
		//登录检查
		$('#submit').click(function() {
			var uname = $('#uname').val();
			var upsw = $('#upsw').val();
			var service = entry;
			
			var flush = $('div#loginFlush');
			
			if(uname == '') {
				$(flush).html('Empty username!');
				return;
			}
				
			if(upsw =='') {
				$(flush).html('Empty password!');
				return;
			}
				
			if(nonce =='') {
				$(flush).html('No nonce');
				return;
			}
				
			upsw = get_upsw_digest(upsw, nonce, servertime);
			//console.info('upsw digest: '+upsw);
			//登录验证
			$.ajax({
				type: "post",
				url: ssoLoginURL,				
				data: {'uname':uname, 'upsw':upsw, 'nonce':nonce, 'service':service, 'servertime': servertime, },
				success: function(json) {
					var ret = JSON.parse(json);
					//验证失败
					if(ret.retcode != 1)
					{
						//重新获取nonce, servertime
						$(flush).html(ret.reason);
						get_nonce();
					}
					if(ret.retcode == 1)
					{
						$(flush).html(ret.reason);
//						console.info('login ok');
						//alert('i')
						
						alert(parent.window.$);
						//实现父页面跳转（父子页面是跨域的）
						
					}
					
					
					//console.info(res);
					//pf.serviceController.login_done(res);
					//window.parent.login_ok(res);
				},
				error: function(res) {
					console.info('Error happened when login!');
					console.info(res);
				},
			});
		});
		
	});
 
	
	function get_upsw_digest(upsw, nonce, servertime)
	{
		upsw = hex_sha1(upsw); //encode password once
		upsw = hex_sha1(upsw); //encode password twice
		upsw = hex_sha1(upsw+servertime+nonce); //encode password with nonce
		return upsw;
	}
	
	/*
	 * A JavaScript implementation of the Secure Hash Algorithm, SHA-1, as defined
	 * in FIPS 180-1
	 * Version 2.2 Copyright Paul Johnston 2000 - 2009.
	 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
	 * Distributed under the BSD License
	 * See http://pajhome.org.uk/crypt/md5 for details.
	 */
	var hexcase=0;var b64pad="";
	function hex_sha1(a){return rstr2hex(rstr_sha1(str2rstr_utf8(a)))}
	function hex_hmac_sha1(a,b){return rstr2hex(rstr_hmac_sha1(str2rstr_utf8(a),str2rstr_utf8(b)))}
	function sha1_vm_test(){return hex_sha1("abc").toLowerCase()=="a9993e364706816aba3e25717850c26c9cd0d89d"}
	function rstr_sha1(a){return binb2rstr(binb_sha1(rstr2binb(a),a.length*8))}
	function rstr_hmac_sha1(c,f){var e=rstr2binb(c);if(e.length>16){e=binb_sha1(e,c.length*8)}
	var a=Array(16),d=Array(16);
	for(var b=0;b<16;b++){a[b]=e[b]^909522486;d[b]=e[b]^1549556828}
	var g=binb_sha1(a.concat(rstr2binb(f)),512+f.length*8);
	return binb2rstr(binb_sha1(d.concat(g),512+160))}
	function rstr2hex(c){try{hexcase}catch(g){hexcase=0}var f=hexcase?"0123456789ABCDEF":"0123456789abcdef";var b="";
	var a;for(var d=0;d<c.length;d++){a=c.charCodeAt(d);b+=f.charAt((a>>>4)&15)+f.charAt(a&15)}return b}
	function str2rstr_utf8(c){var b="";var d=-1;var a,e;
	while(++d<c.length){a=c.charCodeAt(d);e=d+1<c.length?c.charCodeAt(d+1):0;if(55296<=a&&a<=56319&&56320<=e&&e<=57343){a=65536+((a&1023)<<10)+(e&1023);d++}if(a<=127){b+=String.fromCharCode(a)}else{if(a<=2047){b+=String.fromCharCode(192|((a>>>6)&31),128|(a&63))}else{if(a<=65535){b+=String.fromCharCode(224|((a>>>12)&15),128|((a>>>6)&63),128|(a&63))}else{if(a<=2097151){b+=String.fromCharCode(240|((a>>>18)&7),128|((a>>>12)&63),128|((a>>>6)&63),128|(a&63))}}}}}return b}function rstr2binb(b){var a=Array(b.length>>2);for(var c=0;c<a.length;c++){a[c]=0}for(var c=0;c<b.length*8;c+=8){a[c>>5]|=(b.charCodeAt(c/8)&255)<<(24-c%32)}return a}function binb2rstr(b){var a="";for(var c=0;c<b.length*32;c+=8){a+=String.fromCharCode((b[c>>5]>>>(24-c%32))&255)}return a}
	function binb_sha1(v,o){v[o>>5]|=128<<(24-o%32);v[((o+64>>9)<<4)+15]=o;var y=Array(80);var u=1732584193;var s=-271733879;var r=-1732584194;var q=271733878;var p=-1009589776;for(var l=0;l<v.length;l+=16){var n=u;var m=s;var k=r;var h=q;var f=p;for(var g=0;g<80;g++){if(g<16){y[g]=v[l+g]}else{y[g]=bit_rol(y[g-3]^y[g-8]^y[g-14]^y[g-16],1)}var z=safe_add(safe_add(bit_rol(u,5),sha1_ft(g,s,r,q)),safe_add(safe_add(p,y[g]),sha1_kt(g)));p=q;q=r;r=bit_rol(s,30);s=u;u=z}u=safe_add(u,n);s=safe_add(s,m);r=safe_add(r,k);q=safe_add(q,h);p=safe_add(p,f)}return Array(u,s,r,q,p)}function sha1_ft(e,a,g,f){if(e<20){return(a&g)|((~a)&f)}if(e<40){return a^g^f}if(e<60){return(a&g)|(a&f)|(g&f)}return a^g^f}function sha1_kt(a){return(a<20)?1518500249:(a<40)?1859775393:(a<60)?-1894007588:-899497514}
	function safe_add(a,d){var c=(a&65535)+(d&65535);var b=(a>>16)+(d>>16)+(c>>16);return(b<<16)|(c&65535)}
	function bit_rol(a,b){return(a<<b)|(a>>>(32-b))};

	
	
 //});