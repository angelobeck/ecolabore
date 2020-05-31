<?php

class eclApp_homeChat_user
{ // class eclApp_homeChat_user

static function is_child ($me, $name)
{ // function is_child
global $store;
if ($store->user->getStatus ($name))
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
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$id = $store->user->getId ($me->name);
$data = $store->userContent->open ($id, '-register');
if (isset ($data['text']['title']))
$me->data['text']['title'] = $data['text']['title'];
else
$me->data['text']['title'] = $data['text']['caption'];

$me->data['text']['caption'] = $data['text']['caption'];
$me->data['owner_id'] = $id;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->actions ('chat', 'clear'))
self::action_clear ($document);

if ($document->actions ('chat', 'remove') and self::action_remove ($document))
return;

// Context clear
$document->mod->context->appendChild ('homeChat_user_contextClear')
->url (true, true, '_chat-clear-' . TIME);

// Context remove
$document->mod->context->appendChild ('homeChat_user_contextRemove')
->url (true, true, '_chat-remove-' . TIME);

$document->mod->comments = new eclMod_homeChat_user_comments ($document);
$document->mod->comments_add = new eclMod_homeChat_user_commentsAdd ($document);
} // function dispatch

static function action_clear ($document)
{ // function action_clear
global $store;
$me = $document->application;

if (!isset ($document->actions['chat'][2]) or intval ($document->actions['chat'][2]) < TIME - 1800)
return;

$time = intval ($document->actions['chat'][2]);

$where = array (
'user_id' => $me->userId, 
'mode' => MODE_GROUP, 
'owner_id' => $me->data['owner_id'], 
);

$groups = $store->userExtras->search ($where);
if (!isset ($groups[0]['id']))
return;

$comments = $store->userExtras->children ($me->userId, MODE_COMMENT, $groups[0]['id']);
foreach ($comments as $data)
{ // clear each comment
if ($data['created'] < $time)
$store->userExtras->delete ($me->userId, $data['id']);
} // clear each comment
} // function action_clear

static function action_remove ($document)
{ // function action_remove
global $store;
$me = $document->application;
if (!isset ($document->actions['chat'][2]) or intval ($document->actions['chat'][2]) < TIME - 1800)
return false;

$time = intval ($document->actions['chat'][2]);

$where = array (
'user_id' => $me->userId, 
'mode' => MODE_GROUP, 
'owner_id' => $me->data['owner_id'], 
);

$groups = $store->userExtras->search ($where);
if (!isset ($groups[0]['id']))
return false;

$newComments = 0;
$comments = $store->userExtras->children ($me->userId, MODE_COMMENT, $groups[0]['id']);
foreach ($comments as $data)
{ // clear each comment
if ($data['created'] < $time)
$store->userExtras->delete ($me->userId, $data['id']);
else
$newComments++;
} // clear each comment
if ($newComments)
return false;

$store->userExtras->delete ($me->userId, $groups[0]['id']);

$document->application = $me->parent;
$document->application->dispatch ($document);
$document->reload = $document->url ();
return true;
} // function action_remove

} // class eclApp_homeChat_user

?>