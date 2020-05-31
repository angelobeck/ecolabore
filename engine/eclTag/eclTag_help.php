<?php

class eclTag_help
{ // class eclTag_help

static $type = 'scope';

static function render ($render)
{ // function render
global $store;
if (!$render->getVar ('name') or !$render->getVar ('help') or !$render->getVar ('caption'))
return;

$control = [];
$local = [];
$data = [];
if ($render->getVar ('content'))
{ // from current control
if ($render->getVar ('title'))
$local['caption'] = $render->getVar ('title');
elseif ($render->getVar ('caption'))
$local['caption'] = $render->getVar ('caption');
else
return;
$local['content'] = $render->getVar ('content');
$render->local['help_caption'] = $local['caption'];
$control = [];
if ($render->getVar ('icon'))
$local['icon'] = $render->getVar ('icon');
} // from current control
elseif ($render->getVar ('help_msg'))
{ // from previous defined message
$control = $store->control->read ($render->getVar ('help_msg'));
if (!isset ($control['text']['caption']))
return;
$data['caption'] = $control['text']['caption'];
} // from previous message

$local['name'] = $render->getVar ('name');
$local['return_id'] = $local['name'];

$data['caption'] = $render->getVar ('caption');
$local['field_caption'] = $render->getVar ('caption');

$data['url'] = $render->document->mod->humperstilshen->dialog ($control, $local);

$block = $store->control->read ('blocks/help');
$block['local'] = $data;
return $block;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_help

?>