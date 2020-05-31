<?php

class eclMod_modNavbottom
{ // class eclMod_modNavbottom

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;

// Condições
if ($document->printableLayout)
return;

if (!$document->domain->domainId)
return;

$names = $store->domainContent->childrenNames ($document->domain->domainId, MODE_SECTION, 1);
if (!$names)
return;

$row = $mod->appendChild ();

foreach ($names as $name)
{ // each child
$child = $document->domain->child ($name);
if (!$child or !$document->access ($child->access, $child->groups))
continue;

$row->appendChild ($child)
->url ($child->pathway)
->active ($child->pathway == $document->pathway);
} // each child

if (!$row->children)
return;

// Configurações
$name = 'navbottom';
if (isset ($arguments[0]))
$name .= '_' . $arguments[0];
$mod->data = $render->block ('modules/' . $name);
if (!$mod->data and isset ($arguments[0]))
$mod->data = $render->block ('modules/navbottom');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', $name);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modNavbottom_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modNavbottom

?>