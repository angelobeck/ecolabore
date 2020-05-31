<?php

class eclApp_home
{ // class eclApp_home

static function is_child ($me, $name)
{ // function is_child
global $store;
$data = $store->userContent->openChild ($me->userId, MODE_SECTION, $me->id, $name);
if ($data)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
return $store->userContent->childrenNames ($me->userId, MODE_SECTION, $me->id);
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->userContent->open ($me->userId, $me->name);
$me->id = $me->data['id'];
if ($me->data['access'] > $me->access)
$me->access = $me->data['access'];
if (isset ($me->data['flags']['home_type']))
$class = 'eclApp_home' . ucfirst ($me->data['flags']['home_type']);
else
$class = 'eclApp_homeFolder';
$class::constructor_helper ($me);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$view = true;

if ($document->access (4))
{ // user is admin

if ($document->actions ('home', 'edit'))
$view = self::action_edit ($document);

elseif ($document->actions ('home', 'moveto'))
$view = self::action_moveto ($document);

elseif ($document->actions ('home', 'remove'))
return self::action_remove ($document);

elseif ($document->actions ('home', 'move'))
self::action_move ($document);

// Context Edit
$document->mod->context->appendChild ('home_edit')
->active ($document->actions ('home', 'edit'))
->url (true, true, '_home-edit');

// Versioning
$store->userExtras->versioning ($document);

// Context Remove
$document->mod->context->appendChild ('home_remove')
->url (true, true, '_home-remove')
->confirm ('home_removeConfirm');

// Context Move up
if ($me->data['index'] and ($me->data['parent_id'] == 1 or $me->data['parent_id'] == $me->parent->id))
$document->mod->context->appendChild ('home_moveUp')
->url (true, true, '_home-move-up');

// Context Move down
if ($me->data['index'] < count ($store->userContent->children ($me->userId, MODE_SECTION, $me->data['parent_id'])) - 1 and ($me->data['parent_id'] == 1 or $me->data['parent_id'] == $me->parent->id))
$document->mod->context->appendChild ('home_moveDown')
->url (true, true, '_home-move-down');

// Context Move to...
$document->mod->context->appendChild ('home_moveTo')
->url (true, true, '_home-moveto');
} // user is admin

if ($view)
{ // view home
if (!$document->access (3) and !isset ($me->data['flags']['modHits_disable']))
$me->data['hits']++;
if (isset ($me->data['flags']['home_type']))
$class = 'eclApp_home' . ucfirst ($me->data['flags']['home_type']);
else
$class = 'eclApp_homeFolder';
$class::dispatch ($document);
} // view home
} // function dispatch

static function action_edit ($document)
{ // function action_edit
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('home_edit', $me->data, 'homeEdit');

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['home']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save formulary
unset ($document->actions['home']);
$me->data = $formulary->data;
$me->data['updated'] = TIME;
$store->userExtras->createVersion ($me->userId, $me->data, $document);

$document->application->reset ();
if ($document->application->name != $formulary->data['name'])
{ // update name
$document->application->name = $formulary->data['name'];
array_pop ($document->application->pathway);
$document->application->pathway[] = $formulary->data['name'];
$document->reload = $document->url ();
} // update name
else
$document->dataReplace ($document->application->data);

return true;
} // save formulary
$formulary->action = '_home-edit-save';
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_home-edit';
$document->dataMerge ('home_contentEdit');

return false;
} // function action_edit

static function action_remove ($document)
{ // function action_remove
global $store, $io;
$me = $document->application;
unset ($document->actions['home']);

$parent = $me->parent;
$me->remove ();
$parent->reset ();
// reindex brothers
$store->userContent->childrenReindex ($parent->userId, MODE_SECTION, $parent->id);

if ($parent->isDomain)
$document->application = $parent->child ('');
else
$document->application = $parent;
$document->reload = $document->url ();
$document->application->dispatch ($document);
} // function action_remove

static function action_move ($document)
{ // function action_move
global $store;
$parentId = $document->application->data['parent_id'];
$name = $document->application->name;
$parent = $document->application->parent;
unset ($document->application);
$parent->reset ();

$store->userContent->childrenReindex ($parent->userId, MODE_SECTION, $parentId);
$names = $store->userContent->childrenNames ($parent->userId, MODE_SECTION, $parentId);
$index = array_search ($name, $names);
$length = count ($names) - 1;

if ($document->actions ('home', 'move', 'up') and $index)
{ // move up
$move_up = &$store->userContent->open ($parent->userId, $names[$index]);
$move_down = &$store->userContent->open ($parent->userId, $names[$index - 1]);
$move_up['index']--;
$move_down['index']++;
} // move up
elseif ($document->actions ('home', 'move', 'down') and $index < $length)
{ // move down
$move_up = &$store->userContent->open ($parent->userId, $names[$index + 1]);
$move_down = &$store->userContent->open ($parent->userId, $names[$index]);
$move_up['index']--;
$move_down['index']++;
} // move down

unset ($document->actions['home']);
$document->application = $parent->child ($name);
} // function action_move

static function action_moveto ($document)
{ // function action_moveto
global $store;
unset ($document->actions['home']);
$me = $document->application;
$oldParentId = $me->data['parent_id'];

$formulary = $document->createFormulary ('home_moveTo', $me->data, 'moveto');
$formulary->action = '_home-moveto';

if ($formulary->command ('cancel'))
return true;

if ($formulary->command ('save') and $formulary->save ())
{ // save
$newParentId = $formulary->data['parent_id'];
if ($newParentId == $oldParentId)
return true;

$me->data['parent_id'] = $newParentId;
unset ($store->userContent->chargedMode[$me->userId][MODE_SECTION]);
$store->userContent->childrenReindex ($me->userId, MODE_SECTION, $newParentId);
$me->data['index'] = count ($store->userContent->children ($me->userId, MODE_SECTION, $newParentId));
unset ($store->userContent->indexByParent[$me->userId][MODE_SECTION][$oldParentId][$me->id]);
$store->userContent->childrenReindex ($me->userId, MODE_SECTION, $oldParentId);
$store->userContent->indexByParent[$me->userId][MODE_SECTION][$newParentId][$me->id] = $me->id;
unset ($store->userContent->chargedParents[$me->userId][MODE_SECTION][$oldParentId]);
unset ($store->userContent->chargedParents[$me->userId][MODE_SECTION][$newParentId]);
$document->reload = $document->url ($store->userContent->pathway ($me->userId, $me->id));
return false;
} // save

$document->mod->formulary = $formulary;
$document->dataReplace ('home_moveTo');
return false;
} // function action_moveto

static function remove ($me)
{ // function remove
if (isset ($me->data['flags']['home_type']))
$class = 'eclApp_home' . ucfirst ($me->data['flags']['home_type']);
else
$class = 'eclApp_homeFolder';

if (is_callable ($class . '::remove'))
$class::remove ($me);

global $store;
$store->userContent->delete ($me->userId, $me->id);
$store->userFile->deletePrefixedFiles ($me->userId, $me->name);
$store->userExtras->deleteAllChildren ($me->userId, $me->id);
} // function remove

} // class eclApp_home

?>