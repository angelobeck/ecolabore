<?php

class eclMod_modToolbox
{ // class eclMod_modToolbox

public $enabled = true;

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

if (isset ($document->data['flags']['modToolbox_enabled']) and $document->data['flags']['modToolbox_enabled'] == 0)
return;

$app = $document->application;
while (!$app->isDomain)
{ // loop back
$app = $app->parent;
} // loop back
$toolbox = $app->child ('-tools');
if ($toolbox === false)
return;

$row = $mod->appendChild ();
foreach ($toolbox->menuChildren ($document) as $child)
{ // each child
if (!isset ($child->data['text']['caption']))
continue;

$local = array (
'virtual' => 1, 
'url' => "javaScript:gadgets.humperstilshen.actionPopupOpen(" . TIC . $document->url ($child->pathway) . TIC . ", 400, 600)", 
'caption' => $child->data['text']['caption']
);
$row->appendChild (false, $local);
} // each child

if (!$row->children)
return;

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modToolbox_module'));

$mod->enabled = true;
} // function setModule

} // class eclMod_modToolbox

?>