<?php

class eclApp_adminAlerts_update
{ // class eclApp_adminAlerts_update

static function is_child ($me, $name)
{ // function is_child
if ($name == 'update')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
global $io;

return [];

if (defined ('SYSTEM_ENGINE_UPDATE_CHECK'))
{ // last check defined
$value = $io->webservice->json2array (SYSTEM_ENGINE_UPDATE_CHECK);
if (isset ($value['request_date']))
{ // request exists
list ($y, $m, $d) = explode ('-', $value['request_date']);
$requestDate = mktime (0, 0, 30, $m, $d, $y);
if ($requestDate + 1296000 > TIME)
{ // do not check again
if (isset ($value['release']) and $value['release'] == SYSTEM_RELEASE)
return [];

// compare dates
list ($y, $m, $d) = explode ('-', SYSTEM_RELEASE);
$currentRelease = mktime (0, 0, 30, $m, $d, $y);

list ($y, $m, $d) = explode ('-', $value['release']);
$nextRelease = mktime (0, 0, 30, $m, $d, $y);

if ($currentRelease > $nextRelease)
return [];

return array ('update');
} // do not check again
} // request exists
} // last check defined

$data = $io->webservice->request (SYSTEM_ENGINE_UPDATE_URL);
if (isset ($data['EcolaboreEngine']))
{ // update found
$data = $data['EcolaboreEngine'];
$data['request_date'] = date ('Y-m-d', TIME);
$io->systemConstants->set ('SYSTEM_ENGINE_UPDATE_CHECK', $io->webservice->array2json ($data));

if (isset ($data['release']) and $data['release'] == SYSTEM_RELEASE)
return [];

// compare dates
list ($y, $m, $d) = explode ('-', SYSTEM_RELEASE);
$currentRelease = mktime (0, 0, 30, $m, $d, $y);

list ($y, $m, $d) = explode ('-', $data['release']);
$nextRelease = mktime (0, 0, 30, $m, $d, $y);

if ($currentRelease > $nextRelease)
return [];

return array ('update');
} // update found

// A problem occurred
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('adminAlerts_update_newReleaseAvailable');
$me->pathway = array (SYSTEM_ADMIN_URI, 'system', 'update');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $system;
$me = $document->application;
$document->application = $system->child (SYSTEM_ADMIN_URI)->child ('system')->child ('update');
$document->application->dispatch ($document);
} // function dispatch

} // class eclApp_adminAlerts_update

?>