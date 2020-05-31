<?php

class eclMod_modTitle
{ // class eclMod_modTitle

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
if (!isset ($document->data['text']['title']))
return;

// Configurações
$mod->data = $render->block ('modules/title');

// Itens da lista
$mod->appendChild ($document->data)
->set ('editable', $document->contentEditable);

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'title');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modTitle_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modTitle

?>