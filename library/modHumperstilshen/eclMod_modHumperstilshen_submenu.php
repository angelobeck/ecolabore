<?php

class eclMod_modHumperstilshen_submenu
{ // class eclMod_modHumperstilshen_submenu

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
if (!$document->mod->humperstilshen->submenus)
return;

$mod->children = $document->mod->humperstilshen->submenus;

$mod->enabled = true;
} // function setModule

} // class eclMod_modHumperstilshen_submenu

?>