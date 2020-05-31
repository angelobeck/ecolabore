<?php

class eclApp_sectionFormulary_record
{ // class eclApp_sectionFormulary_record

static function is_child ($me, $name)
{ // function is_child
global $store;
if ($name[0] == '-')
return false;

$data = $store->domainExtras->openById ($me->domainId, intval ($name));
if ($data and $data['mode'] == MODE_FORM and $data['parent_id'] == $me->parent->id)
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
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->domainExtras->openById ($me->domainId, intval ($me->name));
$me->id = $me->data['id'];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

if ($me->parent->name == '-received')
$me->data['status'] = 721;

if ($document->actions ('record', 'remove'))
return self::action_remove ($document);

// context remove
$document->mod->context->appendChild ('sectionFormulary_record_remove')
->url (true, true, '_record-remove');

$data = $store->control->read ('sectionFormulary_record_content');
$document->dataMerge ($data);

$formulary = $document->createFormulary ('sectionFormulary_view', $me->data, 'form');
$document->mod->formulary = $formulary;
} // function dispatch

static function action_remove ($document)
{ // function action_remove
$me = $document->application;

if ($me->parent->name == '-received')
$me->data['status'] = 730;
else
$me->remove ();

$parent = $me->parent;
$parent->reset ();
$document->application = $parent;
$document->application->dispatch ($document);
$document->reload = $document->url ($parent->pathway);
return;
} // function action_remove

static function remove ($me)
{ // function remove
global $store;
$store->domainExtras->delete ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, $me->name);
} // function remove

} // class eclApp_sectionFormulary_record

?>