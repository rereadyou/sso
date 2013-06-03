<?php /* Smarty version Smarty-3.1.13, created on 2013-06-03 18:01:01
         compiled from "public/tpl/authentication/login.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:187488626951a5aa928918d8-73219781%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c3b33e1af5fbf5341e8f94cffa52f1dd1ce195ad' => 
    array (
      0 => 'public/tpl/authentication/login.tpl.html',
      1 => 1370253583,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '187488626951a5aa928918d8-73219781',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51a5aa928ac732_61739764',
  'variables' => 
  array (
    'email' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a5aa928ac732_61739764')) {function content_51a5aa928ac732_61739764($_smarty_tpl) {?><!-- 这里是SSO login区域 -->
<div id="loginFlush"></div>
<div id="sslLoginDiv">
    <input type="text" name="email" id="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
"/>
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