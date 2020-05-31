<?php

class eclTag_card
{ // class eclTag_card

static function render ($render, $arguments)
{ // function render
if (isset ($arguments[0]))
$name = 'card_' . $arguments[0];
else
$name = 'card';

$render->tagsStack[] = array ('card', $name);

$render->scissorsIndex++;
$render->scissors[$render->scissorsIndex] = array (
'position' => strlen ($render->buffer), 
'name' => $name, 
);
} // function render

static function close ($render)
{ // function close
global $store;

$name = $render->stackPop ('card');

if ($name === false)
return;

if (!$render->scissorsIndex)
return;

$position = $render->scissors[$render->scissorsIndex]['position'];
$render->scissorsIndex--;

$stackLevel = count ($render->tagsStack);
if ($stackLevel > 0 and $render->tagsStack[$stackLevel - 1][0] == 'grid')
{ // grid card
$border = $render->block ('borders/item');
if (!isset ($border['html']))
return;
} // grid card
else
{ // card alone
$border = $render->block ('borders/card');
if (!isset ($border['html']))
return;
} // card alone

$cut = substr ($render->buffer, $position);
$render->buffer = substr ($render->buffer, 0, $position);

$data = $render->block ('modules/' . $name);
if (!$data)
$data = $render->block ('modules/card');

$data['html'] = $border['html'];
$data['local']['value'] = $cut;

if ($render->document->application->domainId and $render->document->access(4, $render->document->domain->groups))
{ // reference
if ($stackLevel > 0 and $render->tagsStack[$stackLevel - 1][0] == 'grid')
{ // editing grid item
if ($name == 'card')
$name = 'item';
else
{ // modify name
list ($prefix, $sufix) = explode ('_', $name, 2);
$name = 'item_' . $sufix;
} // modify name
} // editing grid item
$pathway = array ($render->document->domain->name, '-personalite', 'modules', $name);
$data['local']['personalite_url'] = $render->document->url ($pathway);
$caption = $store->control->read ('modcard_edit');
$data['local']['personalite_caption'] = $caption['text']['caption'];
$data['local']['personalite_char'] = 'C';
} // reference

return $data;
} // function close

} // class eclTag_card

?>