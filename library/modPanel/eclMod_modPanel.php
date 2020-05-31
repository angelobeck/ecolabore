<?php

class eclMod_modPanel
{ // class eclMod_modPanel

public $main = array ('content', 'list', 'formulary');
public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

$buffer = '[';
foreach ($this->main as $modName)
{ // each child
$buffer .= 'mod`' . $modName . '`;';
} // each child
$mod->data['html'] = $buffer;
$mod->enabled = true;
} // function setModule

} // class eclMod_modPanel

?>