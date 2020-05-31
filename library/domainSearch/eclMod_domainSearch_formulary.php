<?php

class eclMod_domainSearch_formulary
{ // class eclMod_domainSearch_formulary

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;

$document->mod->search->enabled = false;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application;

$formulary = $document->createFormulary ('domainSearch_search', [], 'search');

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
$mod->data = $document->render->block ('modules/search_options');

$mod->enabled = true;
} // function setModule

} // class eclMod_domainSearch_formulary

?>