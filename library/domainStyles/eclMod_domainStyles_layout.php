<?php

class eclMod_domainStyles_layout
{ // class eclMod_domainStyles_layout

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
$me = $document->application;
$mod->enabled = true;

$template = $render->block ('templates/default');

$preset = $store->control->read ('domainStyles_preset');

$palette = $render->block ('palettes/default');
if (isset ($palette['local']))
$preset['local'] = array_replace ($preset['local'], $palette['local']);
$theme = $render->block ('themes/default');
if (isset ($theme['local']))
$preset['local'] = array_replace ($preset['local'], $theme['local']);
$user = $render->block ('themes/user');
if (isset ($user['local']))
$preset['local'] = array_replace ($preset['local'], $user['local']);

$buffer = '';
if (isset ($template['local']['styles']))
$all['styles'] = explode (CRLF, $template['local']['styles']);
else
$all['styles'][] = 'default';

foreach ($all['styles'] as $name)
{ // each stylesheet
$block = $render->block ('styles/' . $name);
if (isset ($block['html']))
$buffer .= $block['html'];
} // each stylesheet

$mod->data['local'] = $preset['local'];
$mod->data['html'] = $buffer;
} // function setModule

} // class eclMod_domainStyles_layout

?>