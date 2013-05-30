<?php /* Smarty version Smarty-3.1.13, created on 2013-05-30 13:53:00
         compiled from "public/tpl/admin/login.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:147692041151a6e93cc41fd4-92656836%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '053b892cd784f7fa435a1acd7d6c3980d2fea260' => 
    array (
      0 => 'public/tpl/admin/login.tpl.html',
      1 => 1369881554,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '147692041151a6e93cc41fd4-92656836',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51a6e93cc5ef45_39887388',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a6e93cc5ef45_39887388')) {function content_51a6e93cc5ef45_39887388($_smarty_tpl) {?><!-- 这里是SSO login区域 -->
<div id="loginFlush"></div>
<div id="sslLoginDiv">
	<input type="text" name="email" id="email" />
	<input type="password" name="upsw" id="upsw" />
	<input type="button" value="submit" id='submitSSOLogin'/>
	<input type="button" value="logout" id='submitSSOLogout'/>

</div>

<script type="text/javascript" src='js/jsbn.js'></script>
<script type="text/javascript" src='js/prng4.js'></script> 
<script type="text/javascript" src='js/rng.js'></script>
<script type="text/javascript" src='js/rsa.js'></script>
<script type='text/javascript' src = 'js/serviceController.js'> </script>
<?php }} ?>