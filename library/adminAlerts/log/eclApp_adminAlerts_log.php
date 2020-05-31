<?php

class eclApp_adminAlerts_log
{ // class eclApp_adminAlerts_log

static function is_child ($me, $name)
{ // function is_child
if (substr ($name, 0, 4) == 'log_')
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
if (is_file (ECOLABORE_LOG_FILE))
$names[] = 'log_error';
if (is_file (DATABASE_LOG_FILE))
$names[] = 'log_database';
if (is_file (SYSTEM_LOG_FILE))
$names[] = 'log_php';
if (!defined ('DATABASE_ENABLE') or !DATABASE_ENABLE)
$names[] = 'log_databasedisabled';
if (!defined ('DATABASE_ENCRYPT_ENABLE') or !DATABASE_ENCRYPT_ENABLE)
$names[] = 'log_encryptiondisabled';
return $names;
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('adminAlerts_' . $me->name);

switch ($me->name)
{ // switch name
case 'log_php':
$me->pathway = array (SYSTEM_ADMIN_URI, 'system', 'log');
break;

case 'log_database':
$me->pathway = array (SYSTEM_ADMIN_URI, 'database', 'log');
break;

case 'log_databasedisabled':
$me->pathway = array (SYSTEM_ADMIN_URI, 'database', 'config');
break;

case 'log_encryptiondisabled':
$me->pathway = array (SYSTEM_ADMIN_URI, 'database', 'cryptography');
break;
} // switch name
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

} // class eclApp_adminAlerts_log

?>