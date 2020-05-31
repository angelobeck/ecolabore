<?php

class eclApp_sectionTeam_create
{ // class eclApp_sectionTeam_create

static function is_child ($me, $name)
{ // function is_child
global $store;
if (!$store->domainContent->findMarker ($me->domainId, 30) and $name == 'team')
return true;
if (!$store->domainContent->findMarker ($me->domainId, 31) and $name == 'partners')
return true;
if (!$store->domainContent->findMarker ($me->domainId, 32) and $name == 'students')
return true;
if (!$store->domainContent->findMarker ($me->domainId, 33) and $name == 'subscriptions')
return true;
if (!$store->domainContent->findMarker ($me->domainId, 34) and $name == 'suppliers')
return true;
if (!$store->domainContent->findMarker ($me->domainId, 35) and $name == 'clients')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
$names = [];

if (!$store->domainContent->findMarker ($me->domainId, 30))
$names[] = 'team';
if (!$store->domainContent->findMarker ($me->domainId, 31))
$names[] = 'partners';
if (!$store->domainContent->findMarker ($me->domainId, 32))
$names[] = 'students';
if (!$store->domainContent->findMarker ($me->domainId, 33))
$names[] = 'subscriptions';
if (!$store->domainContent->findMarker ($me->domainId, 34))
$names[] = 'suppliers';
if (!$store->domainContent->findMarker ($me->domainId, 35))
$names[] = 'clients';

return $names;
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionTeam_create_' . $me->name);
$me->map = array ('section_preset');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_sectionTeam_create

?>