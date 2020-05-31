<?php

class eclMod_modInterface extends eclEngine_listItem
{ // class eclMod_modInterface

public $mode = false;
public $enabled = true;

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;

if (!$this->enabled)
return;

$document = $this->document;
$render = $document->render;

$row = $mod->appendChild ();
$row->children = $this->children;
foreach ($row->children as &$child)
{ // each child
$child->data['virtual'] = 1;
} // each child

if ($document->access (4, $document->domain->groups) and $document->data['flags']['modLayout_from'] == 'domain')
{ // user is admin

$row->appendChild ('modInterface_apearance_module')
->submenu ('interface_apearance');

$row->appendChild ('tool_content')
->submenu ('toolbox');

} // user is admin

// Printable version
$row->appendChild ('modLayout_print')
->virtual ()
->url (true, true, '_layout-print');

if (!$row->children)
return;

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modInterface_module'));

$mod->enabled = true;
} // function setModule

} // class eclMod_modInterface

?>