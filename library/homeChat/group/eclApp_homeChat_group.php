<?php

class eclApp_homeChat_group
{ // class eclApp_homeChat_group

static function is_child ($me, $name)
{ // function is_child
global $store;

$parts = explode ('-', $name);
if (count ($parts) < 3)
return false;

if ($parts[0] != 'group')
return false;

$domainId = intval ($parts[1]);
$id = intval ($parts[2]);
if (!$domainId or !$id)
return false;

$data = $store->domainExtras->openById ($domainId, $id);
if (!$data)
return false;

if ($data['mode'] != MODE_GROUP or $data['owner_id'] != $me->userId)
return false;

return true;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
list ($prefix, $domainId, $id) = explode ('-', $me->name);
$group = $store->domainExtras->openById (intval ($domainId), intval ($id));
$data = $store->domainContent->openById ($group['domain_id'], $group['parent_id']);
if (isset ($data['text']['title']))
$me->data['text']['title'] = $data['text']['title'];
else
$me->data['text']['title'] = $data['text']['caption'];

$me->data['text']['caption'] = $data['text']['caption'];
$me->domainId = $data['domain_id'];
$me->id = $data['id'];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->actions ('group', 'remove'))
return self::action_remove ($document);

// Context remove
$document->mod->context->appendChild ('homeChat_group_contextRemove')
->url (true, true, '_group-remove');

$document->mod->comments->enable ();
} // function dispatch

static function action_remove ($document)
{ // function action_remove
global $store;
$me = $document->application;

$where = array (
'domain_id' => $me->domainId, 
'mode' => MODE_GROUP, 
'parent_id' => $me->id, 
'owner_id' => $document->user->userId, 
);
$groups = $store->domainExtras->search ($where);

if (!isset ($groups[0]['id']))
return;

$store->domainExtras->delete ($me->domainId, $groups[0]['id']);

$document->application = $me->parent;
$document->application->dispatch ($document);
$document->reload = $document->url ();
} // function action_remove

} // class eclApp_homeChat_group

?>