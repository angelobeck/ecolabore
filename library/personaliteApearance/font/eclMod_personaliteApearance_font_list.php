<?php

class eclMod_personaliteApearance_font_list
{ // class eclMod_personaliteApearance_font_list

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
$folder = 'fonts/';
$length = strlen ($folder);
$render = $document->render;
$userDetails = [];

foreach ($store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0) as $data)
{ // each user detail
if (substr ($data['name'], 0, $length) != $folder)
continue;

$userDetails[substr ($data['name'], $length)] = $data['id'];
} // each user detail

foreach ($store->control->scandir ('t', 'fonts') as $name)
{ // each detail

if (isset ($userDetails[$name]))
{ // user detail
$data = $store->domainExtras->openById ($me->domainId, $userDetails[$name]);
unset ($userDetails[$name]);
} // user detail
else
$data = $store->control->read ($folder . $name);

if (!$data or !isset ($data['text']['caption']))
continue;

$data['name'] = $name;
$mod->appendChild ($data);
} // each detail

foreach ($userDetails as $name => $id)
{ // each user detail
$data = $store->domainExtras->openById ($me->domainId, $id);
$data['name'] = $name;
$mod->appendChild ($data);
} // each user detail

$mod->sort ('name');

$mod->enabled = true;
} // function setModule

} // class eclMod_personaliteApearance_font_list

?>