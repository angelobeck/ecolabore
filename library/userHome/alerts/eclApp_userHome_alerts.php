<?php

class eclApp_userHome_alerts
{ // class eclApp_userHome_alerts

static function is_child ($me, $name)
{ // function is_child
if ($name == '-alerts')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-alerts');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userHome_alerts_content');
$me->isDomain = true;
$me->map = ['userHome_alerts_password', 'userHome_alerts_mail', 'userHome_alerts_phone'];
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_user_alerts ($document);
} // function dispatch

} // class eclApp_userHome_alerts

?>