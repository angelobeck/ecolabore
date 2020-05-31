<?php

class eclMod_admin_list
{ // class eclMod_admin_list

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

$mod->data = $store->control->read ('modules/list');
$row = $mod->appendChild ();

foreach ($me->menuChildren ($document) as $child)
{ // each child
$row->appendChild ($child)
->swapTitle ()
->url ($child->pathway)
->virtual ($child->access);
} // each child

if ($row->children)
$mod->enabled = true;
} // function setModule

} // class eclMod_admin_list

?>