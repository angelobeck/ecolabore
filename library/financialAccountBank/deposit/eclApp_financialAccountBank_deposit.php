<?php

class eclApp_financialAccountBank_deposit
{ // class eclApp_financialAccountBank_deposit

static function is_child ($me, $name)
{ // function is_child
if ($name != 'deposit')
return false;
if (isset ($me->data['flags']['pay']['deposit']))
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'method';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (isset ($me->data['flags']['pay']['deposit']))
return array ('deposit');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('financialAccountBank_deposit_content');
$me->data['text']['account'] = $me->parent->data['text']['title'];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_financialAccountBank_deposit

?>