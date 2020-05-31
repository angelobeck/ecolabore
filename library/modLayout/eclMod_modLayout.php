<?php

class eclMod_modLayout
{ // class eclMod_modLayout

public $from = false;
public $name = false;
public $document;
public $application;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $io, $store;
$document = $this->document;
$render = $document->render;
$mod->enabled = true;

if (!$this->from)
$this->from = $document->data['flags']['modLayout_from'];
if (!$this->name)
$this->name = $document->data['flags']['modLayout_name'];

switch ($this->from)
{ // switch from
case 'shared':
case 'templates':
$data = $store->control->read ('layouts/' . $this->name);
break;

case 'control':
$data = $store->control->read ($this->name);
break;

case 'application':
$data = $this->application->data;
break;

case 'domain':
if ($document->access (4, $document->domain->groups))
{ // administrator access

// switch personalite buttons on/off
if (isset ($document->actions['personalite'][1]))
{ // switch personalite buttons
if ($document->actions['personalite'][1] == 'on')
$document->session['personalite'] = 1;
else
unset ($document->session['personalite']);
} // switch personalite buttons

// display Personalite buttons and context options
if (isset ($document->session['personalite']))
{ // personalite buttons on
$document->templateEditable = true;
$document->mod->interface->appendChild ('modLayout_personaliteOff')
->url (true, true, '_personalite-off');
} // personalite buttons on
else
{ // personalite buttons off
$document->mod->interface->appendChild ('modLayout_personaliteOn')
->url (true, true, '_personalite-on');
} // personalite buttons off
} // administrator access

$templates = $store->domainExtras->children ($document->domain->domainId, MODE_TEMPLATE, 0);
$blocks = [];
foreach ($templates as $data)
{ // each template
$blocks[$data['name']] = $data;
} // each template

if (isset ($blocks['layouts/default']))
$data = $blocks['layouts/default'];
else
$data = $store->control->read ('layouts/default');

if ($blocks)
$data['blocks'] = $blocks;
break;
} // switch from

if (!isset ($data) or !$data)
$data = $store->control->read ('layouts/default');

if (isset ($data['flags']))
$document->data['flags'] = array_replace ($document->data['flags'], $data['flags']);

if (isset ($data['blocks']))
{ // blocks
foreach ($data['blocks'] as $name => $block)
{ // each block
if (is_string ($block))
$render->blocks[$name] = $store->control->read ($block);
else
$render->blocks[$name] = $block;
} // each block
} // blocks
unset ($data['blocks']);
$mod->data = $data;
} // function setModule

} // class eclMod_modLayout

?>