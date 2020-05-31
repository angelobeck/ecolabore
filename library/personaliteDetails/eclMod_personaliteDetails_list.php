<?php

class eclMod_personaliteDetails_list
{ // class eclMod_personaliteDetails_list

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
$render = $document->render;
$userDetails = [];

foreach ($store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0) as $data)
{ // each user detail
if (substr ($data['name'], 0, 8) != 'details/')
continue;

$userDetails[substr ($data['name'], 8)] = $data['id'];
} // each user detail

foreach ($store->control->scandir ('t', 'details') as $name)
{ // each detail

if (isset ($userDetails[$name]))
{ // user detail
$data = $store->domainExtras->openById ($me->domainId, $userDetails[$name]);
unset ($userDetails[$name]);
} // user detail
else
$data = $store->control->read ('details/' . $name);

if (!$data or !isset ($data['text']['caption']))
continue;

$data['name'] = $name;
$mod->appendChild ($data)
->appendFolder ($name);
} // each detail

foreach ($userDetails as $name => $id)
{ // each user detail
$data = $store->domainExtras->openById ($me->domainId, $id);
$data['name'] = $name;
$mod->appendChild ($data)
->appendFolder ($name);
} // each user detail

$mod->enabled = true;
} // function setModule

} // class eclMod_personaliteDetails_list

?>