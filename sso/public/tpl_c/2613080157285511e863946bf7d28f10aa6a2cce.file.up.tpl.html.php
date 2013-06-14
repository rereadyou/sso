<?php /* Smarty version Smarty-3.1.13, created on 2013-06-13 18:12:35
         compiled from "public/tpl/admin/up.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:214307491751b2a1915e09f1-14497771%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2613080157285511e863946bf7d28f10aa6a2cce' => 
    array (
      0 => 'public/tpl/admin/up.tpl.html',
      1 => 1371118194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '214307491751b2a1915e09f1-14497771',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51b2a191684027_44719295',
  'variables' => 
  array (
    'err' => 0,
    'hint' => 0,
    'attrs' => 0,
    'k' => 0,
    'modelclass' => 0,
    'objid' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51b2a191684027_44719295')) {function content_51b2a191684027_44719295($_smarty_tpl) {?><div class='up'>
    <?php echo $_smarty_tpl->tpl_vars['err']->value;?>
<?php echo $_smarty_tpl->tpl_vars['hint']->value;?>

    <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['attrs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['i']->key;
?>
    <div class='tblrow' 
        <?php if ($_smarty_tpl->tpl_vars['k']->value=='id'){?> 
            <?php $_smarty_tpl->tpl_vars['objid'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['i']->value), null, 0);?>
            style="display: none;" 
        <?php }?> >
        <?php echo $_smarty_tpl->tpl_vars['k']->value;?>

        <br />
        <input id="<?php echo $_smarty_tpl->tpl_vars['modelclass']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['objid']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" class="<?php echo $_smarty_tpl->tpl_vars['modelclass']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['objid']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['modelclass']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" type="text" name="<?php echo $_smarty_tpl->tpl_vars['modelclass']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" />
        <br />
    </div>
    <?php } ?>

    <div class='tblcol'>
        <input type="submit" model="<?php echo $_smarty_tpl->tpl_vars['modelclass']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['objid']->value;?>
" id="btnsubmit" class="btn<?php echo $_smarty_tpl->tpl_vars['modelclass']->value;?>
" value="update" name="up<?php echo $_smarty_tpl->tpl_vars['modelclass']->value;?>
" />
        <input type="button" id="btncancel" class="btncancel" value="cancel" name="cancel" />
    </div>
</div>
<?php }} ?>