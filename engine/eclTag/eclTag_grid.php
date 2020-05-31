<?php

class eclTag_grid
{ // class eclTag_grid

static function render ($render, $arguments)
{ // function render
if (isset ($arguments[0]))
$name = 'grid_' . $arguments[0];
else
$name = 'grid';

$render->tagsStack[] = array ('grid', $name);

$render->scissorsIndex++;
$render->scissors[$render->scissorsIndex] = array (
'position' => strlen ($render->buffer), 
'name' => $name, 
);
} // function render

static function close ($render)
{ // function close
global $store;

$name = $render->stackPop ('grid');

if ($name === false)
return;

if (!$render->scissorsIndex)
return;

$position = $render->scissors[$render->scissorsIndex]['position'];
$render->scissorsIndex--;

$border = $render->block ('borders/grid');
if (!isset ($border['html']))
return;

$cut = substr ($render->buffer, $position);
$render->buffer = substr ($render->buffer, 0, $position);

$data = $render->block ('modules/' . $name);
if (!$data)
$data = $render->block ('modules/grid');

$data['html'] = $border['html'];
$data['local']['value'] = $cut;

if ($render->document->access(4, $render->document->domain->groups))
{ // reference
$pathway = array ($render->document->domain->name, '-personalite', 'modules', $name);
$data['local']['personalite_url'] = $render->document->url ($pathway);
$caption = $store->control->read ('modgrid_edit');
$data['local']['personalite_caption'] = $caption['text']['caption'];
$data['local']['personalite_char'] = 'G';
} // reference

return $data;
} // function close

} // class eclTag_grid

?>