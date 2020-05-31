<?php

class eclMod_modNavmain
{ // class eclMod_modNavmain

public $document;
public $showSublevel = false;

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

if ($document->pathway[0] == SYSTEM_PROFILES_URI)
return $this->profileNavBar ($mod);

$row = $mod->appendChild();

foreach ($document->domain->menuChildren ($document) as $child)
{ // each child
$row->appendChild ($child)
->swapTitle()
->url ($child->pathway)
->active ($child->pathway == $document->pathway);
} // each child

if (!$row->children)
return;

// Configurações
$mod->data = $render->block ('modules/navmain');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'navmain');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modNavmain_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

private function profileNavBar ($mod)
{ // function profileNavBar
global $store;
$document = $this->document;
$me = $document->application;

while (!$me->parent->isDomain)
{ // find domain
$me = $me->parent;
} // find domain
$row = $mod->appendChild ();
foreach ($me->menuChildren ($document) as $child)
{ // each child
$row->appendChild ($child->data)
->url ($child->pathway)
->active ($child->pathway == $document->pathway);
} // each child

if (!$row->children)
return;

$mod->data = $store->control->read ('modules/navbar');
$mod->enabled = true;
} // function profileNavBar

} // class eclMod_modNavmain

?>