<?php

class eclMod_modSummary
{ // class eclMod_modSummary

public $document;
public $children = [];

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function appendTitle ($string)
{ // function appendTitle
$id = 'summary_' . count ($this->children);
$data = [];
$data['url'] = '#' . $id;
$data['caption'] = $this->document->textMerge ($string);
$this->children[] = $this->document->createListItem ($data);
return $id;
} // function appendTitle

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;
$me = $document->application;

if (!$this->children)
return;

$row = $mod->appendChild();
$row->children = $this->children;

// Configurações
$mod->data = $render->block ('modules/summary');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'summary');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modSummary_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modSummary

?>