<?php /* Smarty version Smarty-3.1.13, created on 2013-05-29 17:32:46
         compiled from "public/tpl/authentication/login.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:187488626951a5aa928918d8-73219781%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c3b33e1af5fbf5341e8f94cffa52f1dd1ce195ad' => 
    array (
      0 => 'public/tpl/authentication/login.tpl.html',
      1 => 1369819965,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '187488626951a5aa928918d8-73219781',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51a5aa928ac732_61739764',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a5aa928ac732_61739764')) {function content_51a5aa928ac732_61739764($_smarty_tpl) {?><div id='authen_login'>
    email: <input id='authen_email' type="text" />
    password: <input id='authen_psw' type="password" />
    <input type="button" id="btnLoginSubmit" value="submit" />
</div>

<script type='text/javascript' src = 'js/jquery-1.9.1.js'> </script>
<script type='text/javascript' src = 'js/functions.js'> </script>
<script type='text/javascript' src = 'js/authentication.js'> </script>
<?php }} ?>