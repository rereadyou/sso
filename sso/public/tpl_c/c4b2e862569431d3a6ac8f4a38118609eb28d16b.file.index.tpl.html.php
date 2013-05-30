<?php /* Smarty version Smarty-3.1.13, created on 2013-05-30 09:48:28
         compiled from "public/tpl/home/index.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:130258427751a6afec210870-22181807%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c4b2e862569431d3a6ac8f4a38118609eb28d16b' => 
    array (
      0 => 'public/tpl/home/index.tpl.html',
      1 => 1369793962,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '130258427751a6afec210870-22181807',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51a6afec2657a9_90579293',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a6afec2657a9_90579293')) {function content_51a6afec2657a9_90579293($_smarty_tpl) {?><html>
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