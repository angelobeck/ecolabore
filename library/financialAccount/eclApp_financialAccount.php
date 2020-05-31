<?php

class eclApp_financialAccount
{ // class eclApp_financialAccount

static function is_child ($me, $name)
{ // function is_child
global $store;
$data = $store->domainContent->openChild ($me->domainId, MODE_ACCOUNT, 0, $name);
if ($data)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'account';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
return $store->domainContent->childrenNames ($me->domainId, MODE_ACCOUNT, 0);
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->domainContent->open ($me->domainId, $me->name);
$me->id = $me->data['id'];
if ($me->data['access'] > $me->access)
$me->access = $me->data['access'];
if (isset ($me->data['flags']['financialAccount_type']))
$class = 'eclApp_financialAccount' . ucfirst ($me->data['flags']['financialAccount_type']);
else
$class = 'eclApp_financialAccountBank';
$class::constructor_helper ($me);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$view = true;

if ($document->access (4))
{ // user is admin

if ($document->actions ('account', 'edit'))
$view = self::action_edit ($document);

elseif ($document->actions ('account', 'remove'))
return self::action_remove ($document);

elseif ($document->actions ('account', 'move'))
self::action_move ($document);

// Context Edit
$document->mod->context->appendChild ('financialAccount_edit')
->active ($document->actions ('account', 'edit'))
->url (true, true, '_account-edit');

// Context Remove
$document->mod->context->appendChild ('financialAccount_remove')
->url (true, true, '_account-remove')
->confirm ('financialAccount_removeConfirm');

// Context Move up
if ($me->data['index'])
$document->mod->context->appendChild ('financialAccount_moveUp')
->url (true, true, '_account-move-up');

// Context Move down
if ($me->data['index'] < count ($store->domainContent->children ($me->domainId, MODE_ACCOUNT, $me->data['parent_id'])) - 1)
$document->mod->context->appendChild ('financialAccount_moveDown')
->url (true, true, '_account-move-down');
} // user is admin

if ($view)
{ // view account
if (!$document->access (3) and !isset ($me->data['flags']['modHits_disable']))
$me->data['hits']++;
if (isset ($me->data['flags']['financialAccount_type']))
$class = 'eclApp_financialAccount' . ucfirst ($me->data['flags']['financialAccount_type']);
else
$class = 'eclApp_financialAccountBank';
$class::dispatch ($document);
} // view account
} // function dispatch

static function action_edit ($document)
{ // function action_edit
$formulary = $document->createFormulary ('financialAccount_edit', $document->application->data, 'accountEdit');

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['account']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save formulary
unset ($document->actions['account']);
$document->application->data = $formulary->data;
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
$formulary->action = '_account-edit-save';
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_account-edit';
$document->dataMerge ('financialAccount_contentEdit');

return false;
} // function action_edit

static function action_remove ($document)
{ // function action_remove
global $store, $io;
$me = $document->application;
unset ($document->actions['account']);

$parent = $me->parent;
$me->remove ();
$parent->reset ();
// reindex brothers
$store->domainContent->childrenReindex ($parent->domainId, MODE_ACCOUNT, $parent->id);

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

$store->domainContent->childrenReindex ($parent->domainId, MODE_ACCOUNT, $parentId);
$names = $store->domainContent->childrenNames ($parent->domainId, MODE_ACCOUNT, $parentId);
$index = array_search ($name, $names);
$length = count ($names) - 1;

if ($document->actions ('account', 'move', 'up') and $index)
{ // move up
$move_up = &$store->domainContent->open ($parent->domainId, $names[$index]);
$move_down = &$store->domainContent->open ($parent->domainId, $names[$index - 1]);
$move_up['index']--;
$move_down['index']++;
} // move up
elseif ($document->actions ('account', 'move', 'down') and $index < $length)
{ // move down
$move_up = &$store->domainContent->open ($parent->domainId, $names[$index + 1]);
$move_down = &$store->domainContent->open ($parent->domainId, $names[$index]);
$move_up['index']--;
$move_down['index']++;
} // move down

unset ($document->actions['account']);
$document->application = $parent->child ($name);
} // function action_move

static function remove ($me)
{ // function remove
if (isset ($me->data['flags']['financialAccount_type']))
$class = 'eclApp_financialAccount' . ucfirst ($me->data['flags']['financialAccount_type']);
else
$class = 'eclApp_financialAccountBank';

if (is_callable ($class . '::remove'))
$class::remove ($me);

global $store;
$store->domainContent->delete ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, $me->name);
} // function remove

} // class eclApp_financialAccount

?>