<?php

class eclMod_modSearch
{ // class eclMod_modSearch

public $document;

public $enabled = true;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
if (!$this->enabled)
return;

$document = $this->document;
if ($document->application->isDomain)
return;

if ($document->printableLayout)
return;

$document = $this->document;
$render = $document->render;
$me = $document->application;

$formulary = $document->createFormulary ('modSearch_search', [], 'search');
if ($id = $store->domainContent->findMarker ($me->domainId, 3))
$formulary->pathway = $store->domainContent->pathway ($me->domainId, $id);
else
$formulary->pathway = array ($document->domain->name, '-search');

$data = [];
if (isset ($document->actions['tag'][1]))
{ // tag
$search = $document->actions['tag'];
array_shift ($search);
$formulary->data['key'] = implode (' ', $search);
$formulary->data['tags_only'] = 1;
} // tag
if (isset ($document->actions['search'][1]))
{ // tag
$search = $document->actions['search'];
array_shift ($search);
$formulary->data['key'] = implode (' ', $search);
} // tag
else
{ // from formulary
$formulary->save ();
if (isset ($formulary->data['tags_only']) and $formulary->data['tags_only'])
{ // tags
if (isset ($formulary->data['key']))
{ // key exists
$action = explode (' ', $formulary->data['key']);
array_unshift ($action, 'tag');
$document->actions['tag'] = $action;
} // key exists
} // tags
else
{ // key
if (isset ($formulary->data['key']))
{ // key exists
$action = explode (' ', $formulary->data['key']);
array_unshift ($action, 'search');
$document->actions['search'] = $action;
} // key exists
} // key
} // from formulary

$mod->childrenMerge ($formulary->create ());
$mod->data = $render->block ('modules/search');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'search');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modSearch_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modSearch

?>