<?php

class eclApp_domainCreate
{ // class eclApp_domainCreate

static function is_child ($me, $name)
{ // function is_child
global $io, $store;
if (!$name or $name[0] == '-')
return false;
if (!$io->database->tableEnabled ($store->domain))
return false;
if ($store->domain->getStatus ($name) == 0)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'domain';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('domainCreate_content');
$me->map = ['userJoin', 'domainStyles', 'domainScripts', 'domainCreate_index'];
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_domainCreate

?>