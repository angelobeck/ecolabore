<?php

class eclMod_userIndex_list
{ // class eclMod_userIndex_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

$names = $store->user->childrenNames ();

$userId;
$name;
$data;
$pathway;

$row = $mod->appendChild ();
foreach ($names as $name)
{ // each name
$userId = $store->user->getId ($name);
$data = $store->userContent->open ($userId, '-register');
if (!$data)
$data = $store->userContent->open ($userId, '-index');

$pathway = array (SYSTEM_PROFILES_URI, $name);
$row->appendChild ($data)
->swapTitle ()
->url ($pathway);
} // each name

$mod->data = $store->control->read ('modules/list');
$mod->enabled = true;
} // function setModule

} // class eclMod_userIndex_list

?>