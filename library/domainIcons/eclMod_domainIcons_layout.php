<?php

class eclMod_domainIcons_layout
{ // class eclMod_domainIcons_layout

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
$mod->enabled = true;
$svg = $store->control->read ('domainIcons_layout');
$mod->data['html'] = $svg['html'];

$children = $store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0);

$blocks = [];
foreach ($children as $data)
{ // each custom block
if (substr ($data['name'], 0, 6) == 'icons/')
$blocks[substr ($data['name'], 6)] = $data;
} // each custom block

$icons = $store->control->scandir ('t', 'icons');
foreach ($icons as $name)
{ // each icon
if (!isset ($blocks[$name]))
$blocks[$name] = $store->control->read ('icons/' . $name);
} // each icon

foreach ($blocks as $name => $data)
{ // each icon
$data['id'] = $name;
if (isset ($data['local']['width']))
$data['scale'] = 32 / $data['local']['width'];
else
$data['scale'] = 1;

if (isset ($data['html']))
$mod->appendChild ($data);
} // each icon
} // function setModule

} // class eclMod_domainIcons_layout

?>