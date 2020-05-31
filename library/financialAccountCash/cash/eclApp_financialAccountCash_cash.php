<?php

class eclApp_financialAccountCash_cash
{ // class eclApp_financialAccountCash_cash

static function is_child ($me, $name)
{ // function is_child
if ($name != 'cash')
return false;
if (isset ($me->data['flags']['pay']['cash']))
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'method';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (isset ($me->data['flags']['pay']['cash']))
return array ('cash');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('financialAccountCash_cash_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_financialAccountCash_cash

?>