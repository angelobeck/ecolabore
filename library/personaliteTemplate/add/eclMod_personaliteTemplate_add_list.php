<?php

class eclMod_personaliteTemplate_add_list
{ // class eclMod_personaliteTemplate_add_list

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
$folder = $me->name;

foreach ($store->control->scandir ('t', $folder) as $name)
{ // each detail
$data = $store->control->read ($folder . '/' . $name);

if (!$data or !isset ($data['text']['caption']))
continue;

$data['name'] = $name;
$data['text']['caption'] = $document->textMerge ($data['name'], ' ', $data['text']['caption']);
$mod->appendChild ($data)
->appendFolder ($name);
} // each detail

$mod->enabled = true;
} // function setModule

} // class eclMod_personaliteTemplate_add_list

?>