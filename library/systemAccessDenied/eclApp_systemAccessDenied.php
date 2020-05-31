<?php

class eclApp_systemAccessDenied
{ // class eclApp_systemAccessDenied

static function is_child ($me, $name)
{ // function is_child
if ($name == '-access-denied')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-access-denied');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('systemAccessDenied_content');
$me->ignoreSubfolders = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

$document->base = 'system_message';

if ($document->pathway[0] == SYSTEM_PROFILES_URI)
$document->dataMerge ('systemAccessDenied_contentProfile');

$io->request->header ('HTTP/1.1 503 Service Temporarily Unavailable');
$io->request->header ('X-Robots-Tag: noindex');
$io->request->header ('Retry-After: 86400');
} // function dispatch

} // class eclApp_systemAccessDenied

?>