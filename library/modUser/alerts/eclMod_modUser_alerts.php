<?php

class eclMod_modUser_alerts extends eclEngine_listItem
{ // class eclMod_modUser_alerts

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store, $system;
$document = $this->document;
$render = $document->render;

if (!$document->access (1))
return;

$row = $mod->appendChild ();
$row->children = $this->children;

if ($document->access (4, $system->groups))
{ // webmaster
if ($system->child (SYSTEM_ADMIN_URI) and $system->child (SYSTEM_ADMIN_URI)->child ('alerts'))
{ // exists
$alerts = $system->child (SYSTEM_ADMIN_URI)->child ('alerts');
foreach ($alerts->children () as $child)
{ // each child
$row->appendChild ($child->data)
->swapTitle ()
->url ($child->pathway);
} // each child
} // alerts exists
} // webmaster

$alerts = $system->child (SYSTEM_PROFILES_URI)->child ($document->user->name)->child ('-alerts');
if ($alerts)
{ // user alerts
foreach ($alerts->children () as $child)
{ // each child
if (isset ($child->data['flags']['modUser_alerts_popUp']))
$row->appendChild ($child->data)
->url ($child->pathway)
->popUpOpen ();
else
$row->appendChild ($child->data)
->url ($child->pathway);
} // each child
} // user alerts

foreach ($row->children as &$child)
{ // each child
$child->data['virtual'] = 1;
} // each child

if (!$row->children)
return;

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modUser_alerts_module'));

$mod->enabled = true;
} // function setModule

} // class eclMod_modUser_alerts

?>