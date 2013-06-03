<?php /* Smarty version Smarty-3.1.13, created on 2013-06-03 19:49:23
         compiled from "public/tpl/admin/listao.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:29760150851a843ccb96fa8-49815398%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f7c2ae34e135c435ddafc5eee68cf305042a14b2' => 
    array (
      0 => 'public/tpl/admin/listao.tpl.html',
      1 => 1370260098,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '29760150851a843ccb96fa8-49815398',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51a843ccbccbc0_00151735',
  'variables' => 
  array (
    'aoflush' => 0,
    'aono' => 0,
    'ao' => 0,
    'fn' => 0,
    'val' => 0,
    'aoclass' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a843ccbccbc0_00151735')) {function content_51a843ccbccbc0_00151735($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'core/common/plugins/modifier.truncate.php';
?><link rel='stylesheet' type='text/css' href='css/admin.css' /> 
<div class='aoflush'><?php echo $_smarty_tpl->tpl_vars['aoflush']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aono']->value;?>
</div>
<div class='aolist'>
    <div class='fields tblhead'>
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['field'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['field']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['name'] = 'field';
$_smarty_tpl->tpl_vars['smarty']->value['section']['field']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['ao']->value->attrs) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <div class='tblcol'>
                <?php echo $_smarty_tpl->tpl_vars['ao']->value->attrs[$_smarty_tpl->getVariable('smarty')->value['section']['field']['index']];?>

            </div>
        <?php endfor; endif; ?>
            <div class='tblcol'>
                actions
            </div>
    </div>
    <div class='records'>
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['o'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['o']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['name'] = 'o';
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['ao']->value->oa) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['o']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['o']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['o']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['o']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['o']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['o']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['o']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['o']['total']);
?>
            <div class='tblrow'>
            <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['attr'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['attr']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['name'] = 'attr';
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['ao']->value->attrs) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['attr']['total']);
?>
                <?php $_smarty_tpl->tpl_vars["fn"] = new Smarty_variable($_smarty_tpl->tpl_vars['ao']->value->attrs[$_smarty_tpl->getVariable('smarty')->value['section']['attr']['index']], null, 0);?>
                <?php $_smarty_tpl->tpl_vars["val"] = new Smarty_variable($_smarty_tpl->tpl_vars['ao']->value->oa[$_smarty_tpl->getVariable('smarty')->value['section']['o']['index']]->{$_smarty_tpl->tpl_vars['fn']->value}, null, 0);?>
                <div class='tblcol' title='<?php echo $_smarty_tpl->tpl_vars['val']->value;?>
'>
                    <?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['val']->value,20,'...');?>

                </div>
            <?php endfor; endif; ?>
                <div class='tblcol'>
                    <input type="button" class="<?php echo $_smarty_tpl->tpl_vars['aoclass']->value;?>
" value="delete" name="deluser" />
                    <input type="button" class="<?php echo $_smarty_tpl->tpl_vars['aoclass']->value;?>
" value="update" name="update" />
                </div>
            </div>
        <?php endfor; endif; ?>
    </div>
</div>
<?php }} ?>