<?php

class eclMod_domainGlossary_list
{ // class eclMod_domainGlossary_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application;
$row = $mod->appendChild ();

$children = $store->domainContent->children ($me->domainId, MODE_KEYWORD, 0, 4, 0, 0, 'name', 'asc');
if (!$children)
return;

foreach ($children as $data)
{ // each child
$pathway = $me->pathway;
$pathway[] = substr ($data['name'], 5);
$row->appendChild ($data)
->url ($pathway);
} // each child

$mod->data = $document->render->block ('modules/list_glossary');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'list_glossary');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modList_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_domainGlossary_list

?>