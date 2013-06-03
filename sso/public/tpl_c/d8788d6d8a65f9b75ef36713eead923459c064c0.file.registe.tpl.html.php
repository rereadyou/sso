<?php /* Smarty version Smarty-3.1.13, created on 2013-06-03 15:54:06
         compiled from "public/tpl/authentication/registe.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:171723019251ac2b4bdc2b37-85820778%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8788d6d8a65f9b75ef36713eead923459c064c0' => 
    array (
      0 => 'public/tpl/authentication/registe.tpl.html',
      1 => 1370246044,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '171723019251ac2b4bdc2b37-85820778',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51ac2b4bde0a35_13114247',
  'variables' => 
  array (
    'email' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51ac2b4bde0a35_13114247')) {function content_51ac2b4bde0a35_13114247($_smarty_tpl) {?><form method="post" action="registe">
    <br />email<br />
    <input id="regmail" type="email" name="email" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
"/>
    <br />password<br />
    <input id="regpsw" type="password" name="password" />
    <br />confirm password <br />
    <input id="regrepsw" type="password" name="repassword" />
    <br /><br />
    <input type="submit" value="registe" />
</form>
<?php }} ?>