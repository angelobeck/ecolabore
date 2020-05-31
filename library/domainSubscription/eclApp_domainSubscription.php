<?php

class eclApp_domainSubscription
{ // class eclApp_domainSubscription

static function is_child ($me, $name)
{ // function is_child
if ($name == '-subscriptions')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-subscriptions');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('domainSubscription_view');
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_domainSubscription

?>