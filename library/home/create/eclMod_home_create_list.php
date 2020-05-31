<?php

class eclMod_home_create_list
{ // class eclMod_home_create_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$row = $mod->appendChild ();

$pathway = $this->document->application->pathway;
foreach ($this->document->application->children () as $child)
{ // each child
$row->appendChild ($child)
->virtual (1)
->url ($pathway, true, '_create-' . $child->name);
} // each child

$mod->data = $store->control->read ('modules/list');
$mod->enabled = true;
} // function setModule

} // class eclMod_home_create_list

?>