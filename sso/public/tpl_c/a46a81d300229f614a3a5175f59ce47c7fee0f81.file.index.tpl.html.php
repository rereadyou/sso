<?php /* Smarty version Smarty-3.1.13, created on 2013-05-24 16:18:09
         compiled from "public/tpl/Home/index.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:1778958972519f2241cb83f2-30428526%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a46a81d300229f614a3a5175f59ce47c7fee0f81' => 
    array (
      0 => 'public/tpl/Home/index.tpl.html',
      1 => 1369295540,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1778958972519f2241cb83f2-30428526',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_519f2241cda5b1_69815959',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_519f2241cda5b1_69815959')) {function content_519f2241cda5b1_69815959($_smarty_tpl) {?><html>
    <head>
    </head>

    <body>
        <form name='user' method='post' action='/'>
            <input type='text' value='<?php echo $_smarty_tpl->tpl_vars['user']->value->name;?>
' />
            <input type='password' value='<?php echo $_smarty_tpl->tpl_vars['user']->value->psw;?>
' />
            <input type='submit' value='submit'/>
        </form>
    </body>
</html>
<?php }} ?>