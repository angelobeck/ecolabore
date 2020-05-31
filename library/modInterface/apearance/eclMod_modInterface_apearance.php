<?php

class eclMod_modInterface_apearance
{ // class eclMod_modInterface_apearance

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

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modInterface_apearance_module'));

$row = $mod->appendChild();

$apearance = $document->domain->child ('-personalite');

foreach ($apearance->menuChildren ($document) as $child)
{ // each item
$row->appendChild ($child)
->url ($child->pathway)
->popUpOpen();
} // each item

$mod->enabled = true;
} // function setModule

} // class eclMod_modInterface_apearance

?>