<?php /* Smarty version Smarty-3.1.13, created on 2013-06-09 10:01:59
         compiled from "public/tpl/admin/addapp.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:176599866251a5a839043c45-43124443%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f37d570e6670136399d172a65fbe8c6977f6c095' => 
    array (
      0 => 'public/tpl/admin/addapp.tpl.html',
      1 => 1370743316,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '176599866251a5a839043c45-43124443',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51a5a839065233_52244886',
  'variables' => 
  array (
    'err' => 0,
    'attrs' => 0,
    'aoclass' => 0,
    'addclass' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a5a839065233_52244886')) {function content_51a5a839065233_52244886($_smarty_tpl) {?><div id='addmodel'>
    <?php echo $_smarty_tpl->tpl_vars['err']->value;?>

    <form method='post' action=''>
    <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['field'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['field']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['name'] = 'field';
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['attrs']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['field']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['field']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['field']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['field']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['field']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['field']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['field']['total']);
?>
        <span>
            <?php echo $_smarty_tpl->tpl_vars['attrs']->value[$_smarty_tpl->getVariable('smarty')->value['section']['field']['index']];?>

        </span> <br />
        <input id="<?php echo $_smarty_tpl->tpl_vars['attrs']->value[$_smarty_tpl->getVariable('smarty')->value['section']['field']['index']];?>
" class='modeldata' type="text" name="<?php echo $_smarty_tpl->tpl_vars['attrs']->value[$_smarty_tpl->getVariable('smarty')->value['section']['field']['index']];?>
" value="" /> <br />
    <?php endfor; endif; ?>

    <div class='tblcol'>
        <input type="submit" class="<?php echo $_smarty_tpl->tpl_vars['aoclass']->value;?>
" value="add new" name="add<?php echo $_smarty_tpl->tpl_vars['addclass']->value;?>
" />
        <input type="button" class="<?php echo $_smarty_tpl->tpl_vars['aoclass']->value;?>
" value="clear" name="clear" />
    </div>
    </form>
</div>
<?php }} ?>