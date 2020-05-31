<?php

class eclTag_collapse
{ // class eclTag_collapse

static function render ($render, $arguments)
{ // function render
$name = implode (' ', $arguments);

$render->tagsStack[] = array ('collapse', $name);

$render->scissorsIndex++;
$render->scissors[$render->scissorsIndex] = array (
'position' => strlen ($render->buffer), 
'name' => $name, 
);
} // function render

static function close ($render)
{ // function close
global $store;

$label = $render->stackPop ('collapse');

if ($label === false)
return;

if (!$render->scissorsIndex)
return;

$position = $render->scissors[$render->scissorsIndex]['position'];
$render->scissorsIndex--;

$border = $render->block ('borders/collapse');
if (!isset ($border['html']))
return;

$cut = substr ($render->buffer, $position);
$render->buffer = substr ($render->buffer, 0, $position);

$data = [];
$data['html'] = $border['html'];
$data['local']['value'] = $cut;
$data['local']['label'] = $label;
return $data;
} // function close

} // class eclTag_collapse

?>