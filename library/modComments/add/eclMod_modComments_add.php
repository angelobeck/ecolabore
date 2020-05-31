<?php

class eclMod_modComments_add
{ // class eclMod_modComments_add

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

if (!$document->mod->comments->children)
return;

$mod->children = $document->mod->comments->children;

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'comments_add');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modComments_add_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->data = $render->block ('modules/comments_add');
$mod->enabled = true;
} // function setModule

} // class eclMod_modComments_add

?>