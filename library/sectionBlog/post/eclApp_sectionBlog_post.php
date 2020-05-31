<?php

class eclApp_sectionBlog_post
{ // class eclApp_sectionBlog_post

static function is_child ($me, $name)
{ // function is_child
global $store;
if ($name[0] == '-')
return false;

$data = $store->domainContent->openChild ($me->domainId, MODE_POST, $me->id, $name);
if ($data)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
return $store->domainContent->childrenNames ($me->domainId, MODE_POST, $me->id);
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->domainContent->open ($me->domainId, $me->name);
$me->id = $me->data['id'];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$view = true;

if ($document->access (4))
{ // user is admin

if ($document->actions ('post', 'edit'))
$view = self::action_edit ($document);

elseif ($document->actions ('post', 'remove'))
return self::action_remove ($document);

// Edit
$document->mod->context->appendChild ('sectionBlog_post_edit')
->active ($document->actions ('post', 'edit'))
->url (true, true, '_post-edit');

// Versioning
$store->domainExtras->versioning ($document);

// Context Remove
$document->mod->context->appendChild ('sectionBlog_post_remove')
->url (true, true, '_post-remove')
->confirm ('sectionBlog_post_msgRemoveConfirm');

// Context new post
$pathway = $document->application->parent->pathway;
$pathway[] = '-new-post';
$document->mod->context->appendChild ('sectionBlog_post_contextNew')
->url ($pathway);
} // user is admin

if (!$view)
return;

// Hit counter
if ($document->access (4))
$document->mod->editor->enable ();
else
$document->application->data['hits']++;

$document->mod->content = new eclMod_sectionBlog_post_content ($document);
$document->mod->pages = new eclMod_sectionBlog_post_pages ($document);
$document->mod->comments->enable ();
} // function dispatch

static function action_edit ($document)
{ // function action_edit
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('sectionBlog_post_edit', $me->data, 'post');
if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['post']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save formulary
unset ($document->actions['post']);
$me->data = $formulary->data;
if (!$me->data['owner_id'])
$me->data['owner_id'] = $document->user->userId;

// New version
$store->domainExtras->createVersion ($me->domainId, $me->data, $document);

$me->name = $formulary->data['name'];
array_pop ($me->pathway);
$me->pathway[] = $formulary->data['name'];
$document->dataReplace ($me->data);
return true;
} // save formulary
$formulary->action = '_post-edit-save';
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_post-edit';
$document->dataReplace ('sectionBlog_post_edit');

return false;
} // function action_edit

static function action_remove ($document)
{ // function action_remove
global $io, $store;
unset ($document->actions['post']);
$me = $document->application;
$parent = $me->parent;
$me->remove ();

// reindex brothers
$store->domainContent->childrenReindex ($parent->domainId, MODE_POST, $parent->id);

$parent->reset ();
$document->application = $parent;
$document->application->dispatch ($document);
$document->reload = $document->url ();
} // function action_remove

static function remove ($me)
{ // function remove
global $store;
$store->domainContent->delete ($me->domainId, $me->id);
$store->domainExtras->deleteAllChildren ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, $me->name);
} // function remove

} // class eclApp_sectionBlog_post

?>