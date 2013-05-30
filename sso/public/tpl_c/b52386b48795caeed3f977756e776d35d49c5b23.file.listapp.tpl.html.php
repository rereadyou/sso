<?php /* Smarty version Smarty-3.1.13, created on 2013-05-30 17:10:53
         compiled from "public/tpl/admin/listapp.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:19504999551a7090e2681f8-44048015%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b52386b48795caeed3f977756e776d35d49c5b23' => 
    array (
      0 => 'public/tpl/admin/listapp.tpl.html',
      1 => 1369905052,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19504999551a7090e2681f8-44048015',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51a7090e2ee7b4_61899988',
  'variables' => 
  array (
    'apps' => 0,
    'fn' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a7090e2ee7b4_61899988')) {function content_51a7090e2ee7b4_61899988($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include 'core/common/plugins/function.cycle.php';
?><link rel='stylesheet' type='text/css' href='css/admin.css' /> 
<div id='apps'>
    <div id='apps_field' class='tblrow'>
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['fields'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['fields']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['name'] = 'fields';
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['apps']->value->attrs) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['fields']['total']);
?>
            <div id='' class='tblcol'>
                <?php echo $_smarty_tpl->tpl_vars['apps']->value->attrs[$_smarty_tpl->getVariable('smarty')->value['section']['fields']['index']];?>

            </div>
        <?php endfor; endif; ?>
    </div>

    <div id='apps'>
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['app'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['app']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['name'] = 'app';
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['apps']->value->oa) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['app']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['app']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['app']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['app']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['app']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['app']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['app']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['app']['total']);
?>
            <div id='approw' class='tblrow' bgcolor="<?php echo smarty_function_cycle(array('values'=>"#eeeeee, #999999"),$_smarty_tpl);?>
" >
                <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['f'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['f']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['name'] = 'f';
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['apps']->value->attrs) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total']);
?>
                    <?php $_smarty_tpl->tpl_vars["fn"] = new Smarty_variable($_smarty_tpl->tpl_vars['apps']->value->attrs[$_smarty_tpl->getVariable('smarty')->value['section']['f']['index']], null, 0);?>
                    <div id='' class='tblcol' >
                        <?php echo $_smarty_tpl->tpl_vars['apps']->value->oa[$_smarty_tpl->getVariable('smarty')->value['section']['app']['index']]->{$_smarty_tpl->tpl_vars['fn']->value};?>

                    </div>
                <?php endfor; endif; ?>
            </div>
        <?php endfor; endif; ?>
    </div>
</div>
<?php }} ?>