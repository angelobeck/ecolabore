<?php

class eclApp_sectionBlog_post_new
{ // class eclApp_sectionBlog_post_new

static function is_child ($me, $name)
{ // function is_child
if ($name == '-new-post')
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-new-post');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->access = 4;
$me->data = $store->control->read ('sectionBlog_post_new');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;
$formulary = $document->createFormulary ('sectionBlog_post_new', [], 'create');

if ($formulary->command ('cancel'))
{ // cancel
$document->application = $me->parent;
$document->application->dispatch ($document);
$document->reload = $document->url ();
return;
} // cancel

if ($formulary->command ('save') and $formulary->save (1))
{ // save post
$parent = $document->application->parent;
$data = &$formulary->data;
$data['mode'] = MODE_POST;
$data['parent_id'] = $parent->id;
$data['owner_id'] = $document->user->userId;
$data['subscription_id'] = $document->subscription->id;
$data['id'] = $store->domainContent->insert ($parent->domainId, $data);
$me->id = $data['id'];
$formulary->save (2);
$store->domainExtras->createVersion ($me->domainId, $data, $document);

unset ($document->application);
$parent->reset ();
$document->application = $parent->child ($data['name']);
$document->application->dispatch ($document);
$document->reload = $document->url ();
return;
} // save post

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_sectionBlog_post_new

?>