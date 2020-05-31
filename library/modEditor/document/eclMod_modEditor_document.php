<?php

class eclMod_modEditor_document
{ // class eclMod_modEditor_document

public $mode = false;
public $enabled = false;

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

$control = $store->control->read ('modEditor_document_module');
$mod->data = $control;

$mod->appendChild ('modEditor_document_save')
->set ('url', 'javascript:humperstilshen.refresh()');

$mod->enabled = true;
} // function setModule

} // class eclMod_modEditor_document

?>