<?php

class eclTag_personalite
{ // class eclTag_personalite

static $type = 'scope';

static function render ($render, $arguments)
{ // function render
global $store;

if (!$arguments)
$arguments[0] = 'module';

switch ($arguments[0])
{ // switch argument
case 'post':
if (!$render->document->templateEditable)
return;
if ($render->me === false)
return;

$document = $render->document;
$me = $render->me;
if (!$me->domainId or !$me->id)
return;

if (!$document->access (4, $me->groups))
return;

$data = $store->control->read ('blocks/personalite');
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'card');
$data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modCard_edit');
$data['local']['personalite_caption'] = $caption['text']['caption'];
return $data;
break;

default:
if (!isset ($render->data['personalite_url']))
return;

$block = $store->control->read ('blocks/personalite');
$block['local']['personalite_url'] = $render->data['personalite_url'];
$block['local']['personalite_caption'] = $render->data['personalite_caption'];
return $block;
} // switch argument
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_personalite

?>