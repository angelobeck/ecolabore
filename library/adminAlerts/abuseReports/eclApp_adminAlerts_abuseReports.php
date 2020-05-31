<?php

class eclApp_adminAlerts_abuseReports
{ // class eclApp_adminAlerts_abuseReports

static function is_child ($me, $name)
{ // function is_child
if($name == 'abuse-reports')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
$names = [];

return $names;
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('adminAlerts_' . $me->name);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $system;
$me = $document->application;
switch ($me->name)
{ // switch name
case 'log_php':
$document->application = $system->child (SYSTEM_ADMIN_URI)->child ('system')->child ('log');
$document->application->dispatch ($document);
break;

case 'log_database':
$document->application = $system->child (SYSTEM_ADMIN_URI)->child ('database')->child ('log');
$document->application->dispatch ($document);
break;

case 'log_databasedisabled':
$document->application = $system->child (SYSTEM_ADMIN_URI)->child ('database')->child ('config');
$document->application->dispatch ($document);
break;

case 'log_encryptiondisabled':
$document->application = $system->child (SYSTEM_ADMIN_URI)->child ('database')->child ('cryptographhy');
$document->application->dispatch ($document);
break;


case 'log_error':
default:
} // switch name
} // function dispatch

} // class eclApp_adminAlerts_abuseReports

?>