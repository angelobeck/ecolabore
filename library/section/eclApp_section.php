<?php

class eclApp_section
{ // class eclApp_section

static function is_child ($me, $name)
{ // function is_child
global $store;
$data = $store->domainContent->openChild ($me->domainId, MODE_SECTION, $me->id, $name);
if ($data)
return true;

if (!$me->isDomain)
return false;

$data = $store->domainContent->open ($me->domainId, $name);
if (!$data or !$data['parent_id'])
return false;

if ($data['parent_id'] == 1)
return true;

$parent = $store->domainContent->openById ($me->domainId, $data['parent_id']);
if (isset ($parent['flags']['section_type']) and $parent['flags']['section_type'] == 'menu')
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
return $store->domainContent->childrenNames ($me->domainId, MODE_SECTION, $me->id);
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->domainContent->open ($me->domainId, $me->name);
$me->id = $me->data['id'];
if ($me->data['access'] > $me->access)
$me->access = $me->data['access'];
if (isset ($me->data['flags']['section_type']))
$class = 'eclApp_section' . ucfirst ($me->data['flags']['section_type']);
else
$class = 'eclApp_sectionFolder';
$class::constructor_helper ($me);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$view = true;

if ($document->access (4))
{ // user is admin

if ($document->actions ('section', 'edit'))
$view = self::action_edit ($document);

elseif ($document->actions ('section', 'moveto'))
$view = self::action_moveto ($document);

elseif ($document->actions ('section', 'remove'))
return self::action_remove ($document);

elseif ($document->actions ('section', 'move'))
self::action_move ($document);

// Context Edit
$document->mod->context->appendChild ('section_edit')
->active ($document->actions ('section', 'edit'))
->url (true, true, '_section-edit');

// Versioning
$store->domainExtras->versioning ($document);

// Context Remove
$document->mod->context->appendChild ('section_remove')
->url (true, true, '_section-remove')
->confirm ('section_removeConfirm');

// Context Move up
if ($me->data['index'] and ($me->data['parent_id'] == 1 or $me->data['parent_id'] == $me->parent->id))
$document->mod->context->appendChild ('section_moveUp')
->url (true, true, '_section-move-up');

// Context Move down
if ($me->data['index'] < count ($store->domainContent->children ($me->domainId, MODE_SECTION, $me->data['parent_id'])) - 1 and ($me->data['parent_id'] == 1 or $me->data['parent_id'] == $me->parent->id))
$document->mod->context->appendChild ('section_moveDown')
->url (true, true, '_section-move-down');

// Context Move to...
$document->mod->context->appendChild ('section_moveTo')
->url (true, true, '_section-moveto');
} // user is admin

if ($view)
{ // view section
if (!$document->access (3) and !isset ($me->data['flags']['modHits_disable']))
$me->data['hits']++;
if (isset ($me->data['flags']['section_type']))
$class = 'eclApp_section' . ucfirst ($me->data['flags']['section_type']);
else
$class = 'eclApp_sectionFolder';
$class::dispatch ($document);
} // view section
} // function dispatch

static function action_edit ($document)
{ // function action_edit
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('section_edit', $me->data, 'sectionEdit');

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['section']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save formulary
unset ($document->actions['section']);
$me->data = $formulary->data;
$me->data['updated'] = TIME;
$store->domainExtras->createVersion ($me->domainId, $me->data, $document);

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
$formulary->action = '_section-edit-save';
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_section-edit';
$document->dataMerge ('section_contentEdit');

return false;
} // function action_edit

static function action_remove ($document)
{ // function action_remove
global $store, $io;
$me = $document->application;
unset ($document->actions['section']);

$parent = $me->parent;
$me->remove ();
$parent->reset ();
// reindex brothers
$store->domainContent->childrenReindex ($parent->domainId, MODE_SECTION, $parent->id);

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

$store->domainContent->childrenReindex ($parent->domainId, MODE_SECTION, $parentId);
$names = $store->domainContent->childrenNames ($parent->domainId, MODE_SECTION, $parentId);
$index = array_search ($name, $names);
$length = count ($names) - 1;

if ($document->actions ('section', 'move', 'up') and $index)
{ // move up
$move_up = &$store->domainContent->open ($parent->domainId, $names[$index]);
$move_down = &$store->domainContent->open ($parent->domainId, $names[$index - 1]);
$move_up['index']--;
$move_down['index']++;
} // move up
elseif ($document->actions ('section', 'move', 'down') and $index < $length)
{ // move down
$move_up = &$store->domainContent->open ($parent->domainId, $names[$index + 1]);
$move_down = &$store->domainContent->open ($parent->domainId, $names[$index]);
$move_up['index']--;
$move_down['index']++;
} // move down

unset ($document->actions['section']);
$document->application = $parent->child ($name);
} // function action_move

static function action_moveto ($document)
{ // function action_moveto
global $store;
unset ($document->actions['section']);
$me = $document->application;
$oldParentId = $me->data['parent_id'];

$formulary = $document->createFormulary ('section_moveTo', $me->data, 'moveto');
$formulary->action = '_section-moveto';

if ($formulary->command ('cancel'))
return true;

if ($formulary->command ('save') and $formulary->save ())
{ // save
$newParentId = $formulary->data['parent_id'];
if ($newParentId == $oldParentId)
return true;

$me->data['parent_id'] = $newParentId;
unset ($store->domainContent->chargedMode[$me->domainId][MODE_SECTION]);
$store->domainContent->childrenReindex ($me->domainId, MODE_SECTION, $newParentId);
$me->data['index'] = count ($store->domainContent->children ($me->domainId, MODE_SECTION, $newParentId));
unset ($store->domainContent->indexByParent[$me->domainId][MODE_SECTION][$oldParentId][$me->id]);
$store->domainContent->childrenReindex ($me->domainId, MODE_SECTION, $oldParentId);
$store->domainContent->indexByParent[$me->domainId][MODE_SECTION][$newParentId][$me->id] = $me->id;
unset ($store->domainContent->chargedParents[$me->domainId][MODE_SECTION][$oldParentId]);
unset ($store->domainContent->chargedParents[$me->domainId][MODE_SECTION][$newParentId]);
$document->reload = $document->url ($store->domainContent->pathway ($me->domainId, $me->id));
return false;
} // save

$document->mod->formulary = $formulary;
$document->dataReplace ('section_moveTo');
return false;
} // function action_moveto

static function remove ($me)
{ // function remove
if (isset ($me->data['flags']['section_type']))
$class = 'eclApp_section' . ucfirst ($me->data['flags']['section_type']);
else
$class = 'eclApp_sectionFolder';

if (is_callable ($class . '::remove'))
$class::remove ($me);

global $store;
$store->domainContent->delete ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, $me->name);
$store->domainExtras->deleteAllChildren ($me->domainId, $me->id);
} // function remove

} // class eclApp_section

?>