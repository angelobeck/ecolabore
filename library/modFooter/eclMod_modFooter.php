<?php

class eclMod_modFooter
{ // class eclMod_modFooter

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

if (!$document->domain->domainId)
return;

// Configurações
$mod->data = $render->block ('modules/footer');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$mod->enabled = true;
$pathway = array ($document->domain->name, '-personalite', 'modules', 'footer');

if (!isset ($mod->data['text']['content']))
{ // insert info
$mod->data = $store->control->read ('modFooter_create');
$row = $mod->appendChild ();
$row->appendChild ('modFooter_create')
->url ($pathway)
->popUpOpen ();

return;
} // insert info

$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modFooter_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

if (!isset ($mod->data['text']['content']))
return;

$mod->appendChild ($mod->data);

$mod->enabled = true;
} // function setModule

} // class eclMod_modFooter

?>